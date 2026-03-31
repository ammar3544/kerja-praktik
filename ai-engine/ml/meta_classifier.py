from sklearn.ensemble import RandomForestClassifier
import numpy as np

rf_model = RandomForestClassifier()

def hybrid_scoring(feature_vector):
    heuristic_score = sum(feature_vector[:5])  # contoh rule-based

    ml_prob = rf_model.predict_proba([feature_vector])[0][1]

    final_score = (0.7 * heuristic_score) + (0.3 * ml_prob * 100)

    return final_score