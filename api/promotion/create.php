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
    !empty($data->promo_name) &&
    !empty($data->discount_type) &&
    isset($data->discount_value)
){
    $promo->promo_name = $data->promo_name;
    $promo->description = $data->description;
    $promo->discount_type = $data->discount_type;
    $promo->discount_value = $data->discount_value;

    if($promo->create()){
        http_response_code(201);
        echo json_encode(array("message" => "Promotion was created."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create promotion."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create promotion. Data is incomplete."));
}
?>