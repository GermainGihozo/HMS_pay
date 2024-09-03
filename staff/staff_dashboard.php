<?php
include 'connection.php';
include 'navbar.php';
session_start();

if (!isset($_SESSION['userId']) || $_SESSION['role'] == 'Admin') {
    header("Location: staff_login.php");
    exit();
}

$searchTerm = '';
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $searchTerm = "%$searchTerm%";

    // Fetching patient data with search filter
    $stmt = $conn->prepare("
        SELECT patients.id, patients.names, bills.amount 
        FROM patients 
        LEFT JOIN bills ON patients.id = bills.patient_id
        WHERE patients.names LIKE ?
    ");
    $stmt->bind_param("s", $searchTerm);
} else {
    // Fetching all patient data without search filter
    $stmt = $conn->prepare("
        SELECT patients.id, patients.names, bills.amount 
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
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h3>Welcome, <?php echo $_SESSION['username']; ?></h3>

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
                    <th>Current Bill</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['names']); ?></td>
                        <td><?php echo $row['amount'] !== null ? '$' . number_format($row['amount'], 2) : 'No bill'; ?></td>
                        <td>
                            <a href="generate_bill.php?patient_id=<?php echo $row['id']; ?>" class="btn btn-success">Generate Bill</a>
                            <?php if ($row['amount'] !== null): ?>
                                <a href="cancel_bill.php?patient_id=<?php echo $row['id']; ?>" class="btn btn-danger">Cancel Bill</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
