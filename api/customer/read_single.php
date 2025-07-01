<?php
// PASTIKAN BARIS HEADER INI ADA DI PALING ATAS SETIAP FILE ENDPOINT API LO!
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS"); // Izinkan semua metode
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Sertakan file koneksi database dan model
include_once '../../config/database.php';
include_once '../../models/Customer.php';

// Inisialisasi koneksi database
$database = new Database();
$db = $database->getConnection();

// Inisialisasi objek Customer
$customer = new Customer($db);

// Ambil ID customer dari URL
$customer->id_customer = isset($_GET['id']) ? $_GET['id'] : die();

// Baca detail customer
if ($customer->readOne()) {
    $customer_arr = array(
        "id_customer" => $customer->id_customer,
        "nama" => $customer->nama,
        "email" => $customer->email,
        "no_hp" => $customer->no_hp,
        "points" => $customer->points // <<< TAMBAHKAN BARIS INI
    );

    http_response_code(200); // OK
    echo json_encode($customer_arr);
} else {
    http_response_code(404); // Not found
    echo json_encode(array("message" => "Customer not found."));
}
?>