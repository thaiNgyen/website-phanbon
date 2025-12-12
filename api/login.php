<?php
header("Content-Type: application/json");

require_once __DIR__ . '/../app/config/database.php';

$database = new Database();
$db = $database->getConnection();

// Nhận JSON từ Flutter
$data = json_decode(file_get_contents("php://input"), true);

if (!$data || empty($data['username']) || empty($data['password'])) {
    echo json_encode(["success" => false, "message" => "Missing username or password"]);
    exit;
}

// Tìm user theo username
$sql = "SELECT * FROM account WHERE username = :u LIMIT 1";
$stmt = $db->prepare($sql);
$stmt->execute([":u" => $data['username']]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo json_encode(["success" => false, "message" => "User not found"]);
    exit;
}

// Kiểm tra mật khẩu
if (!password_verify($data['password'], $user['password'])) {
    echo json_encode(["success" => false, "message" => "Wrong password"]);
    exit;
}

// Đăng nhập thành công → trả thông tin user
echo json_encode([
    "success" => true,
    "message" => "Login success",
    "data" => [
        "id"       => $user['id'],
        "username" => $user['username'],
        "fullname" => $user['fullname'],
        "role"     => $user['role']
    ]
]);
