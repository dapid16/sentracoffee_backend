<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

include_once '../../config/database.php';

$data = json_decode(file_get_contents("php://input"));

if (
    empty($data->id_customer) ||
    !isset($data->total_amount) ||
    empty($data->details) ||
    !is_array($data->details)
) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Incomplete data provided."]);
    exit();
}

try {
    $database = new Database();
    $db = $database->getConnection();
    $db->beginTransaction();

    $id_promotion = null;
    $discount_amount = 0;
    $original_total = 0;
    
    foreach($data->details as $item) {
        $original_total += $item->subtotal;
    }

    if (!empty($data->promo_name)) {
        $promo_query = "SELECT * FROM promotions WHERE promo_name = :promo_name AND is_active = 1 LIMIT 1";
        $promo_stmt = $db->prepare($promo_query);
        $promo_stmt->bindParam(':promo_name', $data->promo_name);
        $promo_stmt->execute();

        if ($promo_stmt->rowCount() > 0) {
            $promo = $promo_stmt->fetch(PDO::FETCH_ASSOC);
            $id_promotion = $promo['id_promotion'];
            $discount_value = (float)$promo['discount_value'];

            if ($promo['discount_type'] == 'persen') {
                $discount_amount = $original_total * ($discount_value / 100);
            } else {
                $discount_amount = $discount_value;
            }
             if ($discount_amount > $original_total) {
                $discount_amount = $original_total;
            }
        }
    }
    
    $final_total_amount = $original_total - $discount_amount;
    
    $points_earned = 0;
    $points_used = isset($data->points_used) ? (int)$data->points_used : 0;
    
    if ($points_used > 0) {
        $final_total_amount = 0;
    } else {
        $points_earned = floor($final_total_amount / 1000) * 100;
    }
    $points_change = $points_earned - $points_used;
    
    $id_staff = isset($data->staffId) ? $data->staffId : null;
    $payment_method = !empty($data->payment_method) ? $data->payment_method : 'Cash';

    $query1 = "INSERT INTO transactions SET id_customer=:id_customer, id_staff=:id_staff, payment_method=:payment_method, total_amount=:total_amount, status='Completed', id_promotion=:id_promotion, discount_amount=:discount_amount, points_earned=:points_earned";
    $stmt1 = $db->prepare($query1);
    $stmt1->bindParam(":id_customer", $data->id_customer);
    $stmt1->bindParam(":id_staff", $id_staff, $id_staff === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
    $stmt1->bindParam(":payment_method", $payment_method);
    $stmt1->bindParam(":total_amount", $final_total_amount);
    $stmt1->bindParam(":id_promotion", $id_promotion);
    $stmt1->bindParam(":discount_amount", $discount_amount);
    $stmt1->bindParam(":points_earned", $points_earned);
    $stmt1->execute();
    $id_transaction_baru = $db->lastInsertId();

    $query2 = "INSERT INTO transaction_details SET id_transaction=:id_transaction, id_menu=:id_menu, quantity=:quantity, subtotal=:subtotal";
    $stmt2 = $db->prepare($query2);
    foreach ($data->details as $detail) {
        $stmt2->bindParam(":id_transaction", $id_transaction_baru);
        $stmt2->bindParam(":id_menu", $detail->id_menu);
        $stmt2->bindParam(":quantity", $detail->quantity);
        $stmt2->bindParam(":subtotal", $detail->subtotal);
        $stmt2->execute();
    }
    
    // --- Logika Pengurangan Stok Otomatis ---
    foreach ($data->details as $detail) {
        $id_menu = $detail->id_menu;
        $quantity_sold = $detail->quantity;

        $comp_query = "SELECT id_raw_material, quantity_needed FROM menu_compositions WHERE id_menu = ?";
        $comp_stmt = $db->prepare($comp_query);
        $comp_stmt->bindParam(1, $id_menu);
        $comp_stmt->execute();

        while ($comp_row = $comp_stmt->fetch(PDO::FETCH_ASSOC)) {
            $id_bahan = $comp_row['id_raw_material'];
            $butuh_per_porsi = $comp_row['quantity_needed'];
            $total_bahan_terpakai = $butuh_per_porsi * $quantity_sold;

            $stock_update_query = "UPDATE raw_materials SET current_stock = current_stock - ? WHERE id_raw_material = ?";
            $stock_stmt = $db->prepare($stock_update_query);
            $stock_stmt->bindParam(1, $total_bahan_terpakai);
            $stock_stmt->bindParam(2, $id_bahan);
            $stock_stmt->execute();
        }
    }
    
    if ($points_change != 0) {
        $history_query = "INSERT INTO loyalty_points_history SET id_customer=:id_customer, id_transaction=:id_transaction, points_change=:points_change, type=:type, description=:description";
        $history_stmt = $db->prepare($history_query);
        
        $type = ($points_used > 0) ? "redeem" : "earn";
        $description = ($points_used > 0) ? "Redeemed with points" : "Earned from purchase";

        $history_stmt->bindParam(":id_customer", $data->id_customer);
        $history_stmt->bindParam(":id_transaction", $id_transaction_baru);
        $history_stmt->bindParam(":points_change", $points_change);
        $history_stmt->bindParam(":type", $type);
        $history_stmt->bindParam(":description", $description);
        $history_stmt->execute();

        $customer_update_query = "UPDATE customers SET points = points + :points_change WHERE id_customer = :id_customer";
        $customer_stmt = $db->prepare($customer_update_query);
        $customer_stmt->bindParam(":points_change", $points_change);
        $customer_stmt->bindParam(":id_customer", $data->id_customer);
        $customer_stmt->execute();
    }

    $db->commit();
    http_response_code(201);
    echo json_encode(["success" => true, "message" => "Transaction created successfully."]);

} catch (Exception $e) {
    if ($db->inTransaction()) {
        $db->rollBack();
    }
    http_response_code(503);
    echo json_encode(["success" => false, "message" => "Transaction failed on server.", "error" => $e->getMessage()]);
}
?>