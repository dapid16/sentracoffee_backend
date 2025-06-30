<?php
// Headers Wajib
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';

$database = new Database();
$db = $database->getConnection();

// --- PERBAIKAN #1: Ambil kolom 'role' dari database ---
$query = "SELECT id_staff, nama_staff, role, email, no_hp FROM staffs ORDER BY nama_staff ASC";

$stmt = $db->prepare($query);
$stmt->execute();
$num = $stmt->rowCount();

if ($num > 0) {
    $staffs_arr = array();
    $staffs_arr["records"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        
        // --- PERBAIKAN #2: Hapus logic role bohongan ---
        // Sekarang kita pakai data 'role' asli dari database
        $staff_item = array(
            "id_staff" => $id_staff,
            "nama_staff" => $nama_staff,
            "email" => $email,
            "no_hp" => $no_hp,
            "role" => $role, // Ini adalah data 'role' asli dari DB
            "gambar" => null // Tetap null untuk sekarang
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