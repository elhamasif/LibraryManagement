<?php
// Include database connection
include 'db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Delete book from database
    $sql = "DELETE FROM books WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<p>Book removed successfully!</p>";
    } else {
        echo "<p>Error removing book: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $conn->close();
}

echo "<a href='index.php'>Go Back</a>";
?>
