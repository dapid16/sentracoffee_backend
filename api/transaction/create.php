<?php
// Header yang diperlukan
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

include_once __DIR__ . '/../../config/database.php';

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if (
    !empty($data->id_customer) &&
    !empty($data->id_staff) &&
    !empty($data->payment_method) &&
    isset($data->total_amount) &&
    !empty($data->details) &&
    is_array($data->details)
) {
    // Logika perhitungan poin
    $points_earned = 0;
    $points_used = isset($data->points_used) ? (int)$data->points_used : 0;
    $description = "Earned from purchase";

    if ($points_used > 0) {
        $description = "Redeemed with points";
    } else {
        $points_earned = floor($data->total_amount / 1000) * 100;
    }
    
    $points_change_for_customer = $points_earned - $points_used;

    $db->beginTransaction();

    try {
        // 1. INSERT ke tabel `transactions`
        $query1 = "INSERT INTO transactions SET id_customer=:id_customer, id_staff=:id_staff, payment_method=:payment_method, total_amount=:total_amount, points_earned=:points_earned, status='Completed'";
        $stmt1 = $db->prepare($query1);
        $stmt1->bindParam(":id_customer", $data->id_customer);
        $stmt1->bindParam(":id_staff", $data->id_staff);
        $stmt1->bindParam(":payment_method", $data->payment_method);
        $stmt1->bindParam(":total_amount", $data->total_amount);
        $stmt1->bindParam(":points_earned", $points_earned);
        $stmt1->execute();
        $id_transaction_baru = $db->lastInsertId();

        // 2. INSERT ke tabel `transaction_details`
        $query2 = "INSERT INTO transaction_details SET id_transaction=:id_transaction, id_menu=:id_menu, quantity=:quantity, subtotal=:subtotal";
        $stmt2 = $db->prepare($query2);
        foreach ($data->details as $detail) {
            $stmt2->bindParam(":id_transaction", $id_transaction_baru);
            $stmt2->bindParam(":id_menu", $detail->id_menu);
            $stmt2->bindParam(":quantity", $detail->quantity);
            $stmt2->bindParam(":subtotal", $detail->subtotal);
            $stmt2->execute();
        }

        // 3. INSERT ke tabel `loyalty_points_history`
        $query3 = "INSERT INTO loyalty_points_history SET id_customer=:id_customer, id_transaction=:id_transaction, points_change=:points_change, type=:type, description=:description";
        $stmt3 = $db->prepare($query3);
        $type = ($points_used > 0) ? "redeem" : "earn";
        $stmt3->bindParam(":id_customer", $data->id_customer);
        $stmt3->bindParam(":id_transaction", $id_transaction_baru);
        $stmt3->bindParam(":points_change", $points_change_for_customer);
        $stmt3->bindParam(":type", $type);
        $stmt3->bindParam(":description", $description);
        $stmt3->execute();

        // 4. UPDATE total poin di tabel `customers`
        $query4 = "UPDATE customers SET points = points + :points_change WHERE id_customer = :id_customer";
        $stmt4 = $db->prepare($query4);
        $stmt4->bindParam(":points_change", $points_change_for_customer);
        $stmt4->bindParam(":id_customer", $data->id_customer);
        $stmt4->execute();

        $db->commit();

        http_response_code(201);
        echo json_encode(["success" => true, "message" => "Transaction created successfully."]);

    } catch (Exception $e) {
        $db->rollBack();

        http_response_code(503);
        echo json_encode(["success" => false, "message" => "Transaction failed.", "error" => $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Incomplete data provided."]);
}
?>