<?php
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');

class ProductController {
    private $productModel;
    private $db;
    
    public function __construct() {
        // Luôn bắt đầu session khi controller được khởi tạo
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $this->db = (new database())->getConnection();
        $this->productModel = new ProductModel($this->db);
    }

    // Kiểm tra quyền Admin
    private function isAdmin() {
        return SessionHelper::isAdmin();
    }


    public function index() {
        $products = $this->productModel->getProducts();
        include 'app/views/product/list.php';
    }

    public function show($id) {
        $product = $this->productModel->getProductById($id);
        if ($product) {
            include 'app/views/product/show.php';
        } else {
            echo "Không thấy sản phẩm.";
        }
    }

    // Thêm sản phẩm (chỉ Admin)
public function add() {
if (!$this->isAdmin()) {
echo "Bạn không có quyền truy cập chức năng này!";
exit;
}
$categories = (new CategoryModel($this->db))->getCategories();
include_once 'app/views/product/add.php';
}

    // Lưu sản phẩm mới (chỉ Admin)
    public function save() {
    if (!$this->isAdmin()) {
    echo "Bạn không có quyền truy cập chức năng này!";
    exit;
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? '';
    $category_id = $_POST['category_id'] ?? null;
    $image = (isset($_FILES['image']) && $_FILES['image']['error'] == 0)
    ? $this->uploadImage($_FILES['image'])
    : "";
    $result = $this->productModel->addProduct($name, $description, $price,
    $category_id, $image);
    if (is_array($result)) {
    $errors = $result;
    $categories = (new CategoryModel($this->db))->getCategories();
    include 'app/views/product/add.php';
    } else {
    header('Location: /Website-PhanBon/Product');
    }
    }
    }

    // Sửa sản phẩm (chỉ Admin)
public function edit($id) {
    if (!$this->isAdmin()) {
    echo "Bạn không có quyền truy cập chức năng này!";
    exit;
    }
    $product = $this->productModel->getProductById($id);
    $categories = (new CategoryModel($this->db))->getCategories();
    if ($product) {
    include 'app/views/product/edit.php';
    } else {
    echo "Không thấy sản phẩm.";
    }
    }

    // Cập nhật sản phẩm (chỉ Admin)
public function update() {
    if (!$this->isAdmin()) {
    echo "Bạn không có quyền truy cập chức năng này!";
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$id = $_POST['id'];
$name = $_POST['name'];
$description = $_POST['description'];
$price = $_POST['price'];
$category_id = $_POST['category_id'];
$image = (isset($_FILES['image']) && $_FILES['image']['error'] == 0)
? $this->uploadImage($_FILES['image'])
: $_POST['existing_image'];
$edit = $this->productModel->updateProduct($id, $name, $description,
$price, $category_id, $image);
if ($edit) {
header('Location: /Website-PhanBon/Product');
} else {
echo "Đã xảy ra lỗi khi lưu sản phẩm.";
}
}
}

    // Xóa sản phẩm (chỉ Admin)
public function delete($id) {
    if (!$this->isAdmin()) {
    echo "Bạn không có quyền truy cập chức năng này!";
    exit;
    }
    if ($this->productModel->deleteProduct($id)) {
    header('Location: /Website-PhanBon/Product');
    } else {
    echo "Đã xảy ra lỗi khi xóa sản phẩm.";
    }
    }

    private function uploadImage($file) {
        $document_root = $_SERVER['DOCUMENT_ROOT'];
        $target_dir = $document_root . "/Website-PhanBon/uploads/";

        if (!is_dir($target_dir)) {
            $old_umask = umask(0);
            $created = mkdir($target_dir, 0777, true);
            umask($old_umask);
            if (!$created) {
                throw new Exception("Không thể tạo thư mục uploads.");
            }
        }

        if (!is_writable($target_dir)) {
            @chmod($target_dir, 0777);
            if (!is_writable($target_dir)) {
                throw new Exception("Thư mục uploads không có quyền ghi.");
            }
        }

        $imageFileType = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        $unique_filename = uniqid() . '_' . time() . '.' . $imageFileType;
        $target_file = $target_dir . $unique_filename;

        if (getimagesize($file["tmp_name"]) === false) {
            throw new Exception("File không phải là hình ảnh.");
        }

        if ($file["size"] > 10 * 1024 * 1024) {
            throw new Exception("Hình ảnh có kích thước quá lớn (tối đa 10MB).");
        }

        if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
            throw new Exception("Chỉ cho phép các định dạng JPG, JPEG, PNG và GIF.");
        }

        if (!move_uploaded_file($file["tmp_name"], $target_file)) {
            throw new Exception("Có lỗi xảy ra khi tải lên hình ảnh.");
        }

        return "uploads/" . $unique_filename;
    }

    // ==================================================================
    // == PHẦN XỬ LÝ GIỎ HÀNG ==
    // ==================================================================

    public function cart() {
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        include 'app/views/product/cart.php';
    }

    /**
     * Thêm sản phẩm vào giỏ hàng (Xử lý bằng AJAX) - CẢI TIẾN
     */
    public function addToCart($id) {
        header('Content-Type: application/json');
        
        $product = $this->productModel->getProductById($id);
        
        if (!$product) {
            echo json_encode([
                'success' => false, 
                'message' => 'Không tìm thấy sản phẩm.'
            ]);
            exit();
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Thêm hoặc tăng số lượng
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity']++;
        } else {
            $_SESSION['cart'][$id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'image' => $product->image
            ];
        }

        // Tính tổng số lượng sản phẩm trong giỏ hàng
        $cartCount = 0;
        foreach ($_SESSION['cart'] as $item) {
            $cartCount += $item['quantity'];
        }

        echo json_encode([
            'success' => true,
            'message' => 'Đã thêm vào giỏ hàng!',
            'cartCount' => $cartCount
        ]);
        exit();
    }

    /**
     * Cập nhật số lượng sản phẩm trong giỏ hàng (Xử lý bằng AJAX)
     */
    public function updateCart() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || !isset($data['id']) || !isset($data['quantity'])) {
            echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ.']);
            exit;
        }

        $productId = $data['id'];
        $quantity = (int)$data['quantity'];

        if (isset($_SESSION['cart'][$productId]) && $quantity > 0) {
            $_SESSION['cart'][$productId]['quantity'] = $quantity;

            $item = $_SESSION['cart'][$productId];
            $itemSubtotal = $item['price'] * $item['quantity'];

            $cartTotal = 0;
            $cartCount = 0;
            foreach ($_SESSION['cart'] as $cartItem) {
                $cartTotal += $cartItem['price'] * $cartItem['quantity'];
                $cartCount += $cartItem['quantity'];
            }

            $response = [
                'success' => true,
                'itemSubtotal' => number_format($itemSubtotal, 0, ',', '.') . ' VND',
                'cartTotal' => number_format($cartTotal, 0, ',', '.') . ' VND',
                'cartCount' => $cartCount
            ];
        } else {
            $response = ['success' => false, 'message' => 'Sản phẩm không tồn tại hoặc số lượng không hợp lệ.'];
        }

        echo json_encode($response);
        exit;
    }

    /**
     * Xóa một sản phẩm khỏi giỏ hàng (Xử lý bằng AJAX)
     */
    public function removeFromCart() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || !isset($data['id'])) {
            echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ.']);
            exit;
        }

        $productId = $data['id'];

        if (isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);

            $cartTotal = 0;
            $cartCount = 0;
            foreach ($_SESSION['cart'] as $cartItem) {
                $cartTotal += $cartItem['price'] * $cartItem['quantity'];
                $cartCount += $cartItem['quantity'];
            }

            $response = [
                'success' => true,
                'cartTotal' => number_format($cartTotal, 0, ',', '.') . ' VND',
                'cartCount' => $cartCount,
                'cartEmpty' => empty($_SESSION['cart'])
            ];
        } else {
            $response = ['success' => false, 'message' => 'Sản phẩm không có trong giỏ hàng.'];
        }

        echo json_encode($response);
        exit;
    }

    // ==================================================================
    // == PHẦN XỬ LÝ THANH TOÁN ==
    // ==================================================================

    public function checkout() {
        include 'app/views/product/checkout.php';
    }

    public function processCheckout() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];
            
            if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
                echo "Giỏ hàng trống.";
                return;
            }
            
            $this->db->beginTransaction();
            
            try {
                // Lưu đơn hàng với status mặc định là 'pending'
                $query = "INSERT INTO orders (name, phone, address, status, payment_method, shipping_fee) 
                         VALUES (:name, :phone, :address, 'pending', 'COD', 0)";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':phone', $phone);
                $stmt->bindParam(':address', $address);
                $stmt->execute();
                $order_id = $this->db->lastInsertId();
                
                // Lưu chi tiết đơn hàng
                $cart = $_SESSION['cart'];
                foreach ($cart as $product_id => $item) {
                    $query = "INSERT INTO order_details (order_id, product_id, quantity, price) 
                             VALUES (:order_id, :product_id, :quantity, :price)";
                    $stmt = $this->db->prepare($query);
                    $stmt->bindParam(':order_id', $order_id);
                    $stmt->bindParam(':product_id', $product_id);
                    $stmt->bindParam(':quantity', $item['quantity']);
                    $stmt->bindParam(':price', $item['price']);
                    $stmt->execute();
                }
                
                unset($_SESSION['cart']);
                $this->db->commit();
                
                header('Location: /Website-PhanBon/Product/orderConfirmation');
            } catch (Exception $e) {
                $this->db->rollBack();
                echo "Đã xảy ra lỗi: " . $e->getMessage();
            }
        }
    }

    public function orderConfirmation() {
        include 'app/views/product/orderConfirmation.php';
    }
    // Thêm vào ProductController
public function manage() {
    if (!$this->isAdmin()) {
        echo "Bạn không có quyền truy cập chức năng này!";
        exit;
    }
    
    $products = $this->productModel->getProducts();
    $categories = (new CategoryModel($this->db))->getCategories();
    include 'app/views/product/manage.php';
}

    

}
?>