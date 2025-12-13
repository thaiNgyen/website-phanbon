<?php
header("Content-Type: application/json");
require_once __DIR__ . "/../app/config/database.php";

$database = new database();
$conn = $database->getConnection();

$order_id = $_GET['order_id'] ?? '';

if ($order_id == '') {
    echo json_encode(["success" => false, "message" => "Missing order_id"]);
    exit;
}

$sqlOrder = "SELECT * FROM orders WHERE id = ?";
$stmt = $conn->prepare($sqlOrder);
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo json_encode(["success" => false, "message" => "Order not found"]);
    exit;
}

$sqlDetail = "SELECT od.*, p.name, p.image 
              FROM order_details od
              JOIN product p ON p.id = od.product_id
              WHERE od.order_id = ?";
$stmt2 = $conn->prepare($sqlDetail);
$stmt2->execute([$order_id]);
$items = $stmt2->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    "success" => true,
    "order" => $order,
    "items" => $items
]);
