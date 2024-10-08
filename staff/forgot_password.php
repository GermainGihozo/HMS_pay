<?php
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($userId);
        $stmt->fetch();

        // Generate verification code
        $verificationCode = rand(100000, 999999);
        $expiry = date("Y-m-d H:i:s", strtotime("+15 minutes"));

        // Store the code in the database
        $insertStmt = $conn->prepare("INSERT INTO password_resets (user_id, verification_code, expiry) VALUES (?, ?, ?)");
        $insertStmt->bind_param("iss", $userId, $verificationCode, $expiry);
        $insertStmt->execute();

        // Send verification code via email
        mail($email, "Password Reset Code", "Your password reset code is: $verificationCode");

        // Redirect to verification page
        header("Location: verify_code.php?email=$email");
    } else {
        echo "Email not found.";
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-dark text-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card bg-secondary text-light">
                    <div class="card-body">
                        <h4 class="card-title text-center mb-4">Forgot Password</h4>
                        <form action="forgot_password.php" method="post">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Send Reset Code</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
