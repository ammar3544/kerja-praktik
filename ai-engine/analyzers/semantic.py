from sentence_transformers import SentenceTransformer
from sklearn.metrics.pairwise import cosine_similarity

model = SentenceTransformer("paraphrase-multilingual-MiniLM-L12-v2")

def semantic_similarity(comments):

    texts = [c["text"] for c in comments]

    embeddings = model.encode(texts)

    similarity = cosine_similarity(embeddings)

    clusters = []

    for i in range(len(similarity)):
        for j in range(i+1,len(similarity)):
            if similarity[i][j] > 0.85:
                clusters.append((i,j))

    return clusters