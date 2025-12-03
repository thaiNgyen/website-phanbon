from fastapi import FastAPI, UploadFile, File
from fastapi.responses import JSONResponse
import torch, io, os
from typing import List
from utils.preprocess import build_transform, load_image
from utils.recommender import suggest

APP_DIR = os.path.dirname(__file__)
ROOT = os.path.abspath(os.path.join(APP_DIR, ".."))
MODEL_PATH = os.path.join(ROOT, "models", "model.pt")
LABELS_PATH = os.path.join(ROOT, "models", "labels.txt")
THRESHOLD = 0.65
DISPLAY_LABELS = {
    "coffee__healthy": "Healthy",
    "coffee__rust": "Rust",
    "coffee__red_spider_mite": "Red spider mite",
}

app = FastAPI(title="Coffee Leaf Disease API", version="0.1.0")

from fastapi.staticfiles import StaticFiles
app.mount("/web", StaticFiles(directory=os.path.join(APP_DIR, "static"), html=True), name="web")

# Lazy load model and labels
_model = None
_labels: List[str] = []

def load_labels(path=LABELS_PATH) -> List[str]:
    with open(path, "r", encoding="utf-8") as f:
        return [x.strip() for x in f if x.strip()]

def load_model(path=MODEL_PATH):
    m = torch.jit.load(path, map_location="cpu") if path.endswith('.pt') else torch.load(path, map_location="cpu")
    m.eval()
    return m

def ensure_loaded():
    global _model, _labels
    if not _labels:
        _labels = load_labels()
    if _model is None:
        if not os.path.exists(MODEL_PATH):
            raise FileNotFoundError(f"Missing model weights at {MODEL_PATH}")
        _model = load_model()
        
def to_display(label: str) -> str:
    return DISPLAY_LABELS.get(label, label)

@app.get("/health")
def health():
    ok = os.path.exists(MODEL_PATH) and os.path.exists(LABELS_PATH)
    return {"status": "ok" if ok else "missing_weights_or_labels"}

@app.get("/labels")
def labels():
    return {"labels": load_labels()}

@app.post("/predict")
async def predict(file: UploadFile = File(...)):
    try:
        ensure_loaded()
        img = load_image(io.BytesIO(await file.read()))
        tfm = build_transform()
        x = tfm(img).unsqueeze(0)  # [1,3,H,W]

        with torch.no_grad():
            logits = _model(x)
            probs = torch.softmax(logits, dim=1).squeeze(0)
            conf, idx = torch.max(probs, dim=0)

        raw_label = _labels[int(idx)]
        conf = float(conf)

        if conf < THRESHOLD:
            return JSONResponse({
                "label": "unknown",
                "confidence": conf,
                "fertilizer_suggestion": "Ảnh chưa rõ (confidence thấp). Hãy chụp gần hơn, đủ sáng hoặc thử lại.",
            })

        disp = to_display(raw_label)
        return JSONResponse({
            "label": disp,
            "confidence": conf,
            "fertilizer_suggestion": suggest(raw_label)  # vẫn truyền raw cho recommender
        })
    except FileNotFoundError as e:
        return JSONResponse({"error": str(e)}, status_code=400)
    except Exception as e:
        return JSONResponse({"error": str(e)}, status_code=500)
