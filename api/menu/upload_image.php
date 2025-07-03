<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

$target_dir = "../../uploads/";


if (!is_dir($target_dir)) {
    http_response_code(500);
    echo json_encode(array("success" => false, "message" => "Server error: Target directory 'uploads' does not exist."));
    exit();
}


if (!is_writable($target_dir)) {
    http_response_code(500);
    echo json_encode(array("success" => false, "message" => "Server error: Target directory 'uploads' is not writable. Check permissions."));
    exit();
}

if (!isset($_FILES['image'])) {
    http_response_code(400);
    echo json_encode(array("success" => false, "message" => "No file key 'image' was sent in the request."));
    exit();
}


$upload_error = $_FILES['image']['error'];
if ($upload_error !== UPLOAD_ERR_OK) {
    $error_messages = [
        UPLOAD_ERR_INI_SIZE   => 'File is larger than upload_max_filesize in php.ini.',
        UPLOAD_ERR_FORM_SIZE  => 'File is larger than MAX_FILE_SIZE specified in the form.',
        UPLOAD_ERR_PARTIAL    => 'File was only partially uploaded.',
        UPLOAD_ERR_NO_FILE    => 'No file was uploaded.',
        UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder.',
        UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
        UPLOAD_ERR_EXTENSION  => 'A PHP extension stopped the file upload.'
    ];
    $message = isset($error_messages[$upload_error]) ? $error_messages[$upload_error] : 'Unknown upload error.';
    
    http_response_code(500);
    echo json_encode(array("success" => false, "message" => "PHP Upload Error: " . $message));
    exit();
}


$image_name = uniqid() . '-' . basename($_FILES["image"]["name"]);
$target_file = $target_dir . $image_name;

if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
    http_response_code(200);
    echo json_encode(array(
        "success" => true,
        "message" => "Image uploaded successfully.",
        "filename" => $image_name
    ));
} else {
    http_response_code(503);
    echo json_encode(array("success" => false, "message" => "Server failed to move the uploaded file."));
}
?>