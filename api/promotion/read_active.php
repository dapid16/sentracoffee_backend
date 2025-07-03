<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';
include_once '../../models/Promotion.php';

$database = new Database();
$db = $database->getConnection();
$promo = new Promotion($db);

$stmt = $promo->readActive(); 
$num = $stmt->rowCount();

if($num > 0) {
    $promos_arr=array();
    $promos_arr["records"]=array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $promo_item=array(
            "id_promotion" => $id_promotion,
            "promo_name" => $promo_name,
            "description" => html_entity_decode($description),
            "discount_type" => $discount_type,
            "discount_value" => $discount_value,
            "is_active" => (bool)$is_active
        );
        array_push($promos_arr["records"], $promo_item);
    }
    http_response_code(200);
    echo json_encode($promos_arr);
} else {
    http_response_code(404);
    echo json_encode(array("records" => [], "message" => "No active promotions found."));
}
?>