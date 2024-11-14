<?php
// Include database configuration file
require_once 'db_config.php';

// Initialize the response array
$response = array();

// Check if the database connection is established
if (!isset($conn) || $conn->connect_error) {
    $response['error'] = "Database connection failed: " . $conn->connect_error;
    echo json_encode($response);
    exit();
}

// Check if sensor data and patient ID are provided
if (isset($_POST['sensor_data']) && isset($_POST['patient_id'])) {
    $sensor_data = $_POST['sensor_data'];
    $patient_id = $_POST['patient_id'];

    // Insert sensor data into the database
    $sql = "INSERT INTO sensor_data (patient_id, data) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('is', $patient_id, $sensor_data);

    if ($stmt->execute()) {
        $response['message'] = "Sensor data stored successfully!";
    } else {
        $response['error'] = "Failed to store sensor data: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
} else {
    $response['error'] = "Invalid input. Please provide both sensor data and patient ID.";
}

// Close the database connection
$conn->close();

// Return the response as JSON
echo json_encode($response);
?>
