<?php
session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection settings (directly in index.php)
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'meet_user');
define('DB_PASSWORD', 'Welkom01!');  // Replace with your MySQL password if necessary
define('DB_NAME', 'patient_database');

// Create a connection
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $birth_date = $_POST['birth_date'];
    $gender = $_POST['gender'];

    // Validate form inputs
    if (empty($first_name) || empty($last_name) || empty($birth_date) || empty($gender)) {
        $error_message = "Vul alle velden in. Alstublieft!";
    } else {
        // Insert user data into the database
        $sql = "INSERT INTO patients (first_name, last_name, birth_date, gender) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssss', $first_name, $last_name, $birth_date, $gender);

        if ($stmt->execute()) {
            // Registration success, display success message and trigger the redirect
            $success_message = "Patienten data successvol verstuurd!";
            $redirect = true;  // Flag to trigger the JavaScript redirect
        } else {
            $error_message = "Fout in data versturen. Probeer nog een keer.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoer Patient Gegevens</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 500px;
            margin-top: 50px;
        }
        .card {
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    .form-control {
        box-shadow: 0 0 8px rgba(0, 123, 255, 0.5);
        border-color: #030603;
    }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <h3 class="text-center mb-4">Invoer Patient Gegevens</h3>

        <!-- Display success or error messages -->
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($success_message); ?>
            </div>
            <script>
                // Redirect to Page3.html after 3 seconds
                setTimeout(function() {
                    window.location.href = "Page3.php";
                }, 3000);
            </script>
        <?php endif; ?>

        <!-- Form for user input -->
        <form action="Page2.php" method="POST">
            <div class="form-group mb-3">
                <label for="first_name">Voornaam</label>
                <input type="text" id="first_name" name="first_name" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label for="last_name">Achternaam</label>
                <input type="text" id="last_name" name="last_name" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label for="birth_date">Geboortedatum</label>
                <input type="date" id="birth_date" name="birth_date" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label for="gender">Geslacht</label>
                <select id="gender" name="gender" class="form-control" required>
                    <option value="">Selecteer geslacht</option>
                    <option value="Male">Man</option>
                    <option value="Female">Vrouw</option>
                </select>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
