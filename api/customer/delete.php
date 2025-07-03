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


header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


include_once '../../config/database.php';
include_once '../../models/Customer.php';


$database = new Database();
$db = $database->getConnection();


$customer = new Customer($db);


$data = json_decode(file_get_contents("php://input"));


if (!empty($data->id_customer)) {
    
    $customer->id_customer = $data->id_customer;

  
    if ($customer->delete()) {
        http_response_code(200); 
        echo json_encode(array("message" => "Customer was deleted."));
    } else {
        http_response_code(503); 
        echo json_encode(array("message" => "Unable to delete customer."));
    }
} else {
    http_response_code(400); 
    echo json_encode(array("message" => "Unable to delete customer. ID is missing."));
}
?>