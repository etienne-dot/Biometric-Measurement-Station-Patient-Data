<?php
// Database configuration
$db_config = [
    'user' => 'meet_user',
    'password' => 'Welkom01!',
    'host' => 'localhost',
    'database' => 'patient_database'
];

// Function to establish a database connection
function getDBConnection($db_config) {
    $conn = new mysqli($db_config['host'], $db_config['user'], $db_config['password'], $db_config['database']);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to trigger sensor.py and update the database
function startMeasurement() {
    // Execute the sensor.py script
    shell_exec('python3 /var/www/html/sensor.py 2>&1');
}

// Handle form submission (when the "Start meting" button is clicked)
$measurementDone = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Start the measurement process
    startMeasurement();
    $measurementDone = true; // Signal that measurement is complete
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Start Met Meten</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
            margin: 0;
        }
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .container h1, .container p, .button-container {
            margin: 10px 0;
        }
        .start-button {
            background-color: #007BFF;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 30px;
            font-size: 18px;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        }
        .start-button:active {
            transform: scale(0.95);
            box-shadow: 4px 4px 15px rgba(0, 0, 0, 0.2);
        }
    </style>
    <script>
        function redirectToEndPage() {
            setTimeout(function() {
                window.location.href = 'Endpage.html';
            }, 5000);
        }
    </script>
</head>
<body onload="<?php if ($measurementDone) echo 'redirectToEndPage()'; ?>">
    <div class="container">
        <h1>Start Met Meten</h1>
        <p>Klik op de knop hieronder om de meting te starten.</p>

        <!-- Form to trigger the measurement -->
        <div class="button-container">
            <form method="post" action="">
                <button type="submit" class="start-button">Start meting</button>
            </form>
        </div>
    </div>
</body>
</html>
