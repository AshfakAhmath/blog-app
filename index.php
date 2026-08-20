<?php
include('includes/header.php');
include('includes/db.php');
require_once('Parsedown.php');

$parsedown = new Parsedown();
$parsedown->setSafeMode(true);

$sql = "SELECT blogpost.*, user.username 
        FROM blogpost 
        JOIN user ON blogpost.user_id = user.id 
        ORDER BY blogpost.created_at DESC";

$result = mysqli_query($conn, $sql);
?>

<div class="hero">
    <h1>Welcome to BlogSpace</h1>
    <p>A simple, custom blog platform to share thoughts and developer logs.</p>

    <div class="hero-actions">
        <?php if (isset($_SESSION['user_id'])): ?>
            <a class="btn btn-primary" href="create-post.php">Write a New Blog Post</a>
        <?php else: ?>
            <a class="btn btn-primary" href="login.php">Login to Post</a>
            <a class="btn btn-outline" href="register.php">Register Account</a>
        <?php endif; ?>
    </div>
</div>

<h2>Recent Blog Posts</h2>

<div class="post-list">
    <?php if ($result && mysqli_num_rows($result) > 0): ?>
        <?php while ($post = mysqli_fetch_assoc($result)): ?>
            <div class="card">
                <h3>
                    <a href="post.php?id=<?= $post['id']; ?>" class="post-title-link">
                        <?= htmlspecialchars($post['title']); ?>
                    </a>
                </h3>
                <p class="post-meta">
                    By <strong><?= htmlspecialchars($post['username']); ?></strong>
                    on <?= date('F j, Y', strtotime($post['created_at'])); ?>
                </p>
                <p>
                    <?php
                        $plainText = strip_tags($parsedown->text($post['content']));
                        echo htmlspecialchars(substr($plainText, 0, 150)) . '...';
                    ?>
                </p>
                <a href="post.php?id=<?= $post['id']; ?>" class="btn btn-outline">Read More</a>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="alert alert-info">No blog posts found yet. Be the first to create one!</div>
    <?php endif; ?>
</div>

<?php include('includes/footer.php'); ?>