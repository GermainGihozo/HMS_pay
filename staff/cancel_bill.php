<?php
include 'connection.php';

if (isset($_GET['patient_id'])) {
    $patientId = $_GET['patient_id'];

    $stmt = $conn->prepare("DELETE FROM bills WHERE patient_id = ?");
    $stmt->bind_param("i", $patientId);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Bill canceled successfully.";
    } else {
        echo "No bill found for this patient.";
    }

    $stmt->close();
    $conn->close();

    header("Location: staff_dashboard.php");
    exit();
}
?>
