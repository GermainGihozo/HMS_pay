<?php
session_start(); 
include 'navbar.php';
include 'connection.php';

// Functionality for updating admin details and project settings goes here...

// Change Background Image
// Assuming you have connected to your database in connection.php

if (isset($_POST['change_bg'])) {
    if (isset($_FILES['background_image']) && $_FILES['background_image']['error'] === UPLOAD_ERR_OK) {
        $bgImage = $_FILES['background_image']['name'];
        $target = "images/" . basename($bgImage);

        if (move_uploaded_file($_FILES['background_image']['tmp_name'], $target)) {
            // Update the background image in the database
            $stmt = $conn->prepare("UPDATE project_settings SET background_image = ? WHERE id = 1");
            $stmt->bind_param("s", $bgImage);
            if ($stmt->execute()) {
                echo "<div class='alert alert-success'>Background image updated!</div>";
            } else {
                echo "<div class='alert alert-danger'>Failed to update background image.</div>";
            }
            $stmt->close();
        } else {
            echo "<div class='alert alert-danger'>Failed to upload image.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>No image uploaded or upload error.</div>";
    }
}


// Update Admin Name
if (isset($_POST['update_name'])) {
    $newName = trim($_POST['admin_name']);
    $userId = $_SESSION['userId'];

    // Check for empty input
    if (!empty($newName)) {
        $stmt = $conn->prepare("UPDATE users SET names = ? WHERE id = ?");
        $stmt->bind_param("si", $newName, $userId);
        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Name updated successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>Failed to update name.</div>";
        }
        $stmt->close(); // Close statement
    } else {
        echo "<div class='alert alert-danger'>Name cannot be empty.</div>";
    }
}

// Update Password
if (isset($_POST['change_password'])) {
    $currentPassword = $_POST['current_password'];
    $newPassword = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
    $userId = $_SESSION['userId'];

    // Check current password
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row && password_verify($currentPassword, $row['password'])) {
        // Update password
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $newPassword, $userId);
        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Password updated successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>Failed to update password.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Incorrect current password.</div>";
    }
    $stmt->close(); // Close statement
}

// Update Project Title
if (isset($_POST['update_title'])) {
    $newTitle = trim($_POST['project_title']);

    if (!empty($newTitle)) {
        // Store the title in a settings table or a config file
        $stmt = $conn->prepare("UPDATE project_settings SET title = ?");
        $stmt->bind_param("s", $newTitle);
        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Project title updated!</div>";
        } else {
            echo "<div class='alert alert-danger'>Failed to update project title.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Project title cannot be empty.</div>";
    }
    $stmt->close(); // Close statement
}

$conn->close(); // Close database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <title>Settings - Admin</title>
</head>
<body>
    <div class="container mt-5">
        <h2>Settings</h2>
        
        <!-- Change Background Image -->
        <div class="mb-4">
            <h4>Change Background Image</h4>
            <form method="post" enctype="multipart/form-data">
                <input type="file" name="background_image" class="form-control" accept="image/*" required>
                <button type="submit" class="btn btn-primary mt-2" name="change_bg">Change Background</button>
            </form>
        </div>

        <!-- Update Admin Name -->
        <div class="mb-4">
            <h4>Update Admin Name</h4>
            <form method="post">
                <input type="text" name="admin_name" class="form-control" placeholder="Enter new name" required>
                <button type="submit" class="btn btn-primary mt-2" name="update_name">Update Name</button>
            </form>
        </div>

        <!-- Update Password -->
        <div class="mb-4">
            <h4>Change Password</h4>
            <form method="post">
                <input type="password" name="current_password" class="form-control" placeholder="Current password" required>
                <input type="password" name="new_password" class="form-control mt-2" placeholder="New password" required>
                <button type="submit" class="btn btn-primary mt-2" name="change_password">Change Password</button>
            </form>
        </div>

        <!-- Update Project Title -->
        <div class="mb-4">
            <h4>Update Project Title</h4>
            <form method="post">
                <input type="text" name="project_title" class="form-control" placeholder="Enter new project title" required>
                <button type="submit" class="btn btn-primary mt-2" name="update_title">Update Title</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
