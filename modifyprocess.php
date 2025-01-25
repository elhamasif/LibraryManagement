<?php
// Turn on error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $book_name = htmlspecialchars(trim($_POST['book_name']));
    $author_name = htmlspecialchars(trim($_POST['author_name']));
    $category = htmlspecialchars(trim($_POST['category']));
    $fees = htmlspecialchars(trim($_POST['fees']));

    // Update book details
    $sql = "UPDATE books SET book_name = ?, author_name = ?, category = ?, fees = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssdi", $book_name, $author_name, $category, $fees, $id);

    if ($stmt->execute()) {
        echo "<p>Book updated successfully!</p>";
        echo "<a href='index.php'>Go Back</a>";
    } else {
        echo "<p>Error updating book: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $conn->close();
    exit;
}

// Fetch book details for modification
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM books WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();

    if ($book) {
?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Modify Book</title>
            <link rel="stylesheet" href="modifyprocess.css">
        </head>

        <body>

            <form method="POST" action="">
                <h1>Modify Book Details</h1>
                <input type="hidden" name="id" value="<?php echo $book['id']; ?>">
                <label for="book_name">Book Name:</label><br>
                <input type="text" id="book_name" name="book_name" value="<?php echo htmlspecialchars($book['book_name']); ?>" required><br><br>

                <label for="author_name">Author Name:</label><br>
                <input type="text" id="author_name" name="author_name" value="<?php echo htmlspecialchars($book['author_name']); ?>" required><br><br>

                <label for="category">Category:</label><br>
                <input type="text" id="category" name="category" value="<?php echo htmlspecialchars($book['category']); ?>" required><br><br>

                <label for="fees">Fees:</label><br>
                <input type="number" id="fees" name="fees" value="<?php echo htmlspecialchars($book['fees']); ?>" step="0.01" required><br><br>

                <button type="submit">Save Changes</button>
            </form>
        </body>

        </html>
<?php
    } else {
        echo "<p>Book not found.</p>";
        echo "<a href='index.php'>Go Back</a>";
    }

    $stmt->close();
    $conn->close();
    exit;
}
?>