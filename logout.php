<?php
    session_start();

    if (isset($_POST["logout"]) || $_SERVER["REQUEST_METHOD"] == "POST") {
        session_unset();    
        session_destroy();  
        header("Location: login.php");
        exit();             
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
</head>
<body>
    <form action="logout.php" method="post">
        <button type="submit" name="logout">Logout</button>
    </form>
</body>
</html>