from flask import Flask, jsonify
from flask_cors import CORS
import subprocess
import os

# Initialize Flask app
app = Flask(__name__)

# Apply CORS configuration
CORS(app)

@app.route('/start-chatbot', methods=['GET'])
def start_chatbot():
    try:
        # Run the Streamlit app
        subprocess.Popen(["streamlit", "run", "rootabot.py"], cwd=os.getcwd())
        return jsonify({"status": "success", "message": "Chatbot started"}), 200
    except Exception as e:
        return jsonify({"status": "error", "message": str(e)}), 500

if __name__ == '__main__':
    app.run(debug=True)
