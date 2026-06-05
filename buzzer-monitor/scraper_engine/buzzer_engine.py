import numpy as np
import joblib
from similarity_analyzer import SemanticAnalyzer
from graph_analyzer import BehaviorAnalyzer
from ml_classifier import calculate_entropy

class BuzzerEngineV4:
    def __init__(self):
        try:
            self.model = joblib.load('scraper_engine/buzzer_model.pkl')
            self.scaler = joblib.load('scraper_engine/scaler.pkl')
        except:
            self.model = None 
            
        self.semantic = SemanticAnalyzer()
        self.graph = BehaviorAnalyzer()

    def analyze_comments(self, comments_df):
        # Perbaikan: Ubah 'content' menjadi 'text' sesuai struktur dataframe database
        all_texts = comments_df['text'].tolist()
        features_list = []
        
        centrality = self.graph.analyze_network(comments_df)
        
        for _, row in comments_df.iterrows():
            # Perbaikan: Ambil kolom 'text'
            semantic_feats = self.semantic.get_features(row['text'], all_texts) 
            
            features = [
                semantic_feats[0],      # 1. Semantic Similarity
                semantic_feats[1],      # 2. Lexical Richness (TTR)
                calculate_entropy(row['username']), # 3. Username Entropy
                centrality.get(row['username'], 0),  # 4. Graph Centrality
                row.get('history_score', 0),         # 5. Global Lookup
                row.get('spike_score', 0),           # 6. Temporal Spike
            ]
            features += [0] * (18 - len(features)) 
            features_list.append(features)

        final_results = []
        
        if self.model:
            try:
                ml_probs = self.model.predict_proba(self.scaler.transform(features_list))[:, 1]
            except:
                ml_probs = [0.5] * len(comments_df)
        else:
            ml_probs = [0.5] * len(comments_df)

        for i, (idx, row) in enumerate(comments_df.iterrows()):
            h_score = self._calculate_heuristic(row, features_list[i])
            ml_score = ml_probs[i] * 100
            
            final_score = (0.7 * h_score) + (0.3 * ml_score)
            
            final_results.append({
                'id': row['id'],
                'score': final_score,
                'label': self._get_label(final_score, row)
            })
            
        return final_results

    def _calculate_heuristic(self, row, features):
        score = 0
        if features[0] > 0.8: score += 30 
        if features[2] > 4.0: score += 20 
        if features[5] > 0.7: score += 20 
        return min(score, 100)

    def _get_label(self, score, row):
        if score > 85 and calculate_entropy(row['username']) > 4.5: return "Bot"
        if score > 70: return "Coordinated Buzzer"
        if score >= 50: return "Suspicious"
        return "Organic"