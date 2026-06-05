from sentence_transformers import SentenceTransformer
from sklearn.metrics.pairwise import cosine_similarity

class SemanticAnalyzer:
    def __init__(self):
        # Sesuai Dokumentasi v4: Menggunakan Multilingual MiniLM
        self.model = SentenceTransformer('paraphrase-multilingual-MiniLM-L12-v2')

    def get_features(self, comment_text, batch_texts):
        # Deteksi parafrase (Spinning Detection)
        # Mengambil sampel batch jika data terlalu besar untuk efisiensi
        embeddings = self.model.encode([comment_text] + batch_texts[:100])
        
        target = embeddings[0].reshape(1, -1)
        others = embeddings[1:]
        
        sim_matrix = cosine_similarity(target, others)
        avg_sim = sim_matrix.mean()
        
        # Linguistic Analysis (Fase 2.6)
        words = comment_text.lower().split()
        ttr = len(set(words)) / len(words) if len(words) > 0 else 0
        
        return [avg_sim, ttr]