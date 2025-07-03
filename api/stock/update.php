<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { exit(); }

include_once '../../config/database.php';

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->id_raw_material) && isset($data->current_stock)) {
    try {
        $database = new Database();
        $db = $database->getConnection();
        
        $query = "UPDATE raw_materials SET current_stock = :current_stock WHERE id_raw_material = :id_raw_material";
        $stmt = $db->prepare($query);
        
        $stmt->bindParam(':current_stock', $data->current_stock);
        $stmt->bindParam(':id_raw_material', $data->id_raw_material);

        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode(["message" => "Stock updated."]);
        } else {
            throw new Exception("Unable to update stock.");
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["message" => "Server error."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Incomplete data."]);
}
?>