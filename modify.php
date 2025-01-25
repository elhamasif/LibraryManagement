<?php
        // Include database connection
        include 'db.php';

        if (!empty($_GET['search_name'])) {
            $searchName = '%' . htmlspecialchars($_GET['search_name']) . '%';

            // Query to search books by name
            $sql = "SELECT * FROM books WHERE book_name LIKE ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $searchName);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='book-item' style='display: inline-block; width: 200px; margin-right: 10px; border: 1px solid #ddd; padding: 10px; text-align: center;'>";
                    echo "<h3 style='font-size: 16px;'>" . htmlspecialchars($row['book_name']) . "</h3>";
                    echo "<p style='font-size: 14px;'><strong>Author:</strong> " . htmlspecialchars($row['author_name']) . "</p>";
                    echo "<p style='font-size: 14px;'><strong>Category:</strong> " . htmlspecialchars($row['category']) . "</p>";
                    echo "<p style='font-size: 14px;'><strong>Fees:</strong> $" . htmlspecialchars($row['fees']) . "</p>";
                    if (!empty($row['image'])) {
                        echo "<img src='" . htmlspecialchars($row['image']) . "' alt='Book Image' style='width: 100%; height: auto;'>";
                    }
                    echo "<button style='margin-top: 10px;' onclick=\"window.location.href='modifyprocess.php?id=" . $row['id'] . "'\">Modify</button>";
                    echo "<button style='margin-top: 10px; margin-left: 5px;' onclick=\"window.location.href='removeprocess.php?id=" . $row['id'] . "'\">Remove</button>";
                    echo "</div>";
                }
            } else {
                echo "<p>No books found matching your search.</p>";
            }

            $stmt->close();
        }

        $conn->close();
        ?>