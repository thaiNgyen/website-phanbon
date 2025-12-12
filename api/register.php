<?php
header("Content-Type: application/json");

// Nạp file cần thiết
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/models/User.php';

// Nhận dữ liệu POST từ Flutter
$data = json_decode(file_get_contents("php://input"), true);

if (!$data || empty($data['username']) || empty($data['password']) || empty($data['fullname'])) {
    echo json_encode([
        "success" => false,
        "message" => "Missing required fields"
    ]);
    exit;
}

// Kết nối DB
$database = new Database();
$db = $database->getConnection();

// Kiểm tra username đã tồn tại chưa
$check = $db->prepare("SELECT id FROM account WHERE username = :u");
$check->execute([":u" => $data['username']]);

if ($check->rowCount() > 0) {
    echo json_encode([
        "success" => false,
        "message" => "Username already exists"
    ]);
    exit;
}

// Tạo User Model
$userModel = new User($db);

// Tạo user mới
$created = $userModel->create([
    "username" => $data['username'],
    "fullname" => $data['fullname'],
    "password" => $data['password'],
    "role"     => "user"   // mặc định user
]);

echo json_encode([
    "success" => $created,
    "message" => $created ? "Register success" : "Register failed"
]);
