<?php
require_once "connection.php"; // Ensure this is the correct path to your connection file

// Initialize variables for filters
$filterType = isset($_GET['filterType']) ? $_GET['filterType'] : 'appointments';
$startDate = isset($_GET['startDate']) ? $_GET['startDate'] : '';
$endDate = isset($_GET['endDate']) ? $_GET['endDate'] : '';
$patientId = isset($_GET['patientId']) ? $_GET['patientId'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';

// Fetch data using a function that executes the appropriate query based on filters
function fetchData($conn, $filterType, $startDate, $endDate, $patientId, $status) {
    $sql = "";

    if ($filterType == 'appointments') {
        $sql = "SELECT a.id, a.date, a.time, p.names AS names, a.description 
                FROM appointments a
                JOIN patients p ON a.patient_id = p.id
                WHERE 1=1";
                
        // Append conditions based on filters
        if ($startDate) $sql .= " AND a.date >= '$startDate'";
        if ($endDate) $sql .= " AND a.date <= '$endDate'";
        if ($patientId) $sql .= " AND a.patient_id = '$patientId'";
    } elseif ($filterType == 'bills') {
        $sql = "SELECT b.id, b.date, b.amount, p.names AS names, b.status 
                FROM bills b
                JOIN patients p ON b.patient_id = p.id
                WHERE 1=1";
                
        // Append conditions based on filters
        if ($startDate) $sql .= " AND b.date >= '$startDate'";
        if ($endDate) $sql .= " AND b.date <= '$endDate'";
        if ($patientId) $sql .= " AND b.patient_id = '$patientId'";
        if ($status) $sql .= " AND b.status = '$status'";
    }

    // Execute the query and handle errors
    $result = $conn->query($sql);

    if ($conn->error) {
        die("Error in SQL query: " . $conn->error);
    }

    return $result;
}

$results = fetchData($conn, $filterType, $startDate, $endDate, $patientId, $status);

// Check if $results is not null and contains rows
if (!$results || $results->num_rows == 0) {
    die("No records found or query failed.");
}

// Output headers for downloading the CSV file
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=report.csv');

$output = fopen('php://output', 'w');

// Write the column headers
if ($filterType == 'appointments') {
    fputcsv($output, ['ID', 'Date', 'Time', 'Patient', 'Description']);
} elseif ($filterType == 'bills') {
    fputcsv($output, ['ID', 'Date', 'Amount', 'Patient', 'Status']);
}

// Write the data rows
while ($row = $results->fetch_assoc()) {
    $formattedDate = date('Y-m-d', strtotime($row['date'])); // Adjust date format if needed
    if ($filterType == 'appointments') {
        fputcsv($output, [$row['id'], $formattedDate, $row['time'], $row['names'], $row['description']]);
    } elseif ($filterType == 'bills') {
        fputcsv($output, [$row['id'], $formattedDate, $row['amount'], $row['names'], $row['status']]);
    }
}

fclose($output);
exit();
?>
