from youtube_comment_downloader import YoutubeCommentDownloader
from youtube_comment_downloader import SORT_BY_POPULAR
import time


def scrape_youtube(url):

    downloader = YoutubeCommentDownloader()

    comments = []

    try:

        generator = downloader.get_comments_from_url(url, sort_by=SORT_BY_POPULAR)

        for c in generator:

            comments.append({
                "user": c.get("author", "unknown"),
                "text": c.get("text", ""),
                "likes": parse_likes(c.get("votes", 0)),
                "time": int(time.time()),
                "platform": "youtube"
            })
            if len(comments) >= 10000:
                break

    except:
        return []

    return comments

def parse_likes(like_text):

    if not like_text:
        return 0

    like_text = like_text.lower().replace(',', '.').strip()

    try:

        if "rb" in like_text:
            return int(float(like_text.replace("rb","")) * 1000)

        if "jt" in like_text:
            return int(float(like_text.replace("jt","")) * 1000000)

        return int(float(like_text))

    except:
        return 0