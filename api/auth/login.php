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

ini_set('display_errors', 1);
error_reporting(E_ALL);

include_once '../../config/database.php';

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->email) && !empty($data->password)) {

    $email = htmlspecialchars(strip_tags($data->email));
    $password = $data->password;

    $query_owner = "SELECT id_owner, nama_owner, email, password FROM owners WHERE email = ? LIMIT 1";
    $stmt_owner = $db->prepare($query_owner);
    $stmt_owner->bindParam(1, $email);
    $stmt_owner->execute();

    if ($stmt_owner->rowCount() > 0) {
        $row = $stmt_owner->fetch(PDO::FETCH_ASSOC);
        if ($password == $row['password']) {
            http_response_code(200);
            echo json_encode([
                "success" => true,
                "message" => "Login admin berhasil.",
                "role" => "admin",
                "data" => [
                    "id_owner" => $row['id_owner'],
                    "nama_owner" => $row['nama_owner'],
                    "email" => $row['email']
                ]
            ]);
            exit();
        }
    }

 
    $query_staff = "SELECT id_staff, nama_staff, email, password, role FROM staffs WHERE email = ? LIMIT 1";
    $stmt_staff = $db->prepare($query_staff);
    $stmt_staff->bindParam(1, $email);
    $stmt_staff->execute();
    
    if ($stmt_staff->rowCount() > 0) {
        $row = $stmt_staff->fetch(PDO::FETCH_ASSOC);
        if ($password == $row['password']) {
            http_response_code(200);
            echo json_encode([
                "success" => true,
                "message" => "Login staff berhasil.",
                "role" => "staff",
                "data" => [
                    "id_staff" => $row['id_staff'],
                    "nama_staff" => $row['nama_staff'],
                    "email" => $row['email'],
                    "role" => $row['role']
                ]
            ]);
            exit();
        }
    }


    $query_customer = "SELECT id_customer, nama, email, password, no_hp, points FROM customers WHERE email = ? LIMIT 1";
    $stmt_customer = $db->prepare($query_customer);
    $stmt_customer->bindParam(1, $email);
    $stmt_customer->execute();

    if ($stmt_customer->rowCount() > 0) {
        $row = $stmt_customer->fetch(PDO::FETCH_ASSOC);
        if ($password == $row['password']) {
            http_response_code(200);
            echo json_encode([
                "success" => true,
                "message" => "Login customer berhasil.",
                "role" => "customer",
                "data" => [
                    "id_customer" => intval($row['id_customer']),
                    "nama" => $row['nama'],
                    "email" => $row['email'],
                    "no_hp" => $row['no_hp'],
                    "points" => intval($row['points'])
                ]
            ]);
            exit();
        }
    }

    http_response_code(401);
    echo json_encode(["success" => false, "message" => "Login gagal. Email atau password salah."]);

} else {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Email dan password tidak boleh kosong."]);
    exit;
}
?>