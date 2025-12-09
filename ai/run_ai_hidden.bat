@echo off
cd /d "E:\xampp\htdocs\website-phanbon\ai"
call .venv\Scripts\activate
uvicorn app.main:app --host 127.0.0.1 --port 8000
