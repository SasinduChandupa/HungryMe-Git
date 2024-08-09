<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hungrymedb";

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the order ID from the POST request
    $orderID = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;

    if ($orderID > 0) {
        // Prepare SQL statement to update the order status
        $sql = "UPDATE `order` SET OrderStatus = 'On the Way' WHERE OrderID = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $orderID);
            if ($stmt->execute()) {
                header("Location: HUNGRYME-DeliveryBoy.php");
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error;
        }
    } else {
        echo "Invalid Order ID.";
    }
}

// Close the database connection
$conn->close();
?>
