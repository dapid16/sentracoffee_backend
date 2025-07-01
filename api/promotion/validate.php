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

$data = json_decode(file_get_contents("php://input"));

if(empty($data->promo_name) || !isset($data->total_price)){
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Nama promo dan total harga dibutuhkan."]);
    exit();
}

try {
    $database = new Database();
    $db = $database->getConnection();

    $query = "SELECT * FROM promotions WHERE promo_name = :promo_name AND is_active = 1 LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':promo_name', $data->promo_name);
    $stmt->execute();

    if($stmt->rowCount() > 0){
        $promo = $stmt->fetch(PDO::FETCH_ASSOC);
        $total_price = (float)$data->total_price;
        $discount_value = (float)$promo['discount_value'];
        $discount_amount = 0;

        if($promo['discount_type'] == 'persen'){
            $discount_amount = $total_price * ($discount_value / 100);
        } else {
            $discount_amount = $discount_value;
        }
        
        if ($discount_amount > $total_price) {
            $discount_amount = $total_price;
        }

        $new_total = $total_price - $discount_amount;

        http_response_code(200);
        echo json_encode([
            "success" => true,
            "message" => "Promo berhasil digunakan.",
            "promo_name" => $promo['promo_name'],
            "discount_amount" => $discount_amount,
            "new_total_price" => $new_total
        ]);

    } else {
        http_response_code(404);
        echo json_encode(["success" => false, "message" => "Nama promo tidak valid atau sudah tidak aktif."]);
    }

} catch (Exception $e) {
    http_response_code(503);
    echo json_encode(["success" => false, "message" => "Terjadi kesalahan pada server.", "error" => $e->getMessage()]);
}
?>