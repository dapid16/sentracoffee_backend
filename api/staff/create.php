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

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if (
    !empty($data->nama_staff) &&
    !empty($data->email) &&
    !empty($data->password) &&
    !empty($data->role) &&
    !empty($data->id_owner)
) {
    
    $check_query = "SELECT id_staff FROM staffs WHERE email = ?";
    $check_stmt = $db->prepare($check_query);
    $check_stmt->bindParam(1, $data->email);
    $check_stmt->execute();

    if($check_stmt->rowCount() > 0){
        http_response_code(409); 
        echo json_encode(array("message" => "Email already registered for a staff."));
        exit();
    }

    $query = "INSERT INTO staffs (nama_staff, email, password, role, no_hp, id_owner) VALUES (:nama_staff, :email, :password, :role, :no_hp, :id_owner)";
    $stmt = $db->prepare($query);

    $nama_staff = htmlspecialchars(strip_tags($data->nama_staff));
    $email = htmlspecialchars(strip_tags($data->email));
    $password = htmlspecialchars(strip_tags($data->password)); 
    $role = htmlspecialchars(strip_tags($data->role));
    $no_hp = isset($data->no_hp) ? htmlspecialchars(strip_tags($data->no_hp)) : null;
    $id_owner = htmlspecialchars(strip_tags($data->id_owner));

    $stmt->bindParam(":nama_staff", $nama_staff);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":password", $password);
    $stmt->bindParam(":role", $role);
    $stmt->bindParam(":no_hp", $no_hp);
    $stmt->bindParam(":id_owner", $id_owner);

    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(array("message" => "Staff was created."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create staff."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create staff. Data is incomplete."));
}
?>