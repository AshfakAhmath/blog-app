<?php
include('header.php');
?>

<div class="hero">
    <h1>Welcome to DevBlog</h1>
    <p>A simple, custom blog platform to share thoughts and developer logs.</p>
    
    <?php if (isset($_SESSION['user_id'])): ?>
        <a class="btn btn-primary" href="create-post.php">Write a New Blog Post</a>
    <?php else: ?>
        <a class="btn btn-primary" href="login.php">Login to Post</a>
        <a class="btn btn-outline" href="register.php">Register Account</a>
    <?php endif; ?>
</div>

<h2>Recent Blog Posts</h2>
<div class="alert alert-info" style="margin-top: 15px;">
    No blog posts found yet. Be the first to create one!
</div>

<?php
include('footer.php');
?>