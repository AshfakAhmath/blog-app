<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlogSpace</title>
    <link rel="icon" type="image/png" href="favicon.png">
    <link rel="stylesheet" href="css/style.css?v=<?= file_exists(__DIR__ . '/../css/style.css') ? filemtime(__DIR__ . '/../css/style.css') : time(); ?>">
</head>
<body>

<nav class="navbar">
  <div class="nav-container">
    <a class="brand-logo" href="index.php">BlogSpace</a>
    <ul class="nav-links">
      <li><a href="index.php">Home</a></li>
      
      <?php if (isset($_SESSION['user_id'])): ?>
          <li><a href="create-post.php">Create Post</a></li>
          <li><span class="user-welcome">Welcome, <?= htmlspecialchars($_SESSION['username']); ?></span></li>
          <li><a class="btn btn-danger" href="logout.php">Logout</a></li>
      <?php else: ?>
          <li><a href="login.php">Login</a></li>
          <li><a class="btn btn-primary" href="register.php">Register</a></li>
      <?php endif; ?>
    </ul>
  </div>
</nav>

<div class="main-container">