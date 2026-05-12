import os
from werkzeug.utils import secure_filename

UPLOAD_FOLDER = "uploads/original"

os.makedirs(UPLOAD_FOLDER, exist_ok=True)

def save_image(file):

    filename = secure_filename(file.filename)

    save_path = os.path.join(
        UPLOAD_FOLDER,
        filename
    )

    file.save(save_path)

    return save_path