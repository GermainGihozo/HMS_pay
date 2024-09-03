<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <title>Manage Appointments</title>
</head>
<body>
  <div class="container mt-5">
    <h2>Manage Appointments</h2>
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addAppointmentModal">Add Appointment</button>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Patient Name</th>
          <th>Doctor Name</th>
          <th>Date</th>
          <th>Time</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <!-- Appointment rows will be inserted here -->
      </tbody>
    </table>
  </div>

  <!-- Add Appointment Modal -->
  <div class="modal fade" id="addAppointmentModal" tabindex="-1" aria-labelledby="addAppointmentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addAppointmentModalLabel">Add Appointment</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="add-appointment-form">
            <div class="mb-3">
              <label for="patient-name" class="form-label">Patient Name</label>
              <input type="text" class="form-control" id="patient-name" required>
            </div>
            <div class="mb-3">
              <label for="doctor-name" class="form-label">Doctor Name</label>
              <input type="text" class="form-control" id="doctor-name" required>
            </div>
            <div class="mb-3">
              <label for="appointment-date" class="form-label">Date</label>
              <input type="date" class="form-control" id="appointment-date" required>
            </div>
            <div class="mb-3">
              <label for="appointment-time" class="form-label">Time</label>
              <input type="time" class="form-control" id="appointment-time" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Appointment</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="manage-appointments.js"></script>
</body>
</html>
