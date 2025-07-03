<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';
include_once '../../models/menu.php';

$database = new Database();
$db = $database->getConnection();
$menu = new Menu($db);

$stmt = $db->prepare("SELECT * FROM menus WHERE is_available = 1");
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$menus = ["records" => $rows];
http_response_code(200);
echo json_encode($menus);
?>
