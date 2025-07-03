<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}


include_once '../../config/database.php';
include_once '../../models/Menu.php';


$database = new Database();
$db = $database->getConnection();

$menu = new Menu($db);

$stmt = $menu->read();
$num = $stmt->rowCount();

if ($num > 0) {
    $menus_arr = array();
    $menus_arr["records"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        extract($row); 

        $menu_item = array(
            "id_menu" => $id_menu,
            "nama_menu" => $nama_menu,
            "kategori" => $kategori,
            "harga" => $harga,
            "is_available" => $is_available, 
            "gambar" => $gambar
        );

        array_push($menus_arr["records"], $menu_item);
    }

    http_response_code(200);
    echo json_encode($menus_arr);
} else {
    http_response_code(404);
    echo json_encode(
        array("records" => [], "message" => "No menus found.")
    );
}
?>