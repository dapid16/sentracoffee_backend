<?php
// Headers Wajib
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
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

// --- PERBAIKAN #1: Tambahkan pengecekan untuk id_owner ---
if (
    !empty($data->nama_staff) &&
    !empty($data->email) &&
    !empty($data->password) &&
    !empty($data->role) &&
    !empty($data->id_owner) // <-- WAJIB ADA
) {
    // Kita tetap pakai password plain text sesuai permintaan lo
    $password = $data->password;

    // --- PERBAIKAN #2: Tambahkan id_owner ke dalam query INSERT ---
    $query = "INSERT INTO staffs (id_owner, nama_staff, role, email, password, no_hp) VALUES (:id_owner, :nama_staff, :role, :email, :password, :no_hp)";
    
    $stmt = $db->prepare($query);

    // Bind data ke query
    $stmt->bindParam(":id_owner", $data->id_owner); // <<< TAMBAHKAN INI
    $stmt->bindParam(":nama_staff", $data->nama_staff);
    $stmt->bindParam(":role", $data->role);
    $stmt->bindParam(":email", $data->email);
    $stmt->bindParam(":password", $password);
    
    $no_hp = isset($data->no_hp) ? $data->no_hp : null;
    $stmt->bindParam(":no_hp", $no_hp);

    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(array("message" => "Staff was created."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create staff."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create staff. Data is incomplete. Pastikan id_owner, nama_staff, email, password, dan role terisi."));
}
?>