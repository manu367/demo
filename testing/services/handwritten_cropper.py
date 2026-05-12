import cv2
import os

CROP_FOLDER = "uploads/cropped"

os.makedirs(CROP_FOLDER, exist_ok=True)

def crop_handwritten_area(image_path):

    img = cv2.imread(image_path)

    gray = cv2.cvtColor(
        img,
        cv2.COLOR_BGR2GRAY
    )

    # Contrast Increase
    contrast = cv2.convertScaleAbs(
        gray,
        alpha=1.8,
        beta=0
    )

    # Sharpen
    kernel = [
        [0, -1, 0],
        [-1, 5,-1],
        [0, -1, 0]
    ]

    kernel = cv2.UMat(
        cv2.getStructuringElement(
            cv2.MORPH_RECT,
            (3,3)
        )
    )

    sharpen = cv2.filter2D(
        contrast,
        -1,
        cv2.getGaussianKernel(3,0)
    )

    filename = os.path.basename(image_path)

    crop_path = os.path.join(
        CROP_FOLDER,
        filename
    )

    cv2.imwrite(
        crop_path,
        sharpen
    )

    return crop_path