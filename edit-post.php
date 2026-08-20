<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("includes/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$post_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$post_id) {
    header("Location: index.php");
    exit();
}

$sql = "SELECT * FROM blogpost WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $post_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$post = mysqli_fetch_assoc($result);

if (!$post) {
    header("Location: index.php");
    exit();
}

if ($_SESSION['user_id'] != $post['user_id']) {
    include('includes/header.php');
    echo "<div class='card post-card'><div class='alert alert-danger'><strong>Unauthorized Action:</strong> You do not have permission to edit this post.</div></div>";
    include('includes/footer.php');
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title   = trim(filter_input(INPUT_POST, "title", FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
    $content = trim($_POST['content'] ?? '');

    if (empty($title) || empty($content)) {
        $error = "Title and content cannot be blank or contain only spaces.";
    } else {
        $update_sql = "UPDATE blogpost SET title = ?, content = ? WHERE id = ? AND user_id = ?";
        $update_stmt = mysqli_prepare($conn, $update_sql);
        mysqli_stmt_bind_param($update_stmt, "ssii", $title, $content, $post_id, $_SESSION['user_id']);
        
        if (mysqli_stmt_execute($update_stmt)) {
            header("Location: post.php?id=" . $post_id);
            exit();
        } else {
            $error = "Failed to update blog post.";
        }
    }
}

include('includes/header.php');
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easymde@2.18.0/dist/easymde.min.css">
<script src="https://cdn.jsdelivr.net/npm/easymde@2.18.0/dist/easymde.min.js"></script>

<div class="card form-card">
    <h2>Edit Blog Post</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error; ?></div>
    <?php endif; ?>

    <form action="edit-post.php?id=<?= $post_id; ?>" method="post">
        <div class="form-group">
            <label for="title">Blog Title</label>
            <input type="text" id="title" name="title" class="form-control" value="<?= htmlspecialchars($post['title']); ?>" required>
        </div>

        <div class="form-group">
            <label for="content">Content (Markdown Format)</label>
            <textarea id="markdown-editor" name="content" class="form-control"><?= htmlspecialchars($post['content']); ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Update Post</button>
        <a href="post.php?id=<?= $post_id; ?>" class="btn btn-outline btn-ml">Cancel</a>
    </form>
</div>

<script src="js/script.js"></script>

<?php include('includes/footer.php'); ?>