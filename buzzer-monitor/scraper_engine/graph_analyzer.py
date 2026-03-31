import networkx as nx
import community as community_louvain
from collections import defaultdict


class GraphAnalyzer:

    def analyze(self, comments):

        G = nx.Graph()

        for i in range(len(comments)):

            for j in range(i+1, len(comments)):

                text_i = comments[i]["text"].lower()
                text_j = comments[j]["text"].lower()

                if text_i[:20] == text_j[:20]:

                    user_i = comments[i]["user"]
                    user_j = comments[j]["user"]

                    G.add_edge(user_i, user_j)

        clusters = []

        if len(G.nodes) > 0:

            partition = community_louvain.best_partition(G)

            groups = defaultdict(list)

            for user, cid in partition.items():

                groups[cid].append(user)

            for cid, members in groups.items():

                if len(members) > 3:

                    clusters.append({
                        "cluster": cid,
                        "members": members,
                        "size": len(members)
                    })

        return clusters