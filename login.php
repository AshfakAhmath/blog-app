<?php
    session_start();
    include("db.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
</head>
<body>
    <form action="login.php" method="post">
        <label for="username">Username: </label>
        <input type="text" name="username"><br>
        <label for="password">Password: </label>
        <input type="password" name="password"><br>
        <button type="submit">Login</button>
    </form>
</body>
</html>

<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

        if (empty($username)) {
            echo "Username is empty";
        } elseif (empty($password)) {
            echo "Password is empty";
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
                $_SESSION["role"]     = $user['role'];

                header("Location: index.php");
                exit();
            } else {
                echo "Invalid username or password";
            }
        }
    }
?>