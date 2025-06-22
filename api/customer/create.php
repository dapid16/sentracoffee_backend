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

// Ambil data POST dari body request (JSON)
$data = json_decode(file_get_contents("php://input"));

// Pastikan data tidak kosong dan email unik
if (
    !empty($data->nama) &&
    !empty($data->email) &&
    !empty($data->password)
) {
    // Set properti customer
    $customer->nama = $data->nama;
    $customer->email = $data->email;
    $customer->password = $data->password; // Sesuai kesepakatan, tidak di-hash
    $customer->no_hp = isset($data->no_hp) ? $data->no_hp : null; // No HP bisa opsional

    // Cek apakah email sudah terdaftar
    $customer->findByEmail(); // Cek keberadaan email
    if ($customer->id_customer != null) { // Jika customer ditemukan dengan email ini
        http_response_code(409); // Conflict
        echo json_encode(array("message" => "Email already registered."));
        exit();
    }

    // Buat customer
    if ($customer->create()) {
        http_response_code(201); // Created
        echo json_encode(array("message" => "Customer was created."));
    } else {
        http_response_code(503); // Service Unavailable
        echo json_encode(array("message" => "Unable to create customer."));
    }
} else {
    http_response_code(400); // Bad Request
    echo json_encode(array("message" => "Unable to create customer. Data is incomplete."));
    exit();
}
?>