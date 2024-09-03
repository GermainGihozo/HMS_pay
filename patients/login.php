<?php
include 'connection.php'; // Include the database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Prepare and bind
    $stmt = $conn->prepare("SELECT password FROM patients WHERE names = ?");
    $stmt->bind_param("s", $username);
    
    // Execute the query
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();
        
        // Verify the password
        if (password_verify($password, $hashedPassword)) {
            // Login successful
            session_start();
            $_SESSION['username'] = $username; // Store username in session
            header("Location: dashboard.php"); // Redirect to a welcome page or dashboard
            exit();
        } else {
            // Login failed
            echo "Invalid username or password.";
        }
    } else {
        // Username not found
        echo "Invalid username or password.";
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
  <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"> -->
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <title>Patient Login</title>
</head>
<body class="bg-dark text-light">
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card bg-secondary text-light">
          <div class="card-body">
            <h4 class="card-title text-center mb-4">Patient Login</h4>
            <form id="login-form" action="login.php" method="post">
              <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
              </div>
              <button type="submit" class="btn btn-primary w-100">Login</button>
              <p class="mt-3 text-center">Don't have an account? <a href="register.php" class="text-light">Register here</a></p>
            </>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="server.js"></script>
</body>
</html>
