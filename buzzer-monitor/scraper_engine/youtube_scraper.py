from youtube_comment_downloader import YoutubeCommentDownloader
from youtube_comment_downloader import SORT_BY_POPULAR
from transformers import pipeline
import time
import re

# Inisialisasi model sentimen secara global agar tidak me-load ulang di setiap perulangan
try:
    analyzer = pipeline("sentiment-analysis", model="w11wo/indonesian-roberta-base-sentiment-classifier")
except Exception:
    analyzer = None

def clean_text(text):
    text = re.sub(r'http\S+', '', text)
    text = re.sub(r'[^a-zA-Z0-9\s]', '', text)
    return text.strip()

def scrape_youtube(url):
    downloader = YoutubeCommentDownloader()
    comments = []

    try:
        generator = downloader.get_comments_from_url(url, sort_by=SORT_BY_POPULAR)

        for c in generator:
            raw_text = c.get("text", "")
            cleaned = clean_text(raw_text)
            
            # Default Sentiment
            final_sentiment = "netral"
            final_score = 0.0
            
            if analyzer and cleaned:
                try:
                    result = analyzer(cleaned[:256])[0]
                    label = result["label"].lower()
                    score = float(result["score"])
                    
                    if score >= 0.3:
                        if "pos" in label: 
                            final_sentiment = "positif"
                        elif "neg" in label: 
                            final_sentiment = "negatif"
                    final_score = round(score, 3)
                except Exception:
                    pass

            comments.append({
                "username": c.get("author", "unknown"),
                "text": raw_text,
                "likes": parse_likes(c.get("votes", 0)),
                "time": int(time.time()),
                "platform": "youtube",
                "sentiment": final_sentiment,
                "sentiment_score": final_score
            })
            
            if len(comments) >= 200: # Limit diturunkan sedikit demi kecepatan inferensi sentimen web
                break

    except Exception as e:
        return []

    return comments

def parse_likes(like_text):
    if not like_text:
        return 0
    like_text = str(like_text).lower().replace(',', '.').strip()
    try:
        if "rb" in like_text:
            return int(float(like_text.replace("rb","")) * 1000)
        if "jt" in like_text:
            return int(float(like_text.replace("jt","")) * 1000000)
        return int(float(like_text))
    except:
        return 0