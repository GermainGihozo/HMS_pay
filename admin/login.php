<?php
session_start();

// Include database connection
require_once 'connection.php';

// Initialize variables for errors
$usernameErr = $passwordErr = "";
$loginSuccess = false;

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Validate username
    if (empty($username)) {
        $usernameErr = "Username is required.";
    }

    // Validate password
    if (empty($password)) {
        $passwordErr = "Password is required.";
    }

    // Check if there are no errors
    if (empty($usernameErr) && empty($passwordErr)) {
        // Prepare and execute SQL query
        $sql = "SELECT password FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($hashedPassword);
            $stmt->fetch();

            // Verify the password
            if (password_verify($password, $hashedPassword)) {
                $loginSuccess = true;
                $_SESSION['username'] = $username;
                header("Location: dashboard.php"); // Redirect to a dashboard or another page
                exit();
            } else {
                $passwordErr = "Invalid username or password.";
            }
        } else {
            $usernameErr = "Invalid username or password.";
        }

        $stmt->close();
    }

    $conn->close();
}
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/login.css"> 
  <title>Admin Login</title>
</head>
<body>
  <!-- Header Section -->
  <div class="header">
    <h1>Welcome to Patient Pay - Admin Portal</h1>
  </div>

  <!-- Login Form -->
  <div class="login-container mt-5">
    <h2>Admin Login</h2>
    <form id="login-form" method="POST" action="login.php">
      <div class="mb-3">
        <label for="username" class="form-label">Username
          <span class="error">*<?php echo isset($usernameErr) ? $usernameErr : ''; ?></span>
        </label>
        <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password 
          <span class="error">*<?php echo isset($passwordErr) ? $passwordErr : ''; ?></span>
        </label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
      </div>
      <button type="submit" class="btn btn-primary">Login</button>
    </form>
  </div>

  <!-- Footer Section -->
  <div class="footer">
    <p>&copy; 2024 Patient Pay. All rights reserved.</p>
  </div>

  <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
