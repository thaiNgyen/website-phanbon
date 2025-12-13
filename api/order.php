<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");
require_once __DIR__ . "/../app/config/database.php";

// Lấy kết nối DB
$db = new database();
$conn = $db->getConnection();

$input = json_decode(file_get_contents("php://input"), true);

// Kiểm tra dữ liệu hợp lệ
if (!$input || !isset($input["items"])) {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
    exit;
}

$username = $input["username"] ?? "";
$fullname = $input["fullname"] ?? "";
$phone = $input["phone"] ?? "";
$address = $input["address"] ?? "";
$payment = $input["payment_method"] ?? "COD";
$note = $input["note"] ?? "";
$items = $input["items"];

// =============== 1️⃣ Lưu đơn hàng ===============
try {
    $sql = "INSERT INTO orders (name, phone, address, payment_method, note)
        VALUES (:name, :phone, :address, :payment, :note)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ":name" => $fullname,   // dùng fullname làm tên
        ":phone" => $phone,
        ":address" => $address,
        ":payment" => $payment,
        ":note" => $note,
    ]);

    $order_id = $conn->lastInsertId();

} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Order insert failed", "error" => $e->getMessage()]);
    exit;
}

// =============== 2️⃣ Lưu từng sản phẩm trong giỏ ===============
try {
    $sql_detail = "INSERT INTO order_details (order_id, product_id, quantity, price)
                   VALUES (:oid, :pid, :qty, :price)";
    $stmt_detail = $conn->prepare($sql_detail);

    foreach ($items as $it) {
        $stmt_detail->execute([
            ":oid" => $order_id,
            ":pid" => $it["id"],
            ":qty" => $it["quantity"],
            ":price" => $it["price"]
        ]);
    }

} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Detail insert failed", "error" => $e->getMessage()]);
    exit;
}

// =============== 3️⃣ Trả về JSON cho Flutter ===============
echo json_encode([
    "success" => true,
    "order_id" => $order_id,
    "message" => "Order created"
]);
