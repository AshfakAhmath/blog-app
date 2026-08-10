<?php
session_start();
include("db.php");

// Authorization Check: Only logged-in users can access this page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title   = filter_input(INPUT_POST, "title", FILTER_SANITIZE_SPECIAL_CHARS);
    // Note: Do not sanitize content with SPECIAL_CHARS if you want to allow raw Markdown text!
    $content = trim($_POST['content']);

    if (empty($title) || empty($content)) {
        $error = "Please fill in both the title and post content.";
    } else {
        $user_id = $_SESSION['user_id'];

        $sql = "INSERT INTO blogPost (user_id, title, content) VALUES (?, ?, ?)";
        
        try {
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "iss", $user_id, $title, $content);
            mysqli_stmt_execute($stmt);

            // Redirect to home page after publishing
            header("Location: index.php");
            exit();
        } catch (mysqli_sql_exception $e) {
            $error = "Failed to publish blog post: " . $e->getMessage();
        }
    }
}

include('header.php');
?>

<!-- Include EasyMDE Markdown Editor CSS & JS CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/easy-markdown-editor/2.16.1/easymde.min.css">
<script src="https://cdn.jsdelivr.net/easy-markdown-editor/2.16.1/easymde.min.js"></script>

<div class="card" style="max-width: 800px; margin: 20px auto;">
    <h2 style="margin-bottom: 20px;">Create a New Blog Post</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error; ?></div>
    <?php endif; ?>

    <form action="create-post.php" method="post">
        <div class="form-group">
            <label for="title">Blog Title</label>
            <input type="text" id="title" name="title" class="form-control" placeholder="Enter post title..." required>
        </div>

        <div class="form-group">
            <label for="content">Content (Markdown Format)</label>
            <textarea id="markdown-editor" name="content" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Publish Post</button>
        <a href="index.php" class="btn btn-outline" style="margin-left: 10px;">Cancel</a>
    </form>
</div>

<script>
    // Initialize EasyMDE Markdown Editor on the textarea
    const easyMDE = new EasyMDE({ 
        element: document.getElementById('markdown-editor'),
        placeholder: "Write your post using Markdown formatting...",
        autosave: { enabled: false }
    });
</script>

<?php include('footer.php'); ?>