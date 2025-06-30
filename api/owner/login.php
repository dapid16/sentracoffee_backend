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

    $email = $data->email;
    $password = $data->password;

    // Query untuk mengecek apakah owner dengan email tersebut ada
    $query = "SELECT id_owner, nama_owner, email, password FROM owners WHERE email = ? LIMIT 1";

    // Persiapkan statement
    $stmt = $db->prepare($query);

    // Bind parameter email
    $stmt->bindParam(1, $email);

    // Eksekusi query
    $stmt->execute();
    $num = $stmt->rowCount();

    // Jika owner ditemukan (ada 1 baris)
    if ($num > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $id_owner = $row['id_owner'];
        $nama_owner = $row['nama_owner'];
        $hashed_password_from_db = $row['password'];

        // --- Verifikasi Password ---
        // CATATAN PENTING UNTUK TUGAS RPL:
        // Di aplikasi nyata, JANGAN PERNAH simpan password sebagai teks biasa.
        // Gunakan `password_hash()` saat register dan `password_verify()` di sini.
        // Untuk sekarang, kita pakai perbandingan biasa karena data di DB lo masih teks biasa.
        
        if ($password == $hashed_password_from_db) {
            // Jika password cocok
            http_response_code(200); // 200 OK
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
            // Jika password salah
            http_response_code(401); // 401 Unauthorized
            echo json_encode(array("success" => false, "message" => "Login gagal. Password salah."));
        }
    } else {
        // Jika email tidak ditemukan
        http_response_code(401); // 401 Unauthorized
        echo json_encode(array("success" => false, "message" => "Login gagal. Email tidak terdaftar."));
    }
} else {
    // Jika data email atau password tidak dikirim
    http_response_code(400); // 400 Bad Request
    echo json_encode(array("success" => false, "message" => "Login gagal. Data tidak lengkap."));
}
?>