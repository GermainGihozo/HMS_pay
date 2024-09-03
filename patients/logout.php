<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Logout</title>
</head>
<body>
<head>
    <meta charset="UTF-8">
    <title>Logout</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-dark text-light">
    <div class="container mt-5">
        <h3 class="text-center">You have been logged out.</h3>
        <p class="text-center"><a href="login.php" class="btn btn-secondary">Login Again</a></p>
    </div>
  <script>
    // Redirect to login page
    window.location.href = 'login.php';
  </script>
</body>
</html>
<?php
session_start();
session_destroy();
header("Location: login.php");
exit();
?>
