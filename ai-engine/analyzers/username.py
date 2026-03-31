import math
from collections import Counter

def shannon_entropy(username):
    prob = [n_x/len(username) for x,n_x in Counter(username).items()]
    entropy = -sum(p * math.log2(p) for p in prob)
    return entropy

def username_analysis(usernames):
    scores = []
    flags = []

    for u in usernames:
        ent = shannon_entropy(u)
        scores.append(ent)
        if ent > 3.5:
            flags.append(True)
        else:
            flags.append(False)

    return {
        "username_score": sum(scores)/len(scores),
        "high_username_entropy": any(flags)
    }