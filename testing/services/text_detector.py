import cv2
import pytesseract
from PIL import Image

def detect_text(image_path):

    try:

        text = pytesseract.image_to_string(
            Image.open(image_path)
        )

        cleaned_text = text.strip()

        # minimum text check
        if len(cleaned_text) > 5:
            return {
                "has_text": True,
                "text_length": len(cleaned_text)
            }

        return {
            "has_text": False,
            "text_length": 0
        }

    except Exception as e:

        return {
            "has_text": False,
            "error": str(e)
        }