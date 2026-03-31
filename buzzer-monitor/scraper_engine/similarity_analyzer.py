from sentence_transformers import SentenceTransformer
from sklearn.metrics.pairwise import cosine_similarity


class SimilarityAnalyzer:

    def __init__(self):
        self.model = SentenceTransformer(
            "paraphrase-multilingual-MiniLM-L12-v2"
        )

    def analyze(self, comments):

        texts = [c["text"] for c in comments]

        embeddings = self.model.encode(texts)

        sim_matrix = cosine_similarity(embeddings)

        suspicious = []

        for i in range(len(texts)):

            similar_count = 0

            for j in range(len(texts)):

                if i == j:
                    continue

                if sim_matrix[i][j] > 0.85:
                    similar_count += 1

            if similar_count > 3:

                suspicious.append({
                    "text": texts[i],
                    "similarity": similar_count
                })

        return suspicious