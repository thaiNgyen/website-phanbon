def suggest(disease_label: str) -> str:
    """Gợi ý xử lý/phan bón cho RoCoLe 3 lớp."""
    if not disease_label:
        return "Không xác định. Duy trì chăm sóc cân bằng và theo dõi thêm."

    d = disease_label.lower()

    # Healthy
    if "healthy" in d:
        return ("Lá khoẻ mạnh 🌿: duy trì lịch bón theo khuyến cáo/đất; "
                "N–P–K cân đối, bổ sung hữu cơ, tưới – tỉa hợp lý.")

    # Rust
    if "rust" in d:
        return ("Bệnh gỉ sắt 🍂: cắt tỉa lá bệnh, vệ sinh vườn; "
                "cân đối NPK (tăng K, Ca; tránh dư N); "
                "có thể phun gốc đồng/Mancozeb theo nhãn thuốc.")

    # Red spider mite
    if "spider" in d or "mite" in d:
        return ("Bọ ve đỏ 🕷️: tăng ẩm, rửa lá; IPM/thiên địch; "
                "chỉ dùng thuốc trừ ve khi vượt ngưỡng; "
                "bổ sung hữu cơ, N-P-K cân bằng để cây khoẻ.")

    return "Không có khuyến nghị cụ thể. Theo dõi thêm và xử lý theo tình trạng vườn."