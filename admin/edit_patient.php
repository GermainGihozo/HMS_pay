<?php
session_start();
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit();
}

require_once "connection.php";

$patient_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$pageTitle = "Edit Patient"; // Add this line to define $pageTitle

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $names = htmlspecialchars($_POST['names']);
    $address = htmlspecialchars($_POST['address']);
    $tel_no = htmlspecialchars($_POST['tel_no']);
    $gender = htmlspecialchars($_POST['gender']);
    $age = htmlspecialchars($_POST['age']);

    $stmt = $conn->prepare("UPDATE patients SET names=?, address=?, telNo=?, gender=?, age=? WHERE id=?");
    $stmt->bind_param("ssssii", $names, $address, $tel_no, $gender, $age, $patient_id);

    if ($stmt->execute()) {
        echo "<p class='text-success'>Patient updated successfully!</p>";
    } else {
        echo "<p class='text-danger'>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $conn->close();

    header("Location: patients.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM patients WHERE id = ?");
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();
$patient = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Head content here -->
    <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/bootstrap.min.css">
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
    <!-- Add your header and navigation here -->
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
    <div class="container">
        <h2>Edit Patient</h2>
        <form method="POST" action="edit_patient.php?id=<?php echo $patient_id; ?>">
            <div class="mb-3">
                <label for="names" class="form-label">Names</label>
                <input type="text" class="form-control" id="names" name="names" value="<?php echo htmlspecialchars($patient['names']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($patient['address']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="tel_no" class="form-label">Tel no</label>
                <input type="text" class="form-control" id="tel_no" name="tel_no" value="<?php echo htmlspecialchars($patient['tel_no'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label for="gender" class="form-label">Gender</label>
                <select class="form-select" id="gender" name="gender" required>
                    <option value="Male" <?php if ($patient['gender'] === 'Male') echo 'selected'; ?>>Male</option>
                    <option value="Female" <?php if ($patient['gender'] === 'Female') echo 'selected'; ?>>Female</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="age" class="form-label">Age</label>
                <input type="number" class="form-control" id="age" name="age" value="<?php echo htmlspecialchars($patient['age']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Patient</button>
        </form>
    </div>
    <!-- Add your footer here -->
</body>
</html>
