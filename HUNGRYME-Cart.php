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
    header("Location: HUNGRYME.php"); // Redirect to login page if not logged in
    exit();
}

$username = $_SESSION['username']; // Get the logged-in username from the session

$sql = "SELECT * FROM cart WHERE username='$username'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="title.jpg">
    <title>Cart - HUNGRYME</title>
    <link rel="stylesheet" type="text/css" href="Bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="Styles.css">
</head>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="title.jpg">
    <title>HUNGRYME</title>
    <link rel="stylesheet" type="text/css" href="Bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="Styles.css">
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
                <li id="l6"><button id="navcart" class="cart"><i class="fa-solid fa-cart-shopping fa-xl"></i></button></li>
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

<script>
        function DarkMode() {
            var element = document.body;
            element.classList.toggle("dark-mode");
        }
    </script>

    <div class="Topic" id="home">
        <div class="row">
            <h1>H U N G R Y M E</h1>
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
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <h2>Cart Items</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Item Price</th>
                    <th>Item Image</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['item_name'] . "</td>";
                        echo "<td>" . $row['item_price'] . "</td>";
                        echo "<td><img src='" . $row['item_image'] . "' alt='" . $row['item_name'] . "' style='width: 100px;'></td>";
                        echo "<td><button class='btn btn-danger' onclick='removeFromCart(" . $row['CusID'] . ")'>Remove</button></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No items in cart</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <button onclick="window.location.href='HUNGRYME.php'">Continue Shopping</button>
        <button onclick="window.location.href='HUNGRYME.php'">Continue Shopping</button>
    </div>

    <script>
        function removeFromCart(id) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'remove_from_cart.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        alert('Item removed from cart!');
                        window.location.reload(); // Reload the page to update the cart
                    } else {
                        alert('Failed to remove item: ' + xhr.responseText);
                    }
                }
            };
            xhr.send('id=' + encodeURIComponent(id));
        }
    </script>
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