<?php
include 'connection.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $verificationCode = $_POST['verification_code'];
    $newPassword = $_POST['new_password'];

    // Find the record in password_resets
    $stmt = $conn->prepare("SELECT user_id FROM password_resets WHERE verification_code = ? AND expiry > NOW()");
    $stmt->bind_param("s", $verificationCode);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($userId);
        $stmt->fetch();

        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        // Update the user's password in the users table
        $updateStmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $updateStmt->bind_param("si", $hashedPassword, $userId);
        if ($updateStmt->execute()) {
            // Optionally, delete the verification code from the password_resets table
            $deleteStmt = $conn->prepare("DELETE FROM password_resets WHERE user_id = ?");
            $deleteStmt->bind_param("i", $userId);
            $deleteStmt->execute();

            echo "Password updated successfully.";
        } else {
            echo "Failed to update password.";
        }

        $updateStmt->close();
    } else {
        echo "Invalid or expired verification code.";
    }

    $stmt->close();
    $conn->close();
}
?>
