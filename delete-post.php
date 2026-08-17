<?php
session_start();
include("includes/db.php");

if (!isset($_SESSION['user_id']) || $_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit();
}

$post_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if ($post_id) {
    $sql = "DELETE FROM blogpost WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $post_id, $_SESSION['user_id']);
    mysqli_stmt_execute($stmt);
}

header("Location: index.php");
exit();
?>