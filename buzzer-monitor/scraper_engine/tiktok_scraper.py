import requests
import re
import time


def extract_aweme_id(url):

    match = re.search(r'/video/(\d+)', url)

    if match:
        return match.group(1)

    return None


def scrape_tiktok(url):

    aweme_id = extract_aweme_id(url)

    if not aweme_id:
        return []

    api_url = "https://www.tiktok.com/api/comment/list/"

    params = {
        "aweme_id": aweme_id,
        "count": 50,
        "cursor": 0
    }

    headers = {
        "User-Agent": "Mozilla/5.0",
        "Accept": "application/json"
    }

    response = requests.get(api_url, params=params, headers=headers)

    try:
        data = response.json()
    except:
        return []

    comments = []

    if "comments" not in data:
        return []

    for c in data["comments"]:

        comments.append({
            "user": c["user"]["unique_id"],
            "text": c["text"],
            "likes": c["digg_count"],
            "time": int(time.time()),
            "platform": "tiktok"
        })

    return comments