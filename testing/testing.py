import pdfplumber
from pdf2image import convert_from_path
import pytesseract
from PIL import Image
import easyocr
import requests
import cv2
import os

# -----------------------------
# 1. TEXT EXTRACTION
# -----------------------------

def extract_text_from_pdf(pdf_path):
    text = ""

    # Try normal extraction
    try:
        with pdfplumber.open(pdf_path) as pdf:
            for page in pdf.pages:
                t = page.extract_text()
                if t:
                    text += t + "\n"
    except:
        pass

    # If empty → OCR
    if len(text.strip()) < 50:
        print("⚠️ Using OCR for scanned PDF...")
        pages = convert_from_path(pdf_path)
        for page in pages:
            text += pytesseract.image_to_string(page)

    return text


def extract_text_from_image(image_path):
    # Try EasyOCR (better)
    try:
        reader = easyocr.Reader(['en'])
        result = reader.readtext(image_path, detail=0)
        text = " ".join(result)
    except:
        text = ""

    # fallback to Tesseract
    if len(text.strip()) < 20:
        img = cv2.imread(image_path)
        gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
        text = pytesseract.image_to_string(gray)

    return text


# -----------------------------
# 2. LLM (OLLAMA)
# -----------------------------

def extract_json_with_ollama(text):
    prompt = f"""
Extract all possible structured data from the text.

Return ONLY valid JSON.

Try to include:
- names
- dates
- phone numbers
- emails
- addresses
- amounts
- tables (as arrays)
- any key-value pairs

Text:
{text}
"""

    response = requests.post(
        "http://localhost:11434/api/generate",
        json={
            "model": "llama3",
            "prompt": prompt,
            "stream": False
        }
    )

    return response.json()["response"]


# -----------------------------
# 3. MAIN PIPELINE
# -----------------------------

def process_file(file_path):
    ext = os.path.splitext(file_path)[1].lower()

    if ext == ".pdf":
        text = extract_text_from_pdf(file_path)
    elif ext in [".png", ".jpg", ".jpeg"]:
        text = extract_text_from_image(file_path)
    else:
        raise Exception("Unsupported file format")

    print("\n--- Extracted Text ---\n")
    print(text[:1000])  # preview

    print("\n--- Converting to JSON via Ollama ---\n")
    json_output = "" #extract_json_with_ollama(text)

    return json_output


# -----------------------------
# RUN
# -----------------------------

if __name__ == "__main__":
    file_path = "sample.pdf"  # change this

    result = process_file(file_path)

    print("\n--- FINAL JSON ---\n")
    print(result)import pdfplumber
from pdf2image import convert_from_path
import pytesseract
from PIL import Image
import easyocr
import requests
import cv2
import os

# -----------------------------
# 1. TEXT EXTRACTION
# -----------------------------

def extract_text_from_pdf(pdf_path):
    text = ""

    # Try normal extraction
    try:
        with pdfplumber.open(pdf_path) as pdf:
            for page in pdf.pages:
                t = page.extract_text()
                if t:
                    text += t + "\n"
    except:
        pass

    # If empty → OCR
    if len(text.strip()) < 50:
        print("⚠️ Using OCR for scanned PDF...")
        pages = convert_from_path(pdf_path)
        for page in pages:
            text += pytesseract.image_to_string(page)

    return text


def extract_text_from_image(image_path):
    # Try EasyOCR (better)
    try:
        reader = easyocr.Reader(['en'])
        result = reader.readtext(image_path, detail=0)
        text = " ".join(result)
    except:
        text = ""

    # fallback to Tesseract
    if len(text.strip()) < 20:
        img = cv2.imread(image_path)
        gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
        text = pytesseract.image_to_string(gray)

    return text


# -----------------------------
# 2. LLM (OLLAMA)
# -----------------------------

def extract_json_with_ollama(text):
    prompt = f"""
Extract all possible structured data from the text.

Return ONLY valid JSON.

Try to include:
- names
- dates
- phone numbers
- emails
- addresses
- amounts
- tables (as arrays)
- any key-value pairs

Text:
{text}
"""

    response = requests.post(
        "http://localhost:11434/api/generate",
        json={
            "model": "llama3",
            "prompt": prompt,
            "stream": False
        }
    )

    return response.json()["response"]


# -----------------------------
# 3. MAIN PIPELINE
# -----------------------------

def process_file(file_path):
    ext = os.path.splitext(file_path)[1].lower()

    if ext == ".pdf":
        text = extract_text_from_pdf(file_path)
    elif ext in [".png", ".jpg", ".jpeg"]:
        text = extract_text_from_image(file_path)
    else:
        raise Exception("Unsupported file format")

    print("\n--- Extracted Text ---\n")
    print(text[:1000])  # preview

    print("\n--- Converting to JSON via Ollama ---\n")
    json_output = ""; ##extract_json_with_ollama(text)

    return json_output


# -----------------------------
# RUN
# -----------------------------

if __name__ == "__main__":
    file_path = "sample.pdf"  # change this

    result = process_file(file_path)

    print("\n--- FINAL JSON ---\n")
    print(result)