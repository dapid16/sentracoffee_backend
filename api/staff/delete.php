<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

include_once '../../config/database.php';
include_once '../../models/Staff.php';

$database = new Database();
$db = $database->getConnection();
$staff = new Staff($db);
$data = json_decode(file_get_contents("php://input"));

if (!empty($data->id_staff)) {
    $staff->id_staff = $data->id_staff;

    // Panggil fungsi delete dari model
    if ($staff->delete()) {
        http_response_code(200);
        echo json_encode(["status" => "success", "message" => "Staff was deleted."]);
    } else {
        http_response_code(503);
        echo json_encode(["status" => "error", "message" => "Unable to delete staff."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Incomplete data. Staff ID is required."]);
}
?>