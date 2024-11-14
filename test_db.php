<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'meet_user');
define('DB_PASSWORD', 'Welkom01!');
define('DB_NAME', 'patient_database');

$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connected successfully.";
}
?>
