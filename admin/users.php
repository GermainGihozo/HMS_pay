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

// Initialize search variables
$searchTerm = "";

// Check if the search form is submitted
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
}
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
    .error {
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
       
        <button class="toggler-btn d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar" aria-expanded="false" aria-controls="sidebar">
          <i class="fas fa-bars"></i> <!-- Font Awesome icon -->
        </button>
      </div>
    </header>

    <div class="d-flex flex-grow-1">
      <nav id="sidebar" class="sidebar collapse d-lg-block p-3">
      <h1><?php echo $pageTitle; ?></h1>
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
        <!-- Search form -->
        <form method="GET" action="users.php" class="mb-4">
          <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search users..." value="<?php echo htmlspecialchars($searchTerm); ?>">
            <button class="btn btn-primary" type="submit">Search</button>
          </div>
        </form>

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
            $sql = "SELECT * FROM users WHERE username != ? AND (username LIKE ? OR role LIKE ?)";
            $stmt = $conn->prepare($sql);
            $searchTermLike = '%' . $searchTerm . '%';
            $stmt->bind_param("sss", $loggedInUsername, $searchTermLike, $searchTermLike);
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
                <form id="add-user-form" method="POST" action="users.php">
                  <div class="mb-3">
                    <label for="names" class="form-label">Names <span class="error">*<?php echo $nameErr ?></span></label>
                    <input type="text" class="form-control" id="names" name="names" required>
                  </div>
                  <div class="mb-3">
                    <label for="username" class="form-label">Username <span class="error">* <?php echo $usernameErr ?></span></label>
                    <input type="text" class="form-control" id="username" name="username" required>
                  </div>
                  <div class="mb-3">
                    <label for="password" class="form-label">Password <span class="error">* <?php echo $passwordErr ?></span></label>
                    <input type="password" class="form-control" id="password" name="password" required>
                  </div>
                  <div class="mb-3">
                    <label for="role" class="form-label">Role <span class="error">* <?php echo $roleErr ?></span></label>
                    <select class="form-select" id="role" name="role" required>
                      <option value="admin">Admin</option>
                      <option value="staff">Staff</option>
                    </select>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="submit" class="btn btn-primary">Add User</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>

    <footer class="footer">
      <div class="container">
        <p>&copy; 2024 Hospital Management System. All rights reserved.</p>
      </div>
    </footer>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
