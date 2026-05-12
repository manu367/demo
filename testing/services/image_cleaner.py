import cv2
import os

CLEAN_FOLDER = "uploads/cleaned"

os.makedirs(CLEAN_FOLDER, exist_ok=True)

def clean_image(image_path):

    img = cv2.imread(image_path)

    gray = cv2.cvtColor(
        img,
        cv2.COLOR_BGR2GRAY
    )

    # Noise remove
    blur = cv2.GaussianBlur(
        gray,
        (5,5),
        0
    )

    # Threshold
    thresh = cv2.threshold(
        blur,
        0,
        255,
        cv2.THRESH_BINARY + cv2.THRESH_OTSU
    )[1]

    filename = os.path.basename(image_path)

    cleaned_path = os.path.join(
        CLEAN_FOLDER,
        filename
    )

    cv2.imwrite(cleaned_path, thresh)

    return cleaned_path