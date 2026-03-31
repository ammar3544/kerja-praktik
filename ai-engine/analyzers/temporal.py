from sklearn.ensemble import IsolationForest
import numpy as np

def temporal_analysis(timestamps):
    intervals = np.diff(sorted(timestamps))

    if len(intervals) < 3:
        return {"temporal_score": 0, "temporal_anomaly": False}

    model = IsolationForest(contamination=0.2)
    intervals = intervals.reshape(-1, 1)
    preds = model.fit_predict(intervals)

    anomaly_count = list(preds).count(-1)

    return {
        "temporal_score": anomaly_count * 10,
        "temporal_anomaly": anomaly_count > 2
    }