<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <title>Billing</title>
</head>
<body>
  <div class="container mt-5">
    <h2>Billing</h2>
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#generateBillModal">Generate Bill</button>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Patient Name</th>
          <th>Amount</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <!-- Billing rows will be inserted here -->
      </tbody>
    </table>
  </div>

  <!-- Generate Bill Modal -->
  <div class="modal fade" id="generateBillModal" tabindex="-1" aria-labelledby="generateBillModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="generateBillModalLabel">Generate Bill</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="generate-bill-form">
            <div class="mb-3">
              <label for="patient-name" class="form-label">Patient Name</label>
              <input type="text" class="form-control" id="patient-name" required>
            </div>
            <div class="mb-3">
              <label for="amount" class="form-label">Amount</label>
              <input type="number" class="form-control" id="amount" required>
            </div>
            <button type="submit" class="btn btn-primary">Generate Bill</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="billing.js"></script>
</body>
</html>
