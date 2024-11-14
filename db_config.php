<?php
// Database connection settings
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'meet_user');  // Change this to your MySQL username
define('DB_PASSWORD', 'Welkom01!');      // Change this to your MySQL password
define('DB_NAME', 'patient_database');  // Name of your database

// Function to create a connection to the database
function getDBConnection() {
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    // Check if the connection is successful
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}
?>
