<?php
include 'connection.php';
include 'navbar.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Fetch unpaid bills for the logged-in user
$stmt = $conn->prepare("SELECT b.id, b.date, b.amount FROM bills b JOIN patients p ON b.patient_id = p.id WHERE p.names = ? AND b.status = 'unpaid'");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bill_id = $_POST['bill_id'];
    // Update bill status to 'paid'
    $update_stmt = $conn->prepare("UPDATE bills SET status = 'paid' WHERE id = ?");
    $update_stmt->bind_param("i", $bill_id);
    if ($update_stmt->execute()) {
        echo "Payment successful.";
    } else {
        echo "Payment failed.";
    }
    $update_stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Make Payment</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-dark text-light">
    <div class="container mt-5">
        <h3>Unpaid Bills</h3>
        <table class="table table-dark table-striped">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['date']; ?></td>
                        <td><?php echo $row['amount']; ?></td>
                        <td>
                            <form method="post" action="payments.php">
                                <input type="hidden" name="bill_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="btn btn-success">Pay Now</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
