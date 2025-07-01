<?php
// File: xampp/htdocs/SentraCoffee/api/menu/delete.php

// Header CORS dan tipe konten
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS"); // Kita pakai POST untuk delete agar bisa kirim body
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

// Ambil data JSON yang dikirim (kita akan kirim ID di dalam body)
$data = json_decode(file_get_contents("php://input"));

// Validasi data: pastikan id_menu ada
if (!isset($data->id_menu)) {
    http_response_code(400); // Bad Request
    echo json_encode(['status' => 'error', 'message' => 'ID Menu tidak ditemukan.']);
    exit();
}

// Set ID menu yang akan dihapus
$menu->id_menu = $data->id_menu;

// Panggil method delete() dari model
if ($menu->delete()) {
    // Jika berhasil, kirim respon sukses
    http_response_code(200);
    echo json_encode(['status' => 'success', 'message' => 'Produk berhasil dihapus.']);
} else {
    // Jika gagal, kirim respon error
    http_response_code(503); // Service Unavailable
    echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus produk.']);
}
?>