<?php
session_start(); // Start the session

// Destroy the session to log out the user
session_unset();
session_destroy();

header("Location: login.php");
exit();
?>
