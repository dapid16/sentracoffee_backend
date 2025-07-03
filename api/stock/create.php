<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { exit(); }

include_once '../../config/database.php';

$database = new Database();
$db = $database->getConnection();
$data = json_decode(file_get_contents("php://input"));

if (!empty($data->nama_bahan) && !empty($data->unit) && isset($data->current_stock) && isset($data->min_stock_level)) {
    try {
        $query = "INSERT INTO raw_materials SET nama_bahan=:nama_bahan, unit=:unit, current_stock=:current_stock, min_stock_level=:min_stock_level";
        $stmt = $db->prepare($query);

        $data->nama_bahan=htmlspecialchars(strip_tags($data->nama_bahan));
        $data->unit=htmlspecialchars(strip_tags($data->unit));
        $data->current_stock=htmlspecialchars(strip_tags($data->current_stock));
        $data->min_stock_level=htmlspecialchars(strip_tags($data->min_stock_level));
        
        $stmt->bindParam(':nama_bahan', $data->nama_bahan);
        $stmt->bindParam(':unit', $data->unit);
        $stmt->bindParam(':current_stock', $data->current_stock);
        $stmt->bindParam(':min_stock_level', $data->min_stock_level);

        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode(["message" => "Raw material created."]);
        } else {
            throw new Exception("Unable to create raw material.");
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["message" => "Server error.", "error" => $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Incomplete data."]);
}
?>