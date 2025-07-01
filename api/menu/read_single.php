<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Include file koneksi dan model Customer
include_once '../../config/database.php';
include_once '../../models/Customer.php'; // <<< PASTIKAN PAKAI MODEL CUSTOMER

// Inisialisasi koneksi database
$database = new Database();
$db = $database->getConnection();

// Inisialisasi objek Customer
$customer = new Customer($db); // <<< BUAT OBJEK CUSTOMER

// Ambil ID customer dari URL
$customer->id_customer = isset($_GET['id']) ? $_GET['id'] : die();

// Panggil method readOne() dari model Customer
if ($customer->readOne()) {
    // Buat array untuk respon JSON
    $customer_arr = array(
        "id_customer" => $customer->id_customer,
        "nama" => $customer->nama,
        "email" => $customer->email,
        "no_hp" => $customer->no_hp,
        "points" => $customer->points // <<< PASTIKAN POIN DIKIRIM
    );

    http_response_code(200);
    echo json_encode($customer_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "Customer not found."));
}
?>