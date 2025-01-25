<?php
// Include the database connection
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $book_name = htmlspecialchars(trim($_POST["book_name"]));
    $category = htmlspecialchars(trim($_POST["category"]));
    $fees = htmlspecialchars(trim($_POST["fees"]));
    $author_name = htmlspecialchars(trim($_POST["author_name"]));

    // Handle image upload
    $imagePath = $imageDir . basename($_FILES["image"]["name"]);
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $imageDir = "bookimages/";
        if (!is_dir($imageDir)) {
            mkdir($imageDir, 0777, true);
        }
        $imagePath = $imageDir . basename($_FILES["image"]["name"]);
        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath)) {
            die("Failed to upload the image.");
        }
    }

    // Prepare the SQL statement
    $sql = "INSERT INTO books (book_name, category, fees, author_name, image) VALUES (?, ?, ?, ?, ?)";

    // Use a prepared statement to prevent SQL injection
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdss", $book_name, $category, $fees, $author_name, $imagePath);

    // Execute the query
    if ($stmt->execute()) {
        echo "<h1>Book Added Successfully</h1>";
        echo "<p>Book Name: $book_name</p>";
        echo "<p>Category: $category</p>";
        echo "<p>Fees: $fees</p>";
        echo "<p>Author Name: $author_name</p>";
        echo "<p><img src='$imagePath' alt='Book Image' style='max-width: 200px; max-height: 200px;'></p>";
        echo "<p><a href='index.php'>Go Back</a></p>";
    } else {
        echo "<h1>Error Adding Book</h1>";
        echo "<p>" . $stmt->error . "</p>";
        echo "<p><a href='index.php'>Go Back</a></p>";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
