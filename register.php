<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("includes/db.php");

// Redirect already logged-in users to the home feed
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim(filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
    $email    = trim(filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL) ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username)) {
        $error = "Username is empty.";
    } elseif (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (empty($password)) {
        $error = "Password is empty.";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql  = "INSERT INTO user (username, email, password) VALUES (?, ?, ?)";

        try {
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hash);
            mysqli_stmt_execute($stmt);

            $success = "You are now registered! Redirecting to login...";
            header("refresh:2;url=login.php");
        } catch (mysqli_sql_exception $e) {
            $error = "Username or Email is already taken.";
        }
    }
}

include('includes/header.php');
?>

<div class="card auth-card" style="max-width: 420px; margin: 40px auto;">
    <h2 style="text-align: center; margin-bottom: 20px;">Register for BlogSpace</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error; ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="alert alert-info"><?= $success; ?></div>
    <?php endif; ?>

    <form action="register.php" method="post">
        <div class="form-group" style="margin-bottom: 15px;">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" class="form-control" placeholder="Enter a username" required>
        </div>

        <div class="form-group" style="margin-bottom: 15px;">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
        </div>

        <div class="form-group" style="margin-bottom: 20px;">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Create a strong password" required>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%;">Register</button>

        <p style="margin-top: 20px; text-align: center; font-size: 0.9rem; color: #666;">
            Already have an account? <a href="login.php" style="color: #3498db; text-decoration: none; font-weight: bold;">Login here</a>
        </p>
    </form>
</div>

<?php include('includes/footer.php'); ?>