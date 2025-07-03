<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, OPTIONS"); 
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}


include_once '../../config/database.php';


$database = new Database();
$db = $database->getConnection();


if (!isset($_GET['id_customer'])) {
    http_response_code(400); 
    echo json_encode(array("message" => "Parameter id_customer tidak ditemukan."));
    exit();
}

$id_customer = htmlspecialchars(strip_tags($_GET['id_customer']));


$query_transactions = "SELECT
                            id_transaction,
                            transaction_date,
                            payment_method,
                            total_amount,
                            points_earned,
                            status
                        FROM
                            transactions
                        WHERE
                            id_customer = ?
                        ORDER BY
                            transaction_date DESC";

$stmt_transactions = $db->prepare($query_transactions);
$stmt_transactions->bindParam(1, $id_customer);
$stmt_transactions->execute();

$num = $stmt_transactions->rowCount();

if ($num > 0) {
    $transactions_arr = array();
    $transactions_arr["records"] = array();

    while ($row_transaction = $stmt_transactions->fetch(PDO::FETCH_ASSOC)) {
        extract($row_transaction);

        $transaction_item = array(
            "id_transaction" => $id_transaction,
            "transaction_date" => $transaction_date,
            "payment_method" => $payment_method,
            "total_amount" => $total_amount,
            "points_earned" => $points_earned,
            "status" => $status,
            "details" => array()
        );

                $query_details = "SELECT
                            td.quantity,
                            td.subtotal,
                            m.nama_menu
                        FROM
                            transaction_details td
                        JOIN
                            menus m ON td.id_menu = m.id_menu
                        WHERE
                            td.id_transaction = ?";
        
        $stmt_details = $db->prepare($query_details);
        $stmt_details->bindParam(1, $id_transaction);
        $stmt_details->execute();

        while ($row_detail = $stmt_details->fetch(PDO::FETCH_ASSOC)) {
            $detail_item = array(
                "nama_menu" => $row_detail['nama_menu'],
                "quantity" => intval($row_detail['quantity']),
                "subtotal" => $row_detail['subtotal']
            );
            array_push($transaction_item["details"], $detail_item);
        }
        array_push($transactions_arr["records"], $transaction_item);
    }

    http_response_code(200);
    echo json_encode($transactions_arr);

} else {
    http_response_code(404);
    echo json_encode(
        array("records" => [], "message" => "Tidak ada riwayat transaksi ditemukan untuk user ini.")
    );
}
?>