import numpy as np 

# 2.7 Username Entropy: Karakteristik bot-like
def calculate_entropy(username):
    prob = [float(username.count(c)) / len(username) for c in set(username)]
    entropy = - sum([p * np.log2(p) for p in prob])
    return entropy