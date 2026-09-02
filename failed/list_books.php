<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "elibrary";

$conn = new mysqli($servername, $username, $password, $dbname);

$sql = "SELECT * FROM books";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Books</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Book List</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Publisher</th>
            <th>Year Published</th>
            <th>Thumbnail</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['title']; ?></td>
            <td><?php echo $row['publisher']; ?></td>
            <td><?php echo $row['year_published']; ?></td>
            <td><img src="<?php echo $row['thumbnail']; ?>" alt="Thumbnail" width="50"></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
