from collections import Counter

def detect_duplicates(comments):

    texts = [c["text"].lower() for c in comments]

    counter = Counter(texts)

    duplicates = []

    for text,count in counter.items():
        if count > 3:
            duplicates.append(text)

    return duplicates