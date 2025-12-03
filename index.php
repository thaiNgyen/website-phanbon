<?php
// Bắt đầu session nếu chưa được khởi động
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Hiển thị lỗi để dễ dàng gỡ lỗi
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Nạp các tệp cần thiết
require_once 'app/models/ProductModel.php';
require_once 'app/helpers/SessionHelper.php';

// Xử lý URL
// ví dụ: /Product/add
$url = $_GET['url'] ?? '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

// Kiểm tra phần đầu tiên của URL để xác định controller
// Nếu không có, controller mặc định sẽ là 'DefaultController'
$controllerName = isset($url[0]) && $url[0] != '' ? ucfirst($url[0]) . 'Controller' : 'DefaultController';

// Kiểm tra phần thứ hai của URL để xác định action (phương thức)
// Nếu không có, action mặc định sẽ là 'index'
$action = isset($url[1]) && $url[1] != '' ? $url[1] : 'index';

// Kiểm tra xem tệp controller có tồn tại không
if (!file_exists('app/controllers/' . $controllerName . '.php')) {
    // Xử lý không tìm thấy controller
    die('Controller not found: ' . $controllerName);
}

// Nạp tệp controller
require_once 'app/controllers/' . $controllerName . '.php';

// Tạo đối tượng từ controller
$controller = new $controllerName();

// Kiểm tra xem phương thức (action) có tồn tại trong controller không
if (!method_exists($controller, $action)) {
    // Xử lý không tìm thấy action
    die('Action not found in ' . $controllerName);
}

// Gọi action với các tham số còn lại từ URL (nếu có)
call_user_func_array([$controller, $action], array_slice($url, 2));