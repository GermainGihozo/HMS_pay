<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <title>Reports</title>
</head>
<body>
  <div class="container mt-5">
    <h2>Reports</h2>
    <form id="report-form">
      <div class="mb-3">
        <label for="report-type" class="form-label">Report Type</label>
        <select class="form-select" id="report-type" required>
          <option value="patient">Patient Report</option>
          <option value="appointment">Appointment Report</option>
          <option value="billing">Billing Report</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary">Generate Report</button>
    </form>
    <!-- Report results will be displayed here -->
    <div id="report-results" class="mt-4">
      <!-- Report content goes here -->
    </div>
  </div>

  <script src="reports.js"></script>
</body>
</html>
