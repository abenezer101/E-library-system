<?php
session_start();
// if (!isset($_SESSION['admin'])) {
//     header("Location: login.php");
//     exit();
// }

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "elibrary";

$conn = new mysqli($servername, $username, $password, $dbname);

$sql = "SELECT COUNT(*) as user_count FROM users";
$result = $conn->query($sql);
$user_count = $result->fetch_assoc()['user_count'];

$sql = "SELECT COUNT(*) as book_count FROM books";
$result = $conn->query($sql);
$book_count = $result->fetch_assoc()['book_count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="dashboard">
        <h1>Admin Dashboard</h1>
        <div class="card">
            <h2>Users</h2>
            <p><?php echo $user_count; ?> registered users</p>
            <a href="list_users.php">View Users</a>
        </div>
        <div class="card">
            <h2>Books</h2>
            <p><?php echo $book_count; ?> books in library</p>
            <a href="list_books.php">View Books</a>
        </div>
        <div class="card">
            <a href="add_book.php">Add Book</a>
        </div>
        <div class="card">
            <a href="logout.php">Logout</a>
        </div>
    </div>
</body>
</html>
