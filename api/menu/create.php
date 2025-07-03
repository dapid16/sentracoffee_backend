<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { exit(); }

include_once '../../config/database.php';
include_once '../../models/Menu.php';

$database = new Database();
$db = $database->getConnection();
$menu = new Menu($db);
$data = json_decode(file_get_contents("php://input"));

if (!empty($data->nama_menu) && !empty($data->kategori) && isset($data->harga)) {
    $menu->nama_menu = $data->nama_menu;
    $menu->kategori = $data->kategori;
    $menu->harga = $data->harga;
    $menu->is_available = $data->is_available ?? 1;
    $menu->gambar = $data->gambar ?? null;
    
    $db->beginTransaction();
    try {
        if (!$menu->create()) {
            throw new Exception("Unable to create menu.");
        }
        $id_menu_baru = $db->lastInsertId();

        if (isset($data->compositions) && is_array($data->compositions)) {
            $comp_query = "INSERT INTO menu_compositions (id_menu, id_raw_material, quantity_needed) VALUES (:id_menu, :id_raw_material, :quantity_needed)";
            $comp_stmt = $db->prepare($comp_query);

            foreach ($data->compositions as $comp) {
                $comp_stmt->bindParam(':id_menu', $id_menu_baru);
                $comp_stmt->bindParam(':id_raw_material', $comp->id_raw_material);
                $comp_stmt->bindParam(':quantity_needed', $comp->quantity_needed);
                $comp_stmt->execute();
            }
        }
        $db->commit();
        http_response_code(201);
        echo json_encode(["message" => "Menu was created successfully."]);

    } catch (Exception $e) {
        $db->rollBack();
        http_response_code(503);
        echo json_encode(["message" => $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Incomplete data for menu creation."]);
}
?>