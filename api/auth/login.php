<?php
// Headers Wajib untuk API
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Handle pre-flight request (OPTIONS method)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Include file koneksi database
include_once '../../config/database.php';

// Inisialisasi koneksi database
$database = new Database();
$db = $database->getConnection();

// Ambil data POST dari body request (JSON)
$data = json_decode(file_get_contents("php://input"));

// Pastikan data email dan password tidak kosong
if (!empty($data->email) && !empty($data->password)) {

    $email = htmlspecialchars(strip_tags($data->email));
    $password = $data->password;

    // --- Langkah 1: Cek di tabel 'owners' (admin) ---
    $query_owner = "SELECT id_owner, nama_owner, email, password FROM owners WHERE email = ? LIMIT 1";
    $stmt_owner = $db->prepare($query_owner);
    $stmt_owner->bindParam(1, $email);
    $stmt_owner->execute();

    if ($stmt_owner->rowCount() > 0) {
        $row = $stmt_owner->fetch(PDO::FETCH_ASSOC);
        // Verifikasi password (Di dunia nyata, gunakan password_verify)
        if ($password == $row['password']) {
            http_response_code(200);
            echo json_encode([
                "success" => true,
                "message" => "Login admin berhasil.",
                "role" => "admin", // Kirim role sebagai admin
                "data" => [
                    "id_owner" => $row['id_owner'],
                    "nama_owner" => $row['nama_owner'],
                    "email" => $row['email']
                ]
            ]);
            exit(); // Hentikan eksekusi jika sudah berhasil
        }
    }

    // --- Langkah 2: Jika bukan owner, cek di tabel 'customers' ---
    $query_customer = "SELECT id_customer, nama, email, password, no_hp FROM customers WHERE email = ? LIMIT 1";
    $stmt_customer = $db->prepare($query_customer);
    $stmt_customer->bindParam(1, $email);
    $stmt_customer->execute();

    if ($stmt_customer->rowCount() > 0) {
        $row = $stmt_customer->fetch(PDO::FETCH_ASSOC);
        // Verifikasi password
        if ($password == $row['password']) {
            http_response_code(200);
            echo json_encode([
                "success" => true,
                "message" => "Login customer berhasil.",
                "role" => "customer", // Kirim role sebagai customer
                "data" => [
                    "id_customer" => intval($row['id_customer']),
                    "nama" => $row['nama'],
                    "email" => $row['email'],
                    "no_hp" => $row['no_hp']
                ]
            ]);
            exit();
        }
    }

    // --- Langkah 3: Jika tidak ditemukan di mana pun atau password salah ---
    http_response_code(401); // Unauthorized
    echo json_encode(["success" => false, "message" => "Login gagal. Email atau password salah."]);

} else {
    // Data tidak lengkap
    http_response_code(400); // Bad Request
    echo json_encode(["success" => false, "message" => "Email dan password tidak boleh kosong."]);
}
?>