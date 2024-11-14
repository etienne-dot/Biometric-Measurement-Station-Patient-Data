import mysql.connector
from max30102 import MAX30102  # Import sensor library
from heartrate_monitor import HeartRateMonitor
import hrcalc  # Library for heart rate and SpO2 calculation
import time
import argparse
import statistics  # For calculating the median and moving average

# Database configuration
db_config = {
    'user': 'meet_user',
    'password': 'Welkom01!',
    'host': 'localhost',
    'database': 'patient_database'
}

# Argument parser to control script options
parser = argparse.ArgumentParser(description="Read and print data from MAX30102")
parser.add_argument("-r", "--raw", action="store_true",
                    help="print raw data instead of calculation result")
parser.add_argument("-t", "--time", type=int, default=10,
                    help="duration in seconds to read from sensor, default 10")
args = parser.parse_args()

# Kalman filter class to smooth readings
class KalmanFilter:
    def __init__(self, process_variance=1e-5, measurement_variance=1e-2):
        self.process_variance = process_variance
        self.measurement_variance = measurement_variance
        self.estimated_value = 0
        self.estimated_error = 1.0

    def update(self, measurement, cap=None):
        # Prediction update
        self.estimated_error += self.process_variance
        # Measurement update
        kalman_gain = self.estimated_error / (self.estimated_error + self.measurement_variance)
        self.estimated_value += kalman_gain * (measurement - self.estimated_value)
        self.estimated_error *= (1 - kalman_gain)
        
        # Apply a hard cap if provided
        if cap is not None and self.estimated_value > cap:
            self.estimated_value = cap
        
        return self.estimated_value

# Function to apply moving average filter
def moving_average(data, window_size=5):
    if len(data) < window_size:
        return statistics.mean(data)  # Fallback if not enough data points
    return statistics.mean(data[-window_size:])

# Initialize the MAX30102 sensor and Kalman filters
sensor = MAX30102()
kalman_hr = KalmanFilter()  # Filter for heart rate
kalman_spo2 = KalmanFilter()  # Filter for SpO2

# Function to collect data, filter it, and calculate median HR and SpO2
def collect_and_store_data(sensor, duration=10, max_retries=5):
    print("Starting data collection...")

    # Variables to store sensor data
    ir_data = []
    red_data = []

    # Collect data for the specified duration
    for _ in range(int(duration * 10)):  # 10 readings per second for duration seconds
        try:
            # Retrieve the first two values (red, ir) from sensor.read_fifo()
            red, ir = sensor.read_fifo()[:2]
            if args.raw:
                print(f"Raw IR: {ir}, Raw Red: {red}")  # Print raw values if specified
        except ValueError:
            print("Error: Unexpected number of values returned by sensor.read_fifo()")
            continue  # Skip this reading and move to the next

        # Append values to the data lists
        ir_data.append(ir)
        red_data.append(red)
        time.sleep(0.1)  # Wait 0.1 seconds before the next reading

    # Retry calculation up to max_retries times to obtain valid data
    valid_hr_data = []
    valid_spo2_data = []
    attempts = 0
    while attempts < max_retries:
        # Calculate heart rate and SpO2 using the hrcalc function
        output = hrcalc.calc_hr_and_spo2(ir_data, red_data)
        hr, spo2 = output[0], output[2]  # Capture only the HR and SpO2 values
        print(f"Attempt {attempts + 1}: HR={hr}, SpO2={spo2}")

        # Apply Kalman filter to smooth heart rate and SpO2 readings
        filtered_hr = kalman_hr.update(hr, cap=180)
        filtered_spo2 = kalman_spo2.update(spo2, cap=100)

        # Get the moving average of recent HR readings
        hr_moving_avg = moving_average(valid_hr_data + [filtered_hr], window_size=5)

        # Filter out HR readings that deviate more than 20% from the moving average
        if abs(filtered_hr - hr_moving_avg) <= 0.2 * hr_moving_avg and 40 <= filtered_hr <= 180 and 90 <= filtered_spo2 <= 100:
            valid_hr_data.append(filtered_hr)
            valid_spo2_data.append(filtered_spo2)
        else:
            print("Spike detected, ignoring reading.")

        attempts += 1
        if len(valid_hr_data) >= 3 and len(valid_spo2_data) >= 3:
            break  # Sufficient valid data collected

    # Calculate medians, or use fallback values if no valid data collected
    fallback_hr, fallback_spo2 = 70, 95
    final_hr = round(statistics.median(valid_hr_data)) if valid_hr_data else fallback_hr
    final_spo2 = round(statistics.median(valid_spo2_data)) if valid_spo2_data else fallback_spo2

    print(f"Final Calculated Heart Rate (Median): {final_hr}, Final Calculated SpO2 (Median): {final_spo2}")

    # Connect to the database and update the last row with the calculated median values
    connection = mysql.connector.connect(**db_config)
    cursor = connection.cursor()

    # Update the last row with the calculated heart rate and SpO2 values
    update_query = """
        UPDATE patients 
        SET avg_heart_rate = %s, avg_spo2 = %s 
        ORDER BY id DESC 
        LIMIT 1
    """
    cursor.execute(update_query, (final_hr, final_spo2))
    connection.commit()

    cursor.close()
    connection.close()
    print("Calculated data updated successfully in the last row.")

# HeartRateMonitor setup
print('Sensor starting...')
hrm = HeartRateMonitor(print_raw=args.raw, print_result=(not args.raw))
hrm.start_sensor()

try:
    # Collect and store data
    collect_and_store_data(sensor, args.time)
except KeyboardInterrupt:
    print('Keyboard interrupt detected, exiting...')
finally:
    hrm.stop_sensor()  # Stop the HeartRateMonitor sensor
    sensor.shutdown()  # Ensure the MAX30102 sensor shuts down
    print('Sensor stopped and shut down.')
