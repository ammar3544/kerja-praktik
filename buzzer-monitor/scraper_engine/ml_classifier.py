import numpy as np
from sklearn.ensemble import RandomForestClassifier
from sklearn.preprocessing import StandardScaler
import joblib


class BuzzerClassifier:

    def __init__(self):

        self.model = RandomForestClassifier(
            n_estimators=100,
            random_state=42
        )

        self.scaler = StandardScaler()

    def train(self, X, y):

        X_scaled = self.scaler.fit_transform(X)

        self.model.fit(X_scaled, y)

        joblib.dump(self.model, "buzzer_model.pkl")
        joblib.dump(self.scaler, "scaler.pkl")

    def predict(self, features):

        model = joblib.load("buzzer_model.pkl")
        scaler = joblib.load("scaler.pkl")

        X = np.array([features])

        X_scaled = scaler.transform(X)

        prob = model.predict_proba(X_scaled)[0][1]

        return prob