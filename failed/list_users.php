<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "elibrary";

$conn = new mysqli($servername, $username, $password, $dbname);

$sql = "SELECT * FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Users</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>User List</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Phone Number</th>
            <th>Name</th>
            <th>Profile Picture</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['username']; ?></td>
            <td><?php echo $row['phone_number']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><img src="<?php echo $row['profile_picture']; ?>" alt="Profile Picture" width="50"></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
