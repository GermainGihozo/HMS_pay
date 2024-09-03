<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require_once "connection.php";

$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$pageTitle = "Edit User"; // Define the page title

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $names = htmlspecialchars($_POST['names']);
    $role = htmlspecialchars($_POST['role']);

    $stmt = $conn->prepare("UPDATE users SET username=?, names=?, role=? WHERE id=?");
    $stmt->bind_param("sssi", $username, $names, $role, $user_id);

    if ($stmt->execute()) {
        echo "<p class='text-success'>User updated successfully!</p>";
    } else {
        echo "<p class='text-danger'>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $conn->close();

    header("Location: users.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <title><?php echo $pageTitle; ?></title>
</head>
<body>
    <div class="container">
        <h2>Edit User</h2>
        <form method="POST" action="edit_user.php?id=<?php echo $user_id; ?>">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label for="names" class="form-label">names</label>
                <input type="names" class="form-control" id="names" name="names" value="<?php echo htmlspecialchars($user['names'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-select" id="role" name="role" required>
                    <option value="admin" <?php if ($user['role'] === 'admin') echo 'selected'; ?>>Admin</option>
                    <option value="staff" <?php if ($user['role'] === 'staff') echo 'selected'; ?>>Staff</option>
                    <option value="nurse" <?php if ($user['role'] === 'nurse') echo 'selected'; ?>>Nurse</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update User</button>
        </form>
    </div>
</body>
</html>
