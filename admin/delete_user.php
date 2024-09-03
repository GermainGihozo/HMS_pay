<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require_once "connection.php";

$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($user_id > 0) {
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        echo "<p class='text-success'>User deleted successfully!</p>";
    } else {
        echo "<p class='text-danger'>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $conn->close();
}

header("Location: users.php");
exit();
?>
