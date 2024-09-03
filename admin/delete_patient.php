<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require_once "connection.php";

if (isset($_GET['id'])) {
    $patient_id = intval($_GET['id']);

    $stmt = $conn->prepare("DELETE FROM patients WHERE id = ?");
    $stmt->bind_param("i", $patient_id);

    if ($stmt->execute()) {
        echo "<p class='text-success'>Patient deleted successfully!</p>";
    } else {
        echo "<p class='text-danger'>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $conn->close();

    header("Location: patients.php");
    exit();
} else {
    header("Location: patients.php");
    exit();
}
