<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hungrymedb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   // Retrieve and sanitize POST data
    $vegetable = (double)$_POST['vegetable'];
    $chicken = (double)$_POST['Chicken'];
    $beef = (double)$_POST['Beef'];
    $mutton = (double)$_POST['Mutton'];
    $sauce = (double)$_POST['Sauce'];
    $egg = (double)$_POST['Egg'];
    $saucePizza = $_POST['SaucePizza'];  // Treat as string
    $cheese = $_POST['Cheese'];  // Treat as string
    $meet = $_POST['Meat'];  // Treat as string
    $btnID = $_POST['btnID']; // Get the button value

    if ($btnID == 'Rice') {
        // Update query without LIMIT
        $stmt = $conn->prepare("UPDATE customizationprice SET
                    vegetable = ?,
                    chicken = ?,
                    beef = ?,
                    mutton = ?,
                    sauce = ?,
                    egg = ?,
                    SaucePizza = ?,
                    Cheese = ?,
                    Meat = ?
                LIMIT 1");

        // Bind parameters
        $stmt->bind_param("ddddddsss", $vegetable, $chicken, $beef, $mutton, $sauce, $egg, $saucePizza, $cheese, $meet);

        if ($stmt->execute()) {
            echo "<script>alert('Record updated successfully'); window.location.href = 'HUNGRYME-Admin.php';</script>";
        } else {
            echo "<script>alert('Error updating record: " . $stmt->error . "'); window.location.href = 'HUNGRYME-Admin.php';</script>";
        }

        $stmt->close();
    }
}
?>
