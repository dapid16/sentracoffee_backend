<?php
// PASTIKAN BARIS HEADER INI ADA DI PALING ATAS SETIAP FILE ENDPOINT API LO!
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS"); // Izinkan semua metode
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Handle pre-flight request (OPTIONS method) - Ini penting untuk preflight request CORS
// Ini harus ada di SETIAP FILE ENDPOINT, BUKAN HANYA index.php
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200); // Harus 200 OK untuk OPTIONS
    exit(); // Harus exit setelah merespons OPTIONS
}

// ... KODE PHP LAINNYA (include, inisialisasi database, logic utama) ...

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
        "no_hp" => $customer->no_hp
    );

    http_response_code(200); // OK
    echo json_encode($customer_arr);
} else {
    http_response_code(404); // Not found
    echo json_encode(array("message" => "Customer not found."));
}
?>