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

// Ambil data dari body request (JSON)
$data = json_decode(file_get_contents("php://input"));

// Pastikan ID customer dan data yang diperlukan tidak kosong
if (
    !empty($data->id_customer) &&
    !empty($data->nama) &&
    !empty($data->email)
) {
    // Set ID customer yang akan diupdate
    $customer->id_customer = $data->id_customer;

    // Set nilai properti customer
    $customer->nama = $data->nama;
    $customer->email = $data->email;
    $customer->no_hp = isset($data->no_hp) ? $data->no_hp : null;

    // Update customer
    if ($customer->update()) {
        http_response_code(200); // OK
        echo json_encode(array("message" => "Customer was updated."));
    } else {
        http_response_code(503); // Service Unavailable
        echo json_encode(array("message" => "Unable to update customer."));
    }
} else {
    http_response_code(400); // Bad Request
    echo json_encode(array("message" => "Unable to update customer. Data is incomplete or ID is missing."));
}
?>