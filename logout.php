<?php
session_start();

// 1. Clear all session variables
session_unset();

// 2. Destroy the session on the server
session_destroy();

// 3. Redirect immediately to login.php
header("Location: login.php");
exit();
?>