import numpy as np
from sentence_transformers import SentenceTransformer
from sklearn.metrics.pairwise import cosine_similarity
from sklearn.ensemble import RandomForestClassifier
from sklearn.decomposition import LatentDirichletAllocation
from sklearn.feature_extraction.text import CountVectorizer
import networkx as nx
from sklearn.ensemble import IsolationForest
import math


model = SentenceTransformer("paraphrase-multilingual-MiniLM-L12-v2")


def semantic_similarity(texts):

    embeddings = model.encode(texts)

    sim_matrix = cosine_similarity(embeddings)

    scores = []

    for i in range(len(sim_matrix)):

        scores.append(float(np.mean(sim_matrix[i])))

    return scores


def username_entropy(username):

    prob = [ float(username.count(c)) / len(username) for c in dict.fromkeys(list(username)) ]

    entropy = - sum([ p * math.log(p) / math.log(2.0) for p in prob ])

    return entropy


def topic_model(texts):

    vectorizer = CountVectorizer(stop_words='english')

    X = vectorizer.fit_transform(texts)

    lda = LatentDirichletAllocation(n_components=3)

    lda.fit(X)

    return lda.components_


def build_graph(texts):

    G = nx.Graph()

    for i in range(len(texts)):
        G.add_node(i)

    for i in range(len(texts)):
        for j in range(i+1,len(texts)):

            if texts[i] == texts[j]:
                G.add_edge(i,j)

    return nx.number_connected_components(G)


def temporal_anomaly(times):

    model = IsolationForest()

    times = np.array(times).reshape(-1,1)

    model.fit(times)

    return model.predict(times)


def classify(comments):

    texts = [c["text"] for c in comments]

    users = [c["user"] for c in comments]

    times = [c["time"] for c in comments]


    semantic = semantic_similarity(texts)

    temporal = temporal_anomaly(times)

    graph_cluster = build_graph(texts)

    topic_model(texts)


    features = []

    for i in range(len(comments)):

        entropy = username_entropy(users[i])

        features.append([
            semantic[i],
            entropy,
            temporal[i],
            graph_cluster
        ])


    X = np.array(features)

    y = np.random.randint(0,2,len(X))

    model = RandomForestClassifier()

    model.fit(X,y)

    probs = model.predict_proba(X)[:,1]


    results = []

    for i,c in enumerate(comments):

        score = int(probs[i]*100)

        if score > 70:
            label = "Coordinated Buzzer"
        elif score > 50:
            label = "Suspicious"
        else:
            label = "Organic"

        results.append({
            "user":c["user"],
            "text":c["text"],
            "score":score,
            "label":label
        })

    return results