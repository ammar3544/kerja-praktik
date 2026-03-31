from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.decomposition import LatentDirichletAllocation
import numpy as np

class NarrativeAnalyzer:

    def __init__(self, n_topics=5):
        self.n_topics = n_topics
        self.vectorizer = TfidfVectorizer(
            max_df=0.95,
            min_df=2,
            stop_words="english"
        )
        self.lda = LatentDirichletAllocation(
            n_components=n_topics,
            random_state=42
        )

    def analyze(self, comments):

        texts = []

        for c in comments:
            if isinstance(c, dict):
                texts.append(c.get("text",""))
            else:
                texts.append(str(c))

        if len(texts) < 5:
            return {
                "topics": [],
                "narratives": []
            }

        tfidf = self.vectorizer.fit_transform(texts)

        lda_matrix = self.lda.fit_transform(tfidf)

        words = self.vectorizer.get_feature_names_out()

        topics = []

        for idx, topic in enumerate(self.lda.components_):

            top_words = [
                words[i]
                for i in topic.argsort()[-10:]
            ]

            topics.append({
                "topic_id": idx,
                "keywords": top_words
            })

        narratives = []

        for i, row in enumerate(lda_matrix):

            dominant_topic = np.argmax(row)

            narratives.append({
                "comment_index": i,
                "topic": int(dominant_topic),
                "score": float(row[dominant_topic])
            })

        return {
            "topics": topics,
            "narratives": narratives
        }