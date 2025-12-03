<?php
// Chỉ bắt đầu session nếu chưa có session nào được khởi động
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../helpers/SessionHelper.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;600;800&display=swap" rel="stylesheet">

    <style>
    .cart-link-wrapper {
        position: relative;
        display: inline-block;
    }

    .cart-link-wrapper a {
        text-decoration: none;
        color: inherit;
        display: flex;
        align-items: center;
        gap: 5px;
        padding: 8px 15px;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .cart-link-wrapper a:hover {
        background-color: rgba(40, 167, 69, 0.1);
    }

    .cart-count-badge {
        position: absolute;
        top: -8px;
        right: -8px;
        background-color: #dc3545;
        color: white;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: bold;
        transition: all 0.3s ease;
    }

    @keyframes cartCountBounce {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.3);
        }

        100% {
            transform: scale(1);
        }
    }

    .cart-count-badge.bounce {
        animation: cartCountBounce 0.5s ease;
    }

    .fancy-logo {
        font-family: 'Be Vietnam Pro', sans-serif;
        font-weight: 800;
        font-size: 1.8rem;
        background: linear-gradient(90deg, #2ecc71, #27ae60);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.15);
        letter-spacing: 1px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .fancy-logo span {
        color: #1B5E20;
        -webkit-text-fill-color: #1B5E20;
    }

    .fancy-logo i {
        color: #27ae60;
        font-size: 2.4rem;
    }

    /* Admin Dropdown Styles */
    .admin-dropdown {
        position: relative;
    }

    .admin-dropdown .dropdown-menu {
        min-width: 320px;
        max-height: 600px;
        overflow-y: auto;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        border: none;
        padding: 0;
        margin-top: 0.5rem;
    }

    .admin-dropdown .dropdown-header {
        background: linear-gradient(135deg, #00A74F 0%, #00c853 100%);
        color: white;
        font-weight: 700;
        font-size: 1.1rem;
        padding: 1rem 1.5rem;
        border-radius: 12px 12px 0 0;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .admin-dropdown .dropdown-divider {
        margin: 0;
        border-color: #e9ecef;
    }

    .admin-dropdown .section-header {
        background-color: #f8f9fa;
        color: #00A74F;
        font-weight: 700;
        font-size: 0.85rem;
        padding: 0.75rem 1.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin: 0;
    }

    .admin-dropdown .dropdown-item {
        padding: 0.75rem 1.5rem;
        transition: all 0.2s ease;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        color: #212529;
    }

    .admin-dropdown .dropdown-item:hover {
        background: linear-gradient(90deg, #e8f5e9 0%, #f1f8f4 100%);
        padding-left: 2rem;
        color: #00A74F;
    }

    .admin-dropdown .dropdown-item i {
        width: 24px;
        margin-right: 10px;
        font-size: 1.1rem;
    }

    .admin-dropdown .dropdown-item .badge {
        margin-left: auto;
        font-size: 0.75rem;
    }

    .admin-nav-link {
        position: relative;
        padding: 0.5rem 1rem !important;
        border-radius: 8px;
        transition: all 0.3s ease;
        font-weight: 600;
        color: #00A74F !important;
        background: linear-gradient(135deg, rgba(0, 167, 79, 0.1) 0%, rgba(0, 200, 83, 0.1) 100%);
    }

    .admin-nav-link:hover {
        background: linear-gradient(135deg, rgba(0, 167, 79, 0.2) 0%, rgba(0, 200, 83, 0.2) 100%);
        transform: translateY(-2px);
    }

    .admin-nav-link i {
        margin-right: 6px;
        font-size: 1.2rem;
    }

    /* Scrollbar cho dropdown */
    .admin-dropdown .dropdown-menu::-webkit-scrollbar {
        width: 6px;
    }

    .admin-dropdown .dropdown-menu::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .admin-dropdown .dropdown-menu::-webkit-scrollbar-thumb {
        background: #00A74F;
        border-radius: 3px;
    }

    .admin-dropdown .dropdown-menu::-webkit-scrollbar-thumb:hover {
        background: #008A42;
    }

    /* Badge cho số lượng */
    .admin-dropdown .item-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 600;
    }

    .badge-danger {
        background-color: #dc3545;
    }

    .badge-warning {
        background-color: #ffc107;
        color: #000;
    }

    .badge-info {
        background-color: #17a2b8;
    }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand fancy-logo" href="/Website-PhanBon/Product/">
            <i class="bi bi-leaf-fill me-2"></i>PHÂN BÓN <span>VIỆT</span>
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto">
                <!-- Trang chủ (mọi người đều thấy) -->
                <li class="nav-item">
                    <a class="nav-link font-weight-bold text-danger" href="/Website-PhanBon/Product/">
                        <i class="bi bi-house-door-fill"></i> TRANG CHỦ
                    </a>
                </li>

                <!-- Dropdown Quản Trị Admin (CHỈ ADMIN THẤY) -->
                <?php if (SessionHelper::isAdmin()): ?>
                <li class="nav-item dropdown admin-dropdown">
                    <a class="nav-link dropdown-toggle admin-nav-link" href="#" id="adminDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="bi bi-speedometer2"></i> QUẢN TRỊ ADMIN
                    </a>
                    <div class="dropdown-menu" aria-labelledby="adminDropdown">
                        <!-- Header -->
                        <div class="dropdown-header">
                            <i class="bi bi-shield-check"></i> BẢNG ĐIỀU KHIỂN
                        </div>

                        <!-- Dashboard & Reports -->
                        <h6 class="section-header">
                            <i class="bi bi-graph-up me-2"></i> Tổng Quan & Báo Cáo
                        </h6>
                        <a class="dropdown-item" href="/Website-PhanBon/Admin/dashboard">
                            <i class="bi bi-speedometer2 text-primary"></i>
                            <span>Dashboard Tổng Quan</span>
                        </a>
                        <a class="dropdown-item" href="/Website-PhanBon/Admin/reports">
                            <i class="bi bi-file-earmark-bar-graph text-info"></i>
                            <span>Báo Cáo Chi Tiết</span>
                        </a>

                        <div class="dropdown-divider"></div>

                        <!-- Quản Lý Sản Phẩm -->
                        <h6 class="section-header">
                            <i class="bi bi-box-seam me-2"></i> Quản Lý Sản Phẩm
                        </h6>
                        <a class="dropdown-item" href="/Website-PhanBon/Product/manage">
                            <i class="bi bi-grid-3x3-gap text-success"></i>
                            <span>Danh Sách Sản Phẩm</span>
                            <?php
                            // Đếm số sản phẩm
                            $db = (new Database())->getConnection();
                            $stmt = $db->query("SELECT COUNT(*) as count FROM product");
                            $productCount = $stmt->fetch(PDO::FETCH_OBJ)->count;
                            ?>
                            <span class="badge badge-info item-badge"><?php echo $productCount; ?></span>
                        </a>
                        <a class="dropdown-item" href="/Website-PhanBon/Category/list">
                            <i class="bi bi-tags text-warning"></i>
                            <span>Quản Lý Danh Mục</span>
                        </a>

                        <div class="dropdown-divider"></div>

                        <!-- Quản Lý Đơn Hàng -->
                        <h6 class="section-header">
                            <i class="bi bi-cart-check me-2"></i> Quản Lý Bán Hàng
                        </h6>
                        <a class="dropdown-item" href="/Website-PhanBon/Order/manage">
                            <i class="bi bi-receipt-cutoff text-danger"></i>
                            <span>Quản Lý Đơn Hàng</span>
                            <?php
                            // Đếm đơn hàng chờ xử lý
                            $stmt = $db->query("SELECT COUNT(*) as count FROM orders WHERE status = 'pending'");
                            $pendingOrders = $stmt->fetch(PDO::FETCH_OBJ)->count;
                            if ($pendingOrders > 0):
                            ?>
                            <span class="badge badge-danger item-badge"><?php echo $pendingOrders; ?> mới</span>
                            <?php endif; ?>
                        </a>
                        <a class="dropdown-item" href="/Website-PhanBon/Inventory/index">
                            <i class="bi bi-box text-secondary"></i>
                            <span>Quản Lý Tồn Kho</span>
                        </a>

                        <div class="dropdown-divider"></div>

                        <!-- Cài Đặt -->
                        <h6 class="section-header">
                            <i class="bi bi-gear me-2"></i> Hệ Thống
                        </h6>
                        <a class="dropdown-item" href="/Website-PhanBon/Admin/settings">
                            <i class="bi bi-sliders text-primary"></i>
                            <span>Cài Đặt Hệ Thống</span>
                        </a>
                        <a class="dropdown-item" href="/Website-PhanBon/User/manage">
                            <i class="bi bi-people text-info"></i>
                            <span>Quản Lý Người Dùng</span>
                        </a>

                        <a class="dropdown-item" href="/Website-PhanBon/User/activityLogs">
                            <i class="bi bi-journal-text text-warning"></i>
                            <span>Nhật Ký Hoạt Động</span>
                        </a>
                    </div>
                </li>
                <?php endif; ?>
            </ul>

            <ul class="navbar-nav">
                <!-- Giỏ hàng -->
                <li class="nav-item">
                    <div class="cart-link-wrapper">
                        <a id="cart-nav-link" href="/Website-PhanBon/Product/cart">
                            <i class="bi bi-cart3"></i>
                            <span>Giỏ hàng</span>
                            <?php
                            $cartCount = 0;
                            if (isset($_SESSION['cart'])) {
                                foreach ($_SESSION['cart'] as $item) {
                                    $cartCount += $item['quantity'];
                                }
                            }
                            ?>
                            <span id="cart-count-badge" class="cart-count-badge"
                                style="display: <?php echo ($cartCount > 0) ? 'flex' : 'none'; ?>;">
                                <?php echo $cartCount; ?>
                            </span>
                        </a>
                    </div>
                </li>

                <!-- Hiển thị username + role nếu đã đăng nhập -->
                <li class="nav-item">
                    <?php
                    if (SessionHelper::isLoggedIn()) {
                        echo '<a class="nav-link"><i class="bi bi-person-circle"></i> ' 
                            . htmlspecialchars($_SESSION['username']) . 
                             ' <span class="badge badge-success">(' . SessionHelper::getRole() . ')</span></a>';
                    } else {
                        echo '<a class="nav-link" href="/Website-PhanBon/account/login">
                                <i class="bi bi-box-arrow-in-right"></i> Đăng nhập
                              </a>';
                    }
                    ?>
                </li>

                <!-- Hiển thị nút đăng xuất nếu đã đăng nhập -->
                <li class="nav-item">
                    <?php
                    if (SessionHelper::isLoggedIn()) {
                        echo '<a class="nav-link text-danger" href="/Website-PhanBon/account/logout">
                                <i class="bi bi-box-arrow-right"></i> Đăng xuất
                              </a>';
                    }
                    ?>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4"></div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
    // Hàm cập nhật số lượng giỏ hàng trong header
    function updateCartCount(newCount) {
        const badge = document.getElementById('cart-count-badge');

        if (badge) {
            if (newCount > 0) {
                badge.textContent = newCount;
                badge.style.display = 'flex';

                badge.classList.remove('bounce');
                void badge.offsetWidth; // Trigger reflow
                badge.classList.add('bounce');

                setTimeout(() => {
                    badge.classList.remove('bounce');
                }, 500);
            } else {
                badge.style.display = 'none';
            }
        }
    }

    document.addEventListener('cartUpdated', function(e) {
        if (e.detail && e.detail.cartCount !== undefined) {
            updateCartCount(e.detail.cartCount);
        }
    });

    // Đóng dropdown khi click outside
    document.addEventListener('click', function(event) {
        const dropdown = document.querySelector('.admin-dropdown');
        if (dropdown && !dropdown.contains(event.target)) {
            const dropdownMenu = dropdown.querySelector('.dropdown-menu');
            if (dropdownMenu && dropdownMenu.classList.contains('show')) {
                dropdownMenu.classList.remove('show');
            }
        }
    });
    </script>
</body>

</html>