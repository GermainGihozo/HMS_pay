<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

$pageTitle = "Reports";
$currentPage = "reports"; // Sets the active link in the sidebar

require_once "connection.php"; // Ensure this is the correct path to your connection file

// Initialize variables for filters
$filterType = isset($_GET['filterType']) ? $_GET['filterType'] : 'appointments';
$startDate = isset($_GET['startDate']) ? $_GET['startDate'] : '';
$endDate = isset($_GET['endDate']) ? $_GET['endDate'] : '';
$patientId = isset($_GET['patientId']) ? $_GET['patientId'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';

// Function to get patients list for dropdown
function getPatients($conn) {
    $stmt = $conn->prepare("SELECT id, names FROM patients");
    $stmt->execute();
    return $stmt->get_result();
}

// Fetch the data based on filters
function fetchData($conn, $filterType, $startDate, $endDate, $patientId, $status) {
    if ($filterType == 'appointments') {
        $sql = "SELECT a.id, a.date, a.time, p.names AS patient_name, a.description
                FROM appointments a
                JOIN patients p ON a.patient_id = p.id
                WHERE 1=1";
        if ($startDate) $sql .= " AND a.date >= ?";
        if ($endDate) $sql .= " AND a.date <= ?";
        if ($patientId) $sql .= " AND a.patient_id = ?";
        
        $stmt = $conn->prepare($sql);
        $bindParams = [];
        if ($startDate) $bindParams[] = $startDate;
        if ($endDate) $bindParams[] = $endDate;
        if ($patientId) $bindParams[] = $patientId;
        if (!empty($bindParams)) {
          if (!empty($bindParams)) {
            $stmt->bind_param(str_repeat('s', count($bindParams)), ...$bindParams);
        }
        
        }
        $stmt->execute();
        return $stmt->get_result();
    } elseif ($filterType == 'bills') {
        $sql = "SELECT b.id, b.date, b.amount, p.names AS patient_name, b.status
                FROM bills b
                JOIN patients p ON b.patient_id = p.id
                WHERE 1=1";
        if ($startDate) $sql .= " AND b.date >= ?";
        if ($endDate) $sql .= " AND b.date <= ?";
        if ($patientId) $sql .= " AND b.patient_id = ?";
        if ($status) $sql .= " AND b.status = ?";
        
        $stmt = $conn->prepare($sql);
        $bindParams = [];
        if ($startDate) $bindParams[] = $startDate;
        if ($endDate) $bindParams[] = $endDate;
        if ($patientId) $bindParams[] = $patientId;
        if ($status) $bindParams[] = $status;
        if (!empty($bindParams)) {
            $stmt->bind_param(str_repeat('s', count($bindParams)), ...$bindParams);
        }
        $stmt->execute();
        return $stmt->get_result();
    }
}

$results = fetchData($conn, $filterType, $startDate, $endDate, $patientId, $status);
$patients = getPatients($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Head content here -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <title><?php echo $pageTitle; ?></title>
</head>
<body>
  <div class="d-flex flex-column min-vh-100">
    

    <div class="d-flex flex-grow-1">
      
      <nav id="sidebar" class="sidebar collapse d-lg-block p-3">
      <header class="header">
      <div class="container d-flex justify-content-between align-items-center">
        <h1><?php echo $pageTitle; ?></h1>
      </div>
    </header>
        <!-- Sidebar content -->
        <?php include 'sidebar.php'; ?>
      </nav>
      <main class="main-content flex-fill">
        <div class="container">
          <form method="GET" action="reports.php" class="mb-3">
            <div class="row">
              <div class="col-md-3">
                <label for="filterType" class="form-label">Report Type</label>
                <select class="form-select" name="filterType" id="filterType">
                  <option value="appointments" <?php echo $filterType == 'appointments' ? 'selected' : ''; ?>>Appointments</option>
                  <option value="bills" <?php echo $filterType == 'bills' ? 'selected' : ''; ?>>Bills</option>
                </select>
              </div>
              <div class="col-md-3">
                <label for="startDate" class="form-label">Start Date</label>
                <input type="date" class="form-control" name="startDate" id="startDate" value="<?php echo $startDate; ?>">
              </div>
              <div class="col-md-3">
                <label for="endDate" class="form-label">End Date</label>
                <input type="date" class="form-control" name="endDate" id="endDate" value="<?php echo $endDate; ?>">
              </div>
              <div class="col-md-3">
                <label for="patientId" class="form-label">Patient</label>
                <select class="form-select" name="patientId" id="patientId">
                  <option value="">All Patients</option>
                  <?php while ($row = $patients->fetch_assoc()) { ?>
                    <option value="<?php echo $row['id']; ?>" <?php echo $patientId == $row['id'] ? 'selected' : ''; ?>>
                      <?php echo htmlspecialchars($row['names']); ?>
                    </option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <?php if ($filterType == 'bills') { ?>
            <div class="row mt-3">
              <div class="col-md-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" name="status" id="status">
                  <option value="">All Status</option>
                  <option value="Paid" <?php echo $status == 'Paid' ? 'selected' : ''; ?>>Paid</option>
                  <option value="Unpaid" <?php echo $status == 'Unpaid' ? 'selected' : ''; ?>>Unpaid</option>
                </select>
              </div>
            </div>
            <?php } ?>
            <div class="mt-3">
              <button type="submit" class="btn btn-primary">Filter</button>
            </div>
          </form>

          <!-- Display Results -->
          <table class="table table-striped">
            <thead>
              <tr>
                <th>ID</th>
                <th>Date</th>
                <?php if ($filterType == 'appointments') { ?>
                <th>Time</th>
                <th>Patient</th>
                <th>Description</th>
                <?php } elseif ($filterType == 'bills') { ?>
                <th>Amount</th>
                <th>Patient</th>
                <th>Status</th>
                <?php } ?>
              </tr>
            </thead>
            <tbody>
              <?php if ($results->num_rows > 0) {
                while ($row = $results->fetch_assoc()) { ?>
                  <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['date']; ?></td>
                    <?php if ($filterType == 'appointments') { ?>
                    <td><?php echo $row['time']; ?></td>
                    <td><?php echo htmlspecialchars($row['patient_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                    <?php } elseif ($filterType == 'bills') { ?>
                    <td><?php echo $row['amount']; ?></td>
                    <td><?php echo htmlspecialchars($row['patient_name']); ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <?php } ?>
                  </tr>
                <?php }
              } else { ?>
                <tr><td colspan="5">No records found</td></tr>
              <?php } ?>
            </tbody>
          </table>

          <!-- Export Options -->
          <div class="mt-3">
             <a href="export_csv.php?filterType=<?php echo $filterType; ?>&startDate=<?php echo $startDate; ?>&endDate=<?php echo $endDate; ?>&patientId=<?php echo $patientId; ?>&status=<?php echo $status; ?>" class="btn btn-success">Export to CSV</a>
              <a href="export_pdf.php?filterType=<?php echo $filterType; ?>&startDate=<?php echo $startDate; ?>&endDate=<?php echo $endDate; ?>&patientId=<?php echo $patientId; ?>&status=<?php echo $status; ?>" class="btn btn-secondary">Export to PDF</a>
          </div>
        </div>
      </main>
    </div>

        <footer class="footer">
            <div class="container text-center">
                <p>&copy; <?php echo date("Y"); ?> HMS. All rights reserved.</p>
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
