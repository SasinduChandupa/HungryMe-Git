<?php
// update_item.php

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hungrymedb";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updateMenuItem'])) {
    $itemId = $_POST['MenuItemID'];
    $shopOwnerID = $_POST['ShopOwnerID'];
    $shopOwnerName = $_POST['ShopOwnerName'];
    $menuitemName = $_POST['menuitemName'];
    $menuitemLocation = $_POST['menuitemLocation'];
    $menuitemDistrict = $_POST['menuitemDistrict'];
    $menuitemPrice = $_POST['menuitemPrice'];
    $menuitemDescription = $_POST['menuitemDescription'];

    // Handle file upload
    $menuitemImage = null;
    if (isset($_FILES['menuitemImage']) && $_FILES['menuitemImage']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "uploads/"; // Directory where images will be uploaded
        $target_file = $target_dir . basename($_FILES["menuitemImage"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Check if file is an image
        $check = getimagesize($_FILES["menuitemImage"]["tmp_name"]);
        if ($check !== false) {
            if (move_uploaded_file($_FILES["menuitemImage"]["tmp_name"], $target_file)) {
                $menuitemImage = $target_file;
            } else {
                echo "Sorry, there was an error uploading your file.";
                exit;
            }
        } else {
            echo "File is not an image.";
            exit;
        }
    }

    // Prepare SQL query
    $sql = "UPDATE menuitem SET
            ShopID = :shopOwnerID,
            ShopName = :shopOwnerName,
            MenuName = :menuitemName,
            Location = :menuitemLocation,
            District = :menuitemDistrict,
            Price = :menuitemPrice,
            Description = :menuitemDescription,
            ImagePath = :menuitemImage
            WHERE MenuItemID = :itemId";

    $stmt = $pdo->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':itemId', $itemId);
    $stmt->bindParam(':shopOwnerID', $shopOwnerID);
    $stmt->bindParam(':shopOwnerName', $shopOwnerName);
    $stmt->bindParam(':menuitemName', $menuitemName);
    $stmt->bindParam(':menuitemLocation', $menuitemLocation);
    $stmt->bindParam(':menuitemDistrict', $menuitemDistrict);
    $stmt->bindParam(':menuitemPrice', $menuitemPrice);
    $stmt->bindParam(':menuitemDescription', $menuitemDescription);
    $stmt->bindParam(':menuitemImage', $menuitemImage);

    // Execute the query
    if ($stmt->execute()) {
        echo "Menu item updated successfully.";
    } else {
        echo "Error updating menu item.";
    }
} else {
    echo "Invalid request.";
}
?>
