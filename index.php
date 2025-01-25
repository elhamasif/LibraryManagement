<?php
// Start session
/*
session_start();

// Turn on error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);



$tokenMessage = "";
if (isset($_SESSION['token'])) {
    $tokenId = $_SESSION['token']['id'];
    $token = $_SESSION['token']['value'];
    $tokenMessage = "
                     <p><strong>Student ID:</strong> $tokenId</p>
                     <p><strong>Generated Token:</strong> $token</p>";
    unset($_SESSION['token']); // Clear token data after displaying
}

if (isset($_SESSION['error'])) {
    $tokenMessage = "<p style='color: red;'>{$_SESSION['error']}</p>";
    unset($_SESSION['error']); // Clear error after displaying
}*/
// Path to the JSON file
$tokensPath = 'token.json';

// Check if the file exists and load the tokens
if (file_exists($tokensPath)) {
    $jsonContent = file_get_contents($tokensPath);
    $tokens = json_decode($jsonContent, true)['tokens'] ?? [];
} else {
    $tokens = []; // Default to an empty array if the file doesn't exist
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercise</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <img src="images/img.png" alt="Screenshot of ID">
    <div class="head">
        <h1><b><i><u>Book Borrowing Management</u></i></b></h1>
    </div>
    <div>
        <div class="outerclass">
            <div class="leftBox" style="padding: 20px; border:2px solid rgb(169, 169, 199); background-color: rgb(124, 230, 230);">
                <h2>Search Books</h2>
                <form method="GET" action="">
                    <label for="book_name">Book Name:</label>
                    <input type="text" id="book_name" name="book_name"><br><br>

                    <label for="author_name">Author Name:</label>
                    <input type="text" id="author_name" name="author_name"><br><br>

                    <label for="category">Category:</label><br>
                    <select id="category" name="category">
                        <option value="">-- All Categories --</option>
                        <option value="story">Story</option>
                        <option value="science">Science</option>
                        <option value="history">History</option>
                        <option value="technology">Technology</option>
                    </select><br><br>

                    <label for="min_fees">Fees Range:</label><br>
                    Min: <input type="number" id="min_fees" name="min_fees" step="0.01">
                    Max: <input type="number" id="max_fees" name="max_fees" step="0.01"><br><br>

                    <button type="submit">Search</button>
                </form>
                <div style="margin-top: 20px; max-height: 633px; overflow-y: auto; border:2px solid rgb(169, 169, 199); padding: 10px;background-color: rgb(206, 239, 239);">
                    <?php include 'search.php'; ?></div>
            </div>

            <div class="middleBox">

                <div class="book-results" style="max-width: 400px; overflow-x: auto; border: 2px solid rgb(169, 169, 199); padding: 10px; background-color: rgb(206, 239, 239);">

                    <h2>Modify Books</h2>
                    <form method="GET" action="">
                        <label for="search_name">Book Name:</label>
                        <input type="text" id="search_name" name="search_name" placeholder="Enter book name" required>
                        <button type="submit">Search</button>




                    </form>
                    <?php include 'modify.php'; ?>
                </div>

                <div class="third">

                    <div class="box1">
                        
                        <h3>Available Tokens</h3>
                        <ul class="scrollable">
                            <?php
                            if (!empty($tokens)) {
                                foreach ($tokens as $token) {
                                    echo "<li>" . htmlspecialchars($token) . "</li>";
                                }
                            } else {
                                echo "<li>No tokens available</li>";
                            }
                            ?>

                    </div>
                </div>
                <div class="fourth">
                    <!--<form action="process.php" method="post">
                        <div class="box4">
                            <h2>Borrow Book</h2>
                            <label for="SName">Student Name</label><br>
                            <input type="text" id="SName" name="Sname"><br>
                            <label for="SId">Student Id</label><br>
                            <input type="text" id="SId" name="Sid"><br>
                            <label for="Smail">Student Email</label><br>
                            <input type="text" id="Smail" name="Smail"><br>-->
                    <div class="box4">
                        <form action="process.php" method="post">
                            <label for="student-name">Student Full Name:</label><br>
                            <input type="text" id="student-name" name="student-name" required><br>
                            <label for="student-id">Student ID:</label><br>
                            <input type="text" id="student-id" name="student-id" required><br>
                            <label for="email">Email:</label><br>
                            <input type="email" id="email" name="email" required><br>
                            <label for="book-title">Book Title:</label><br>
                            <select id="book-title" name="book-title" required><br>
                                <option value="Book 1">Book 1</option>
                                <option value="Book 2">Book 2</option>
                                <option value="Book 3">Book 3</option>
                                <option value="Book 4">Book 4</option>
                                <option value="Book 5">Book 5</option>
                            </select><br>
                            <label for="borrow-date">Borrow Date:</label><br>
                            <input type="date" id="borrow-date" name="borrow-date" required><br>
                            <label for="return-date">Return Date:</label><br>
                            <input type="date" id="return-date" name="return-date" required><br>
                            <label for="token">Enter Token:</label><br>
                            <input type="text" id="token" name="token" placeholder="Enter your token"><br>
                            <label for="fees">Fees (in TK):</label><br>
                            <input type="number" id="fees" name="fees" required><br>
                            <input type="submit" value="Submit"><br>
                        </form>
                    </div>


                    <?php /*
                    // Include database connection
                    include 'db.php';

                    // Fetch books grouped by category
                    $sql = "SELECT book_name, category FROM books ORDER BY category, book_name";
                    $result = $conn->query($sql);

                    // Create the dropdown
                    echo '<label for="selectBook">Choose a Book</label><br>';
                    echo '<select name="selectBook" id="selectBook" required>';

                    $currentCategory = ""; // To track category changes
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            // Check if the category has changed
                            if ($currentCategory !== $row['category']) {
                                // Close previous optgroup if it's not the first iteration
                                if ($currentCategory !== "") {
                                    echo '</optgroup>';
                                }
                                // Start a new optgroup
                                $currentCategory = $row['category'];
                                echo '<optgroup label="' . htmlspecialchars(ucwords($currentCategory)) . '">';
                            }
                            // Add book option
                            echo '<option value="' . htmlspecialchars($row['book_name']) . '">' . htmlspecialchars($row['book_name']) . '</option>';
                        }
                        // Close the last optgroup
                        echo '</optgroup>';
                    } else {
                        echo '<option value="">No books available</option>';
                    }

                    echo '</select><br>';
                    */ ?>
                    <!--<label for="Borrow">Borrow Book Date:</label><br>
                            <?php //date_default_timezone_set('Asia/Dhaka'); 
                            ?>
                            <input type="date" id="Borrow" name="Borrow"><br>
                            <label for="Token">Token No:</label><br>
                            <input type="text" id="Token" name="Token"><br>
                            <label for="Return">Return Book Date:</label><br>
                            <input type="date" id="Return" name="Return"><br>
                            <label for="Fees">Fees:</label><br>
                            <input type="number" id="Fees" name="Fees"><br>
                            <button type="submit">Submit</button>-->
                           
               
                

                <div class="box5">

                    <h3>Used Tokens</h3>
                    <ul>
                        <?php
                        // Load used tokens from used_token.json
                        $usedTokensPath = 'used_token.json';
                        $usedTokens = file_exists($usedTokensPath) ? json_decode(file_get_contents($usedTokensPath), true)['used_tokens'] ?? [] : [];
                        foreach ($usedTokens as $usedToken) {
                            echo "<li>" . htmlspecialchars($usedToken) . "</li>";
                        }
                        ?>


                </div>
            </div>
        </div>
        <div class="rightBox">

            <div class="box7">
                <div class="box7">
                    <h1>Book Catagories</h1>
                </div>
            </div>
            <div class="box7">
                <a href="story_books.php" style="text-decoration: none;">
                    <div class="box8" style="cursor: pointer;">
                        <img src="images/storybook.jpeg" style="height: 130px; width: 120.5px;">
                    </div>
                </a>
                <a href="science_books.php" style="text-decoration: none;">
                    <div class="box8" style="cursor: pointer;">
                        <img src="images/sciencebook.jpg" style="height: 130px; width: 120.5px;">
                    </div>
                </a>
            </div>
            <div class="box7">
                <a href="history_books.php" style="text-decoration: none;">
                    <div class="box8" style="cursor: pointer;">
                        <img src="images/history.jpg" style="height: 130px; width: 120.5px;">
                    </div>
                </a>
                <a href="technology_books.php" style="text-decoration: none;">
                    <div class="box8" style="cursor: pointer;">
                        <img src="images/technology.jpg" style="height: 130px; width: 120.5px;">
                    </div>
                </a>
            </div>


            <div class="box9">
                <h2>Add a New Book</h2>
                <form action="add.php" method="post" enctype="multipart/form-data">
                    <label for="book_name">Book Name:</label><br>
                    <input type="text" id="book_name" name="book_name" required><br><br>

                    <label for="category">Category:</label><br>
                    <select id="category" name="category" required>
                        <option value="">-- Select a Category --</option>
                        <option value="story">Story</option>
                        <option value="science">Science</option>
                        <option value="history">History</option>
                        <option value="technology">Technology</option>
                    </select><br><br>

                    <label for="fees">Fees:</label><br>
                    <input type="number" id="fees" name="fees" step="0.01" required><br><br>

                    <label for="author_name">Author Name:</label><br>
                    <input type="text" id="author_name" name="author_name" required><br><br>

                    <label for="image">Book Image:</label><br>
                    <input type="file" id="image" name="image" accept="image/*" required><br><br>

                    <button type="submit">Add Book</button>
                </form>
            </div>



        </div>
    </div>
    </div>
    <div class="dead"></div>
</body>

</html>