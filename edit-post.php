<?php
// 1. Session start guard
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("db.php");

// 2. Auth Guard: Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// 3. Validate post ID parameter from URL
$post_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$post_id) {
    header("Location: index.php");
    exit();
}

// 4. Fetch post record
$sql = "SELECT * FROM blogPost WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $post_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$post = mysqli_fetch_assoc($result);

if (!$post) {
    header("Location: index.php");
    exit();
}

// 5. Ownership Authorization Guard (using != for type compatibility)
if ($_SESSION['user_id'] != $post['user_id']) {
    include('header.php');
    echo "<div class='card' style='margin-top: 20px;'><div class='alert alert-danger'><strong>Unauthorized Action:</strong> You do not have permission to edit this post.</div></div>";
    include('footer.php');
    exit();
}

$error = "";

// 6. Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title   = filter_input(INPUT_POST, "title", FILTER_SANITIZE_SPECIAL_CHARS);
    $content = trim($_POST['content']);

    if (empty($title) || empty($content)) {
        $error = "Please fill in both the title and content.";
    } else {
        $update_sql = "UPDATE blogPost SET title = ?, content = ? WHERE id = ? AND user_id = ?";
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

include('header.php');
?>

<!-- Include EasyMDE Markdown Editor CSS & JS CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/easy-markdown-editor/2.16.1/easymde.min.css">
<script src="https://cdn.jsdelivr.net/easy-markdown-editor/2.16.1/easymde.min.js"></script>

<div class="card" style="max-width: 800px; margin: 20px auto;">
    <h2 style="margin-bottom: 20px;">Edit Blog Post</h2>

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
        <a href="post.php?id=<?= $post_id; ?>" class="btn btn-outline" style="margin-left: 10px;">Cancel</a>
    </form>
</div>

<script>
    const easyMDE = new EasyMDE({ 
        element: document.getElementById('markdown-editor'),
        placeholder: "Write your post using Markdown formatting...",
        minHeight: "250px",
        autosave: { enabled: false }
    });
</script>

<?php include('footer.php'); ?>