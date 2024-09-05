<?php
include 'connection.php';
include 'navbar.php';
session_start();

// Check if the user is logged in and not an Admin
if (!isset($_SESSION['userId']) || $_SESSION['role'] == 'Admin') {
    header("Location: staff_login.php");
    exit();
}

$searchTerm = '';
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $searchTerm = "%$searchTerm%";

    // Fetch patient data with search filter
    $stmt = $conn->prepare("
        SELECT patients.id, patients.names, bills.amount, bills.status 
        FROM patients 
        LEFT JOIN bills ON patients.id = bills.patient_id
        WHERE patients.names LIKE ?
    ");

    $stmt->bind_param("s", $searchTerm);
} else {
    // Fetch all patient data without search filter
    $stmt = $conn->prepare("
        SELECT patients.id, patients.names, bills.amount, bills.status
        FROM patients 
        LEFT JOIN bills ON patients.id = bills.patient_id
    ");
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            color: #343a40;
        }
        .container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        h3 {
            color: #007bff;
        }
        .table {
            background-color: #ffffff;
        }
        .table thead {
            background-color: #343a40;
            color: #ffffff;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .input-group .form-control {
            border-right: none;
        }
        .input-group .btn {
            border-left: none;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h3>Welcome, <?php echo $_SESSION['staff_username']; ?></h3>

        <!-- Search Form -->
        <form class="mb-4" method="GET" action="staff_dashboard.php">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search for a patient" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <button class="btn btn-primary" type="submit">Search</button>
            </div>
        </form>

        <!-- Patients Table -->
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Patient Name</th>
                    <th>Current Bill (Rwf)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['names']); ?></td>
                    <td>
                        <?php 
                        // Check if status exists and handle accordingly
                        if (isset($row['status']) && $row['status'] === 'Paid') {
                            echo 'No bill';
                        } elseif ($row['amount'] !== null) {
                            echo number_format($row['amount'], 2);
                        } else {
                            echo 'No bill';
                        }
                        ?>
                    </td>
                    <td>
                        <a href="generate_bill.php?patient_id=<?php echo $row['id']; ?>" class="btn btn-success">Generate Bill</a>
                        
                        <!-- Only show the Cancel Bill button if the bill is unpaid -->
                        <?php if (isset($row['status']) && $row['status'] === 'Unpaid'): ?>
                            <a href="cancel_bill.php?patient_id=<?php echo $row['id']; ?>" class="btn btn-danger">Cancel Bill</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
