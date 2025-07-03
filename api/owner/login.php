<?php
// Headers Wajib untuk API
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


if (!empty($data->email) && !empty($data->password)) {

    $email = $data->email;
    $password = $data->password;

    // Query untuk mengecek apakah owner dengan email tersebut ada
    $query = "SELECT id_owner, nama_owner, email, password FROM owners WHERE email = ? LIMIT 1";

  
    $stmt = $db->prepare($query);

    $stmt->bindParam(1, $email);

    $stmt->execute();
    $num = $stmt->rowCount();

    
    if ($num > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $id_owner = $row['id_owner'];
        $nama_owner = $row['nama_owner'];
        $hashed_password_from_db = $row['password'];

       
        
        if ($password == $hashed_password_from_db) {
            
            http_response_code(200); 
            echo json_encode(array(
                "success" => true,
                "message" => "Login berhasil.",
                "data" => array(
                    "id_owner" => $id_owner,
                    "nama_owner" => $nama_owner,
                    "email" => $email
                )
            ));
        } else {
            
            http_response_code(401); 
            echo json_encode(array("success" => false, "message" => "Login gagal. Password salah."));
        }
    } else {
        
        http_response_code(401); 
        echo json_encode(array("success" => false, "message" => "Login gagal. Email tidak terdaftar."));
    }
} else {
    
    http_response_code(400); 
    echo json_encode(array("success" => false, "message" => "Login gagal. Data tidak lengkap."));
}
?>