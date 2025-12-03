<?php include 'app/views/shares/header.php'; ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm border-0">
                
                <div class="card-header bg-success text-white text-center p-3">
                    <h2 class="mb-0">Thông tin thanh toán</h2>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="/Website-PhanBon/Product/processCheckout">
                        
                        <div class="form-floating mb-3">
                            <input type="text" id="name" name="name" class="form-control" placeholder="Họ và tên" required>
                            <label for="name">Họ và tên</label>
                        </div>
                        
                        <div class="form-floating mb-3">
                            <input type="tel" id="phone" name="phone" class="form-control" placeholder="Số điện thoại" required>
                            <label for="phone">Số điện thoại</label>
                        </div>
                        
                        <div class="form-floating mb-3">
                            <textarea id="address" name="address" class="form-control" placeholder="Địa chỉ" style="height: 100px" required></textarea>
                            <label for="address">Địa chỉ</label>
                        </div>
                        
                        <div class="d-grid gap-2">
                             <button type="submit" class="btn btn-success btn-lg">Xác nhận thanh toán</button>
                        </div>
                       
                    </form>

                    <div class="text-center mt-3">
                        <a href="/Website-PhanBon/Product/cart" class="text-decoration-none text-success fw-bold">← Quay lại giỏ hàng</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>