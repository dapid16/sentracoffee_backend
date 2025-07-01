<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, OPTIONS");

// Handle pre-flight
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

include_once '../../config/database.php';

// Cek apakah id_customer dikirim
if (!isset($_GET['id_customer'])) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Parameter id_customer tidak ditemukan."]);
    exit();
}

$id_customer = htmlspecialchars(strip_tags($_GET['id_customer']));

$database = new Database();
$db = $database->getConnection();

$query = "
    SELECT 
        id_point_history,
        points_change,
        type,
        history_date,
        description
    FROM 
        loyalty_points_history
    WHERE 
        id_customer = ?
    ORDER BY 
        history_date DESC
";

try {
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $id_customer);
    $stmt->execute();

    $num = $stmt->rowCount();

    if ($num > 0) {
        $history_arr = [];
        $history_arr["records"] = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $history_item = array(
                "id_point_history" => $id_point_history,
                "points_change" => $points_change,
                "type" => $type,
                "history_date" => $history_date,
                "description" => $description
            );
            array_push($history_arr["records"], $history_item);
        }
        http_response_code(200);
        echo json_encode($history_arr);
    } else {
        http_response_code(404);
        echo json_encode(["records" => [], "message" => "Tidak ada riwayat poin ditemukan."]);
    }
} catch (Exception $e) {
    http_response_code(503);
    echo json_encode(["success" => false, "message" => "Service error.", "error" => $e->getMessage()]);
}
?>  