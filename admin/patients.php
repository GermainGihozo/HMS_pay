<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

$pageTitle = "Manage Patients";
$currentPage = "patients"; // Sets the active link in the sidebar

require_once "connection.php"; // Ensure this is the correct path to your connection file

// Initialize variables
$names = $address = $tel_no = $gender = $age = $password ="";
$errors = [];

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['names'])) {
    // Retrieve and sanitize form data
    $names = htmlspecialchars($_POST['names']);
    $address = htmlspecialchars($_POST['address']);
    $tel_no = htmlspecialchars($_POST['tel_no']);
    $gender = htmlspecialchars($_POST['gender']);
    $age = htmlspecialchars($_POST['age']);
    $password = 123;
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Validate form data
    if (empty($names)) $errors[] = "Names are required";
    if (empty($address)) $errors[] = "Address is required";
    if (empty($tel_no) || !preg_match("/^[0-9]{10}$/", $tel_no)) $errors[] = "A valid telephone number is required";
    if (empty($age) || !filter_var($age, FILTER_VALIDATE_INT)) $errors[] = "A valid age is required";

    // If there are no errors, proceed to insert data into the database
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO patients (names, address, telNo, gender, age ,password) VALUES (?, ?, ?, ?, ?,?)");
        $stmt->bind_param("sssssi", $names, $address, $tel_no, $gender, $age,$hashedPassword);

        if ($stmt->execute()) {
            echo "<p class='text-success'>New patient added successfully!</p>";
        } else {
            echo "<p class='text-danger'>Error: " . $stmt->error . "</p>";
        }

        $stmt->close();
        // $conn->close();
    } else {
        foreach ($errors as $error) {
            echo "<p class='text-danger'>Error: $error</p>";
        }
    }
}

// Handle search query
$searchTerm = "";
if (isset($_GET['search'])) {
    $searchTerm = htmlspecialchars($_GET['search']);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Head content here -->
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
  <!-- Body content here -->
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
        <!-- <h4 class="text-center">Admin Panel</h4> -->
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
          <!-- <li class="nav-item"> -->
            <!-- <a class="nav-link <?php echo ($currentPage == 'appointments') ? 'active' : ''; ?>" href="appointments.php">Manage Appointments</a> -->
          <!-- </li> -->
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
        <!-- Add a search form -->
        <form method="GET" action="patients.php" class="d-flex mb-3">
          <input type="text" name="search" class="form-control me-2" placeholder="Search by name..." value="<?php echo $searchTerm; ?>">
          <button type="submit" class="btn btn-outline-success">Search</button>
        </form>

        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addPatientModal">Add Patient</button>
        <table class="table table-striped table-dark">
          <thead>
            <tr>
              <th>Name</th>
              <th>Gender</th>
              <th>Age</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            // Modify the SQL query to filter results based on the search term
            $sql = "SELECT * FROM patients";
            if (!empty($searchTerm)) {
                $sql .= " WHERE names LIKE ?";
            }
            $stmt = $conn->prepare($sql);
            if (!empty($searchTerm)) {
                $searchTerm = "%$searchTerm%";
                $stmt->bind_param("s", $searchTerm);
            }
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['names']) . "</td>
                            <td>" . htmlspecialchars($row['gender']) . "</td>
                            <td>" . htmlspecialchars($row['age']) . "</td>
                            <td>
                                <a href='edit_patient.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm'>Edit</a>
                                <a href='delete_patient.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this patient?\")'>Delete</a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No patients found</td></tr>";
            }

            $stmt->close();
            $conn->close();
            ?>
          </tbody>
        </table>

        <!-- Add Patient Modal -->

        <div class="modal fade" id="addPatientModal" tabindex="-1" aria-labelledby="addPatientModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="addPatientModalLabel">Add Patient</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <form id="add-patient-form" method="POST" action="patients.php">
                      <div class="mb-3">
                        <label for="patient-name" class="form-label">Names</label>
                        <input type="text" class="form-control" id="patient-name" name="names" placeholder="Enter patient's names" required>
                      </div>
                      <div class="mb-3">
                        <label for="adress" class="form-label">Address</label>
                        <input type="text" class="form-control" id="adress" name="address" placeholder="Enter the Patient's address" required>
                      </div>
                      <div class="mb-3">
                        <label for="telephone" class="form-label">Tel no</label>
                        <input type="number" class="form-control" id="telephone" name="tel_no" placeholder="Enter the telephone number" required>
                      </div>
                      <div class="mb-3">
                        <label for="patient-gender" class="form-label">Gender</label>
                        <select class="form-select" id="patient-gender" name="gender" required>
                          <option value="Male">Male</option>
                          <option value="Female">Female</option>
                        </select>
                      </div>
                      <div class="mb-3">
                        <label for="patient-age" class="form-label">Age</label>
                        <input type="number" class="form-control" id="patient-age" name="age" placeholder="Enter patient's ages" required>
                      </div>
                      <button type="submit" class="btn btn-primary">Add Patient</button>
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
  <script src="manage-patients.js"></script>
</body>
</html>
