<?php
// File: xampp/htdocs/SentraCoffee/api/menu/update_menu.php (Versi Refactor)

// Header CORS
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit(0);
}

// Include file database dan model
include_once '../../config/database.php';
include_once '../../models/menu.php';

// Buat koneksi database
$database = new Database();
$db = $database->getConnection();

// Buat instance dari objek Menu
$menu = new Menu($db);

// Ambil data JSON yang dikirim dari Flutter
$data = json_decode(file_get_contents("php://input"));

// Validasi data
if (
    !isset($data->id_menu) ||
    !isset($data->nama_menu) ||
    !isset($data->harga) ||
    !isset($data->kategori)
) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap.']);
    exit();
}

// Set properti objek Menu dengan data dari Flutter
$menu->id_menu = $data->id_menu;
$menu->nama_menu = $data->nama_menu;
$menu->kategori = $data->kategori;
$menu->harga = $data->harga;
$menu->is_available = isset($data->is_available) ? $data->is_available : 1;
$menu->gambar = isset($data->image) ? $data->image : null; // 'image' dari JSON Flutter

// Panggil method update() dari model
if ($menu->update()) {
    // Jika berhasil, kirim respon sukses
    http_response_code(200);
    echo json_encode(['status' => 'success', 'message' => 'Produk berhasil diperbarui.']);
} else {
    // Jika gagal, kirim respon error
    http_response_code(503); // Service Unavailable
    echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui produk.']);
}
?>