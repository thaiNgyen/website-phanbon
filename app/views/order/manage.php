<?php include 'app/views/shares/header.php'; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Đơn Hàng</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #00A74F;
            --secondary-color: #00c853;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
        }

        body {
            background-color: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .page-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 2.5rem 0;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .stats-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: transform 0.2s;
            border-left: 4px solid;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        }

        .stats-card.total { border-color: #6c757d; }
        .stats-card.pending { border-color: var(--warning-color); }
        .stats-card.completed { border-color: #28a745; }
        .stats-card.cancelled { border-color: var(--danger-color); }

        .stats-card .icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 1rem;
        }

        .main-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .card-header-custom {
            background-color: var(--primary-color);
            color: white;
            padding: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .status-badge {
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-processing { background-color: #cfe2ff; color: #084298; }
        .status-shipping { background-color: #d1ecf1; color: #0c5460; }
        .status-completed { background-color: #d1e7dd; color: #0f5132; }
        .status-cancelled { background-color: #f8d7da; color: #842029; }

        .btn-action {
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            font-size: 0.9rem;
            transition: all 0.2s;
            margin: 0 0.2rem;
        }

        .dropdown-status {
            min-width: 180px;
        }

        table.dataTable thead th {
            background-color: #f8f9fa;
            font-weight: 600;
            border-bottom: 2px solid var(--primary-color);
        }

        .action-buttons {
            display: flex;
            gap: 0.3rem;
            justify-content: center;
        }

        .btn-excel {
            background-color: #217346;
            color: white;
            border: none;
        }

        .btn-excel:hover {
            background-color: #1a5c37;
            color: white;
        }
    </style>
</head>
<body>
    <div class="page-header">
        <div class="container">
            <h1><i class="bi bi-receipt-cutoff me-2"></i>Quản Lý Đơn Hàng</h1>
            <p class="mb-0 mt-2">Theo dõi và quản lý tất cả đơn hàng của khách hàng</p>
        </div>
    </div>

    <div class="container mb-5">
        <!-- Statistics -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="stats-card total">
                    <div class="icon" style="background-color: #e9ecef; color: #495057;">
                        <i class="bi bi-receipt"></i>
                    </div>
                    <h6 class="text-muted mb-1">Tổng Đơn Hàng</h6>
                    <h2 class="mb-0"><?php echo $statistics->total_orders; ?></h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card pending">
                    <div class="icon" style="background-color: #fff3cd; color: #856404;">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <h6 class="text-muted mb-1">Chờ Xử Lý</h6>
                    <h2 class="mb-0"><?php echo $statistics->pending_orders; ?></h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card completed">
                    <div class="icon" style="background-color: #d1e7dd; color: #0f5132;">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <h6 class="text-muted mb-1">Hoàn Thành</h6>
                    <h2 class="mb-0"><?php echo $statistics->completed_orders; ?></h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card cancelled">
                    <div class="icon" style="background-color: #f8d7da; color: #842029;">
                        <i class="bi bi-x-circle"></i>
                    </div>
                    <h6 class="text-muted mb-1">Đã Hủy</h6>
                    <h2 class="mb-0"><?php echo $statistics->cancelled_orders; ?></h2>
                </div>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="main-card">
            <div class="card-header-custom">
                <h4 class="mb-0"><i class="bi bi-list-ul me-2"></i>Danh Sách Đơn Hàng</h4>
                <button class="btn btn-excel" onclick="exportExcel()">
                    <i class="bi bi-file-earmark-excel me-2"></i>Xuất Excel
                </button>
            </div>
            <div class="card-body p-4">
                <table id="ordersTable" class="table table-hover">
                    <thead>
                        <tr>
                            <th>Mã ĐH</th>
                            <th>Khách Hàng</th>
                            <th>SĐT</th>
                            <th>Tổng Tiền</th>
                            <th>Thanh Toán</th>
                            <th>Trạng Thái</th>
                            <th>Ngày Đặt</th>
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><strong>#<?php echo $order->id; ?></strong></td>
                            <td><?php echo htmlspecialchars($order->name); ?></td>
                            <td><?php echo htmlspecialchars($order->phone); ?></td>
                            <td>
                                <strong class="text-success">
                                    <?php echo number_format($order->total_amount + ($order->shipping_fee ?? 0), 0, ',', '.'); ?> VNĐ
                                </strong>
                            </td>
                            <td>
                                <?php 
                                $paymentMethod = $order->payment_method ?? 'COD';
                                echo $paymentMethod == 'COD' ? '<span class="badge bg-info">COD</span>' : '<span class="badge bg-primary">Chuyển khoản</span>';
                                ?>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn status-badge status-<?php echo $order->status ?? 'pending'; ?> dropdown-toggle" 
                                            type="button" 
                                            data-bs-toggle="dropdown">
                                        <?php 
                                        $statusLabels = [
                                            'pending' => 'Chờ xử lý',
                                            'processing' => 'Đang xử lý',
                                            'shipping' => 'Đang giao',
                                            'completed' => 'Hoàn thành',
                                            'cancelled' => 'Đã hủy'
                                        ];
                                        echo $statusLabels[$order->status ?? 'pending'];
                                        ?>
                                    </button>
                                    <ul class="dropdown-menu dropdown-status">
                                        <li><a class="dropdown-item" href="#" onclick="updateStatus(<?php echo $order->id; ?>, 'pending')">
                                            <i class="bi bi-clock text-warning"></i> Chờ xử lý
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" onclick="updateStatus(<?php echo $order->id; ?>, 'processing')">
                                            <i class="bi bi-arrow-repeat text-primary"></i> Đang xử lý
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" onclick="updateStatus(<?php echo $order->id; ?>, 'shipping')">
                                            <i class="bi bi-truck text-info"></i> Đang giao
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" onclick="updateStatus(<?php echo $order->id; ?>, 'completed')">
                                            <i class="bi bi-check-circle text-success"></i> Hoàn thành
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="#" onclick="updateStatus(<?php echo $order->id; ?>, 'cancelled')">
                                            <i class="bi bi-x-circle"></i> Hủy đơn
                                        </a></li>
                                    </ul>
                                </div>
                            </td>
                            <td><?php echo date('d/m/Y H:i', strtotime($order->created_at)); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="/Website-PhanBon/Order/detail/<?php echo $order->id; ?>" 
                                       class="btn btn-sm btn-info btn-action" 
                                       title="Chi tiết">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <button class="btn btn-sm btn-danger btn-action" 
                                            onclick="confirmDelete(<?php echo $order->id; ?>)" 
                                            title="Xóa">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="bi bi-exclamation-triangle me-2"></i>Xác Nhận Xóa</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn xóa đơn hàng này?</p>
                    <p class="text-danger mb-0"><i class="bi bi-info-circle me-1"></i>Hành động này không thể hoàn tác!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <a href="#" id="confirmDeleteBtn" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i>Xóa
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#ordersTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/vi.json'
                },
                order: [[0, 'desc']],
                pageLength: 10
            });
        });

        function updateStatus(orderId, status) {
            if (confirm('Xác nhận cập nhật trạng thái đơn hàng?')) {
                fetch('/Website-PhanBon/Order/updateStatus', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        order_id: orderId,
                        status: status
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Cập nhật thất bại: ' + data.message);
                    }
                });
            }
        }

        function confirmDelete(orderId) {
            document.getElementById('confirmDeleteBtn').href = '/Website-PhanBon/Order/delete/' + orderId;
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }

        function exportExcel() {
            window.location.href = '/Website-PhanBon/Order/exportExcel';
        }
    </script>
</body>
</html>
<?php include 'app/views/shares/footer.php'; ?>