from flask import Flask
from routes.image_routes import image_bp

app = Flask(__name__)

app.register_blueprint(image_bp)

if __name__ == "__main__":
    app.run(
        host="0.0.0.0",
        port=8089,
        debug=True
    )