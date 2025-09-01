from fastapi import FastAPI, UploadFile, File
from fastapi.responses import JSONResponse, FileResponse
from fastapi.staticfiles import StaticFiles
import pandas as pd
import torch
from torchvision.models import resnet18, ResNet18_Weights
from torchvision import transforms
from PIL import Image
import matplotlib.pyplot as plt
import seaborn as sns
import os
import uuid
import re

app = FastAPI()


app.mount("/uploads", StaticFiles(directory="uploads"), name="uploads")
app.mount("/reports", StaticFiles(directory="reports"), name="reports")

@app.get("/")
def home():
    return {"message": "Excel & AI API is running 🚀"}

# ---------- Analyze Excel ----------
@app.post("/analyze-excel/")
async def analyze_excel(file: UploadFile = File(...)):
    try:
        print("📂 analyze-excel got:", file.filename, "|", file.content_type)
        # make sure we read from the beginning
        file.file.seek(0)
        df = pd.read_excel(file.file)

        # normalize headers
        df.columns = [str(c).strip().lower() for c in df.columns]

        # numeric coercion (robust)
        if "price" in df.columns:
            df["price"] = pd.to_numeric(df["price"], errors="coerce")
        if "stock" in df.columns:
            df["stock"] = pd.to_numeric(df["stock"], errors="coerce")

        total_products  = int(len(df))
        average_price   = (float(round(df["price"].mean(), 2))
                           if "price" in df.columns and df["price"].notna().any()
                           else None)
        low_stock_count = (int((df["stock"] < 5).sum())
                           if "stock" in df.columns and df["stock"].notna().any()
                           else None)

        return {
            "total_products": total_products,
            "average_price": average_price,
            "low_stock_count": low_stock_count,
        }
    except Exception as e:
        return JSONResponse(status_code=400, content={"error": str(e)})

# ---------- Validate Excel ----------
@app.post("/validate-excel/")
async def validate_excel(file: UploadFile = File(...)):
    try:
        print("📂 validate-excel got:", file.filename, "|", file.content_type)
        file.file.seek(0)
        df = pd.read_excel(file.file)
        df.columns = df.columns.str.strip().str.lower()

        required = ["id", "name", "price", "stock"]
        missing = [c for c in required if c not in df.columns]
        if missing:
            return {"status": "error", "message": f"Missing column(s): {', '.join(missing)}"}

        errors = []
        if df["id"].duplicated().any():
            errors.append("Duplicate product IDs found.")

        if not pd.api.types.is_numeric_dtype(df["price"]):
            errors.append("Price column contains non-numeric values.")
        elif (pd.to_numeric(df["price"], errors="coerce") <= 0).any():
            errors.append("Some products have invalid (<=0) prices.")

        if not pd.api.types.is_numeric_dtype(df["stock"]):
            errors.append("Stock column contains non-numeric values.")
        elif (pd.to_numeric(df["stock"], errors="coerce") < 0).any():
            errors.append("Some products have negative stock.")

        if errors:
            return {"status": "error", "errors": errors}

        products = df.to_dict(orient="records")
        return {"status": "success", "total_products": len(products), "products": products}

    except Exception as e:
        return JSONResponse(status_code=400, content={"error": str(e)})

# ---------- Graph ----------
@app.post("/analyze-excel-graph/")
async def analyze_excel_graph(file: UploadFile = File(...)):
    try:
        print("📂 graph got:", file.filename, "|", file.content_type)
        file.file.seek(0)
        df = pd.read_excel(file.file)
        df.columns = [col.strip().lower() for col in df.columns]

        for col in ["id", "name", "stock"]:
            if col not in df.columns:
                return JSONResponse(status_code=400, content={"error": f"Missing column: {col}"})

        plt.figure(figsize=(10, 6))
        sns.barplot(x="name", y="stock", data=df, palette="viridis")
        plt.xticks(rotation=45, ha="right")
        plt.title("📊 Product Stock Levels")
        plt.xlabel("Product"); plt.ylabel("Stock Quantity")

        os.makedirs("reports", exist_ok=True)
        filename = f"report_{uuid.uuid4().hex}.png"
        path = os.path.join("reports", filename)

        plt.tight_layout(); plt.savefig(path); plt.close()

        return FileResponse(
            path,
            media_type="image/png",
            filename="stock_report.png",
            background=lambda: os.remove(path) if os.path.exists(path) else None
        )
    except Exception as e:
        return JSONResponse(status_code=400, content={"error": str(e)})



# ✅ ImageNet classes
imagenet_labels = []
with open("imagenet_classes.txt", "r") as f:
    for line in f.readlines():
        label = line.strip()
        label = label.split(":")[-1]
        label = label.replace("'", "").replace(",", "").strip()
        imagenet_labels.append(label)


# ✅ Load ResNet model
weights = ResNet18_Weights.IMAGENET1K_V1
model = resnet18()
model.load_state_dict(torch.load("resnet18_imagenet.pth", map_location="cpu"))
model.eval()

transform = weights.transforms()


# ✅ Category mapping function
import re

def map_category(label: str) -> str:
    label = label.lower()

    # Groceries (fruits, vegetables, food)
    grocery_keywords = [
        "bread", "milk", "food", "fruit", "vegetable", "grocery",
        "broccoli", "cauliflower", "cabbage", "cucumber", "tomato",
        "carrot", "onion", "potato", "pepper", "apple", "banana",
        "orange", "grape", "strawberry", "pineapple", "mango", "lettuce"
    ]
    if any(re.search(rf"\b{word}\b", label) for word in grocery_keywords):
        return "Groceries"

    # Electronics
    electronic_keywords = [
        "laptop", "phone", "camera", "tv", "electronic", "computer",
        "headphone", "tablet", "remote", "monitor"
    ]
    if any(re.search(rf"\b{word}\b", label) for word in electronic_keywords):
        return "Electronics"

    # Cookware
    cookware_keywords = [
        "pan", "pot", "knife", "cookware", "utensil",
        "spoon", "fork", "plate", "bowl", "microwave", "teapot"
    ]
    if any(re.search(rf"\b{word}\b", label) for word in cookware_keywords):
        return "Cookware"

    # Health & beauty
    health_keywords = [
        "cream", "shampoo", "soap", "toothpaste", "cosmetic", "lotion",
        "perfume", "makeup", "lipstick", "brush", "sunscreen"
    ]
    if any(re.search(rf"\b{word}\b", label) for word in health_keywords):
        return "Health and beauty"

    # Toys & games
    toy_keywords = ["toy", "game", "puzzle", "lego", "doll", "ball", "play"]
    if any(re.search(rf"\b{word}\b", label) for word in toy_keywords):
        return "Toys and games"

    # Books & stationary
    stationary_keywords = ["book", "pen", "pencil", "notebook", "stationary", "paper"]
    if any(re.search(rf"\b{word}\b", label) for word in stationary_keywords):
        return "Books and stationary"

    # Furniture
    furniture_keywords = ["chair", "table", "sofa", "bed", "desk", "couch", "lamp", "furniture"]
    if any(re.search(rf"\b{word}\b", label) for word in furniture_keywords):
        return "Furniture"

    # Footwear
    footwear_keywords = ["shoe", "sandal", "boot", "sneaker", "trainer", "slipper", "running"]
    if any(re.search(rf"\b{word}\b", label) for word in footwear_keywords):
        return "Footwear"

    # Accessories
    accessory_keywords = ["watch", "bag", "belt", "hat", "glasses", "ring", "necklace", "bracelet"]
    if any(re.search(rf"\b{word}\b", label) for word in accessory_keywords):
        return "Accessories"

    return "Uncategorized"



# 📸 Image classification endpoint
@app.post("/classify-image/")
async def classify_image(file: UploadFile = File(...)):
    try:
        os.makedirs("uploads", exist_ok=True)
        img_filename = f"{uuid.uuid4().hex}_{file.filename}"
        img_path = os.path.join("uploads", img_filename)

        with open(img_path, "wb") as buffer:
            buffer.write(await file.read())

        image = Image.open(img_path).convert("RGB")
        img_t = transform(image).unsqueeze(0)

        with torch.no_grad():
            outputs = model(img_t)
            probs = torch.nn.functional.softmax(outputs, dim=1)[0]
            top5 = torch.topk(probs, 5)

        predictions = []
        for idx, score in zip(top5.indices, top5.values):
            label = imagenet_labels[idx.item()]
            mapped_category = map_category(label)

            predictions.append({
                "imagenet_label": label,
                "mapped_category": mapped_category,
                "confidence": round(score.item(), 4)
            })

        # 📊 Create bar chart
        plt.figure(figsize=(8, 5))
        labels = [p["mapped_category"] for p in predictions]
        scores = [p["confidence"] for p in predictions]
        sns.barplot(x=scores, y=labels, palette="viridis")
        plt.xlabel("Confidence")
        plt.title("Top-5 Predictions (Mapped Categories)")

        os.makedirs("reports", exist_ok=True)
        report_filename = f"report_{uuid.uuid4().hex}.png"
        report_path = os.path.join("reports", report_filename)

        plt.tight_layout()
        plt.savefig(report_path)
        plt.close()

        return {
            "filename": file.filename,
            "predictions": predictions,
            "image_url": f"http://127.0.0.1:8000/{img_path}",
            "report_url": f"http://127.0.0.1:8000/{report_path}"
        }

    except Exception as e:
        return JSONResponse(content={"error": str(e)}, status_code=500)
