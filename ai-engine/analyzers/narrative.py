from sklearn.feature_extraction.text import CountVectorizer
from sklearn.decomposition import LatentDirichletAllocation

def extract_topics(comments):

    texts = [c["text"] for c in comments]

    vectorizer = CountVectorizer(stop_words="english")

    X = vectorizer.fit_transform(texts)

    lda = LatentDirichletAllocation(n_components=3)

    lda.fit(X)

    words = vectorizer.get_feature_names_out()

    topics = []

    for topic in lda.components_:
        top_words = [words[i] for i in topic.argsort()[-5:]]
        topics.append(top_words)

    return topics