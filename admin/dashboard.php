<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <title>Admin Dashboard</title>
  <style>
    body {
      background-color: #f8f9fa;
    }
    .sidebar {
      height: 100vh;
      background-color: #343a40;
      color: white;
    }
    .sidebar a {
      color: white;
      text-decoration: none;
    }
    .sidebar a:hover {
      background-color: #495057;
    }
    .main-content {
      padding: 2rem;
    }
    .nav-link.active {
      background-color: #495057;
    }
  </style>
</head>
<body>
  <div class="d-flex">
    <nav class="sidebar p-3">
      <h2 class="text-center">Admin Panel</h2>
      <ul class="nav flex-column">
        <li class="nav-item">
          <a class="nav-link active" href="dashboard.php">Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="users.php">Manage Users</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="patients.php">Manage Patients</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="appointments.php">Manage Appointments</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="billing.php">Billing</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="reports.php">Reports</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="settings.php">Settings</a>
        </li>
        <li class="nav-item mt-4">
          <a class="nav-link text-danger" href="logout.php">Logout</a>
        </li>
      </ul>
    </nav>
    <div class="main-content flex-fill">
      <h2>Welcome to the Admin Dashboard, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
      <!-- Dashboard content goes here -->
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
