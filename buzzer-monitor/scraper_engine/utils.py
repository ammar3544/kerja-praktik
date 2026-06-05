import numpy as np
import re
import pandas as pd
from sentence_transformers import SentenceTransformer, util
import torch

class SemanticMatcher:
    def __init__(self):
        # Model ini ringan dan akurat untuk perbandingan kalimat multibahasa
        self.model = SentenceTransformer('paraphrase-multilingual-MiniLM-L12-v2')

    def get_similarity_scores(self, current_comment, all_comments):
        """
        Fase 2.1: Semantic Embeddings.
        Menghitung seberapa mirip sebuah komentar dengan narasi umum di task tersebut.
        """
        if not all_comments: return 0.0
        
        # Encode komentar menjadi vektor numerik
        current_embedding = self.model.encode(current_comment, convert_to_tensor=True)
        all_embeddings = self.model.encode(all_comments, convert_to_tensor=True)
        
        # Hitung Cosine Similarity
        cosine_scores = util.cos_sim(current_embedding, all_embeddings)
        
        # Ambil rata-rata kemiripan (mengeluarkan skor 0 sampai 1)
        return float(torch.mean(cosine_scores))
    
def calculate_temporal_density(timestamps):
    """
    Fase 2.5: Advanced Temporal Forensics.
    Mendeteksi anomali waktu dan serangan gelombang (Burst/Spike).
    """
    if len(timestamps) < 2:
        return 0.0
    
    # Konversi ke format datetime
    ts = pd.to_datetime(timestamps)
    ts = ts.sort_values()
    
    # Hitung selisih waktu antar komentar (dalam detik)
    diffs = ts.diff().dt.total_seconds().dropna()
    
    # Jika rata-rata jarak antar komentar sangat kecil (misal < 5 detik), 
    # ini indikasi serangan burst.
    avg_diff = diffs.mean()
    
    # Normalisasi skor: semakin kecil jarak waktu, semakin tinggi skor kecurigaan (0-1)
    # Kita asumsikan jarak < 2 detik sangat mencurigakan
    density_score = 1.0 / (avg_diff + 1) 
    return min(density_score * 10, 1.0)

def clean_text(text):

    text = text.lower()

    text = re.sub(r"http\S+", "", text)

    text = re.sub(r"[^a-zA-Z0-9 ]", "", text)

    return text

def remove_emoji(text):
    return re.sub(r'[^\w\s]', '', text)

# scraper_engine/utils.py
def check_global_history(username):
    """
    Fase 1: Memeriksa apakah username ini pernah 
    terdeteksi di task lain (Global Tracking)
    """
    # Di sini nantinya Anda bisa memanggil API Laravel 
    # atau mengecek database BuzzerProfile langsung
    known_buzzers = ['bot_user1', 'spammer_pro'] 
    return 1.0 if username in known_buzzers else 0.0

def calculate_entropy(username):
    """
    Fase 2.7: Username Entropy.
    Mendeteksi nama pengguna yang dihasilkan secara acak (bot-like).
    """
    if not username: return 0
    # Menghitung probabilitas kemunculan karakter
    prob = [float(username.count(c)) / len(username) for c in set(username)]
    # Rumus Shannon Entropy
    entropy = - sum([p * np.log2(p) for p in prob])
    return entropy

def calculate_lexical_richness(text):
    """
    Fase 2.6: Linguistic Analysis.
    Melihat keberagaman kosa kata (Type-Token Ratio).
    """
    words = re.findall(r'\w+', text.lower())
    if not words: return 0
    return len(set(words)) / len(words)

def calculate_heuristic_score(row):
    """
    Fase 3: Menghitung 70% bagian dari skor (Heuristik/Aturan).
    Menjumlahkan poin kecurigaan dari fitur-fitur Fase 2.
    """
    score = 0
    
    # Aturan 1: Template/Duplicate (Bobot Tinggi)
    if row['is_template'] > 0: score += 40
    
    # Aturan 2: Temporal Spike (Deteksi Serangan)
    score += (row['spike_score'] * 30)
    
    # Aturan 3: Semantic Similarity (Narasi Seragam)
    if row['semantic_score'] > 0.8: score += 20
    
    # Aturan 4: Low Entropy (Username Bot)
    if row['entropy_score'] < 2.0: score += 10
    
    return min(score, 100)