<?php
$jsonDirectory = "borrow_records/";

if (isset($_GET['uid']) && !empty($_GET['uid'])) {
    $uid = htmlspecialchars($_GET['uid']);
    $jsonFilePath = $jsonDirectory . $uid . ".json";

    if (file_exists($jsonFilePath)) {
        $borrowData = json_decode(file_get_contents($jsonFilePath), true);

        echo "<h1>Library Receipt Details</h1>";
        echo "<p><strong>UID:</strong> {$borrowData['uid']}</p>";
        echo "<p><strong>Student Name:</strong> {$borrowData['studentName']}</p>";
        echo "<p><strong>Student ID:</strong> {$borrowData['studentId']}</p>";
        echo "<p><strong>Student Email:</strong> {$borrowData['studentEmail']}</p>";
        echo "<p><strong>Book:</strong> {$borrowData['book']}</p>";
        echo "<p><strong>Borrow Date:</strong> {$borrowData['borrowDate']}</p>";
        echo "<p><strong>Return Date:</strong> {$borrowData['returnDate']}</p>";
        echo "<p><strong>Token:</strong> {$borrowData['token']}</p>";
    } else {
        echo "<p>Error: Borrowing record not found for UID: $uid</p>";
    }
} else {
    echo "<p>Error: UID not provided.</p>";
}
?>
