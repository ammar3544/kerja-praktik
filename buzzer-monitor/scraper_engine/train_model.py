import numpy as np
from ml_classifier import BuzzerClassifier

classifier = BuzzerClassifier()

X = np.array([
    [4.2,5,1,1,0.1],
    [4.0,4,1,1,0.2],
    [2.1,0,0,0,0.9],
    [2.3,1,0,0,0.8],
    [3.9,4,1,1,0.3]
])

y = np.array([1,1,0,0,1])

classifier.train(X,y)

print("Model trained")