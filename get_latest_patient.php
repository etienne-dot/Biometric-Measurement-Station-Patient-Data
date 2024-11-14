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

// Query to get the latest patient based on the highest ID
$sql = "SELECT id FROM patients ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

// Check if any patient data is returned
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $response['highest_id'] = $row['id'];
} else {
    $response['error'] = "No patients found.";
}

// Close the database connection
$conn->close();

// Return the response as JSON
echo json_encode($response);
?>
