import re
import os
import pandas as pd
from loguru import logger
from tiktokcomment import TiktokComment
from tiktokcomment.typing import Comments
from transformers import pipeline
from typing import Optional, Dict, Any
from datetime import datetime
import requests
import json
import sys

# Fungsi Helper (Pastikan fungsi ini ada di file Anda atau utils)
def clean_text(text):
    text = re.sub(r'http\S+', '', text)
    text = re.sub(r'[^a-zA-Z0-9\s]', '', text)
    return text.strip()

def extract_text(c):
    return c.get("text", "")

def get_tiktok_video_stats(aweme_id):
    # Placeholder stats jika API eksternal tidak tersedia
    return {"views": 0, "likes": 0}

def run_scraper(aweme_id: str, sentiment: bool = True, threshold: float = 0.4, limit: int = 50, lang: str = "id"):
    """
    Scrape TikTok comments dan sesuaikan dengan skema database Laravel.
    """
    logger.info(f"Mulai Scraping TikTok ID: {aweme_id}")
    
    video_stats = get_tiktok_video_stats(aweme_id)
    
    try:
        # Menggunakan library tiktokcomment sesuai kode awal Anda
        comments_obj: Comments = TiktokComment()(aweme_id=aweme_id)
        comments_dict = comments_obj.dict
    except Exception as e:
        logger.error(f"Gagal mengambil komentar: {e}")
        return pd.DataFrame(), video_stats

    data = None
    for key in ["comments", "data", "items"]:
        if key in comments_dict:
            data = comments_dict[key]
            break

    if not data:
        return pd.DataFrame(), video_stats

    if limit > 0 and len(data) > limit:
        data = data[:limit]

    # Inisialisasi Model Sentiment (Hanya jika dibutuhkan)
    analyzer = None
    if sentiment:
        model_id = "w11wo/indonesian-roberta-base-sentiment-classifier" if lang == "id" else "distilbert-base-uncased-finetuned-sst-2-english"
        analyzer = pipeline("sentiment-analysis", model=model_id)

    processed_data = []

    for c in data:
        raw = extract_text(c)
        clean = clean_text(raw)
        
        # Penentuan Sentiment
        final_sentiment = "netral"
        final_score = 0.0
        
        if sentiment and analyzer and clean:
            result = analyzer(clean[:256])[0]
            label = result["label"].lower()
            score = float(result["score"])
            
            # PERBAIKAN: Jangan buat threshold terlalu tinggi agar tidak semua jadi netral
            if score >= threshold:
                if lang == "id":
                    if "pos" in label: final_sentiment = "positif"
                    elif "neg" in label: final_sentiment = "negatif"
                else:
                    final_sentiment = label
            final_score = round(score, 3)

        # SESUAIKAN DENGAN SKEMA LARAVEL/DATABASE
        processed_data.append({
            "username": c.get("user_name", {}).get("unique_id", "Anonymous"),
            "text": raw,
            "platform": "tiktok",
            "sentiment": final_sentiment,
            "sentiment_score": final_score,
            "raw_id": c.get("cid")
        })

    return pd.DataFrame(processed_data), video_stats

# Fungsi untuk dipanggil oleh main_runner.py
def scrape_tiktok(url):
    # Ekstrak ID dari URL
    match = re.search(r'/video/(\d+)', url)
    if not match:
        return []
    
    aweme_id = match.group(1)
    df, stats = run_scraper(aweme_id, sentiment=True, threshold=0.3, limit=50, lang="id")
    
    # Kembalikan dalam bentuk list dictionary agar dibaca main_runner.py
    return df.to_dict(orient='records')

if __name__ == "__main__":
    # Test manual
    if len(sys.argv) > 1:
        test_url = sys.argv[1]
        print(json.dumps(scrape_tiktok(test_url)))