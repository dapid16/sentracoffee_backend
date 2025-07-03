<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';

$database = new Database();
$db = $database->getConnection();


$query = "SELECT id_staff, nama_staff, role, email, no_hp FROM staffs ORDER BY nama_staff ASC";

$stmt = $db->prepare($query);
$stmt->execute();
$num = $stmt->rowCount();

if ($num > 0) {
    $staffs_arr = array();
    $staffs_arr["records"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        
       
        $staff_item = array(
            "id_staff" => $id_staff,
            "nama_staff" => $nama_staff,
            "email" => $email,
            "no_hp" => $no_hp,
            "role" => $role, 
            "gambar" => null 
        );
        array_push($staffs_arr["records"], $staff_item);
    }

    http_response_code(200);
    echo json_encode($staffs_arr);

} else {
    http_response_code(404);
    echo json_encode(
        array("records" => [], "message" => "No staff found.")
    );
}
?>