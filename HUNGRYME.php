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

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['signup'])) {
        // Sign-Up Logic
        $signup_name = $conn->real_escape_string($_POST['signup-name']); // Sanitize input
        $signup_password = $_POST['signup-password'];
        $signup_role = $conn->real_escape_string($_POST['signup-role']); // Sanitize input

        $password_hash = password_hash($signup_password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO tbllogin (username, password, role) VALUES ('$signup_name', '$signup_password', '$signup_role')";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['username'] = $signup_name;
            $_SESSION['role'] = $signup_role;
            header("Location: HUNGRYME.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    if (isset($_POST['login'])) {
        // Sanitize input
        $login_name = $conn->real_escape_string($_POST['login-name']);
        $login_pass = $_POST['login-pass'];
        $login_role = $conn->real_escape_string($_POST['login-role']);

        // Query to fetch user with matching username and role
        $sql = "SELECT * FROM tbllogin WHERE username='$login_name' AND role='$login_role'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // Verify password
            if (password_verify($login_pass, $row['password'])) {
                // Password is correct, set session variables
                $_SESSION['username'] = $login_name;
                $_SESSION['role'] = $row['role'];
                header("Location: HUNGRYME.php");
                exit();
            } else {
                echo "Invalid password!";
            }
        } else {
            echo "No user found with that username and role!";
        }
    }
}

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

 <!-- Login / SignUp Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form">
                    <form action="HUNGRYME.php" method="POST" id="loginForm">
                        <h2>Log in</h2>
                        <div class="form-element">
                        <label for="login-role">Role</label>
                        <select id="login-role" name="login-role" class="form-control" required>
                            <option value="" disabled selected>Select your role</option>
                            <option value="customer">Customer</option>
                            <option value="shop-owner">Shop Owner</option>
                            <option value="delivery">Delivery</option>
                            <option value="admin">Admin</option>
                        </select>
                        </div>
                        <div class="form-element">
                            <label for="login-name">User Name</label>
                            <input type="text" name="login-name" id="login-name" placeholder="Enter User Name" required>
                        </div>
                        <div class="form-element">
                            <label for="login-pass">Password</label>
                            <input type="password" name="login-pass" id="login-pass" placeholder="Enter Password" required>
                        </div>
                        <div class="form-element">
                        <button type="submit" id="btnlogin" name="login">Log in</button>
                        </div>
                        <div class="form-element">
                            <button type="button" id="showSignUp" >Sign Up</button>
                        </div>
                    </form>
                    <form action="HUNGRYME.php" method="POST" id="signUpForm" style="display: none;">
                        <h2>Sign Up</h2>
                        <div class="form-element">
                        <label for="signup-role">Role</label>
                        <select id="signup-role" name="signup-role" class="form-control" required>
                            <option value="" disabled selected>Select your role</option>
                            <option value="customer">Customer</option>
                            <option value="shop-owner">Shop Owner</option>
                            <option value="delivery">Delivery</option>
                            <option value="admin">Admin</option>
                        </select>
                        </div>
                        <div class="form-element">
                            <label for="signup-name">User Name</label>
                            <input type="text" name="signup-name" id="signup-name" placeholder="Enter User Name" required>
                        </div>
                        <div class="form-element">
                            <label for="signup-password">Password</label>
                            <input type="password" name="signup-password" id="signup-password" placeholder="Enter new Password" required>
                        </div>
                        <div class="form-element">
                            <button type="submit" name="signup" id="btnsignup">Sign Up</button>
                        </div>
                        <div class="form-element">
                            <button type="button" id="showLogin">Log in</button>
                        </div>
                    </form>
                </div>
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
                    <br><br>
                    <form id="food-search-form">
                        <div class="di-flex">
                            <div class="form-group">
                                <select id="food-select" name="food-select" class="form-control">
                                    <option value="" disabled selected>Select Food 🔽</option>
                                    <option value="rice">Rice</option>
                                    <option value="kottu">Kottu</option>
                                    <option value="noodle">Noodle</option>
                                    <option value="pizza">Pizza</option>
                                    <option value="beverage">Beverage</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <select id="district-select" name="district-select" class="form-control">
                                    <option value="" disabled selected>Select a district 🔽</option>
                                    <option value="Ampara">Ampara</option>
                                    <option value="Anuradhapura">Anuradhapura</option>
                                    <option value="Badulla">Badulla</option>
                                    <option value="Batticaloa">Batticaloa</option>
                                    <option value="Colombo">Colombo</option>
                                    <option value="Galle">Galle</option>
                                    <option value="Gampaha">Gampaha</option>
                                    <option value="Hambantota">Hambantota</option>
                                    <option value="Jaffna">Jaffna</option>
                                    <option value="Kalutara">Kalutara</option>
                                    <option value="Kandy">Kandy</option>
                                    <option value="Kegalle">Kegalle</option>
                                    <option value="Kilinochchi">Kilinochchi</option>
                                    <option value="Kurunegala">Kurunegala</option>
                                    <option value="Mannar">Mannar</option>
                                    <option value="Matale">Matale</option>
                                    <option value="Matara">Matara</option>
                                    <option value="Monaragala">Monaragala</option>
                                    <option value="Mullaitivu">Mullaitivu</option>
                                    <option value="Nuwara Eliya">Nuwara Eliya</option>
                                    <option value="Polonnaruwa">Polonnaruwa</option>
                                    <option value="Puttalam">Puttalam</option>
                                    <option value="Ratnapura">Ratnapura</option>
                                    <option value="Trincomalee">Trincomalee</option>
                                    <option value="Vavuniya">Vavuniya</option>
                                </select>
                            </div>
                            <div class="searchbtn">
                                <button type="submit" name="search" id="btnsearch">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="image">
                    <img src="HUNGRYME logo.png" alt="Circle Image" class="rotate-image">
                </div>
            </div>
        </div>
    </div>

    <div class="table">
        <table id="shop-table">
            <thead>
                <tr>
                    <th>Shop Name</th>
                    <th>Location</th>
                    <th>price</th>
                    <th>Contact</th>
                    <th>Image</th>
                </tr>
            </thead>
            <tbody id="shop-table-body">
                <!-- Table body will be populated dynamically -->
            </tbody>
        </table>
    </div>

    <script>
        document.getElementById('food-search-form').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission
            
            var food = document.getElementById('food-select').value;
            var district = document.getElementById('district-select').value;
            
            updateTable(food, district);
        });

        function updateTable(food, district) {
            // Example logic to generate specific image name based on selected values
            var imageName = `${district.slice(0, 2)}${food.charAt(0)}.jpg`;
            
            // Example data: replace with actual data retrieval logic
            var shops = [
                { name: 'Aluroma', location: 'Dewata', price: 'Rs:990.00', contact: '1234567890', image: 'GaFriedRice.jpg', food: 'rice', district: 'Galle' },
                { name: 'The Kitchen', location: 'Kotte', price: 'Rs:780.00', contact: '0987654321', image: 'CoFriedRice.jpg', food: 'rice', district: 'Colombo' },
                // Add more shop data as needed
            ];

            // Filter shops based on selected food and district
            var filteredShops = shops.filter(function(shop) {
                return shop.food === food && shop.district === district;
            });

            var tableBody = document.getElementById('shop-table-body');
            tableBody.innerHTML = '';
            
            filteredShops.forEach(function(shop) {
                var row = document.createElement('tr');
                row.innerHTML = `
                    <td>${shop.name}</td>
                    <td>${shop.location}</td>
                    <td>${shop.price}</td>
                    <td>${shop.contact}</td>
                    <td><img src="${shop.image}" alt="${shop.name}" style="width: 100px;"></td>
                `;
                tableBody.appendChild(row);
            });
        }
    </script>



    <br>
    <center>
        <div class="container" id="c">
            <div class="row">
                <div class="col-12" id="img">
                    <div class="horizontal-scroll-wrapper">
                        <div class="row flex-nowrap">
                            <div class="col-sm-6 col-md-4 col-lg-3">
                                <div class="card" style="width: 100%;">
                                    <a class="nav-link" href="####"><img class="card-img-top popup-img"
                                            src="mixrice.jpg" id="image"></a>
                                    <div class="overlay-text"> Fried Rice</div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-3">
                                <div class="card" style="width: 100%;">
                                    <a class="nav-link" href="####"><img class="card-img-top popup-img" src="kottu.jpg"
                                            id="image"></a>
                                    <div class="overlay-text"> Kottu</div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-3">
                                <div class="card" style="width: 100%;">
                                    <a class="nav-link" href="####"><img class="card-img-top popup-img" src="noodle.jpg"
                                            id="image"></a>
                                    <div class="overlay-text"> Noodle</div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-3">
                                <div class="card" style="width: 100%;">
                                    <a class="nav-link" href="####"><img class="card-img-top popup-img" src="Pizza.jpg"
                                            id="image"></a>
                                    <div class="overlay-text"> Pizza</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </center>

    <br>
    <div class="container" id="D">
        <div class="row">
            <div class="image-container">
                <div class="text-container">
                    <h3 id="dh3">How it works</h3>
                    <h4 id="dh4">What we serve</h4>
                    <p id="dp">Product Quality Is Our Priority, And Always Guarantees <br> Freshness And Safety
                        Until It
                        Is In Your
                        Hands.</p>
                    <div class="horizontal-scroll-wrapper">
                        <div style="display: flex; justify-content: space-between;" id="hh">
                            <h3><img src="phone.png"><br> Easy To Order</h3>
                            <h3><img src="bike.png"><br>Fastest Delivery</h3>
                            <h3><img src="man.png"><br>Best Quality</h3>
                        </div>
                        <div style="display: flex; justify-content: space-between;" id="pp">
                            <p>You only order through the app</p>
                            <p>Delivery will be on time</p>
                            <p>The best quality of food for you</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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