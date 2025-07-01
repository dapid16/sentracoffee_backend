<?php
// sentra-coffee-backend/index.php

// Header standar untuk API
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

include_once 'config/database.php';

$request_uri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
$base_path_index = array_search('SentraCoffee', $request_uri);

if ($base_path_index !== false) {
    $request_uri = array_slice($request_uri, $base_path_index + 1);
}

if (empty($request_uri) || $request_uri[0] !== 'api' || count($request_uri) < 2) {
    http_response_code(404);
    echo json_encode(array("message" => "API endpoint not found."));
    exit();
}

$resource = isset($request_uri[1]) ? $request_uri[1] : '';
$action = isset($request_uri[2]) ? $request_uri[2] : '';
$id = isset($request_uri[3]) ? $request_uri[3] : '';

$endpoint_file = '';

// --- LOGIKA ROUTING ---
// Aturan spesifik diletakkan di atas aturan umum
if ($resource === 'transaction' && $action === 'create') {
    $endpoint_file = 'api/transaction/create.php';
} 
else if ($resource === 'report' && $action === 'wallet') {
    $endpoint_file = 'api/report/wallet.php';
}
else if ($resource === 'customer' && $action === 'loyalty_history') {
    $endpoint_file = 'api/customer/loyalty_history.php';
}
else if ($resource === 'customer' && $action === 'login') {
    $endpoint_file = 'api/customer/login.php';
} 
// +++ TAMBAHKAN BLOK INI UNTUK PROMO +++
else if ($resource === 'promotion') {
    switch ($action) {
        case 'create':
            $endpoint_file = 'api/promotion/create.php';
            break;
        case 'read':
            $endpoint_file = 'api/promotion/read.php';
            break;
        case 'read_active':
            $endpoint_file = 'api/promotion/read_active.php';
            break;
        case 'update_status':
            $endpoint_file = 'api/promotion/update_status.php';
            break;
        default:
            // Biarkan kosong agar jatuh ke 404 di bawah
            break;
    }
}
// +++ AKHIR BLOK TAMBAHAN +++
else if ($action === 'read' && !empty($id)) {
    $endpoint_file = 'api/' . $resource . '/read_single.php';
    $_GET['id'] = $id;
} else {
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