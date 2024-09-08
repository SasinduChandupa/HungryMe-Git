<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hungrymedb";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the logged-in username from the cookie
$customerName = $_COOKIE['username'];

// Retrieve the CusID for this customer name
$sql = "SELECT CusID FROM customer WHERE name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $customerName);
$stmt->execute();
$result = $stmt->get_result();

$totalPrice = 0;


if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $cusID = $row['CusID'];

    // Retrieve CartID's related to this CusID from the cart table
    $cartSql = "SELECT CartID FROM cart WHERE CusID = ?";
    $cartStmt = $conn->prepare($cartSql);
    $cartStmt->bind_param("i", $cusID);
    $cartStmt->execute();
    $cartResult = $cartStmt->get_result();

    if ($cartResult->num_rows > 0) {
        $cartItems = [];
        
        while ($cartRow = $cartResult->fetch_assoc()) {
            $cartID = $cartRow['CartID'];

            // Retrieve MenuItemID's related to this CartID from the cartmenuitem table
            $menuItemSql = "SELECT MenuItemID, Quantity FROM cartmenuitem WHERE CartID = ?";
            $menuItemStmt = $conn->prepare($menuItemSql);
            $menuItemStmt->bind_param("i", $cartID);
            $menuItemStmt->execute();
            $menuItemResult = $menuItemStmt->get_result();

            while ($menuItemRow = $menuItemResult->fetch_assoc()) {
                $menuItemID = $menuItemRow['MenuItemID'];
                $quantity = $menuItemRow['Quantity'];

                // Fetch item details
                $itemDetailsSql = "SELECT m.MenuName, m.Price, m.ImagePath, s.ShopName
                                   FROM menuitem m
                                   JOIN shop s ON m.ShopID = s.ShopID
                                   WHERE m.MenuItemID = ?";
                $itemDetailsStmt = $conn->prepare($itemDetailsSql);
                $itemDetailsStmt->bind_param("i", $menuItemID);
                $itemDetailsStmt->execute();
                $itemDetailsResult = $itemDetailsStmt->get_result();

                while ($itemDetailsRow = $itemDetailsResult->fetch_assoc()) {
                    $cartItems[] = [
                        'MenuName' => $itemDetailsRow['MenuName'],
                        'ShopName' => $itemDetailsRow['ShopName'],
                        'Price' => $itemDetailsRow['Price'],
                        'Quantity' => $quantity,
                        'ImagePath' => $itemDetailsRow['ImagePath'],
                        'CartID' => $cartID,
                        'MenuItemID' => $menuItemID
                    ];
                     // Update totalPrice
                     $totalPrice += $itemDetailsRow['Price'] * $quantity;

                    // Save shop names and item names in cookies
                    $shopNames = [];
                    $itemNames = [];
                    $itemQuantity =[];

                    // Collect shop names and item names
                    foreach ($cartItems as $item) {
                        $shopNames[] = $item['ShopName'];
                        $itemNames[] = $item['MenuName'];
                    }

                    // Set cookies for shop names and item names
                    setcookie("shop_names", json_encode($shopNames), time() + 3600, "/");
                    setcookie("item_names", json_encode($itemNames), time() + 3600, "/");
                }
                $itemDetailsStmt->close();
            }
            $menuItemStmt->close();
        }
    } else {
        $cartItems = [];
    }
    $cartStmt->close();
} else {
    $cartItems = [];
}
// Set the total price cookie
setcookie("totalPrice", $totalPrice, time() + 3600, "/");

// Close connection
$stmt->close();
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="title.jpg">
    <title>HUNGRYME</title>
    <link rel="stylesheet" type="text/css" href="Bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="H_Style.css">
    <script type="text/javascript" src="Bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="jquery-3.7.1.js"></script>
    <script type="text/javascript" src="Bootstrap/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

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
            $("#l1, #l2, #l3, #l4, #l5, #l6, #l7").toggle("slow");
        });

        // Show modal on login button click
        $('#show-popup1').click(function () {
            $('#loginModal').modal('show');
            $('#loginForm').show();
            $('#signUpForm').hide();
        });

        // Show modal on sign-up button click
        $('#btnsignup').click(function () {
            $('#loginModal').modal('show');
            $('#loginForm').hide();
            $('#signUpForm').show();
        });

        // Toggle to sign-up form
        $('#showSignUp').click(function () {
            $('#loginForm').hide();
            $('#signUpForm').show();
        });

        // Toggle to login form
        $('#showLogin').click(function () {
            $('#loginForm').show();
            $('#signUpForm').hide();
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
                </li>
                <li> <button id="btnbars"><i class="fa-solid fa-bars fa-2xl"></i></button>
                </li>
            </ul>
        </div>
    </nav>
    <br><br>
    <div class="Topic" id="home">
        <div class="row">
            <h1>C A R T</h1>
        </div>
        <div class="row">
            <div class="col-sm-8">
                <div class="topic">
                    <div id="typewriter">
                        <script>
                            var i = 0;
                            var firstText = "Hey there...! Welcome to HUNGRYME...";
                            var secondText = "Join us to quench your hunger...";
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
                    <br><br>
                </div>
            </div>
        </div>
        
    </div>
    <script>
        function DarkMode() {
            var element = document.body;
            element.classList.toggle("dark-mode");
        }
    </script>

    <center>
    <table border='1' cellpadding='10' cellspacing='0' style='border-collapse: collapse; text-align: center;'>
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Shop Name</th>
                <th>Price (Rs.)</th>
                <th>Quantity</th>
                <th>Image</th>
                <th>Remove</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($cartItems)): ?>
                <?php foreach ($cartItems as $item): ?>
                    <tr id="cart-item-<?php echo $item['CartID'] . '-' . $item['MenuItemID']; ?>">
                        <td><?php echo $item['MenuName']; ?></td>
                        <td><?php echo $item['ShopName']; ?></td>
                        <td class="price"><?php echo $item['Price']; ?></td>
                        <td>
                            <button class="decrease-quantity" data-cart-id="<?php echo $item['CartID']; ?>"
                                data-menu-item-id="<?php echo $item['MenuItemID']; ?>">-</button>
                            <span class="quantity"><?php echo $item['Quantity']; ?></span>
                            <button class="increase-quantity" data-cart-id="<?php echo $item['CartID']; ?>"
                                data-menu-item-id="<?php echo $item['MenuItemID']; ?>">+</button>
                        </td>
                        <td><img src="<?php echo $item['ImagePath']; ?>" alt="Image" width="100"></td>
                        <td>
                            <button class="remove-item" data-cart-id="<?php echo $item['CartID']; ?>"
                                data-menu-item-id="<?php echo $item['MenuItemID']; ?>">Remove</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">Your cart is empty</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</center>
<br>
<center>
    <label for="totalPrice">Total Price:</label>
    <input type="text" id="totalPrice" readonly>
</center>


    <br>
    <br>
    <center>
        <button id="continueShopping" class="btn btn-primary">Continue Shopping</button>
        <button id="placeOrder" class="btn btn-success">Place Order</button>
    </center>

    <script>
        document.getElementById('continueShopping').addEventListener('click', function() {
            window.location.href = 'HUNGRYME.php';
        });

        document.getElementById('placeOrder').addEventListener('click', function() {
            window.location.href = 'HUNGRYME-PutOrder.php';
        });
    </script>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        let total = 0;

        document.querySelectorAll('.price').forEach(function (element) {
            total += parseFloat(element.textContent);
        });
        document.getElementById('totalPrice').value = total.toFixed(2);

        document.querySelectorAll('.increase-quantity').forEach(function (button) {
            button.addEventListener('click', function () {
                let quantitySpan = this.previousElementSibling;
                let quantity = parseInt(quantitySpan.textContent);
                quantity += 1;
                quantitySpan.textContent = quantity;

                let cartID = this.getAttribute('data-cart-id');
                let menuItemID = this.getAttribute('data-menu-item-id');

                updateQuantityInDatabase(cartID, menuItemID, quantity);

                updateTotalPrice();
            });
        });

        document.querySelectorAll('.decrease-quantity').forEach(function (button) {
            button.addEventListener('click', function () {
                let quantitySpan = this.nextElementSibling;
                let quantity = parseInt(quantitySpan.textContent);
                if (quantity > 1) {
                    quantity -= 1;
                    quantitySpan.textContent = quantity;

                    let cartID = this.getAttribute('data-cart-id');
                    let menuItemID = this.getAttribute('data-menu-item-id');

                    updateQuantityInDatabase(cartID, menuItemID, quantity);

                    updateTotalPrice();
                }
            });
        });

      // Function to handle item removal
document.querySelectorAll('.remove-item').forEach(function(button) {
    button.addEventListener('click', function() {
        var cartID = this.getAttribute('data-cart-id');
        var menuItemID = this.getAttribute('data-menu-item-id');

        // Send an AJAX request to the PHP file to remove the item
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'removeCartItem.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                // On success, remove the row from the table
                document.getElementById('cart-item-' + cartID + '-' + menuItemID).remove();
                
                // Optionally, update the total price
                updateTotalPrice();
            }
        };
        xhr.send('cartID=' + cartID + '&menuItemID=' + menuItemID); // Send the CartID and MenuItemID to the PHP file
    });
});


function updateTotalPrice() {
    // Get all the price elements and sum them
    var prices = document.querySelectorAll('.price');
    var totalPrice = 0;
    prices.forEach(function(priceElement) {
        totalPrice += parseFloat(priceElement.textContent);
    });

    // Update the total price input
    document.getElementById('totalPrice').value = totalPrice.toFixed(2);
}

        function updateQuantityInDatabase(cartID, menuItemID, quantity) {
            $.ajax({
                url: 'update_cart_quantity.php',
                type: 'POST',
                data: {
                    CartID: cartID,
                    MenuItemID: menuItemID,
                    Quantity: quantity
                },
                success: function (response) {
                    console.log('Quantity updated successfully');
                },
                error: function (xhr, status, error) {
                    console.error('Error updating quantity: ' + error);
                }
            });
        }
    });

    </script>

<br><br><br><br>
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
                    class="text-reset fw-bold" href="http://localhost/Final/HungryMe/HUNGRYME.php">Hungryme.com</a>
            </div>
        </div>
    </footer>

</body>

</html>