<?php
session_start(); // Start the session at the very top

include 'connection.php';
include 'navbar.php'; // Include the navigation bar

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Session timeout handling
$timeout_duration = 1800; // 30 minutes

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    session_unset(); // Unset session variables
    session_destroy(); // Destroy the session
    header("Location: login.php?timeout=true");
    exit();
}
$_SESSION['LAST_ACTIVITY'] = time(); // Update last activity time

// Fetch user details
$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT names FROM patients WHERE names = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($fullName);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <title>Dashboard</title>
</head>
<body class="d-flex flex-column min-vh-100 bg-dark text-light">
    <div class="container mt-4">
        <h2 class="text-center">Welcome, <?php echo htmlspecialchars($fullName); ?>!</h2>
        <p class="text-center mt-3">The Patient Pay System allows you to easily manage your healthcare-related payments. You can view your billing information, make payments, and track your payment history all in one place.</p>
        
        <!-- Additional content goes here -->
    </div>

    <footer class="bg-dark text-white text-center py-3 mt-auto">
        <div class="container">
            <p>&copy; 2024 Patient Pay System. All rights reserved.</p>
            <p>Our system is dedicated to providing a seamless and secure payment experience for our patients.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
