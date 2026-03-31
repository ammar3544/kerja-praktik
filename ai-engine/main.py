import json
import sys

from semantic import semantic_similarity
from duplicate import detect_duplicates
from temporal import detect_burst
from narrative import extract_topics


def main():

    comments = json.loads(sys.argv[1])

    clusters = semantic_similarity(comments)

    duplicates = detect_duplicates(comments)

    burst = detect_burst(comments)

    topics = extract_topics(comments)

    result = {
        "clusters":clusters,
        "duplicates":duplicates,
        "burst":burst,
        "topics":topics
    }

    print(json.dumps(result))


if __name__ == "__main__":
    main()