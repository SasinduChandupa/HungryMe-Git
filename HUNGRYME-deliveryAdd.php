<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hungrymedb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $riderName = isset($_POST['rider-name']) ? $conn->real_escape_string($_POST['rider-name']) : '';
    
    if (empty($riderName)) {
        echo "Rider Name is required.";
    } else {
        // Generate a unique username and password
        $usernamePrefix = "DEL";
        $latestIdQuery = "SELECT MAX(SUBSTRING(username, 4)) AS latest_id FROM tbllogin WHERE username LIKE 'DEL%'";
        $result = $conn->query($latestIdQuery);
        $latestId = $result->fetch_assoc()['latest_id'];
        $newId = str_pad(($latestId ? intval($latestId) + 1 : 1), 3, '0', STR_PAD_LEFT);
        $username = $usernamePrefix . $newId;
        $password = $username; // Using username as password for simplicity

        // Prepare and execute SQL statement
        $sql = "INSERT INTO tbllogin (username, password, role) VALUES (?, ?, 'delivery_boy')";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param($username, $password, $riderName);
            if ($stmt->execute()) {
                header("Location: HUNGRYME-Admin.php");
                exit(); // Make sure to exit after redirection
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error;
        }
    }
}

$conn->close();
?>
