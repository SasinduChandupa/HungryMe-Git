<?php
// Database configuration
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

// Fetch data from the database
$sql = "SELECT Name, Address, PhoneNo, Landmarks, OrderID AS OID, PaymentMethod AS PaymentMethod, Email, OrderDate , shopname, menuitem
        FROM `order`";
        
$result = $conn->query($sql);
?>

<?php
// Database configuration
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

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the order_id from the form
    $order_id = $_POST['order_id'];

    // Prepare the SQL query to update the order status using a prepared statement
    $stmt = $conn->prepare("UPDATE `order` SET OrderStatus = 'delivered' WHERE OrderID = ?");
    $stmt->bind_param("i", $order_id); // Assuming OrderID is an integer

    // Execute the query
    if ($stmt->execute()) {
        // Redirect back to the original page or display a success message
        header("Location: HUNGRYME-DeliveryBoy.php");
        exit();
    } else {
        // Handle errors if the query fails
        echo "Error updating record: " . $stmt->error;
    }

    // Close the prepared statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>


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
    $orderID = $_POST['order_id'];
    $deliveryDate = date('Y-m-d H:i:s');

    $sql = "INSERT INTO delivery (DeliveryDate, Status, DeliveryAddress, OrderID) 
            VALUES (?, 'Delivered', 'Address', 'OID')";

    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param('si', $deliveryDate, $orderID);

    if ($stmt->execute()) {
        echo "Order marked as delivered successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="title.jpg">
    <link rel="C:\wamp64\www\Final\Images">
    <title>HUNGRYME_Delivery_Boy</title>
    <link rel="stylesheet" type="text/css" href="Bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="H_Style.css">
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

            // Show modal on handle delivery button click
            $('#showHandleDeliveryModal').click(function () {
                $('#handleDeliveryModal').modal('show');
            });

            // Show modal on handle payment button click
            $('#showHandlePaymentModal').click(function () {
                $('#handlePaymentModal').modal('show');
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
                    <div class="buttonDark">
                        <button onclick="toggleDarkMode()">
                            <i class="fa-solid fa-moon fa-xl"></i>
                        </button>
                    </div>
                </li>
                <li>
                    <button id="btnbars"><i class="fa-solid fa-bars fa-2xl"></i></button>
                </li>
            </ul>
        </div>
    </nav>

    <br><br>

    <div class="container">
        <h2 class="text-center">Delivery Details</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>CusName</th>
                    <th>Address</th>
                    <th>PhoneNumber</th>
                    <th>Location</th>
                    <th>Land Mark</th>
                    <th>OID</th>
                    <th>Shop Names</th>
                    <th>Item Names</th>
                    <th>Click the Order</th>
                    <th>Order Status </th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['Name']); ?></td>
                            <td><?php echo htmlspecialchars($row['Address']); ?></td>
                            <td><?php echo htmlspecialchars($row['PhoneNo']); ?></td>
                            <td><?php echo htmlspecialchars($row['OrderDate']); ?></td>
                            <td><?php echo htmlspecialchars($row['Landmarks']); ?></td>
                            <td><?php echo htmlspecialchars($row['OID']); ?></td>
                            <td><?php echo htmlspecialchars($row['shopname']);?></td>
                            <td><?php echo htmlspecialchars($row['menuitem']); ?></td>
                            <td>
                                <!-- Button example: View Details -->
                                <form method="POST" action="HUNGRYME-editDelivery.php">
                                    <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($row['OID']); ?>">
                                    <button type="submit" style="background-color: yellow; color: black;" class="btn btn-warning" onmouseover="changeColor(this)">Click Order</button>
                                </form>
                            </td>
                            <script>
                                function changeColor(button) {
                                    button.style.backgroundColor = 'red';
                                    button.style.color = 'white';
                                    button.removeEventListener('mouseover', () => changeColor(button));
                                }
                            </script>
                            <td>
                                <!-- Button example: View Details -->
                                <form method="POST" action="HUNGRYME-DeliveryBoy.php">
                                    <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($row['OID']); ?>">
                                    <button type="submit" class="btn btn-primary">Complete Order</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">No records found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <br><br>

    <script>
        $(document).ready(function () {
            $(".btn-delivered").click(function () {
                alert("Delivery marked as Delivered");
            });

            $(".btn-nondelivered").click(function () {
                alert("Delivery marked as Not Delivered");
            });
        });

        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
        }
    </script>

    <footer>
        <div class="text-center text-lg-start" id="footer">
            <section class="d-flex justify-content-center justify-content-lg-between p-4 border-bottom">
                <div class="me-5 d-none d-lg-block">
                    <span>Check with us on social networks:</span>
                </div>
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