<?php
// sentra-coffee-backend/index.php

// Header standar untuk API
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); // Izinkan semua metode HTTP
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Handle pre-flight request (OPTIONS method)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Sertakan file koneksi database
include_once 'config/database.php';

// Mendapatkan path dari URL request
// Contoh: /api/menu/read -> array('api', 'menu', 'read')
$request_uri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

// Asumsikan base path adalah 'SentraCoffee'
// Anda mungkin perlu menyesuaikannya tergantung bagaimana Anda mengakses proyek di server lokal
$base_path_index = array_search('SentraCoffee', $request_uri);
if ($base_path_index !== false) {
    // Hapus bagian base path agar path yang tersisa hanya path API
    $request_uri = array_slice($request_uri, $base_path_index + 1);
}

// Minimal harus ada 'api' dan setidaknya 1 resource (misal: 'menu')
if (empty($request_uri) || $request_uri[0] !== 'api' || count($request_uri) < 2) {
    http_response_code(404);
    echo json_encode(array("message" => "API endpoint not found."));
    exit();
}

// Ambil resource dan action
$resource = isset($request_uri[1]) ? $request_uri[1] : ''; // e.g., 'menu', 'customer', 'transaction'
$action = isset($request_uri[2]) ? $request_uri[2] : '';   // e.g., 'read', 'create', 'login'
$id = isset($request_uri[3]) ? $request_uri[3] : '';       // e.g., '1' for read_single, update, delete

// Menentukan file endpoint yang akan disertakan
$endpoint_file = ''; // Inisialisasi kosong

// --- LOGIKA ROUTING BARU UNTUK 'transaction/create' ---
if ($resource === 'transaction' && $action === 'create') {
    $endpoint_file = 'api/transaction/create.php';
}
// --- AKHIR LOGIKA ROUTING BARU UNTUK 'transaction/create' ---
else if ($resource === 'customer' && $action === 'login') {
    $endpoint_file = 'api/customer/login.php';
}
else if ($action === 'read' && !empty($id)) {
    // Jika action read dan ada ID, arahkan ke read_single
    $endpoint_file = 'api/' . $resource . '/read_single.php';
    $_GET['id'] = $id; // Melewatkan ID via GET parameter
} else {
    // Routing default untuk action lainnya (read, create, update, delete)
    $endpoint_file = 'api/' . $resource . '/' . $action . '.php';
}

// Cek apakah file endpoint ada
if (file_exists($endpoint_file)) {
    include_once $endpoint_file;
} else {
    http_response_code(404);
    echo json_encode(array("message" => "Endpoint for " . $resource . "/" . $action . " not found."));
}

?>