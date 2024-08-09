<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root"; // Change this to your database username
$password = ""; // Change this to your database password
$dbname = "hungrymedb"; // Change this to your database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['CusID']) && isset($_POST['shop_name']) && isset($_POST['item_name'])) {
    $cusID = $conn->real_escape_string($_POST['CusID']);
    $shopName = $conn->real_escape_string($_POST['shop_name']);
    $itemName = $conn->real_escape_string($_POST['item_name']);

    $sql = "DELETE FROM cart WHERE CusID='$cusID' AND shop_name='$shopName' AND item_name='$itemName'";

    if ($conn->query($sql) === TRUE) {
        echo "Item removed from cart successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $conn->close();
    exit();
}
?>