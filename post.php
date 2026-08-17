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

// 1. Updated Error Card handling
if (!$post) {
    echo "<div class='card' style='margin-top: 20px;'>";
    echo "  <div class='alert alert-danger'>The requested blog post does not exist or has been removed.</div>";
    echo "  <a href='index.php' class='btn btn-outline'>&larr; Return to Home</a>";
    echo "</div>";
    include('footer.php');
    exit();
}

// 2. SafeMode enabled for Parsedown
$parsedown = new Parsedown();
$parsedown->setSafeMode(true);
$htmlContent = $parsedown->text($post['content']);
?>

<div class="card" style="margin-top: 20px;">
    <h1 style="margin-bottom: 10px;"><?= htmlspecialchars($post['title']); ?></h1>
    <p style="color: #7f8c8d; font-size: 0.9rem; margin-bottom: 25px; border-bottom: 1px solid #eee; padding-bottom: 10px;">
        Written by <strong><?= htmlspecialchars($post['username']); ?></strong>
        on <?= date('F j, Y', strtotime($post['created_at'])); ?>
    </p>

    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $post['user_id']): ?>
        <div style="margin-bottom: 20px;">
            <a href="edit-post.php?id=<?= $post['id']; ?>" class="btn btn-outline" style="margin-right: 10px;">Edit Post</a>
            <form action="delete-post.php" method="post" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this post?');">
                <input type="hidden" name="id" value="<?= $post['id']; ?>">
                <button type="submit" class="btn btn-danger">Delete Post</button>
            </form>
        </div>
    <?php endif; ?>

    <div class="blog-content" style="line-height: 1.7;">
        <?= $htmlContent; ?>
    </div>

    <div style="margin-top: 30px; border-top: 1px solid #eee; padding-top: 15px;">
        <a href="index.php" class="btn btn-outline">&larr; Back to Home</a>
    </div>
</div>

<?php include('includes/footer.php'); ?>