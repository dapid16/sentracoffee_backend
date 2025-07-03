<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { exit(); }

include_once '../../config/database.php';

if (!isset($_GET['id_menu'])) {
    http_response_code(400);
    echo json_encode(["message" => "Menu ID is required."]);
    exit;
}

$id_menu = $_GET['id_menu'];

try {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "SELECT mc.id_raw_material, mc.quantity_needed, rm.nama_bahan, rm.unit 
              FROM menu_compositions mc
              JOIN raw_materials rm ON mc.id_raw_material = rm.id_raw_material
              WHERE mc.id_menu = ?";
              
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $id_menu);
    $stmt->execute();
    
    $compositions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    http_response_code(200);
    echo json_encode(["records" => $compositions]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["message" => "Server error."]);
}
?>