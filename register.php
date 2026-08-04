<?php
include("db.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
</head>

<body>
    <form action="register.php" method="post">
        <label for="username">Username: </label>
        <input type="text" name="username"><br>
        <label for="email">Email: </label>
        <input type="email" name="email"><br>
        <label for="password">Password: </label>
        <input type="password" name="password"><br>
        <input type="submit" value="SUBMIT">
    </form>
</body>

</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

    if (empty($username)) {
        echo "Username is empty";
    } elseif (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Please enter a valid email address";
    } elseif (empty($password)) {
        echo "Password is empty";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO user (username, email, password) VALUES (?, ?, ?)";
            
            try {
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hash);
                mysqli_stmt_execute($stmt);

                echo "You are now registered!";
            } catch (mysqli_sql_exception $e) {
                echo "Username or Email is already taken.";
            }
    }
}
?>