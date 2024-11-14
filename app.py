from flask import Flask, jsonify
import subprocess

app = Flask(__name__)

@app.route('max30102', methods=['GET'])
def start_measuring():
    # Running the meet_script.py and capturing its output
    result = subprocess.run(['python', 'executor_script.py'], capture_output=True, text=True)
    
    # Return the sensor data as JSON
    return jsonify({"sensor_data": result.stdout.strip()})

if __name__ == '__main__':
    app.run(debug=True)
