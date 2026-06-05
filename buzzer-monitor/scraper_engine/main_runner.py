import sys
import json
import pandas as pd
from run_scraper import scrape_and_save # Modifikasi run_scraper agar bisa diimport
from buzzer_engine import BuzzerEngineV4
from narrative_analyzer import NarrativeAnalyzer

def start_full_analysis(platform, url, task_id):
    print(f"--- FASE 1: Scraping Data {platform} ---")
    # 1. Jalankan Scraper & Simpan ke DB
    raw_data = scrape_and_save(platform, url, task_id) 
    
    if not raw_data:
        print("Gagal mengambil data.")
        return

    # 2. Konversi ke DataFrame untuk Engine
    df = pd.DataFrame(raw_data)
    
    print(f"--- FASE 2 & 3: Feature Engineering & Hybrid Scoring ---")
    # 3. Inisialisasi Engine V4
    engine = BuzzerEngineV4()
    # analyze_comments akan menjalankan Semantic, Graph, dan ML secara internal
    results = engine.analyze_comments(df) 
    
    print(f"--- FASE 4: Narrative Intelligence ---")
    # 4. Jalankan Analisis Narasi (LDA)
    narrative_engine = NarrativeAnalyzer()
    narrative_results = narrative_engine.analyze(raw_data)
    
    # 5. Output Akhir / Update Database dengan Skor
    print(f"Analisis Selesai! Skor Rata-rata: {sum(r['score'] for r in results)/len(results)}")
    return results, narrative_results

if __name__ == "__main__":
    p = sys.argv[1] # youtube/tiktok
    u = sys.argv[2] # url
    t = sys.argv[3] # task_id
    start_full_analysis(p, u, t)