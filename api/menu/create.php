<?php
// PASTIKAN BARIS HEADER INI ADA DI PALING ATAS
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Handle pre-flight request (OPTIONS method)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Sertakan file koneksi database dan model
include_once '../../config/database.php';
include_once '../../models/Menu.php';

// Inisialisasi koneksi database
$database = new Database();
$db = $database->getConnection();

// Inisialisasi objek Menu
$menu = new Menu($db);

// Ambil data POST dari body request (format JSON)
$data = json_decode(file_get_contents("php://input"));

// Pastikan data yang dibutuhkan tidak kosong
if (!empty($data->nama_menu) && !empty($data->kategori) && !empty($data->harga)) {
    // Set properti menu dari data yang diterima
    $menu->nama_menu = $data->nama_menu;
    $menu->kategori = $data->kategori;
    $menu->harga = $data->harga;
    
    // Set properti opsional
    $menu->is_available = isset($data->is_available) ? $data->is_available : 1; // Default ke 1 (Tersedia)

    // --- REVISI DI SINI ---
    // Cek apakah data 'gambar' dikirim, jika tidak, set ke null.
    $menu->gambar = isset($data->gambar) ? $data->gambar : null;
    // --- AKHIR REVISI ---

    // Panggil fungsi create() dari model Menu
    // Fungsi ini sudah kita upgrade sebelumnya untuk menangani 'gambar'
    if ($menu->create()) {
        // Set kode respons - 201 Created
        http_response_code(201);
        echo json_encode(array("message" => "Menu was created."));
    } else {
        // Jika gagal membuat menu - 503 Service Unavailable
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create menu."));
    }
} else {
    // Jika data tidak lengkap - 400 Bad Request
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create menu. Data is incomplete."));
}
?>