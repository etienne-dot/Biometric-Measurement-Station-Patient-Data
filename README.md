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

# Quick install guide:

Copy the content of the repo in the next apache2 folder>  /var/www/html/

Install the required software
