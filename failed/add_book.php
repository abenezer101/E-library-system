<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "elibrary";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $publisher = $_POST['publisher'];
    $year_published = $_POST['year_published'];

    $thumbnail = $_FILES['thumbnail']['name'];
    $thumbnail_tmp = $_FILES['thumbnail']['tmp_name'];
    $thumbnail_path = "thumbnails/" . $thumbnail;
    move_uploaded_file($thumbnail_tmp, $thumbnail_path);

    $pdf = $_FILES['pdf']['name'];
    $pdf_tmp = $_FILES['pdf']['tmp_name'];
    $pdf_path = "pdfs/" . $pdf;
    move_uploaded_file($pdf_tmp, $pdf_path);

    $sql = "INSERT INTO books (title, publisher, year_published, thumbnail, pdf_path)
            VALUES ('$title', '$publisher', '$year_published', '$thumbnail_path', '$pdf_path')";

    if ($conn->query($sql) === TRUE) {
        echo "New book added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Book</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Add a New Book</h1>
    <form action="add_book.php" method="post" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required><br>
        <label for="publisher">Publisher:</label>
        <input type="text" id="publisher" name="publisher" required><br>
        <label for="year_published">Year Published:</label>
        <input type="number" id="year_published" name="year_published" required><br>
        <label for="thumbnail">Thumbnail:</label>
        <input type="file" id="thumbnail" name="thumbnail" required><br>
        <label for="pdf">PDF:</label>
        <input type="file" id="pdf" name="pdf" required><br>
        <button type="submit">Add Book</button>
    </form>
</body>
</html>
