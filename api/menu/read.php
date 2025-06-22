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

// Query menu
$stmt = $menu->read();
$num = $stmt->rowCount();

// Cek apakah ada record yang ditemukan
if ($num > 0) {
    // Array menu
    $menus_arr = array();
    $menus_arr["records"] = array();

    // Ambil setiap baris
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row); // Extract $row ke variabel ($id_menu, $nama_menu, dll)

        $menu_item = array(
            "id_menu" => $id_menu,
            "nama_menu" => $nama_menu,
            "kategori" => $kategori,
            "harga" => $harga,
            "is_available" => $is_available ? true : false // Convert TINYINT(1) to boolean
        );
        array_push($menus_arr["records"], $menu_item);
    }

    // Set kode respons - 200 OK
    http_response_code(200);

    // Tampilkan data dalam format JSON
    echo json_encode($menus_arr);
} else {
    // Jika tidak ada menu ditemukan - 404 Not found
    http_response_code(404);
    echo json_encode(array("message" => "No menus found."));
}
?>