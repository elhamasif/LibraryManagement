<?php
// Turn on error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Borrowing Receipt</title>
    <link rel="stylesheet" href="receipt.css">
</head>

<body>

    <?php

    $errors = [];
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Paths to the JSON files
        $tokensPath = 'token.json';
        $usedTokensPath = 'used_token.json';
     
        // Load available tokens
        $tokensData = json_decode(file_get_contents($tokensPath), true);
        $tokens = $tokensData['tokens'];
     
        // Load used tokens or initialize the used tokens structure
        $usedTokensData = file_exists($usedTokensPath) ? json_decode(file_get_contents($usedTokensPath), true) : ['used_tokens' => []];
        $usedTokens = $usedTokensData['used_tokens'];
     
        // Get the submitted token from the form
        $submittedToken = isset($_POST['token']) ? htmlspecialchars($_POST['token']) : '';
     
        // Process other form data
        $studentName = htmlspecialchars($_POST['student-name']);
        $studentId = htmlspecialchars($_POST['student-id']);
        $borrowDate = htmlspecialchars($_POST['borrow-date']);
        $bookTitle = htmlspecialchars($_POST['book-title']);
        $returnDate = htmlspecialchars($_POST['return-date']);
        $fees = htmlspecialchars($_POST['fees']);
        $email = htmlspecialchars($_POST['email']);
     
        // Validate student name
        if (!preg_match("/^[a-zA-Z\s]+$/", $studentName)) {
            $errors[] = "Invalid name. Only alphabets are allowed.";
        }
     
        // Validate student ID
        if (!preg_match("/^\d{2}-\d{5}-\d{1}$/", $studentId)) {
            $errors[] = "Invalid ID. Format: XX-XXXXX-X.";
        }
     
        // Validate email
        if (!preg_match("/^\d{2}-\d{5}-\d{1}@student\.aiub\.edu$/", $email)) {
            $errors[] = "Invalid email. Must be a valid AIUB student email.";
        }
     
        // Validate borrow and return dates
        if ($borrowDate === $returnDate) {
            $errors[] = "Borrow date and return date cannot be the same.";
        }
     
        $borrowDateObj = new DateTime($borrowDate);
        $returnDateObj = new DateTime($returnDate);
        $interval = $borrowDateObj->diff($returnDateObj)->days;
     
        if ($interval > 10) {
            // Token required for borrow periods exceeding 10 days
            if (empty($submittedToken) || !in_array($submittedToken, $tokens)) {
                $errors[] = "Invalid token or token already used. Please use a valid token.";
            } else {
                // Move the submitted token to the used tokens list
                $usedTokens[] = $submittedToken;
                // Save the updated used tokens list back to used_token.json
                if (file_put_contents($usedTokensPath, json_encode(['used_tokens' => $usedTokens], JSON_PRETTY_PRINT)) === false) {
                    $errors[] = "Failed to update used_token.json.";
                }
                // Remove the used token from available tokens
                $tokens = array_diff($tokens, [$submittedToken]);
                if (file_put_contents($tokensPath, json_encode(['tokens' => array_values($tokens)], JSON_PRETTY_PRINT)) === false) {
                    $errors[] = "Failed to update token.json.";
                }
            }
        }
        if ($returnDateObj <= $borrowDateObj) {
            $errors[] = "Return date must be after the borrow date.";
        }
    
        // Check if the book is already rented
        if (isset($_COOKIE["Book_Name"]) && $_COOKIE["Book_Name"] === $bookTitle) {
            $errors[] = "This book is already rented by {$_COOKIE['Book_Student']}.";
        }
     
        // If there are no errors, proceed with processing
        if (empty($errors)) {
            // Set cookies for the book and student
            setcookie("Book_Name", $bookTitle, time() + 30);
            setcookie("Book_Student", $studentName, time() + 30);

            // Generate a unique identifier
            $uid = uniqid();

            // Collect Borrowing Data
            $borrowData = [
                "uid" => $uid,
                "studentName" => $studentName,
                "studentId" => $studentId,
                "studentEmail" => $email,
                "book" => $bookTitle,
                "borrowDate" => $borrowDate,
                "returnDate" => $returnDate,
                "token" => $submittedToken,
            ];

            // Directory for storing JSON files
            $jsonDirectory = "borrow_records/";

            // Ensure the directory exists
            if (!is_dir($jsonDirectory)) {
                mkdir($jsonDirectory, 0777, true);
            }

            // Write data to JSON file
            $jsonFilePath = $jsonDirectory . $uid . ".json";
            if (file_put_contents($jsonFilePath, json_encode($borrowData, JSON_PRETTY_PRINT)) === false) {
                die("Failed to store borrowing data.");
            }

            // Data for the QR Code
            $qrData = "http://localhost/myproject/recipt.php?uid=" . $uid;

            // Google Chart API URL for QR Code
            $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($qrData);

            echo "
            <div class='receipt-container'>
                <div class='receipt-header'>
                    <h1>Library Receipt</h1>
                </div>

                <div class='details'>
                    <div class='info'>
                        <p>Student Name: $studentName</p>
                        <p>Student ID: $studentId</p>
                        <p>Student Email: $email</p>
                    </div>
                    <div class='QR-code'> 
                        <img src='$qrCodeUrl' alt='QR Codes' width='100' height='100'>
                    </div>
                </div>

                <div class='book-list'>
                    <div class='book'>
                        <p>Selected Book: $bookTitle</p>
                        <p>Token Number: $submittedToken</p>
                        <p>Fees: $fees</p>
                    </div>
                    <div class='Dates'>
                        <p>Borrow Date: $borrowDate</p>
                        <p>Return Date: $returnDate</p>
                    </div> 
                </div>

                <div class='footer'>
                    <p>Thank you for visiting our library!</p>
                </div>
            </div>
            ";
        } else {
            // Display errors
            echo "<div style='color: red;'><h3>Validation Errors:</h3><ul>";
            foreach ($errors as $error) {
                echo "<li>$error</li>";
            }
            echo "</ul><p><a href='index.php'>Go back and fill in all required fields.</a></p></div>";
        }
    }
    ?>

</body>

</html>