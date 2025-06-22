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

// Ambil ID menu dari URL
// Misalnya: http://localhost/sentra-coffee-backend/api/menu/read_single.php?id=1
$menu->id_menu = isset($_GET['id']) ? $_GET['id'] : die();

// Baca detail menu
if ($menu->readOne()) {
    // Buat array
    $menu_arr = array(
        "id_menu" => $menu->id_menu,
        "nama_menu" => $menu->nama_menu,
        "kategori" => $menu->kategori,
        "harga" => $menu->harga,
        "is_available" => $menu->is_available
    );

    // Set kode respons - 200 OK
    http_response_code(200);

    // Tampilkan dalam format JSON
    echo json_encode($menu_arr);
} else {
    // Jika menu tidak ditemukan - 404 Not found
    http_response_code(404);
    echo json_encode(array("message" => "Menu not found."));
}
?>