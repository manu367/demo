from langdetect import detect
import pytesseract
from PIL import Image

def detect_language(image_path):

    text = pytesseract.image_to_string(
        Image.open(image_path)
    )

    try:
        lang = detect(text)
        return lang
    except:
        return "unknown"