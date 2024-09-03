<?php
include 'connection.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, role, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($userId, $role, $hashedPassword);
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {
            if ($role != 'Admin') {
                $_SESSION['userId'] = $userId;
                $_SESSION['role'] = $role;
                $_SESSION['username'] = $username;
                header("Location: staff_dashboard.php");
            } else {
                echo "Admin users cannot log in here.";
            }
        } else {
            echo "Invalid username or password.";
        }
    } else {
        echo "Invalid username or password.";
    }

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
    .fade-in {
        animation: fadeIn 2s ease-in-out;
    }

    @keyframes fadeIn {
        0% { opacity: 0; }
        100% { opacity: 1; }
    }

    .slide-in {
        animation: slideIn 1.5s ease-out;
    }

    @keyframes slideIn {
        0% { transform: translateY(-20px); opacity: 0; }
        100% { transform: translateY(0); opacity: 1; }
    }
  </style>
</head>
<body class="bg-dark text-light d-flex flex-column min-vh-100">
    <header class="bg-dark text-light py-3 mb-4 fade-in">
        <div class="container text-center">
            <h1 class="mb-0">Patient Pay System</h1>
            <p class="mb-0">Secure and Convenient Payment Solutions for Patients</p>
        </div>
    </header>
  
    <div class="container mt-5 slide-in">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card bg-secondary text-light">
                    <div class="card-body">
                        <h4 class="card-title text-center mb-4">Staff Login</h4>
                        <?php if (isset($loginError)): ?>
                            <div class="alert alert-danger text-center">
                                <?php echo $loginError; ?>
                            </div>
                        <?php endif; ?>
                        <form id="login-form" action="staff_login.php" method="post">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>