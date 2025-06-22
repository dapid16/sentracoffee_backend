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
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

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

// Pastikan ID customer tidak kosong
if (!empty($data->id_customer)) {
    // Set ID customer yang akan dihapus
    $customer->id_customer = $data->id_customer;

    // Hapus customer
    if ($customer->delete()) {
        http_response_code(200); // OK
        echo json_encode(array("message" => "Customer was deleted."));
    } else {
        http_response_code(503); // Service Unavailable
        echo json_encode(array("message" => "Unable to delete customer."));
    }
} else {
    http_response_code(400); // Bad Request
    echo json_encode(array("message" => "Unable to delete customer. ID is missing."));
}
?>