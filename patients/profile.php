<?php
include 'connection.php';
include 'navbar.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newName = $_POST['name'];
    $newEmail = $_POST['email'];
    $newAddress = $_POST['address'];
    $newTelNo = $_POST['telNo'];
    $newPassword = $_POST['password'];

    // Update patient information
    if (!empty($newPassword)) {
        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        $stmt = $conn->prepare("UPDATE patients SET names = ?, email = ?, address = ?, telNo = ?, password = ? WHERE names = ?");
        $stmt->bind_param("ssssss", $newName, $newEmail, $newAddress, $newTelNo, $hashedPassword, $username);
    } else {
        // If the password is not changed
        $stmt = $conn->prepare("UPDATE patients SET names = ?, email = ?, address = ?, telNo = ? WHERE names = ?");
        $stmt->bind_param("sssss", $newName, $newEmail, $newAddress, $newTelNo, $username);
    }

    if ($stmt->execute()) {
        echo "Profile updated successfully.";
        // Update session username if the name was changed
        $_SESSION['username'] = $newName;
    } else {
        echo "Profile update failed.";
    }
}

$stmt = $conn->prepare("SELECT names, email, address, telNo FROM patients WHERE names = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($name, $email, $address, $telNo);
$stmt->fetch();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-dark text-light">
    <div class="container mt-5">
        <h3>Edit Profile</h3>
        <form method="post" action="profile.php">
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $name; ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" id="address" name="address" value="<?php echo $address; ?>" required>
            </div>
            <div class="mb-3">
                <label for="telNo" class="form-label">Telephone Number</label>
                <input type="text" class="form-control" id="telNo" name="telNo" value="<?php echo $telNo; ?>" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password (leave blank to keep current)</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter new password">
            </div>
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>
</body>
</html>
