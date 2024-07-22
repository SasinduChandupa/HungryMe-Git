<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Database connection
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

    // Set parameters and execute
    $menuitemName = $_POST['menuitemName'];
    $menuitemLocation = $_POST['menuitemLocation'];
    $menuitemPrice = $_POST['menuitemPrice'];
    $menuitemDescription = $_POST['menuitemDescription'];
    $district = $_POST['menuitemDistrict'];
    $menuitemImage = ""; // Image handling removed

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO menuitem (Description, Price, MenuName, District, Location) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sdsss", $menuitemDescription, $menuitemPrice, $menuitemName, $district, $menuitemLocation);

    if ($stmt->execute()) {
        echo "New menu item added successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="title.jpg">
    <link rel="C:\wamp64\www\Final\Images">
    <title>HUNGRYME_Shop_Owner</title>
    <link rel="stylesheet" type="text/css" href="Bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <script type="text/javascript" src="Bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="jquery-3.7.1.js"></script>
    <style>
        /* The following styles are not relevant to the toggle button or list items */
        #l1,
        #l2,
        #l3,
        #l4,
        #l5,
        #l6,
        #l7 {
            display: none;
            /* Initially hide the list items */
        }
    </style>
</head>

<body>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#btnbars").click(function () {
                $("#l1").toggle("slow");
                $("#l2").toggle("slow");
                $("#l3").toggle("slow");
                $("#l4").toggle("slow");
                $("#l5").toggle("slow");
                $("#l6").toggle("slow");
                $("#l7").toggle("slow");
            });

            // Show modal on shop owner request button click
            $('#showShopOwnerRequestModal').click(function () {
                $('#shopOwnerRequestModal').modal('show');
            });

            // Add menu item to the list
            $('#btnAddMenuItem').click(function () {
                var itemName = $('#menu-item-name').val();
                var itemPrice = $('#menu-item-price').val();
                if (itemName && itemPrice) {
                    $('#menuList').append('<li>' + itemName + ' - ' + itemPrice + '</li>');
                    $('#menu-item-name').val('');
                    $('#menu-item-price').val('');
                }
            });
        });
    </script>

    <nav class="navbar navbar-expand-sm bg-warning">
        <div class="container-fluid">
            <div class="logo textual pull-left">
                <img src="HUNGRYME(txt).png" height="40px" alt="Logo">
            </div>
            <ul class="navbar-nav ml-auto">
                <li id="l1"><a class="nav-link" href="#home">Home</a></li>
                <li id="l2"><a class="nav-link" href="#footer">About us</a></li>
                <li id="l3"><a class="nav-link" href="https://wa.me/94722714507">Contact us</a></li>
                <li id="l4"><a class="nav-link" href="https://maps.app.goo.gl/5sHYmUQesEMHQfWNA">Main Branch</a></li>
                <li id="l7">
                    <div class="buttonDark"> <button onclick="DarkMode()">
                            <i class="fa-solid fa-moon fa-xl"></i>
                        </button>
                    </div>
                    <script>
                        function DarkMode() {
                            var element = document.body;
                            element.classList.toggle("dark-mode");
                        }
                    </script>
                </li>
                <li> <button id="btnbars"><i class="fa-solid fa-bars fa-2xl"></i></button>
                </li>
            </ul>
        </div>
    </nav>

    <div class="Topic">
        <h1> 
            <!-- $username = "shop_owner_username"; -->
        </h1>
    </div>
    <div id="typewriter">
        <script>
            var i = 0;
            var firstText = "Hey there...! Welcome to HUNGRYME...";
            var secondText = "Join us to grow your shop...";
            var speed = 50;
            var phase = 1;

            function typeWriter() {
                if (phase === 1) {
                    if (i < firstText.length) {
                        document.getElementById("typewriter").innerHTML += firstText.charAt(i);
                        i++;
                        setTimeout(typeWriter, speed);
                    } else {
                        setTimeout(clearText, 0); // Clear immediately
                    }
                } else if (phase === 2) {
                    if (i < secondText.length) {
                        document.getElementById("typewriter").innerHTML += secondText.charAt(i);
                        i++;
                        setTimeout(typeWriter, speed);
                    } else {
                        setTimeout(clearText, 0); // Clear immediately
                    }
                }
            }

            function clearText() {
                i = 0;
                document.getElementById("typewriter").innerHTML = "";
                phase = phase === 1 ? 2 : 1; // Switch between phase 1 and phase 2
                setTimeout(typeWriter, speed);
            }

            window.onload = function () {
                typeWriter();
            };
        </script>
    </div>

    <!-- Display the menu list -->
    <div class="container" id="menuList">
        <h3>Menu List</h3>
        <ul id="menuItemsList">
            <!-- Menu items will be dynamically inserted here -->
        </ul>
    </div>

    <br><br>

    <!-- Shop Owner Menu Customization -->
    <div class="container" id="ShopOwnerCon">
    <h2>Add New Item to Menu</h2>
    <form class="form" id="ShopOwnerForm" method="POST" action="#">
    <label for="menuitemName" id="label">Menu Item Name</label>
    <input type="text" id="menuitemName" name="menuitemName" placeholder="Enter Menu Item Name" required>

    <label for="menuitemLocation" id="label">Location</label>
    <input type="text" id="menuitemLocation" name="menuitemLocation" placeholder="Enter Location" required>

    <label for="menuitemDistrict" id="label">District</label>
    <input type="text" id="menuitemDistrict" name="menuitemDistrict" placeholder="Enter District" required>

    <label for="menuitemPrice" id="label">Menu Item Price</label>
    <input type="number" id="menuitemPrice" name="menuitemPrice" placeholder="Enter Menu Item Price" required>

    <label for="menuitemDescription" id="label">Menu Item Description</label>
    <textarea id="menuitemDescription" name="menuitemDescription" placeholder="Ingredients are - eggs, Chicken, ..." ></textarea>

    <label for="menuitemImage" id="label">Menu Item Image</label>
    <input type="file" id="menuitemImage" name="menuitemImage" accept="image/*">

    <div class="checkbox-container">
        <input type="checkbox" id="confirmAdd" name="confirmAdd" required>
        <label for="confirmAdd">Confirm to Add</label>
    </div>
    <button id="btnAddMenuItem" type="submit">Add Menu Item</button>
    </form>
    </div>


    <br><br>
    <!-- Footer -->
    <footer>
        <div class="text-center text-lg-start" id="footer">

            <section class="d-flex justify-content-center justify-content-lg-between p-4 border-bottom">
                <!-- Left -->
                <div class="me-5 d-none d-lg-block">
                    <span>Check with us on social networks:</span>
                </div>
                <!-- Right -->
                <div>
                    <a href="" class="me-4 text-reset"><i class="fab fa-facebook-f"></i></a>
                    <a href="" class="me-4 text-reset"><i class="fab fa-google"></i></a>
                    <a href="" class="me-4 text-reset"><i class="fab fa-instagram"></i></a>
                </div>
            </section>

            <div class="row mt-3">
                <div class="col-md-6 col-lg-4 col-xl-8 mx-auto mb-4">
                    <h4 class="text-uppercase fw-bold mb-4"><i class="fas fa-burger me-3"></i>HungryMe</h4>
                    <p>Join us to quench your hunger</p>
                </div>
                <!-- Grid column -->
                <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
                    <h6 class="text-uppercase fw-bold mb-4">Contact</h6>
                    <p><i class="fas fa-home me-3"></i> No.46, Matara RD, Galle</p>
                    <p><i class="fas fa-envelope me-3"></i>hungryme@gmail.com</p>
                    <p><i class="fas fa-phone me-3"></i> + 94 915 628 313</p>
                    <p><i class="fas fa-phone me-3"></i> + 94 915 628 314</p>
                </div>
            </div>
            <div class="text-center p-4" style="background-color: rgba(0, 0, 0, 0.05);">© 2024 Copyright:<a
                    class="text-reset fw-bold" href="#">Hungryme.com</a>
            </div>
    </footer>
</body>

</html>