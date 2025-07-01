<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

include_once '../../config/database.php';
include_once '../../models/menu.php';

$database = new Database();
$db = $database->getConnection();

$menu = new Menu($db);

$data = json_decode(file_get_contents("php://input"));

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

$menu->id_menu = $data->id_menu;
$menu->nama_menu = $data->nama_menu;
$menu->kategori = $data->kategori;
$menu->harga = $data->harga;
$menu->is_available = isset($data->is_available) ? $data->is_available : 1;
$menu->gambar = isset($data->image) ? $data->image : (isset($data->gambar) ? $data->gambar : null);

if ($menu->update()) {
    http_response_code(200);
    echo json_encode(['status' => 'success', 'message' => 'Produk berhasil diperbarui.']);
} else {
    http_response_code(503);
    echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui produk.']);
}
?>