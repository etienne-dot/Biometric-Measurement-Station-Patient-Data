from max30102 import MAX30102
from time import sleep

# Initialize the sensor
sensor = MAX30102()

try:
    print("Reading data from MAX30102 sensor... Press Ctrl+C to stop.")
    while True:
        # Read data from the sensor
        red, ir = sensor.read_fifo()
        print(f"Red LED: {red}, IR LED: {ir}")
        sleep(1)  # Wait for 1 second between readings
except KeyboardInterrupt:
    # Shutdown the sensor when the script is interrupted
    sensor.shutdown()
    print("Sensor shut down.")
