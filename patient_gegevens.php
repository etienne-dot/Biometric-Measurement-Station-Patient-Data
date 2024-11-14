<?php
// Include the database configuration file
require_once 'db_config.php';

// Create a connection using the function from the config file
$conn = getDBConnection();

// Number of records per page
$records_per_page = 5;

// Pagination: Get the current page number from the URL, default to 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $records_per_page;

// Query to fetch patient data, including avg_heart_rate and avg_spo2
$sql = "SELECT first_name, last_name, birth_date, gender, entry_date, avg_heart_rate, avg_spo2 
        FROM patients 
        LIMIT $start, $records_per_page";
$result = $conn->query($sql);

// Count total rows in the database for pagination
$count_sql = "SELECT COUNT(*) AS total FROM patients";
$count_result = $conn->query($count_sql);
$total_rows = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $records_per_page);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patiëntenoverzicht</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom Styles -->
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 40px;
        }
        .table thead th {
            background-color: #005b96;
            color: white;
        }
        .pagination {
            justify-content: center;
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="text-center mb-4">Patiëntenoverzicht</h1>

    <!-- Table displaying the patient data -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Voornaam</th>
                <th>Achternaam</th>
                <th>Geboortedatum</th>
                <th>Geslacht</th>
                <th>Datum van Invoer</th>
                <th>Gemiddelde Hartslag</th>
                <th>Gemiddelde SpO2</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['first_name']); ?></td>
                        <td><?= htmlspecialchars($row['last_name']); ?></td>
                        <td><?= htmlspecialchars($row['birth_date']); ?></td>
                        <td><?= htmlspecialchars($row['gender']); ?></td>
                        <td><?= htmlspecialchars($row['entry_date']); ?></td>
                        <td><?= htmlspecialchars($row['avg_heart_rate']); ?> BPM</td>
                        <td><?= htmlspecialchars($row['avg_spo2']); ?>%</td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">Geen gegevens beschikbaar</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Pagination links -->
    <nav>
        <ul class="pagination">
            <!-- Previous button -->
            <?php if ($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $page - 1; ?>" aria-label="Vorige">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php else: ?>
                <li class="page-item disabled">
                    <a class="page-link" aria-label="Vorige">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php endif; ?>

            <!-- Page numbers -->
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= ($i == $page) ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
                </li>
            <?php endfor; ?>

            <!-- Next button -->
            <?php if ($page < $total_pages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $page + 1; ?>" aria-label="Volgende">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            <?php else: ?>
                <li class="page-item disabled">
                    <a class="page-link" aria-label="Volgende">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Sluit de databaseverbinding
$conn->close();
?>
