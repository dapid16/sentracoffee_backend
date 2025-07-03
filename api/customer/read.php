<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, OPTIONS");
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

$stmt = $customer->read();
$num = $stmt->rowCount();

if ($num > 0) {
    $customers_arr = array();
    $customers_arr["records"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        
        $customer_item = array(
            "id_customer" => $id_customer,
            "nama" => $nama,
            "email" => $email,
            "no_hp" => $no_hp,
            "points" => (int)$points 
        );
        array_push($customers_arr["records"], $customer_item);
    }

    http_response_code(200);
    echo json_encode($customers_arr);
} else {
    http_response_code(404);
    echo json_encode(
        array("message" => "No customers found.")
    );
}
?>