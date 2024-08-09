<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Database connection
$servername = "localhost";
$username = "root"; // Change this to your database username
$password = ""; // Change this to your database password
$dbname = "hungrymedb"; // Change this to your database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['username'])) {
    header("Location: HUNGRYME-Cart.php"); // Redirect to login page if not logged in
    exit();
}

$username = $_SESSION['username']; // Get the logged-in username from the session

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['CusID']) && isset($_POST['item_name']) && isset($_POST['shop_name']) && isset($_POST['change'])) {
    // Handle the update quantity request
    $cusID = intval($_POST['CusID']);
    $itemName = $conn->real_escape_string($_POST['item_name']);
    $shopName = $conn->real_escape_string($_POST['shop_name']);
    $change = intval($_POST['change']);

    $sql = "SELECT quantity FROM cart WHERE CusID='$cusID' AND item_name='$itemName' AND shop_name='$shopName' AND username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $new_quantity = $row['quantity'] + $change;

        if ($new_quantity <= 0) {
            $new_quantity = 1;
        }

        $update_sql = "UPDATE cart SET quantity='$new_quantity' WHERE CusID='$cusID' AND item_name='$itemName' AND shop_name='$shopName' AND username='$username'";
        if ($conn->query($update_sql) === TRUE) {
            echo $new_quantity;
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } else {
        echo "Item not found";
    }

    $conn->close();
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['item_name']) && isset($_POST['shop_name']) && isset($_POST['customizations'])) {
    $id = intval($_POST['id']);
    $itemName = $conn->real_escape_string($_POST['item_name']);
    $shopName = $conn->real_escape_string($_POST['shop_name']);
    $customizations = $_POST['customizations'];

    // Decode the customizations if it's JSON encoded
    $customizations = json_decode($customizations, true);

    // Flatten the customizations array to a single level
    $flattenedCustomizations = [];
    foreach ($customizations as $key => $values) {
        if (is_array($values)) {
            foreach ($values as $value) {
                $flattenedCustomizations[] = $value;
            }
        } else {
            $flattenedCustomizations[] = $values;
        }
    }

    // Convert the flattened array back to a comma-separated string
    $customizationsString = implode(',', $flattenedCustomizations);

    $update_sql = "UPDATE cart SET customizations='$customizationsString' WHERE CusID='$id' AND item_name='$itemName' AND shop_name='$shopName' AND username='$username'";
    if ($conn->query($update_sql) === TRUE) {
        echo "Customizations saved successfully!";
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $conn->close();
    exit();
}

// Fetch the cart items for the logged-in user
$sql = "SELECT * FROM cart WHERE username='$username'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="title.jpg">
    <title> Cart-HUNGRYME</title>
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
                <li id="l5"><button id="show-popup1" class="login-button"><i class="fa-solid fa-user fa-xl"></i></button></li>
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
    <script>
        function DarkMode() {
            var element = document.body;
            element.classList.toggle("dark-mode");
        }
    </script>


<div class="container" id="addtoCart">
    <h2>Cart Items</h2>
    
    <table class="table table-bordered" id="cartTable">
        <thead>
            <tr>
                <th>Shop Name</th> <!-- Add Shop Name column -->
                <th>Item Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Image</th>
                <th>Customizations</th>
                <th>Action</th>
                
        </thead>
        <tbody>
        <?php
        // Sample data
        $items = [
            ['CusID' => 1, 'shop_name' => 'Shop A', 'item_name' => 'Item 1', 'item_image' => 'item1.jpg', 'item_price' => '10.00', 'quantity' => 1],
            ['CusID' => 2, 'shop_name' => 'Shop B', 'item_name' => 'Item 2', 'item_image' => 'item2.jpg', 'item_price' => '15.00', 'quantity' => 2]
        ];

        // Start output buffering to set cookies
        ob_start();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $cusID = htmlspecialchars($row['CusID'], ENT_QUOTES, 'UTF-8');
                $shopName = htmlspecialchars($row['shop_name'], ENT_QUOTES, 'UTF-8');
                $itemName = htmlspecialchars($row['item_name'], ENT_QUOTES, 'UTF-8');
                $itemImage = htmlspecialchars($row['item_image'], ENT_QUOTES, 'UTF-8');
                $itemPrice = htmlspecialchars($row['item_price'], ENT_QUOTES, 'UTF-8');
                $quantity = htmlspecialchars($row['quantity'], ENT_QUOTES, 'UTF-8');

                 // Set cookies for each item (you might want to set cookies based on user interaction or a specific condition)
                setcookie('shopName', $shopName, time() + (86400 * 30), "/");
                setcookie('itemName', $itemName, time() + (86400 * 30), "/");

                echo "<tr>";
                echo "<td>" . $shopName . "</td>"; // Display shop name
                echo "<td>" . $itemName . "</td>";
                echo "<td>" . $itemPrice . "</td>";
                echo "<td>";
                echo "<button onclick='updateQuantity(" . $cusID . ", \"" . $itemName . "\", \"" . $shopName . "\", -1)'>-</button>";
                echo "<span id='quantity-" . $cusID . "'>" . $quantity . "</span>";
                echo "<button onclick='updateQuantity(" . $cusID . ", \"" . $itemName . "\", \"" . $shopName . "\", 1)'>+</button>";
                echo "</td>";
                echo "<td><img src='" . $itemImage . "' alt='" . $itemName . "' style='width: 100px;'></td>";
                // echo "<td><button class='btn btn-info' onclick='customizeItem(" . $cusID . ", \"" . $itemName . "\")'>Customize</button></td>";
                echo "<td><button class='btn btn-info' onclick='customizeItem(" . $cusID . ", \"" . $itemName . "\", \"" . $shopName . "\")'>Customize</button></td>";

                echo "<td><button class='btn btn-danger' onclick='removeFromCart(\"" . $cusID . "\", \"" . $shopName . "\", \"" . $itemName . "\")'>Remove</button></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No items in cart</td></tr>"; // Adjust colspan to 7 to match the number of columns
        }
        ob_end_flush();

        ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5"></td>
                <td><strong>Total Amount:</strong></td>
                <td>
                    <input type="text" id="totalAmount" class="form-control" readonly>
                </td>
            </tr>
        </tfoot>
    </table>
    <button onclick="window.location.href='HUNGRYME.php'" id="continu">Continue Shopping</button>
    <button onclick="window.location.href='HUNGRYME-PutOrder.php'" id="Place">Place Order</button>
</div>

    <div class="modal" id="customizeModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Customize Item</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                <form id="customizeForm">
                    <input type="hidden" id="customizeItemId" name="id">
                    <input type="hidden" id="itemName" name="item_name">
                    <input type="hidden" id="shopName" name="shop_name">
                    <div id="customizeOptions"></div>
                    <button type="button" class="btn btn-primary" onclick="saveCustomizations()">Save</button>
                </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        function updateQuantity(cusID, itemName, shopName, change) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        document.getElementById('quantity-' + cusID).innerText = xhr.responseText;
                    } else {
                        alert('Failed to update quantity: ' + xhr.responseText);
                    }
                }
            };
            xhr.send('CusID=' + encodeURIComponent(cusID) + '&item_name=' + encodeURIComponent(itemName) + '&shop_name=' + encodeURIComponent(shopName) + '&change=' + encodeURIComponent(change));
        }

    function removeFromCart(cusID, shopName, itemName){
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'remove_from_cart.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    alert(xhr.responseText); // Display the response from the server
                    window.location.reload(); // Reload the page to update the cart
                } else {
                    alert('Failed to remove item: ' + xhr.responseText);
                }
            }
        };
        xhr.send('CusID=' + encodeURIComponent(cusID) + '&shop_name=' + encodeURIComponent(shopName) + '&item_name=' + encodeURIComponent(itemName));
    }

    function customizeItem(cusID, itemName, shopName) {
        document.getElementById('customizeItemId').value = cusID;
        document.getElementById('itemName').value = itemName;
        document.getElementById('shopName').value = shopName;

        var customizeOptions = document.getElementById('customizeOptions');
        customizeOptions.innerHTML = ''; // Clear previous options

        var optionsHtml = '';

        // Build options based on itemName
        if (itemName.includes('Rice') || itemName.includes('Noodles') || itemName.includes('Kottu')) {
            optionsHtml += '<div class="form-group">';
            optionsHtml += '<label>Extra Vegetables</label><br>';
            optionsHtml += '<input type="checkbox" name="customizations[extraVegetable]" value="Extra Vegetable"> Extra Vegetable<br>';
            optionsHtml += '</div>';

            optionsHtml += '<div class="form-group">';
            optionsHtml += '<label>Extra Meat</label><br>';
            optionsHtml += '<input type="checkbox" name="customizations[extraMeat][chicken]" value="Chicken"> Chicken<br>';
            optionsHtml += '<input type="checkbox" name="customizations[extraMeat][beef]" value="Beef"> Beef<br>';
            optionsHtml += '<input type="checkbox" name="customizations[extraMeat][mutton]" value="Mutton"> Mutton<br>';
            optionsHtml += '</div>';

            optionsHtml += '<div class="form-group">';
            optionsHtml += '<label>Extra Sauce</label><br>';
            optionsHtml += '<input type="checkbox" name="customizations[extraSauce]" value="Extra Sauce"> Extra Sauce<br>';
            optionsHtml += '</div>';

            optionsHtml += '<div class="form-group">';
            optionsHtml += '<label>Extra Egg</label><br>';
            optionsHtml += '<input type="checkbox" name="customizations[extraEgg]" value="Extra Egg"> Extra Egg<br>';
            optionsHtml += '</div>';

            optionsHtml += '<div class="form-group">';
            optionsHtml += '<label>Spice Level</label><br>';
            optionsHtml += '<select class="form-control" id="spiceLevel" name="customizations[spiceLevel]">';
            optionsHtml += '<option value="1">Extra spicy</option>';
            optionsHtml += '<option value="2">Spicy</option>';
            optionsHtml += '<option value="3">Mild</option>';
            optionsHtml += '</select>';
            optionsHtml += '</div>';

            if (itemName.includes('Rice')) {
                optionsHtml += '<div class="form-group">';
                optionsHtml += '<label>Rice Type</label><br>';
                optionsHtml += '<input type="radio" name="customizations[riceType]" value="Samba"> Samba<br>';
                optionsHtml += '<input type="radio" name="customizations[riceType]" value="Basmathi"> Basmathi<br>';
                optionsHtml += '</div>';
            }
        } else if (itemName.includes('Pizza')) {
            optionsHtml += '<div class="form-group">';
            optionsHtml += '<label>Extras</label><br>';
            optionsHtml += '<input type="checkbox" name="customizations[extraCheese]" value="Extra Cheese"> Extra Cheese<br>';
            optionsHtml += '<input type="checkbox" name="customizations[extraMeats]" value="Extra Meats"> Extra Meats (chicken, beef, mutton)<br>';
            optionsHtml += '<input type="checkbox" name="customizations[extraSauce]" value="Extra Sauce"> Extra Sauce<br>';
            optionsHtml += '</div>';
        }

        customizeOptions.innerHTML = optionsHtml;
        $('#customizeModal').modal('show'); // Ensure this is using jQuery
    }

    function saveCustomizations() {
        var form = document.getElementById('customizeForm');
        var formData = new FormData(form);
        var customizations = {};

        formData.forEach(function(value, key) {
            if (key.startsWith('customizations')) {
                var name = key.split('[')[1].split(']')[0]; // Extract the customization key
                var values = value.split(','); // If multiple values, split them
                
                // Add customization values to the customizations object
                if (!customizations[name]) {
                    customizations[name] = [];
                }
                customizations[name].push(value);
            }
        });

        // Convert the customizations object to a string
        var customizationsString = JSON.stringify(customizations);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    alert('Customizations saved successfully!');
                    $('#customizeModal').modal('hide');
                    window.location.reload();
                } else {
                    alert('Failed to save customizations: ' + xhr.responseText);
                }
            }
        };

        xhr.send('id=' + encodeURIComponent(document.getElementById('customizeItemId').value) +
                '&item_name=' + encodeURIComponent(document.getElementById('itemName').value) +
                '&shop_name=' + encodeURIComponent(document.getElementById('shopName').value) +
                '&customizations=' + encodeURIComponent(customizationsString));
    }
    $(document).ready(function () {
        calculateTotalAmount();

        function calculateTotalAmount() {
            var total = 0;
            $('#cartTable tbody tr').each(function () {
                var price = parseFloat($(this).find('td:eq(2)').text().replace(/[^0-9.-]/g, '')); // Get price from the third column (zero-indexed)
                if (!isNaN(price)) {
                    total += price;
                }
            });
            $('#totalAmount').val(total.toFixed(2)); // Update the total amount input
            setCookie('totalAmount', total.toFixed(2), 1); // Set cookie for total amount
        }

        // Function to set cookie
        function setCookie(name, value, days) {
            var expires = "";
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + (value || "") + expires + "; path=/";
        }
    });

    </script>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

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
                        class="text-reset fw-bold" href="#">Hungryme.com</a>
                </div>
        </footer>
        
        
</body>

</html>