import pandas as pd
from fastapi import UploadFile

def _to_py(value):
    """Cast NumPy/Pandas scalars to native Python types."""
    # pandas/NumPy scalars usually have .item()
    try:
        return value.item()  # e.g., np.int64 -> int, np.float64 -> float
    except Exception:
        return value

def analyze_excel_file(file: UploadFile) -> dict:
    """Reads an uploaded Excel file and returns summary + insights with JSON-safe types."""
    # Read and normalize headers
    df = pd.read_excel(file.file)
    df.columns = [str(c).strip().lower() for c in df.columns]

    # Coerce numeric columns if present
    if "price" in df.columns:
        df["price"] = pd.to_numeric(df["price"], errors="coerce")
    if "stock" in df.columns:
        df["stock"] = pd.to_numeric(df["stock"], errors="coerce")

    summary: dict = {
        "total_products": int(len(df)),
        "average_price": (float(round(df["price"].mean(), 2))
                          if "price" in df.columns and df["price"].notna().any()
                          else None),
        "low_stock_count": (int((df["stock"] < 5).sum())
                            if "stock" in df.columns and df["stock"].notna().any()
                            else None),
    }

    # Most expensive product
    if "price" in df.columns and df["price"].notna().any():
        max_idx = int(df["price"].idxmax())
        row = df.loc[max_idx]
        summary["most_expensive_product"] = {
            "id": _to_py(row.get("id")) if "id" in df.columns else None,
            "name": (str(row.get("name")) if "name" in df.columns else "Unknown"),
            "price": float(row["price"]),
        }

    # Cheapest product
    if "price" in df.columns and df["price"].notna().any():
        min_idx = int(df["price"].idxmin())
        row = df.loc[min_idx]
        summary["cheapest_product"] = {
            "id": _to_py(row.get("id")) if "id" in df.columns else None,
            "name": (str(row.get("name")) if "name" in df.columns else "Unknown"),
            "price": float(row["price"]),
        }

    # List low-stock products (<5)
    if "stock" in df.columns and df["stock"].notna().any():
        low_df = df[df["stock"] < 5]
        cols = [c for c in ["id", "name", "stock"] if c in low_df.columns]
        low_list = []
        for _, r in low_df[cols].iterrows():
            item = {k: _to_py(r[k]) for k in cols}
            # ensure types are JSON-safe & readable
            if "stock" in item and item["stock"] is not None:
                item["stock"] = int(item["stock"])
            if "name" in item and item["name"] is not None:
                item["name"] = str(item["name"])
            low_list.append(item)
        summary["low_stock_products"] = low_list

    return summary
