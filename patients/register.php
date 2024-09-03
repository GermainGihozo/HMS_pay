<?php
include 'connection.php'; // Include the database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $address = $_POST['address'];
    $telNo = $_POST['telNo'];
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    
    // Basic validation
    if ($password !== $confirmPassword) {
        echo "Passwords do not match.";
        exit();
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO patients (names, address, telNo, gender, age, password) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $fullName, $address, $telNo, $gender, $age, $hashedPassword);

    // Create full name by combining first and last names
    $fullName = $firstName . " " . $lastName;

    // Execute the query
    if ($stmt->execute()) {
        echo "Registration successful.";
        // Optionally, redirect to a login page or login automatically
        header("Location: login.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/styles.css">
  <title>Patient Registration</title>
</head>
<body class="d-flex flex-column min-vh-100 bg-dark text-light">
  <header class="bg-primary text-white text-center py-4 animate-header">
    <div class="container">
        <h1 class="display-4">Patient Registration</h1>
        <p class="lead">Join our Patient Pay System for secure and efficient healthcare billing.</p>
    </div>
  </header>

  <div class="container mt-5 flex-grow-1">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card bg-secondary text-light shadow-lg animate-card">
          <div class="card-body">
            <h4 class="card-title text-center mb-4 animate-heading">Register Your Account</h4>
            <form id="register-form" action="register.php" method="post">
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="firstName" class="form-label">First Name</label>
                  <input type="text" class="form-control" id="firstName" name="firstName" required>
                </div>
                <div class="col-md-6 mb-3">
                  <label for="lastName" class="form-label">Last Name</label>
                  <input type="text" class="form-control" id="lastName" name="lastName" required>
                </div>
              </div>
              <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" id="address" name="address" required>
              </div>
              <div class="mb-3">
                <label for="telNo" class="form-label">Telephone Number</label>
                <input type="tel" class="form-control" id="telNo" name="telNo" required>
              </div>
              <div class="mb-3">
                <label for="gender" class="form-label">Gender</label>
                <select class="form-select" id="gender" name="gender" required>
                  <option value="" disabled selected>Select your gender</option>
                  <option value="male">Male</option>
                  <option value="female">Female</option>
                  <option value="other">Other</option>
                </select>
              </div>
              <div class="mb-3">
                <label for="age" class="form-label">Age</label>
                <input type="number" class="form-control" id="age" name="age" required>
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
              </div>
              <div class="mb-3">
                <label for="confirmPassword" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
              </div>
              <button type="submit" class="btn btn-primary w-100 animate-button">Register</button>
              <p class="mt-3 text-center">Already have an account? <a href="login.php" class="text-light">Login here</a></p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <footer class="bg-dark text-white text-center py-3 mt-auto animate-footer">
    <div class="container">
      <p>&copy; 2024 Patient Pay System. All rights reserved.</p>
    </div>
  </footer>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
