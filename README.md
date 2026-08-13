# Standard DevBlog Application

A full-stack blog application built with PHP, MySQL, Vanilla CSS, and Parsedown.

## 🚀 Live Demo
Check out the live deployed application here:
👉 **[http://blogapp.lovestoblog.com](https://blogapp.lovestoblog.com/)**

## 🛠️ Features
- **User Authentication:** Registration, Login, and Session-based Auth with hashed passwords (`password_hash`).
- **Blog Management (CRUD):** Create, Read, Edit, and Delete posts.
- **Security Guards:** Strictly enforced ownership authorization on editing/deleting posts.
- **Markdown Support:** Integrated EasyMDE editor and Parsedown renderer for blog posts.
- **Responsive UI:** Clean custom Vanilla CSS layout.

## 🗄️ Database Setup
1. Create a MySQL database named `blog_db`.
2. Import the database schema (`user` and `blogPost` tables).
3. Create a `db.php` file based on your local or production database credentials:

```php
<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "blog_db";

$conn = mysqli_connect($host, $user, $password, $database);
?>