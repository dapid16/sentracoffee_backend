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
include_once '../../models/Customer.php';

$database = new Database();
$db = $database->getConnection();

$customer = new Customer($db);

$data = json_decode(file_get_contents("php://input"));

if (
    !empty($data->id_customer) &&
    !empty($data->nama) &&
    !empty($data->email)
) {
    $customer->id_customer = $data->id_customer;

 
    $customer->nama = $data->nama;
    $customer->email = $data->email;
    $customer->no_hp = isset($data->no_hp) ? $data->no_hp : null;

   
    if ($customer->update()) {
        http_response_code(200); 
        echo json_encode(array("message" => "Customer was updated."));
    } else {
        http_response_code(503); 
        echo json_encode(array("message" => "Unable to update customer."));
    }
} else {
    http_response_code(400); 
    echo json_encode(array("message" => "Unable to update customer. Data is incomplete or ID is missing."));
}
?>