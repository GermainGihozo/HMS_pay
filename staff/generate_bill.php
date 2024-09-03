<?php
include 'connection.php';
include 'navbar.php';
session_start();

if (!isset($_SESSION['userId']) || $_SESSION['role'] == 'Admin') {
    header("Location: staff_login.php");
    exit();
}

$patient_id = $_GET['patient_id'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $amount = $_POST['amount'];
    $status = $_POST['status'];
    $date = date('Y-m-d');

    $stmt = $conn->prepare("INSERT INTO bills (patient_id, date, amount, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $patient_id, $date, $amount, $status);
    if ($stmt->execute()) {
        echo "Bill generated successfully.";
    } else {
        echo "Failed to generate bill.";
    }
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Generate Bill</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h3>Generate Bill for Patient ID: <?php echo $patient_id; ?></h3>
        <form method="post" action="generate_bill.php?patient_id=<?php echo $patient_id; ?>">
            <div class="mb-3">
                <label for="amount" class="form-label">Amount</label>
                <input type="text" class="form-control" id="amount" name="amount" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="Unpaid">Unpaid</option>
                    <option value="Paid">Paid</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Generate Bill</button>
        </form>
    </div>
</body>
</html>
