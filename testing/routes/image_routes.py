from flask import Blueprint, request, jsonify

from services.upload_service import save_image
from services.image_cleaner import clean_image
from services.handwritter_detector import is_handwritten
from services.handwritten_cropper import crop_handwritten_area
from services.ocr_service import extract_text
from services.language_detector import detect_language
from services.text_detector import detect_text
from services.response_service import (
    success_response,
    error_response
)

image_bp = Blueprint("image_bp", __name__)

@image_bp.route("/image_data", methods=["POST"])
def image_upload():

    try:

        # ==========================
        # FILE CHECK
        # ==========================

        if "file" not in request.files:

            return jsonify(
                error_response(
                    "No file uploaded"
                )
            ), 400

        file = request.files["file"]

        if file.filename == "":

            return jsonify(
                error_response(
                    "Empty filename"
                )
            ), 400

        # ==========================
        # STEP 1 : SAVE IMAGE
        # ==========================

        image_path = save_image(file)

        # ==========================
        # STEP 2 : CLEAN IMAGE
        # ==========================

        cleaned_image = clean_image(image_path)

        # ==========================
        # STEP 3 : DETECT TEXT
        # ==========================

        text_check = detect_text(cleaned_image)

        if text_check["has_text"] == False:

            return jsonify(
                error_response(
                    "No readable text found"
                )
            ), 400

        # ==========================
        # STEP 4 : HANDWRITTEN CHECK
        # ==========================

        handwritten = is_handwritten(cleaned_image)

        final_text = ""
        language = "unknown"

        # ==========================
        # COMPUTER WRITTEN
        # ==========================

        if handwritten == False:

            final_text = extract_text(
                cleaned_image
            )

        # ==========================
        # HANDWRITTEN
        # ==========================

        else:

            language = detect_language(
                cleaned_image
            )

            cropped_image = crop_handwritten_area(
                cleaned_image
            )

            final_text = extract_text(
                cropped_image,
                handwritten=True
            )

        # ==========================
        # FINAL RESPONSE
        # ==========================

        return jsonify(

            success_response(
                message="Text extracted successfully",
                data={
                    "image_path": image_path,
                    "cleaned_image": cleaned_image,
                    "handwritten": handwritten,
                    "language": language,
                    "text": final_text
                }
            )

        )

    except Exception as e:

        return jsonify(
            error_response(
                message="Internal server error",
                error=str(e)
            )
        ), 500