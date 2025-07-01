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
    !empty($data->nama) &&
    !empty($data->email) &&
    !empty($data->password)
) {
    $customer->nama = $data->nama;
    $customer->email = $data->email;
    $customer->password = $data->password;
    $customer->no_hp = isset($data->no_hp) ? $data->no_hp : null;

    $customer->findByEmail();
    if ($customer->id_customer != null) {
        http_response_code(409); // Conflict
        echo json_encode(array("message" => "Email already registered."));
        exit();
    }

    if ($customer->create()) {
        http_response_code(201);
        echo json_encode(array("message" => "Customer was created."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create customer."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create customer. Data is incomplete."));
    exit();
}
?>