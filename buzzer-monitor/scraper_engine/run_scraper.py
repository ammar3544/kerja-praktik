import sys
import json
import mysql.connector
import pandas as pd
from datetime import datetime
from buzzer_engine import BuzzerEngineV4

# Konfigurasi Database
DB_CONFIG = {
    "host": "127.0.0.1",
    "user": "root",        
    "password": "",        
    "database": "buzzer_monitor" 
}

def main():
    platform = sys.argv[1] if len(sys.argv) > 1 else "youtube"
    url = sys.argv[2] if len(sys.argv) > 2 else ""
    task_id = sys.argv[3] if len(sys.argv) > 3 else 1

    data = []

    # --- FASE 1: SCRAPING ---
    try:
        if platform == "youtube":
            from youtube_scraper import scrape_youtube
            data = scrape_youtube(url)
        elif platform == "tiktok":
            from tiktok_scraper import scrape_tiktok
            data = scrape_tiktok(url)
    except Exception as e:
        print(f"Scraping Error: {str(e)}", file=sys.stderr)

    if len(data) > 0:
        try:
            conn = mysql.connector.connect(**DB_CONFIG)
            cursor = conn.cursor(dictionary=True) 
            
            now = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
            
            # Perbaikan: Menambahkan kolom sentiment dan sentiment_score ke query INSERT
            insert_query = """
                INSERT INTO comments (task_id, username, text, platform, sentiment, sentiment_score, created_at, updated_at) 
                VALUES (%s, %s, %s, %s, %s, %s, %s, %s)
            """
            
            for item in data:
                val_user = item.get('user', item.get('username', 'Anonymous'))
                val_text = item.get('text', 'Kosong')
                val_sentiment = item.get('sentiment', 'netral')
                val_sentiment_score = item.get('sentiment_score', 0.0)
                
                cursor.execute(insert_query, (task_id, val_user, val_text, platform, val_sentiment, val_sentiment_score, now, now))
            
            conn.commit()

            # --- FASE 2 & 3: INTEGRASI ENGINE ANALISIS V4 ---
            print(f"--- Menjalankan Engine Analisis untuk Task ID: {task_id} ---")
            
            # Ambil kembali data termasuk teks untuk dianalisis oleh Engine
            cursor.execute("SELECT id, username, text FROM comments WHERE task_id = %s", (task_id,))
            rows = cursor.fetchall()
            df = pd.DataFrame(rows)

            if not df.empty:
                engine = BuzzerEngineV4()
                analysis_results = engine.analyze_comments(df)

                update_query = "UPDATE comments SET buzzer_score = %s, label = %s WHERE id = %s"
                
                for res in analysis_results:
                    score_to_save = res.get('score', 0) 
                    label_to_save = res.get('label', 'Organic')
                    cursor.execute(update_query, (score_to_save, label_to_save, res['id']))

            # Update Status Task di tabel utama
            cursor.execute("UPDATE tasks SET status = 'completed', updated_at = %s WHERE id = %s", (now, task_id))
            
            conn.commit()
            cursor.close()
            conn.close()
            
            print(json.dumps(data))
            
        except Exception as e:
            print(f"Database Error: {str(e)}", file=sys.stderr)
            print(json.dumps([]))
    else:
        print(json.dumps([]))

if __name__ == "__main__":
    main()