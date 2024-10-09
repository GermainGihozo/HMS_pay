<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Ensure this path is correct based on your structure

include 'connection.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];

    // Check if the user exists
    $stmt = $conn->prepare("SELECT id, email FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($userId, $email);
        $stmt->fetch();

        // Generate a random verification code
        $verificationCode = bin2hex(random_bytes(3)); // 6 characters
        $expiry = date('Y-m-d H:i:s', strtotime('+30 minutes')); // expires in 30 minutes

        // Insert the verification code into the password_resets table
        $insertStmt = $conn->prepare("INSERT INTO password_resets (user_id, verification_code, expiry) VALUES (?, ?, ?)");
        $insertStmt->bind_param("iss", $userId, $verificationCode, $expiry);
        $insertStmt->execute();
        
        // Send the email with the verification code using PHPMailer
        if (sendEmail($email, $verificationCode)) {
            echo "A verification code has been sent to your email.";
        } else {
            echo "Failed to send verification email.";
        }
        
        $insertStmt->close();
    } else {
        echo "Username does not exist.";
    }

    $stmt->close();
    $conn->close();
}

function sendEmail($to, $verificationCode) {
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();                                            // Set mailer to use SMTP
        $mail->Host       = 'smtp.gmail.com';                     // Specify main and backup SMTP servers
        $mail->SMTPAuth   = true;                                 // Enable SMTP authentication
        $mail->Username   = 'kezaliliane60@gmail.com';               // Your Gmail address
        $mail->Password   = 'ttjr qfxq dfvr cwlr';                  // Your Gmail app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;      // Enable TLS encryption, `ssl` also accepted
        $mail->Port       = 465;                                  // TCP port to connect to

        // Recipients
        $mail->setFrom('kezaliliane60@gmail.com', 'Germain');      // Your name or company name
        $mail->addAddress($to);                                   // Add a recipient

        // Content
        $mail->isHTML(true);                                      // Set email format to HTML
        $mail->Subject = 'Password Reset Request';
        $mail->Body    = "Use the following verification code to reset your password: <strong>$verificationCode</strong>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        return false;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-dark text-light d-flex flex-column min-vh-100">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card bg-secondary text-light">
                    <div class="card-body">
                        <h4 class="card-title text-center mb-4">Forgot Password</h4>
                        <form action="forgot_password.php" method="post">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Send Confirmation Code</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
