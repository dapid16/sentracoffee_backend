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
include_once '../../models/Promotion.php';

$database = new Database();
$db = $database->getConnection();
$promo = new Promotion($db);
$data = json_decode(file_get_contents("php://input"));

if(
    !empty($data->id_promotion) &&
    isset($data->is_active)
){
    $promo->id_promotion = $data->id_promotion;
    $promo->is_active = $data->is_active;

    if($promo->updateStatus()){
        http_response_code(200);
        echo json_encode(array("message" => "Promotion status was updated."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to update promotion status."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Data is incomplete."));
}
?>