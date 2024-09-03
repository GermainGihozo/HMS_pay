<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

$pageTitle = "Manage Users";
$currentPage = "users"; // Sets the active link in the sidebar

// Include the database connection file
include 'connection.php';

// Get the logged-in user's username
$loggedInUsername = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <title><?php echo $pageTitle; ?></title>
  <style>
    body {
      background-color: #f8f9fa;
    }
    .sidebar {
      height: 100vh;
      background-color: #343a40;
      color: white;
      transition: width 0.3s;
    }
    .sidebar.collapsed {
      width: 0;
      overflow: hidden;
    }
    .error{
      color: Red;
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
      flex-grow: 1;
    }
    .nav-link.active {
      background-color: #495057;
    }
    .header, .footer {
      background-color: #343a40;
      color: white;
      padding: 1rem;
    }
    .footer {
      position: fixed;
      bottom: 0;
      width: 100%;
    }
    .footer p {
      margin: 0;
    }
    .toggler-btn {
      background-color: #007bff;
      border: none;
      color: white;
      font-size: 1.5rem;
      border-radius: 0.375rem;
      padding: 0.5rem 1rem;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
      transition: background-color 0.3s, transform 0.2s;
    }
    .toggler-btn:hover {
      background-color: #0056b3;
      transform: scale(1.05);
    }
    .toggler-btn:focus {
      box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.5);
      outline: none;
    }
  </style>
</head>
<body>
  <div class="d-flex flex-column min-vh-100">
    <header class="header">
      <div class="container d-flex justify-content-between align-items-center">
        <h1><?php echo $pageTitle; ?></h1>
        <button class="toggler-btn d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar" aria-expanded="false" aria-controls="sidebar">
          <i class="fas fa-bars"></i> <!-- Font Awesome icon -->
        </button>
      </div>
    </header>

    <div class="d-flex flex-grow-1">
      <nav id="sidebar" class="sidebar collapse d-lg-block p-3">
        <h4 class="text-center">Admin Panel</h4>
        <ul class="nav flex-column">
          <li class="nav-item">
            <a class="nav-link <?php echo ($currentPage == 'dashboard') ? 'active' : ''; ?>" href="dashboard.php">Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo ($currentPage == 'users') ? 'active' : ''; ?>" href="users.php">Manage Users</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo ($currentPage == 'patients') ? 'active' : ''; ?>" href="patients.php">Manage Patients</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo ($currentPage == 'appointments') ? 'active' : ''; ?>" href="appointments.php">Manage Appointments</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo ($currentPage == 'billing') ? 'active' : ''; ?>" href="billing.php">Billing</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo ($currentPage == 'reports') ? 'active' : ''; ?>" href="reports.php">Reports</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo ($currentPage == 'settings') ? 'active' : ''; ?>" href="settings.php">Settings</a>
          </li>
          <li class="nav-item mt-4">
            <a class="nav-link text-danger" href="logout.php">Logout</a>
          </li>
        </ul>
      </nav>
      <main class="main-content flex-fill">
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addUserModal">Add User</button>
        <table class="table table-striped table-dark">
          <thead>
            <tr>
              <th>Username</th>
              <th>Role</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            // Fetch users from the database, excluding the logged-in user
            $sql = "SELECT * FROM users WHERE username != ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $loggedInUsername);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['username']) . "</td>
                            <td>" . htmlspecialchars($row['role']) . "</td>
                            <td>
                                <a href='edit_user.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm'>Edit</a>
                                <a href='delete_user.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm'>Delete</a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No users found</td></tr>";
            }

            // Close statement and connection
            $stmt->close();
            $conn->close();
            ?>
          </tbody>
        </table>
        <!-- Add User Modal -->
        <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
              <?php

// Initialize variables
$names = $userName = $password = $confirm_password = $role = "";
$nameErr = $usernameErr = $passErr = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate names
    if (empty($_POST['names'])) {
        $nameErr = "Names required";
    } else {
        $names = $_POST['names'];
    }

    // Validate username
    if (empty($_POST['username'])) {
        $usernameErr = "Username required";
    } else {
        $userName = $_POST['username'];
    }

    // Validate password
    if (empty($_POST['password'])) {
        $passErr = "Password required";
    } else {
        $password = $_POST['password'];
    }

    // Confirm password match
    if ($password != $_POST['confirm_password']) {
        $passErr = "Passwords do not match";
    } else {
        $confirm_password = $_POST['confirm_password'];
    }

    // Assign role
    $role = $_POST['role'];

    // If there are no errors, proceed to insert data into the database
    if (empty($nameErr) && empty($usernameErr) && empty($passErr)) {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Include the database configuration file
        include 'connection.php'; // Ensure this file has the database connection code

        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO users(names, username, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $names, $userName, $hashedPassword, $role);

        // Execute the query
        if ($stmt->execute()) {
          echo "New user created successfully!";
      } else {
          echo "Error: " . $stmt->error;
      }
      
      
        // Close the statement and connection
        $stmt->close();
        $conn->close();
    } else {
        // Output error messages
        echo "Please correct the following errors:<br>";
        if (!empty($nameErr)) echo $nameErr . "<br>";
        if (!empty($usernameErr)) echo $usernameErr . "<br>";
        if (!empty($passErr)) echo $passErr . "<br>";
    }
}
?>


                <form id="add-user-form" method="POST" action="users.php">
                  <div class="mb-3">
                    <label for="names" class="form-label">names <span class="error">*<?php echo $nameErr ?></span></label>
                    <input type="text" class="form-control" id="names" name="names" required>
                  </div>
                  <div class="mb-3">
                    <label for="username" class="form-label">Username <span class="error">* <?php echo $usernameErr ?></span></label>
                    <input type="text" class="form-control" id="username" name="username" required>
                  </div>
                  <div class="mb-3">
                    <label for="password" class="form-label">password <span class="error">* <?php echo $passErr ?></span></label>
                    <input type="password" class="form-control" id="password" name="password" required>
                  </div>
                  <div class="mb-3">
                    <label for="confirm-password" class="form-label">Confirm password <span class="error">* <?php echo $passErr ?></span> </label>
                    <input type="password" class="form-control" id="confirm-password" name="confirm_password" required>
                  </div>
                  <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select" id="role" name="role" required>
                      <option value="admin">Admin</option>
                      <option value="doctor">Doctor</option>
                      <option value="nurse">Nurse</option>
                      <option value="staff">Staff</option>
                    </select>
                  </div>
                  <button type="submit" class="btn btn-primary">Add User</button>
                </form>
              </div>
            </div>
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
  <script>
    document.getElementById('toggler').addEventListener('click', function() {
      document.getElementById('sidebar').classList.toggle('collapsed');
      document.getElementById('main-content').classList.toggle('collapsed');
    });
  </script>
</body>
</html>