from sentence_transformers import SentenceTransformer
import numpy as np

model = SentenceTransformer("all-MiniLM-L6-v2")

def analyze(comments):

    texts = [c["content"] for c in comments]
    embeddings = model.encode(texts)

    results = []

    for i, text in enumerate(texts):
        score = 50
        if "bohong" in text.lower():
            score += 20

        results.append({
            "risk_score": score,
            "threat_level": "HIGH" if score > 70 else "LOW"
        })

    return results