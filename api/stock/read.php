<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include_once '../../config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    $query = "SELECT id_raw_material, nama_bahan, current_stock, unit, min_stock_level FROM raw_materials ORDER BY nama_bahan ASC";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $num = $stmt->rowCount();

    if ($num > 0) {
        $materials_arr = ["records" => []];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $material_item = [
                "id_raw_material" => (int)$id_raw_material,
                "nama_bahan" => $nama_bahan,
                "current_stock" => (float)$current_stock,
                "unit" => $unit,
                "min_stock_level" => (float)$min_stock_level
            ];
            array_push($materials_arr["records"], $material_item);
        }
        http_response_code(200);
        echo json_encode($materials_arr);
    } else {
        http_response_code(404);
        echo json_encode(["records" => [], "message" => "No raw materials found."]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["message" => "Server error."]);
}
?>