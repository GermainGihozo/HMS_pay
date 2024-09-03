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
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <title>Admin Login</title>
  <style>
    body {
      background-color: #f8f9fa;
    }
    .login-container {
      max-width: 400px;
      margin: auto;
      padding: 2rem;
      background-color: white;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    .error {
      color: red;
    }
  </style>
</head>
<body>
  <div class="login-container mt-5">
    <h2 class="text-center mb-4">Admin Login</h2>
    <form id="login-form" method="POST" action="login.php">
      <div class="mb-3">
        <label for="username" class="form-label">Username
        <span class="error">*<?php echo isset($usernameErr) ? $usernameErr : ''; ?></span>
        </label>
        <input type="text" class="form-control" id="username" name="username" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password 
        <span class="error">*<?php echo isset($passwordErr) ? $passwordErr : ''; ?></span>
        </label>
        <input type="password" class="form-control" id="password" name="password" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
  </div>
  <script src="login.js"></script>
</body>
</html>


<?php
// require_once'connection.php';

// $names="Germain";
// $username="igihozo";
// $password="admin";

// $hashedPassword =password_hash($password,PASSWORD_DEFAULT);
// $sql= "INSERT INTO users(names,username,password) VALUES (?,?,?)";
// // prepare statement
// $stmt= $conn->prepare($sql);
// if ($stmt===false) {
//   die("Error preparing the statement" .$conn->error);
// }
// // bind parameters
// $stmt->bind_param("sss",$names,$username,$hashedPassword);
// // execute parameter
// if($stmt->execute()){
//   echo"record inserted successfully";
// }
// else{
//   echo"error  :" . $stmt->error;
// }
// // close statement
// $stmt->close();
// $conn->close();



?> 