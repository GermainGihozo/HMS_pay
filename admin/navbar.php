<?php
// Check if the session is not started yet
if (session_status() == PHP_SESSION_NONE) {
    // Set session parameters before starting the session
    ini_set('session.cookie_lifetime', 3600); // Adjust as needed
    session_set_cookie_params(3600); // Adjust as needed
    
    // Start the session
    session_start();
}
?>



<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="dashboard.php">Admin Dashboard</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" href="dashboard.php">Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="users.php">Manage Users</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="patients.php">Manage Patients</a>
        </li>
        <!-- <li class="nav-item">
          <a class="nav-link" href="appointments.php">Manage Appointments</a>
        </li> -->
        <li class="nav-item">
          <a class="nav-link" href="billing.php">Billing</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="reports.php">Reports</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="settings.php">Settings</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-danger" href="logout.php">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
