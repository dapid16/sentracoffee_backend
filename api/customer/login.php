<?php
// PASTIKAN BARIS HEADER INI ADA DI PALING ATAS SETIAP FILE ENDPOINT API LO!
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS"); // Izinkan semua metode
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Handle pre-flight request (OPTIONS method) - Ini penting untuk preflight request CORS
// Ini harus ada di SETIAP FILE ENDPOINT, BUKAN HANYA index.php

// ... KODE PHP LAINNYA (include, inisialisasi database, logic utama) ...

// Handle pre-flight request (OPTIONS method)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Sertakan file koneksi database dan model Customer
// Menggunakan _DIR_ untuk path yang lebih robust, relatif dari lokasi file login.php
include_once __DIR__ . '/../../config/database.php';
include_once __DIR__ . '/../../models/Customer.php';
// Inisialisasi koneksi database
$database = new Database();
$db = $database->getConnection();

// Inisialisasi objek Customer
$customer = new Customer($db);

// Ambil data POST dari body request (JSON)
$data = json_decode(file_get_contents("php://input"));

// --- Debugging: Log data yang diterima ---
error_log("DEBUG Login: Request received. Raw data: " . file_get_contents("php://input"));
if ($data) {
    error_log("DEBUG Login: Decoded data: " . print_r($data, true));
} else {
    error_log("DEBUG Login: Failed to decode JSON or empty body.");
}
// --- Akhir Debugging ---


// Pastikan data email dan password tidak kosong
if (empty($data->email) || empty($data->password)) {
    http_response_code(400); // Bad Request
    echo json_encode(array("message" => "Login failed. Please provide email and password."));
    error_log("DEBUG Login: Missing email or password in request.");
    exit();
}

// Set properti email dari data request
$customer->email = $data->email;

// Cari customer berdasarkan email
// findByEmail() akan mengembalikan true jika ditemukan, false jika tidak.
// Dan akan mengisi properti $customer jika ditemukan.
$foundCustomer = $customer->findByEmail();

// --- Debugging: Log hasil findByEmail ---
error_log("DEBUG Login: findByEmail result: " . ($foundCustomer ? "true" : "false"));
if ($foundCustomer) {
    error_log("DEBUG Login: Customer properties after findByEmail: id=" . $customer->id_customer . ", email=" . $customer->email . ", pass=" . $customer->password);
}
// --- Akhir Debugging ---


// Verifikasi customer ditemukan dan password cocok
if ($foundCustomer && $customer->password === $data->password) {
    http_response_code(200); // OK

    // Respons jika login berhasil
    echo json_encode(array(
        "message" => "Login successful.",
        "customer_id" => $customer->id_customer,
        "nama" => $customer->nama,
        "email" => $customer->email,
        "no_hp" => $customer->no_hp
        // TODO: Di aplikasi nyata, kembalikan token JWT di sini untuk keamanan (opsional)
    ));
    error_log("DEBUG Login: Login successful for email: " . $customer->email);
} else {
    // Jika customer tidak ditemukan ATAU password salah
    http_response_code(401); // Unauthorized
    echo json_encode(array("message" => "Login failed. Invalid email or password."));
    if (!$foundCustomer) {
        error_log("DEBUG Login: Email '" . $data->email . "' not found in database.");
    } else {
        error_log("DEBUG Login: Password mismatch for email: " . $data->email);
    }
}
?>