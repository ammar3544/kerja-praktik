import re

def clean_text(text):

    text = text.lower()

    text = re.sub(r"http\S+", "", text)

    text = re.sub(r"[^a-zA-Z0-9 ]", "", text)

    return text

def remove_emoji(text):
    return re.sub(r'[^\w\s]', '', text)