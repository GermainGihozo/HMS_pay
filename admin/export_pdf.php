<?php
require_once "connection.php"; // Ensure this is the correct path to your connection file
require('tfpdf/tfpdf.php'); // Ensure this path is correct

// Initialize variables for filters
$filterType = isset($_GET['filterType']) ? $_GET['filterType'] : 'appointments';
$startDate = isset($_GET['startDate']) ? $_GET['startDate'] : '';
$endDate = isset($_GET['endDate']) ? $_GET['endDate'] : '';
$patientId = isset($_GET['patientId']) ? $_GET['patientId'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';

// Fetch data using the same fetchData function from your main script
function fetchData($conn, $filterType, $startDate, $endDate, $patientId, $status) {
    $sql = "";
    if ($filterType == 'appointments') {
        $sql = "SELECT a.id, a.date, a.time, p.names AS names, a.description 
                FROM appointments a
                JOIN patients p ON a.patient_id = p.id
                WHERE 1=1";
        if ($startDate) $sql .= " AND a.date >= ?";
        if ($endDate) $sql .= " AND a.date <= ?";
        if ($patientId) $sql .= " AND a.patient_id = ?";
    } elseif ($filterType == 'bills') {
        $sql = "SELECT b.id, b.date, b.amount, p.names AS names, b.status 
                FROM bills b
                JOIN patients p ON b.patient_id = p.id
                WHERE 1=1";
        if ($startDate) $sql .= " AND b.date >= ?";
        if ($endDate) $sql .= " AND b.date <= ?";
        if ($patientId) $sql .= " AND b.patient_id = ?";
        if ($status) $sql .= " AND b.status = ?";
    }

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die('Error preparing statement: ' . $conn->error);
    }

    $bindParams = [];
    if ($startDate) $bindParams[] = $startDate;
    if ($endDate) $bindParams[] = $endDate;
    if ($patientId) $bindParams[] = $patientId;
    if ($status) $bindParams[] = $status;

    if (!empty($bindParams)) {
        $stmt->bind_param(str_repeat('s', count($bindParams)), ...$bindParams);
    }

    if (!$stmt->execute()) {
        die('Error executing statement: ' . $stmt->error);
    }

    $results = $stmt->get_result();
    if ($results === false) {
        die('Error fetching result: ' . $stmt->error);
    }

    return $results;
}

$results = fetchData($conn, $filterType, $startDate, $endDate, $patientId, $status);

if ($results->num_rows == 0) {
    die('No records found.');
}

// Create a new PDF document
$pdf = new tFPDF();
$pdf->AddPage();

// Set up a custom receipt header
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Byumba Hospital', 0, 1, 'C');
$pdf->SetFont('Arial', 'I', 12);
$pdf->Cell(0, 10, 'Minister of Health, Gicumbi district', 0, 1, 'C');
$pdf->Cell(0, 10, 'Phone: +250784873039', 0, 1, 'C');
$pdf->Ln(10); // Add some space

// Receipt title
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, strtoupper($filterType) . ' RECEIPT', 0, 1, 'C');
$pdf->Ln(10); // Add some space

// Table header with borders
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(230, 230, 230); // Light grey background

if ($filterType == 'appointments') {
    $pdf->Cell(20, 10, 'ID', 1, 0, 'C', true);
    $pdf->Cell(40, 10, 'Date', 1, 0, 'C', true);
    $pdf->Cell(40, 10, 'Time', 1, 0, 'C', true);
    $pdf->Cell(50, 10, 'Patient', 1, 0, 'C', true);
    $pdf->Cell(60, 10, 'Description', 1, 1, 'C', true);
} elseif ($filterType == 'bills') {
    $pdf->Cell(20, 10, 'ID', 1, 0, 'C', true);
    $pdf->Cell(40, 10, 'Date', 1, 0, 'C', true);
    $pdf->Cell(40, 10, 'Amount', 1, 0, 'C', true);
    $pdf->Cell(50, 10, 'Patient', 1, 0, 'C', true);
    $pdf->Cell(40, 10, 'Status', 1, 1, 'C', true);
}

// Table data
$pdf->SetFont('Arial', '', 12);
while ($row = $results->fetch_assoc()) {
    if ($filterType == 'appointments') {
        $pdf->Cell(20, 10, $row['id'], 1);
        $pdf->Cell(40, 10, $row['date'], 1);
        $pdf->Cell(40, 10, $row['time'], 1);
        $pdf->Cell(50, 10, $row['names'], 1);
        $pdf->Cell(60, 10, $row['description'], 1, 1);
    } elseif ($filterType == 'bills') {
        $pdf->Cell(20, 10, $row['id'], 1);
        $pdf->Cell(40, 10, $row['date'], 1);
        $pdf->Cell(40, 10, $row['amount'], 1);
        $pdf->Cell(50, 10, $row['names'], 1);
        $pdf->Cell(40, 10, $row['status'], 1, 1);
    }
}

// Add a footer
$pdf->Ln(10);
$pdf->SetFont('Arial', 'I', 10);
$pdf->Cell(0, 10, 'Thank you for your visit!', 0, 1, 'C');
$pdf->Cell(0, 10, 'This is a system-generated receipt.', 0, 1, 'C');

// Output the PDF to the browser
$pdf->Output('D', 'receipt.pdf');
exit();
