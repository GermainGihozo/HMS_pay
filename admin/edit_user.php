<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require_once "connection.php";

$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$pageTitle = "Edit User"; // Define the page title

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $names = htmlspecialchars($_POST['names']);
    $email = htmlspecialchars($_POST['email']);
    $role = htmlspecialchars($_POST['role']);

    $stmt = $conn->prepare("UPDATE users SET username=?, names=?, email=?, role=? WHERE id=?");

    $stmt->bind_param("ssssi", $username, $names, $email, $role, $user_id);

    if ($stmt->execute()) {
        echo "<p class='text-success'>User updated successfully!</p>";
    } else {
        echo "<p class='text-danger'>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $conn->close();

    header("Location: users.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
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
          <i class="fas fa-bars"></i>
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
          <!-- <li class="nav-item">
            <a class="nav-link <?php echo ($currentPage == 'appointments') ? 'active' : ''; ?>" href="appointments.php">Manage Appointments</a>
          </li> -->
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
      <form method="POST" action="edit_user.php?id=<?php echo $user_id; ?>">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label for="names" class="form-label">names</label>
                <input type="names" class="form-control" id="names" name="names" value="<?php echo htmlspecialchars($user['names'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email </label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
            </div>

            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-select" id="role" name="role" required>
                    <option value="admin" <?php if ($user['role'] === 'admin') echo 'selected'; ?>>Admin</option>
                    <option value="staff" <?php if ($user['role'] === 'staff') echo 'selected'; ?>>Staff</option>
                    <option value="nurse" <?php if ($user['role'] === 'nurse') echo 'selected'; ?>>Nurse</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update User</button>
        </form>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>

    <footer class="footer">
      <div class="container">
        <p>&copy; 2024 Patients pay System. All rights reserved.</p>
      </div>
    </footer>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
