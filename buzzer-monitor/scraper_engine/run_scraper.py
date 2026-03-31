import sys
import json
from similarity_analyzer import SimilarityAnalyzer
from graph_analyzer import GraphAnalyzer
from narrative_analyzer import NarrativeAnalyzer

sys.stdout.reconfigure(encoding='utf-8')

platform = sys.argv[1]
url = sys.argv[2]

data = []

try:

    if platform == "youtube":
        from youtube_scraper import scrape_youtube
        data = scrape_youtube(url)

    elif platform == "tiktok":
        from tiktok_scraper import scrape_tiktok
        data = scrape_tiktok(url)

except Exception:
    data = []

# ==============================
# ANALYZER
# ==============================

similarity = SimilarityAnalyzer()
suspicious = similarity.analyze(data)

graph = GraphAnalyzer()
clusters = graph.analyze(data)

narrative = NarrativeAnalyzer()
result = narrative.analyze(data)

topics = result["topics"]
narratives = result["narratives"]

# ==============================
# OUTPUT KE LARAVEL
# ==============================

output = {
    "comments": data,
    "similarity": suspicious,
    "clusters": clusters,
    "topics": topics,
    "narratives": narratives
}

sys.stdout.write(json.dumps(output, ensure_ascii=False))