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
    $totalAmount = isset($_POST['totalAmount']) ? floatval($_POST['totalAmount']) : 0.0;
    $name = isset($_POST['name']) ? $conn->real_escape_string($_POST['name']) : '';
    $address = isset($_POST['address']) ? $conn->real_escape_string($_POST['address']) : '';
    $phonenum = isset($_POST['phonenum']) ? $conn->real_escape_string($_POST['phonenum']) : '';
    $email = isset($_POST['email']) ? $conn->real_escape_string($_POST['email']) : '';
    $landmark = isset($_POST['landmark']) ? $conn->real_escape_string($_POST['landmark']) : '';
    $paymentMethod = isset($_POST['paymentMethod']) ? $conn->real_escape_string($_POST['paymentMethod']) : '';
    $orderDate = date("Y-m-d H:i:s");

    if (empty($name) || empty($address) || empty($phonenum) || empty($email) || empty($paymentMethod)) {
        echo "Please fill in all required fields.";
    } else {
        $user = isset($_COOKIE['username']) ? $_COOKIE['username'] : null;
        if ($user) {
            // Fetch shop names and item names from the cart
            $sql = "SELECT shop_name, item_name FROM cart WHERE username = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("s", $user);
                $stmt->execute();
                $result = $stmt->get_result();

                $shopNames = [];
                $itemNames = [];

                while ($row = $result->fetch_assoc()) {
                    $shopNames[] = $row['shop_name'];
                    $itemNames[] = $row['item_name'];
                }

                $shopNamesStr = implode(', ', array_unique($shopNames));
                $itemNamesStr = implode(', ', $itemNames);

                // Prepare an SQL statement for insertion
                $stmtInsert = $conn->prepare("INSERT INTO `order` (TotAmount, shopname, menuitem, Name, Address, PhoneNo, Email, Landmarks, PaymentMethod, OrderDate) 
                                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                if ($stmtInsert === false) {
                    die("Prepare failed: " . $conn->error);
                }

                $stmtInsert->bind_param("dsssssssss", $totalAmount, $shopNamesStr, $itemNamesStr, $name, $address, $phonenum, $email, $landmark, $paymentMethod, $orderDate);

                if ($stmtInsert->execute()) {
                    echo "New record created successfully.<br>";
                } else {
                    echo "Error: " . $stmtInsert->error . "<br>";
                }

                $stmtInsert->close();

                // Optionally clear the cart after processing the order
                // $conn->query("DELETE FROM cart WHERE username = '$user'");

                $stmt->close();
            } else {
                echo "Error preparing statement: " . $conn->error;
            }
        } else {
            echo "Username cookie is not set.";
        }
    }
}

$conn->close();
?>



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

// Get username from cookie
$user = isset($_COOKIE['username']) ? $_COOKIE['username'] : null;

if ($user) {
    // Query to get data from the cart table based on the username
    $sql = "SELECT shop_name, item_name FROM cart WHERE username = ?";
    
    // Prepare the statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters
        $stmt->bind_param("s", $user);
        
        // Execute the statement
        $stmt->execute();
        
        // Get result
        $result = $stmt->get_result();
    } else {
        // Handle SQL prepare error
        echo "Error preparing statement: " . $conn->error;
        exit;
    }
} else {
    // Handle missing username
    echo "Username cookie is not set.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="title.jpg">
    <title>HUNGRYME Order</title>
    <link rel="stylesheet" type="text/css" href="Bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="H_Style.css">
    <script type="text/javascript" src="Bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="jquery-3.7.1.js"></script>
    <script type="text/javascript" src="Bootstrap/js/bootstrap.min.js"></script>

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

            // Show modal on login button click
            $('#show-popup1').click(function () {
                $('#login').modal('show');
            });

            // Show modal on sign-up button click
            $('#btnsignup').click(function () {
                $('#login').modal('show');
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

    <script>
        function DarkMode() {
            var element = document.body;
            element.classList.toggle("dark-mode");
        }
    </script>
    
    <div class="Topic">
        <h1>M A K E O R D E R</h1>
    </div>
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
    
    <div class="container" id="containerPayment">
    <button onclick="window.location.href='HUNGRYME-Cart'"  id="navcart" class="cart"><i class="fa-solid fa-cart-shopping fa-xl"></i></button>
    <h2 class="text-center">Enter your information</h2>
    <form method="POST" action="#">
        <div class="form-group">
            <label for="tot"><b>Total</b></label>
            <input type="text" class="form-control" id="tot" name="totalAmount" readonly value="<?php echo htmlspecialchars($_COOKIE['totalAmount']); ?>">
        </div>
        <div class="form-group">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Shop Name</th>
                        <th>Items</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['shop_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <div class="form-group">
            <label for="name"><b>Name:</b></label>
            <input type="text" class="form-control" id="name" name="name" readonly value="<?php echo htmlspecialchars($_COOKIE['username']); ?>">
        </div>
        <div class="form-group">
            <label for="address"><b>Address:</b></label>
            <input type="text" class="form-control" id="address" name="address" placeholder="Enter your address" required>
        </div>
        <div class="form-group">
            <label for="phonenum"><b>Phone Number:</b></label>
            <input type="text" class="form-control" id="phonenum" name="phonenum" placeholder="Enter your phone number" required>
        </div>
        <div class="form-group">
            <label for="email"><b>Email:</b></label>
            <input type="text" class="form-control" id="email" name="email" placeholder="Enter your email" required>
        </div>
        <div class="form-group">
            <label for="landmark"><b>Landmark:</b></label>
            <textarea class="form-control" id="landmark" name="landmark" placeholder="e.g:- in front of Train Station"></textarea>
        </div>

        <h2 class="text-center"><b>Payment Options</b></h2>
        <div class="form-group">
            <label for="paymentMethod"><b>Select Payment Method:</b></label>
            <select class="form-control" id="paymentMethod" name="paymentMethod" onchange="handlePaymentChange()">
                <option value="cod">Cash on Delivery</option>
                <option value="online">Online Payment</option>
            </select>
        </div>
        <center><button type="submit" class="btn btn-primary">Place Order</button></center>
    </form> 
</div>

    <?php
    // Close connection
    $stmt->close();
    $conn->close();
    ?>

    <br><br>

    <!-- Online Payment Modal -->
    <div class="modal fade" id="onlinePaymentModal" tabindex="-1" aria-labelledby="onlinePaymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="onlinePaymentModalLabel">Online Payment Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="cardNumber">Card Number</label>
                            <input type="text" class="form-control" id="cardNumber" placeholder="Enter card number">
                        </div>
                        <div class="form-group">
                            <label for="expiryDate">Expiry Date</label>
                            <input type="text" class="form-control" id="expiryDate" placeholder="MM/YY">
                        </div>
                        <div class="form-group">
                            <label for="cvv">CVV</label>
                            <input type="text" class="form-control" id="cvv" placeholder="Enter CVV">
                        </div>
                        <div class="form-group">
                            <label for="TotalAmount">Total Amount</label>
                            <input type="text" class="form-control" id="TotalAmount" placeholder="RS.">
                        </div>
                        <center><button type="submit" class="btn btn-primary">Place Order</button></center>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function handlePaymentChange() {
            var paymentMethod = document.getElementById('paymentMethod').value;
            if (paymentMethod === 'online') {
                // Show the online payment modal
                var onlinePaymentModal = new bootstrap.Modal(document.getElementById('onlinePaymentModal'));
                onlinePaymentModal.show();
            }
        }
    </script>
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
        </div>
    </footer>
</body>

</html>