<?php
// search.php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $query = $_POST['query'];
    $category = $_POST['category'];

    $conn = new mysqli('localhost', 'root', '', 'library');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM books WHERE title LIKE '%$query%' OR author LIKE '%$query%'";
    if ($category != 'All') {
        $sql .= " AND genre = '$category'";
    }

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<div class='bookdisplay'>";
        while ($row = $result->fetch_assoc()) {
            echo "<div class='book' data-title='" . $row['title'] . "' data-filter='" . $row['genre'] . "'>
                    <a href='#'>
                        <img class='cover' alt='Book cover' src='" . $row['cover'] . "' />
                        <div class='info'>
                            <h5>" . $row['title'] . "</h5>
                        </div>
                    </a>
                  </div>";
        }
        echo "</div>";
    } else {
        echo "No results found.";
    }

    $conn->close();
} else {
    echo "Bad request, missing form.";
}
?>
