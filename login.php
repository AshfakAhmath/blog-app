<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("db.php");

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim(filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        $sql = "SELECT * FROM user WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION["user_id"]  = $user['id'];
            $_SESSION["username"] = $user['username'];
            if (isset($user['role'])) {
                $_SESSION["role"] = $user['role'];
            }

            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid username or password.";
        }
    }
}

include('header.php');
?>

<div class="card auth-card" style="max-width: 420px; margin: 40px auto;">
    <h2 style="text-align: center; margin-bottom: 20px;">Login to DevBlog</h2>
    
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error; ?></div>
    <?php endif; ?>

    <form action="login.php" method="post">
        <div class="form-group" style="margin-bottom: 15px;">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" class="form-control" placeholder="Enter your username" required>
        </div>
        <div class="form-group" style="margin-bottom: 20px;">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
        </div>
        <button type="submit" class="btn btn-primary" style="width: 100%;">Login</button>

        <p style="margin-top: 20px; text-align: center; font-size: 0.9rem; color: #666;">
            Don't have an account? <a href="register.php" style="color: #3498db; text-decoration: none; font-weight: bold;">Register here</a>
        </p>
    </form>
</div>

<?php include('footer.php'); ?>