# scraper_engine/run_analyzer.py
import json
import argparse
import pandas as pd
from utils import check_global_history
from utils import calculate_entropy, calculate_lexical_richness
from utils import SemanticMatcher

def main():
    
    parser = argparse.ArgumentParser()
    parser.add_argument('--file', help='Path ke file JSON data task')
    args = parser.parse_args()

    # Load Data
    with open(args.file, 'r') as f:
        data = json.load(f)

    df = pd.DataFrame(data['comments'])

    # Fase 1: Global Tracking Lookup
    # Kita cek riwayat user di lintas platform (TikTok/YouTube)
    df['history_score'] = df['username'].apply(check_global_history)

    print(f"Memproses {len(df)} komentar untuk Task ID: {data['task_id']}")
    

    # ... (kode load JSON dari tahap sebelumnya)
    
    # --- FASE 2: FEATURE ENGINEERING ---
    
    # 2.7 Username Entropy
    df['entropy_score'] = df['username'].apply(calculate_entropy)
    
    # 2.6 Linguistic Analysis
    df['lexical_score'] = df['content'].apply(calculate_lexical_richness)
    
    # 2.4 Duplicate & Template Detection
    # Menghitung berapa kali konten yang sama persis muncul dalam satu task
    df['duplicate_count'] = df.groupby('content')['content'].transform('count')
    
    # Normalisasi skor duplicate (0-1)
    df['is_template'] = df['duplicate_count'].apply(lambda x: 1.0 if x > 1 else 0.0)

    # Simpan hasil sementara untuk pengecekan
    df.to_csv('analysis_debug.csv', index=False)
    
    print("Fase 2 (Partial): Fitur identitas dan teks berhasil diekstrak.")

    # Pastikan kolom timestamp bertipe datetime
    df['timestamp'] = pd.to_datetime(df['timestamp'])
    
    # Urutkan berdasarkan waktu untuk analisis sekuensial
    df = df.sort_values('timestamp')
    
    # Hitung kepadatan komentar dalam jendela 1 menit
    # Ini membantu mendeteksi "Spike" (lonjakan tiba-tiba)
    df['spike_score'] = 0.0
    for i in range(len(df)):
        current_time = df.iloc[i]['timestamp']
        # Cari berapa banyak komentar dalam radius 30 detik sebelum/sesudah
        window = df[(df['timestamp'] >= current_time - pd.Timedelta(seconds=30)) & 
                    (df['timestamp'] <= current_time + pd.Timedelta(seconds=30))]
        
        # Semakin banyak komentar dalam 1 menit, semakin tinggi spike_score
        df.at[df.index[i], 'spike_score'] = min(len(window) / 50, 1.0) # Threshold 50 koment/menit

    print("Fase 2.5: Analisis Temporal (Burst Detection) berhasil ditambahkan.")
    
    # Simpan kembali ke debug CSV
    df.to_csv('analysis_debug.csv', index=False)

     # --- FASE 2.1: SEMANTIC ANALYSIS ---
    print("Memulai analisis semantik (ini mungkin memakan waktu)...")
    
    matcher = SemanticMatcher()
    all_texts = df['content'].tolist()
    
    # Hitung skor semantik untuk setiap baris
    # Skor tinggi berarti komentar tersebut "senada" dengan banyak komentar lain
    df['semantic_score'] = df['content'].apply(lambda x: matcher.get_similarity_scores(x, all_texts))

    print("Fase 2.1: Analisis Semantik selesai.")
    
    # Simpan kembali ke debug CSV
    df.to_csv('analysis_debug.csv', index=False)

    print("Fase 3: Menjalankan Hybrid Scoring Layer...")

    # 1. Hitung Skor Heuristik (70%)
    df['heuristic_score'] = df.apply(calculate_heuristic_score, axis=1)

    # 2. Simulasi Skor Machine Learning (30%)
    # Nantinya ini akan memanggil: model.predict_proba(features)
    # Untuk sekarang kita beri nilai dasar berdasarkan pola fitur
    df['ml_probability'] = (df['semantic_score'] * 100) 

    # 3. Final Hybrid Calculation
    df['final_score'] = (0.7 * df['heuristic_score']) + (0.3 * df['ml_probability'])

    # Tentukan Label
    df['is_buzzer'] = df['final_score'] > 70 # Threshold kecurigaan

    print(f"Analisis Selesai. Terdeteksi {df['is_buzzer'].sum()} komentar mencurigakan.")
    
    # Simpan hasil akhir
    df.to_csv('analysis_final.csv', index=False)
    
    # Kirim ringkasan kembali ke Laravel (Simulasi output)
    result_summary = {
        'total_comments': len(df),
        'buzzer_count': int(df['is_buzzer'].sum()),
        'average_score': float(df['final_score'].mean())
    }
    print(json.dumps(result_summary))
    
    return df

if __name__ == "__main__":
    main()