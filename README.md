# Standard BlogSpace Application

A full-stack blog application built with PHP, MySQL, Vanilla CSS, and Parsedown.

## 🚀 Live Demo
Check out the live deployed application here:
👉 **[https://ashfakahmath.alwaysdata.net](https://ashfakahmath.alwaysdata.net)**

## 🛠️ Features
- **User Authentication:** Registration, Login, and Session-based Auth with hashed passwords (`password_hash`).
- **Blog Management (CRUD):** Create, Read, Edit, and Delete posts.
- **Security Guards:** Strictly enforced ownership authorization on editing/deleting posts.
- **Markdown Support:** Integrated EasyMDE editor and Parsedown renderer for blog posts.
- **Responsive UI:** Clean custom Vanilla CSS layout.

## 📁 Folder Structure
```
blog-app/
├── css/
│   └── style.css           # All stylesheets
├── js/
│   └── script.js           # JavaScript (EasyMDE editor init)
├── includes/
│   ├── db.php              # Database connection (gitignored)
│   ├── header.php          # Reusable page header & navigation
│   └── footer.php          # Reusable page footer
├── sql/
│   └── blog_db.sql         # Database schema
├── index.php               # Home page (blog listing)
├── post.php                # Single blog post view
├── create-post.php         # Create new post form
├── edit-post.php           # Edit existing post form
├── delete-post.php         # Delete post handler
├── login.php               # User login page
├── register.php            # User registration page
├── logout.php              # Logout handler
├── Parsedown.php           # Markdown parsing library
├── .htaccess               # Apache URL rewriting / HTTPS redirect
└── README.md
```

## 🗄️ Database Setup
1. Create a MySQL database named `blog_db`.
2. Import the schema from `sql/blog_db.sql` (creates `user` and `blogpost` tables).
3. Create `includes/db.php` with your database credentials:

```php
<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "blog_db";

$conn = mysqli_connect($host, $user, $password, $database);
?>
```