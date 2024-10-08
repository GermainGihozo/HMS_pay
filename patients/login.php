<?php
session_start(); // Start the session at the very top

include 'connection.php'; // Include the database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    $stmt = $conn->prepare("SELECT password FROM patients WHERE names = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();
        
        if (password_verify($password, $hashedPassword)) {
            // Regenerate session ID to prevent fixation attacks
            session_regenerate_id(true);

            $_SESSION['username'] = $username; // Store username in session
            $_SESSION['LAST_ACTIVITY'] = time(); // Initialize the session timeout
            header("Location: dashboard.php"); // Redirect to dashboard
            exit();
        } else {
            $loginError = "Invalid username or password.";
        }
    } else {
        $loginError = "Invalid username or password.";
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
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <title>Patient Login</title>
</head>
<body class="d-flex flex-column min-vh-100 bg-dark text-light">
    <header class="bg-primary text-white text-center py-4 animate-header">
        <div class="container">
            <h1 class="display-4">Patient Pay System</h1>
            <p class="lead">Secure and efficient access to your healthcare billing and payment.</p>
        </div>
    </header>

    <div class="container mt-5 flex-grow-1">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card bg-secondary text-light shadow-lg animate-card">
                    <div class="card-body">
                        <h4 class="card-title text-center mb-4 animate-heading">Patient Login</h4>
                        <?php if (isset($loginError)): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($loginError); ?></div>
                        <?php endif; ?>
                        <form id="login-form" action="login.php" method="post">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Enter your names" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 animate-button">Login</button>
                            <p class="mt-3 text-center">Don't have an account? <a href="register.php" class="text-light">Register here</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-3 mt-auto fade-in">
        <div class="container">
            <p>&copy; 2024 Patient Pay System. All rights reserved.</p>
            <p>Your health, our priority. Secure payments, seamless care.</p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
