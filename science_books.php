<?php
// Turn on error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include 'db.php';

// Fetch books with the category "story"
$sql = "SELECT * FROM books WHERE category = ?";
$stmt = $conn->prepare($sql);
$category = "science";
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Story Books</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .book-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .book-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
            padding: 15px;
            text-align: center;
            background: #f9f9f9;
        }

        .book-card img {
            max-width: 100%;
            max-height: 200px;
            border-radius: 8px;
        }

        .book-card h3 {
            font-size: 18px;
            margin: 10px 0;
            color: #333;
        }

        .book-card p {
            margin: 5px 0;
            color: #555;
        }
    </style>
</head>

<body>
    <h1>Science Books</h1>
    <div class="book-container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Fallback to a default image if the image field is null or empty
                $imagePath = !empty($row['image']) ? htmlspecialchars($row['image']) : 'default_book_image.jpg';

                echo "<div class='book-card'>";
                echo "<img src='" . $imagePath . "' alt='Book Image'>";
                echo "<h3>" . htmlspecialchars($row['book_name']) . "</h3>";
                echo "<p><strong>Author:</strong> " . htmlspecialchars($row['author_name']) . "</p>";
                echo "<p><strong>Fees:</strong> $" . htmlspecialchars($row['fees']) . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p>No story books found.</p>";
        }

        // Close statement and connection
        $stmt->close();
        $conn->close();
        ?>
    </div>
</body>

</html>
