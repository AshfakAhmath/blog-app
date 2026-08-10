<?php
session_start();
include("db.php");

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
    $email    = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

    if (empty($username)) {
        $error = "Username is empty";
    } elseif (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address";
    } elseif (empty($password)) {
        $error = "Password is empty";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql  = "INSERT INTO user (username, email, password) VALUES (?, ?, ?)";

        try {
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hash);
            mysqli_stmt_execute($stmt);

            $success = "You are now registered! Redirecting to login...";
            
            // Redirect to login page after 2 seconds
            header("refresh:2;url=login.php");
        } catch (mysqli_sql_exception $e) {
            $error = "Username or Email is already taken.";
        }
    }
}

include('header.php');
?>

<div class="card auth-card">
    <h2 style="text-align: center; margin-bottom: 20px;">Register</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error; ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="alert alert-info"><?= $success; ?></div>
    <?php endif; ?>

    <form action="register.php" method="post">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%;">Register</button>
    </form>
</div>

<?php include('footer.php'); ?>