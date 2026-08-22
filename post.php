<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('includes/header.php');
include('includes/db.php');
require_once('Parsedown.php');

$post_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$post_id) {
    header("Location: index.php");
    exit();
}

$sql = "SELECT blogpost.*, user.username 
        FROM blogpost 
        JOIN user ON blogpost.user_id = user.id 
        WHERE blogpost.id = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $post_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$post = mysqli_fetch_assoc($result);
if (!$post) {
    echo "<div class='card post-card'>";
    echo "  <div class='alert alert-danger'>The requested blog post does not exist or has been removed.</div>";
    echo "  <a href='index.php' class='btn btn-outline'>&larr; Return to Home</a>";
    echo "</div>";
    include('includes/footer.php');
    exit();
}
$parsedown = new Parsedown();
$parsedown->setSafeMode(true);
$htmlContent = $parsedown->text($post['content']);
?>

<div class="card post-card">
    <h1><?= htmlspecialchars($post['title']); ?></h1>
    <p class="post-meta post-meta-divider">
        Written by <strong><?= htmlspecialchars($post['username']); ?></strong>
        on <?= date('F j, Y', strtotime($post['created_at'])); ?>
    </p>

    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $post['user_id']): ?>
        <div class="post-actions">
            <a href="edit-post.php?id=<?= $post['id']; ?>" class="btn btn-outline">Edit Post</a>
            <form action="delete-post.php" method="post" onsubmit="return confirm('Are you sure you want to delete this post?');">
                <input type="hidden" name="id" value="<?= $post['id']; ?>">
                <button type="submit" class="btn btn-danger">Delete Post</button>
            </form>
        </div>
    <?php endif; ?>

    <div class="blog-content">
        <?= $htmlContent; ?>
    </div>

    <div class="post-footer">
        <a href="index.php" class="btn btn-outline">&larr; Back to Home</a>
    </div>
</div>

<?php include('includes/footer.php'); ?>