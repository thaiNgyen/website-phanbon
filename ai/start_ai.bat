@echo off
REM === Khởi động AI model trong Website-PhanBon ===

REM Thư mục gốc AI
set BASE=E:\xampp\htdocs\Website-PhanBon\ai

REM Thư mục starter (chứa thư mục app/main.py)
set STARTER=%BASE%\starter

REM Chuyển vào thư mục starter
pushd "%STARTER%"

REM Chạy uvicorn bằng python trong venv
start "" /MIN "%BASE%\.venv\Scripts\python.exe" -m uvicorn app.main:app --host 127.0.0.1 --port 9000

REM Quay lại thư mục trước
popd
