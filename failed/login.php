<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "elibrary";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM admin WHERE username = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        die("Statement preparation failed: " . $conn->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $admin = $result->fetch_assoc();
        
        // Debugging: Check fetched admin data
        echo "<pre>";
        print_r($admin);
        echo "</pre>";
        
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin'] = $admin['id'];

            // Debugging: Check session assignment
            echo "Session assigned successfully.";
            
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid password";
            
            // Debugging: Password mismatch
            echo "Entered password: " . htmlspecialchars($password) . "<br>";
            echo "Stored hash: " . htmlspecialchars($admin['password']) . "<br>";
            echo "Password verification failed.";
        }
    } else {
        $error = "Invalid username";

        // Debugging: Username not found
        echo "Username entered: " . htmlspecialchars($username) . "<br>";
        echo "Username not found in database.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="login-container">
        <h1>Admin Login</h1>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="login.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
