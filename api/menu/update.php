<?php
// PASTIKAN BARIS HEADER INI ADA DI PALING ATAS SETIAP FILE ENDPOINT API LO!
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS"); // Izinkan semua metode
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Handle pre-flight request (OPTIONS method) - Ini penting untuk preflight request CORS
// Ini harus ada di SETIAP FILE ENDPOINT, BUKAN HANYA index.php
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200); // Harus 200 OK untuk OPTIONS
    exit(); // Harus exit setelah merespons OPTIONS
}

// ... KODE PHP LAINNYA (include, inisialisasi database, logic utama) ...

// Sertakan file koneksi database dan model
include_once '../../config/database.php';
include_once '../../models/Menu.php';

// Inisialisasi koneksi database
$database = new Database();
$db = $database->getConnection();

// Inisialisasi objek Menu
$menu = new Menu($db);

// Ambil data dari body request (JSON)
$data = json_decode(file_get_contents("php://input"));

// Pastikan data tidak kosong
if (!empty($data->id_menu) && !empty($data->nama_menu) && !empty($data->kategori) && !empty($data->harga)) {
    // Set ID menu yang akan diupdate
    $menu->id_menu = $data->id_menu;

    // Set nilai properti menu
    $menu->nama_menu = $data->nama_menu;
    $menu->kategori = $data->kategori;
    $menu->harga = $data->harga;
    // Gunakan nilai dari data request, jika tidak ada, biarkan default true
    $menu->is_available = isset($data->is_available) ? $data->is_available : true;

    // Update menu
    if ($menu->update()) {
        // Set kode respons - 200 OK
        http_response_code(200);
        echo json_encode(array("message" => "Menu was updated."));
    } else {
        // Jika gagal update menu - 503 Service Unavailable
        http_response_code(503);
        echo json_encode(array("message" => "Unable to update menu."));
    }
} else {
    // Jika data tidak lengkap - 400 Bad Request
    http_response_code(400);
    echo json_encode(array("message" => "Unable to update menu. Data is incomplete or ID is missing."));
}
?>