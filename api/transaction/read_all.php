<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';

$database = new Database();
$db = $database->getConnection();

try {
       $query = "
        SELECT
            t.id_transaction,
            t.transaction_date,
            t.payment_method,
            t.total_amount,
            t.discount_amount,
            c.nama as customer_name,
            s.nama_staff as staff_name,
            p.promo_name
        FROM
            transactions t
        LEFT JOIN
            customers c ON t.id_customer = c.id_customer
        LEFT JOIN
            staffs s ON t.id_staff = s.id_staff
        LEFT JOIN
            promotions p ON t.id_promotion = p.id_promotion
        ORDER BY
            t.transaction_date DESC
    ";

    $stmt = $db->prepare($query);
    $stmt->execute();
    $num = $stmt->rowCount();

    if ($num > 0) {
        $transactions_arr = [];
        $transactions_arr["records"] = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $transaction_item = [
                "id_transaction" => $id_transaction,
                "transaction_date" => $transaction_date,
                "customer_name" => $customer_name,
                "staff_name" => $staff_name, 
                "payment_method" => $payment_method,
                "total_amount" => $total_amount,
                "promo_name" => $promo_name, 
                "discount_amount" => $discount_amount,
                "details" => []
            ];

                      $detail_query = "
                SELECT
                    td.quantity,
                    m.nama_menu
                FROM
                    transaction_details td
                JOIN
                    menus m ON td.id_menu = m.id_menu
                WHERE
                    td.id_transaction = ?
            ";
            
            $detail_stmt = $db->prepare($detail_query);
            $detail_stmt->bindParam(1, $id_transaction);
            $detail_stmt->execute();

            while ($detail_row = $detail_stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($transaction_item["details"], $detail_row);
            }

            array_push($transactions_arr["records"], $transaction_item);
        }

        http_response_code(200);
        echo json_encode($transactions_arr);
    } else {
        http_response_code(404);
        echo json_encode(["records" => [], "message" => "No transactions found."]);
    }

} catch (Exception $e) {
    http_response_code(503);
    echo json_encode(["success" => false, "message" => "Service error.", "error" => $e->getMessage()]);
}
?>