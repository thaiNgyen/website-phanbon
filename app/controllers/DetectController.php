<?php
require_once __DIR__ . '/../helpers/AiService.php';

class DetectController {
    private string $apiUrl = 'http://127.0.0.1:9000/predict';

    public function __construct() {
        // Đảm bảo AI server đang chạy (xem phần B)
        AiService::ensureRunning();
    }

    public function index() {
        $result = null; $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['leaf'])) {
            try {
                $docroot = rtrim($_SERVER['DOCUMENT_ROOT'], '/');
                $uploadDir = $docroot . '/Website-PhanBon/uploads/';
                if (!is_dir($uploadDir)) @mkdir($uploadDir, 0777, true);

                if ($_FILES['leaf']['error'] !== UPLOAD_ERR_OK) throw new Exception("Upload lỗi.");
                if (!getimagesize($_FILES['leaf']['tmp_name'])) throw new Exception("Không phải ảnh.");
                $ext = strtolower(pathinfo($_FILES['leaf']['name'], PATHINFO_EXTENSION));
                if (!in_array($ext,['jpg','jpeg','png','webp'])) throw new Exception("Chỉ JPG/JPEG/PNG/WEBP.");

                $name = uniqid().'_'.time().'.'.$ext;
                $dest = $uploadDir.$name;
                move_uploaded_file($_FILES['leaf']['tmp_name'], $dest);

                // gọi FastAPI
                $cfile = new CURLFile($dest, mime_content_type($dest), basename($dest));
                $post = ['file' => $cfile];
                $ch = curl_init($this->apiUrl);
                curl_setopt_array($ch,[
                    CURLOPT_POST=>true, CURLOPT_POSTFIELDS=>$post,
                    CURLOPT_RETURNTRANSFER=>true, CURLOPT_TIMEOUT=>30
                ]);
                $res = curl_exec($ch);
                if ($res === false) throw new Exception("Không gọi được AI: ".curl_error($ch));
                $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                if ($code !== 200) throw new Exception("AI trả mã $code: $res");
                $result = json_decode($res, true);
            } catch (Exception $e) { $error = $e->getMessage(); }
        }

        $pageTitle = "Nhận biết bệnh lá cà phê";
        include 'app/views/product/detect.php';
    }
}
