<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit(0);
}

include_once '../../config/database.php';
include_once '../../models/menu.php';

$database = new Database();
$db = $database->getConnection();

$menu = new Menu($db);

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->id_menu)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'ID Menu tidak ditemukan.']);
    exit();
}

$menu->id_menu = $data->id_menu;

if ($menu->delete()) {
    http_response_code(200);
    echo json_encode(['status' => 'success', 'message' => 'Produk berhasil dihapus.']);
} else {
    http_response_code(503);
    echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus produk.']);
}
?>