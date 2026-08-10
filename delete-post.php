<?php
session_start();
include("db.php");

// 1. Must be logged in and request method must be POST
if (!isset($_SESSION['user_id']) || $_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit();
}

$post_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if ($post_id) {
    // 2. Delete only if post belongs to the logged-in user
    $sql = "DELETE FROM blogPost WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $post_id, $_SESSION['user_id']);
    mysqli_stmt_execute($stmt);
}

header("Location: index.php");
exit();
?>