<?php
                // Include database connection
                include 'db.php';

                // Initialize SQL query
                $sql = "SELECT * FROM books WHERE 1=1";

                // Filter conditions
                $filters = [];
                if (!empty($_GET['book_name'])) {
                    $sql .= " AND book_name LIKE ?";
                    $filters[] = '%' . $_GET['book_name'] . '%';
                }
                if (!empty($_GET['author_name'])) {
                    $sql .= " AND author_name LIKE ?";
                    $filters[] = '%' . $_GET['author_name'] . '%';
                }
                if (!empty($_GET['category'])) {
                    $sql .= " AND category = ?";
                    $filters[] = $_GET['category'];
                }
                if (!empty($_GET['min_fees'])) {
                    $sql .= " AND fees >= ?";
                    $filters[] = $_GET['min_fees'];
                }
                if (!empty($_GET['max_fees'])) {
                    $sql .= " AND fees <= ?";
                    $filters[] = $_GET['max_fees'];
                }

                // Prepare and execute query
                $stmt = $conn->prepare($sql);
                if ($filters) {
                    $stmt->bind_param(str_repeat('s', count($filters)), ...$filters);
                }
                $stmt->execute();
                $result = $stmt->get_result();

                // Display results
                echo '<div>';
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div style='border: 1px solid #ddd; padding: 10px; margin-bottom: 10px;'>";
                        echo "<h3>" . htmlspecialchars($row['book_name']) . "</h3>";
                        echo "<p><strong>Author:</strong> " . htmlspecialchars($row['author_name']) . "</p>";
                        echo "<p><strong>Category:</strong> " . htmlspecialchars($row['category']) . "</p>";
                        echo "<p><strong>Fees:</strong> $" . htmlspecialchars($row['fees']) . "</p>";
                        if (!empty($row['image'])) {
                            echo "<img src='" . htmlspecialchars($row['image']) . "' alt='Book Image' style='max-width: 150px;'>";
                        }
                        echo "</div>";
                    }
                } else {
                    echo "<p>No books found matching your criteria.</p>";
                }
                echo '</div>';

                // Close connection
                $stmt->close();
                $conn->close();
                ?>