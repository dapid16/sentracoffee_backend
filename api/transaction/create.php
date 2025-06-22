<?php
// Header yang diperlukan untuk API
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Handle pre-flight request (OPTIONS method)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// ... (header dan handler OPTIONS lainnya) ...

// Sertakan file koneksi database dan model
// DARI api/transaction/ (naik 2 level ke root SentraCoffee/)
include_once __DIR__ . '/../../config/database.php';
include_once __DIR__ . '/../../models/Transaction.php';
//include_once __DIR__ . '/../../models/Customer.php'; // Jika file transaction juga butuh Customer model
//include_once __DIR__ . '/../../models/Menu.php'; // Jika file transaction juga butuh Menu model

// ... (kode PHP lainnya) ...
// Inisialisasi koneksi database
$database = new Database();
$db = $database->getConnection();

// Inisialisasi objek Transaction
$transaction = new Transaction($db);

// Ambil data POST dari body request (JSON)
$data = json_decode(file_get_contents("php://input"));

// Pastikan data tidak kosong
if (
    !empty($data->payment_method) &&
    !empty($data->total_amount) &&
    !empty($data->transaction_details) &&
    is_array($data->transaction_details)
) {
    // Set properti transaksi
    $transaction->id_customer = isset($data->id_customer) ? $data->id_customer : null;
    $transaction->id_staff = isset($data->id_staff) ? $data->id_staff : 1; // Asumsi default id_staff=1
    
    $transaction->payment_method = $data->payment_method;
    $transaction->total_amount = $data->total_amount;
    $transaction->points_earned = isset($data->points_earned) ? $data->points_earned : 0;
    $transaction->status = isset($data->status) ? $data->status : 'Completed';

    // Set detail transaksi
    $transaction->details = $data->transaction_details;

    // Buat transaksi
    if($transaction->create()){
        http_response_code(201);
        echo json_encode(array(
            "message" => "Transaction was created.",
            "id_transaction" => $transaction->id_transaction
        ));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create transaction."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create transaction. Data is incomplete or malformed."));
}
?>