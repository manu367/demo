import pytesseract
import easyocr
from PIL import Image

reader = easyocr.Reader(
    ['en', 'hi'],
    gpu=False
)

def extract_text(image_path, handwritten=False):

    # ==========================
    # COMPUTER TEXT OCR
    # ==========================

    if handwritten == False:

        text = pytesseract.image_to_string(
            Image.open(image_path)
        )

        return text

    # ==========================
    # HANDWRITTEN OCR
    # ==========================

    results = reader.readtext(image_path)

    final_text = ""

    for item in results:

        final_text += item[1] + "\n"

    return final_text