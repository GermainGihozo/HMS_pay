<?php
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $code = $_POST['verification_code'];

    $stmt = $conn->prepare("SELECT user_id, expiry FROM password_resets WHERE verification_code = ? AND email = ? AND expiry > NOW()");
    $stmt->bind_param("ss", $code, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($userId, $expiry);
        $stmt->fetch();

        // Code is valid, redirect to reset password page
        header("Location: reset_password.php?user_id=$userId");
    } else {
        echo "Invalid or expired code.";
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify Code</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-dark text-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card bg-secondary text-light">
                    <div class="card-body">
                        <h4 class="card-title text-center mb-4">Verify Code</h4>
                        <form action="verify_code.php" method="post">
                            <input type="hidden" name="email" value="<?php echo $_GET['email']; ?>">
                            <div class="mb-3">
                                <label for="verification_code" class="form-label">Verification Code</label>
                                <input type="text" class="form-control" id="verification_code" name="verification_code" placeholder="Enter code" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Verify Code</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
