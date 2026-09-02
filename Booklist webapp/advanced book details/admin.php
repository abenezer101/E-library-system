<?php
// admin.php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $genre = $_POST['genre'];
    $cover = $_POST['cover'];

    $conn = new mysqli('localhost', 'root', '', 'library');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO books (title, author, genre, cover) VALUES ('$title', '$author', '$genre', '$cover')";

    if ($conn->query($sql) === TRUE) {
        echo "New book added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Add Book</title>
</head>
<body>
    <h1>Add a New Book</h1>
    <form action="admin.php" method="POST">
        <label for="title">Title:</label><br>
        <input type="text" id="title" name="title"><br>
        <label for="author">Author:</label><br>
        <input type="text" id="author" name="author"><br>
        <label for="genre">Genre:</label><br>
        <input type="text" id="genre" name="genre"><br>
        <label for="cover">Cover URL:</label><br>
        <input type="text" id="cover" name="cover"><br><br>
        <input type="submit" value="Add Book">
    </form>
</body>
</html>
