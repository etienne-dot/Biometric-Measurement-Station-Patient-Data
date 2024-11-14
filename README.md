# Biometric-Measurement-Station-Patient-Data
Measurement station for recording standard values of the human body.

## Disclaimer: 
This system is intended for testing purposes only and is not suitable for real-world use. It lacks security by design and should not be used for sensitive or critical applications.

## Issues:
1. Heart Rate Reading Inaccuracy
The heart rate reading provided by the system isn't accurate.

2. Hard-Coded Passwords
There are hard-coded passwords inside files (such as database connection scripts), which can present a security risk.

### Note: These issues are not being addressed as they fall outside the current project scope.


## Required hardware:
Raspberry pi 

SD card 16/32GB

M -> F jumper wires

MAX30102 

MAX30101 (optional)

2x capacitors 10μF

Breadboard

Mouse, keyboard, and screen, or alternatively, CNV can be used!

## Required software:
apache2

MariaDB

Numpy

# Quick Setup Guide
**Install and Configure Required Software:**
Ensure all necessary software dependencies are installed and configured.

**Copy Repository Files:**
Copy the contents of the repository to the Apache2 web directory:

**Copying the Files to the Correct Location:**
sudo cp -R /path/to/your/repo/* /var/www/html/

**Ensuring file permissions:**
sudo chown -R www-data:www-data /var/www/html/*

**Access the User Interface:**
You can now access the UI (site) via Apache2.

**Configure Database Credentials:**<br>
Set the database username, password, and name in the following scripts:
* db_config.php
* sensor.py
  
**Connect the Sensor:**
Ensure the sensor is properly connected to the designated pins.
VCC (Pin 1, 3.3V)  	VCC
GND (Pin 6, Ground)	GND
SDA (Pin 3, GPIO2)	SDA
SCL (Pin 5, GPIO3)	SCL

**Verify Sensor Functionality:**
Run the following script to check if the sensor is working correctly:

**Additional Testing Scripts:**
**Database Connection Tester:** Use test_db.php to confirm a successful database connection.
**Sensor Tester:** Use test_sensor.py to verify the sensor is reading correctly.
