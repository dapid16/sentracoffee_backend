<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { exit(0); }

include_once '../../config/database.php';
include_once '../../models/menu.php';

$database = new Database();
$db = $database->getConnection();
$menu = new Menu($db);
$data = json_decode(file_get_contents("php://input"));

if (!isset($data->id_menu) || !isset($data->nama_menu) || !isset($data->harga) || !isset($data->kategori)) {
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

$db->beginTransaction();
try {
    if (!$menu->update()) {
        throw new Exception("Unable to update menu.");
    }
    
    
    $delete_query = "DELETE FROM menu_compositions WHERE id_menu = :id_menu";
    $delete_stmt = $db->prepare($delete_query);
    $delete_stmt->bindParam(':id_menu', $menu->id_menu);
    $delete_stmt->execute();

    
    if (isset($data->compositions) && is_array($data->compositions)) {
        $comp_query = "INSERT INTO menu_compositions (id_menu, id_raw_material, quantity_needed) VALUES (:id_menu, :id_raw_material, :quantity_needed)";
        $comp_stmt = $db->prepare($comp_query);

        foreach ($data->compositions as $comp) {
            $comp_stmt->bindParam(':id_menu', $menu->id_menu);
            $comp_stmt->bindParam(':id_raw_material', $comp->id_raw_material);
            $comp_stmt->bindParam(':quantity_needed', $comp->quantity_needed);
            $comp_stmt->execute();
        }
    }

    $db->commit();
    http_response_code(200);
    echo json_encode(['status' => 'success', 'message' => 'Produk berhasil diperbarui.']);

} catch (Exception $e) {
    $db->rollBack();
    http_response_code(503);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>