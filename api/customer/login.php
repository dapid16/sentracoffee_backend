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


include_once __DIR__ . '/../../config/database.php';
include_once __DIR__ . '/../../models/Customer.php';

$database = new Database();
$db = $database->getConnection();

$customer = new Customer($db);
$data = json_decode(file_get_contents("php://input"));


error_log("DEBUG Login: Request received. Raw data: " . file_get_contents("php://input"));
if ($data) {
    error_log("DEBUG Login: Decoded data: " . print_r($data, true));
} else {
    error_log("DEBUG Login: Failed to decode JSON or empty body.");
}




if (empty($data->email) || empty($data->password)) {
    http_response_code(400); 
    echo json_encode(array("message" => "Login failed. Please provide email and password."));
    error_log("DEBUG Login: Missing email or password in request.");
    exit();
}


$customer->email = $data->email;


$foundCustomer = $customer->findByEmail();


error_log("DEBUG Login: findByEmail result: " . ($foundCustomer ? "true" : "false"));
if ($foundCustomer) {
    error_log("DEBUG Login: Customer properties after findByEmail: id=" . $customer->id_customer . ", email=" . $customer->email . ", pass=" . $customer->password);
}




if ($foundCustomer && $customer->password === $data->password) {
    http_response_code(200); 

    echo json_encode(array(
        "message" => "Login successful.",
        "customer_id" => $customer->id_customer,
        "nama" => $customer->nama,
        "email" => $customer->email,
        "no_hp" => $customer->no_hp
        
    ));
    error_log("DEBUG Login: Login successful for email: " . $customer->email);
} else {
    http_response_code(401); 
    echo json_encode(array("message" => "Login failed. Invalid email or password."));
    if (!$foundCustomer) {
        error_log("DEBUG Login: Email '" . $data->email . "' not found in database.");
    } else {
        error_log("DEBUG Login: Password mismatch for email: " . $data->email);
    }
}
?>