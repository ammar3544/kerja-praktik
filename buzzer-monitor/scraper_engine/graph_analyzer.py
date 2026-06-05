import networkx as nx
import community as community_louvain # Instal: pip install python-louvain

class BehaviorAnalyzer:
    def analyze_network(self, comments_df):
        G = nx.Graph()
        # Membuat edge antar user yang berkomentar di video/task yang sama
        for _, row in comments_df.iterrows():
            G.add_edge(row['username'], f"task_{row.get('task_id', 1)}")
        
        if len(G.nodes) < 2: return {}

        # Sesuai Fase 2: Graph Intelligence (Louvain Community Detection)
        partition = community_louvain.best_partition(G)
        
        # Identifikasi klaster padat (CIB detection)
        centrality = nx.degree_centrality(G)
        
        # Gabungkan skor centrality dengan info komunitas
        results = {}
        for node in G.nodes:
            if isinstance(node, str) and not node.startswith("task_"):
                results[node] = centrality[node] * 1.5 # Bobot untuk pengeroyokan
        return results