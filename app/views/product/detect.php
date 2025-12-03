<?php
// Chuẩn hóa biến để không lỗi
$error  = $error ?? null;
$result = $result ?? null;
$imageUrl = $imageUrl ?? null;
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body {
        background: #f5f5f7;
    }
    .detect-container {
        max-width: 980px;
        margin: 40px auto;
    }
    .detect-card {
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        border: none;
    }
    .upload-area {
        border: 2px dashed #d0d7ff;
        padding: 20px;
        border-radius: 14px;
        background: #f9fbff;
        cursor: pointer;
        transition: all .2s ease;
        text-align: center;
    }
    .upload-area:hover {
        background: #eef3ff;
        border-color: #0d6efd;
    }
    .preview-box {
        height: 230px;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        display:flex;
        justify-content:center;
        align-items:center;
        overflow:hidden;
    }
    .preview-box img {
        width:100%;
        height:100%;
        object-fit:contain;
    }
    .result-box {
        border-radius: 14px;
        border: 1px solid #e5e7eb;
        padding: 20px;
        background: #ffffff;
    }
    .suggest {
        white-space: pre-line;
        font-size: 0.95rem;
    }
</style>

<div class="detect-container">

    <div class="card detect-card p-4">
        <h2 class="mb-1 text-primary"><?= $pageTitle ?></h2>
        <p class="text-muted">Tải ảnh lá cà phê để hệ thống nhận diện bệnh và gợi ý phương án xử lý.</p>

        <?php if ($error): ?>
            <div class="alert alert-danger">
                ⚠️ <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="" method="post" enctype="multipart/form-data">
            <div class="row g-4 mt-2">
                <!-- Upload ảnh -->
                <div class="col-md-6">
                    <label class="fw-semibold mb-2">1. Tải ảnh lên</label>

                    <label class="upload-area" for="leafInput">
                        <div style="font-size: 40px;">📁</div>
                        <div class="mt-2">Chọn ảnh từ máy (jpg, png, webp)</div>
                    </label>

                    <input
                        id="leafInput"
                        type="file"
                        name="leaf"
                        accept="image/*"
                        class="d-none"
                        required
                    />

                    <div id="fileName" class="text-muted mt-2 small">Chưa có ảnh nào được chọn.</div>

                    <button class="btn btn-primary mt-3 w-100 py-2 fw-semibold">
                        🔍 Phân tích bệnh
                    </button>
                </div>

                <!-- Preview ảnh -->
                <div class="col-md-6">
                    <label class="fw-semibold mb-2">Xem trước ảnh</label>
                    <div class="preview-box" id="previewBox">
                      <?php if ($imageUrl): ?>
                        <img src="<?= htmlspecialchars($imageUrl) ?>" alt="Leaf image">
                      <?php else: ?>
                        <span class="text-muted small">Ảnh sẽ hiển thị tại đây sau khi chọn.</span>
                      <?php endif; ?>
                    </div>

                    <?php if ($result): ?>
                        <div class="result-box mt-4">
                            <h5 class="fw-semibold text-primary">Kết quả nhận diện</h5>

                            <div class="mt-2">
                                <strong>Bệnh:</strong>
                                <span class="ms-1 text-dark">
                                    <?= htmlspecialchars($result['label'] ?? '') ?>
                                </span>
                            </div>

                            <div class="mt-1">
                                <strong>Độ tin cậy:</strong>
                                <?= isset($result['confidence'])
                                    ? number_format($result['confidence'] * 100, 1) . '%'
                                    : ''
                                ?>
                            </div>

                            <hr>

                            <h6 class="fw-semibold mb-1">Gợi ý xử lý & bón phân</h6>
                            <p class="suggest text-muted">
                                <?= htmlspecialchars($result['fertilizer_suggestion'] ?? '') ?>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </form>
    </div>
</div>

<script>
// Hiển thị tên file + preview ảnh
document.getElementById('leafInput').addEventListener('change', function () {
    const file = this.files[0];
    const fileName = document.getElementById('fileName');
    const preview = document.getElementById('previewBox');

    if (!file) {
        fileName.textContent = "Chưa có ảnh nào được chọn.";
        preview.innerHTML = '<span class="text-muted small">Ảnh sẽ hiển thị tại đây sau khi chọn.</span>';
        return;
    }

    fileName.textContent = file.name;

    const reader = new FileReader();
    reader.onload = e => {
        preview.innerHTML = `<img src="${e.target.result}">`;
    };
    reader.readAsDataURL(file);
});
</script>
