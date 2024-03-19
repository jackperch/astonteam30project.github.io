<?php
session_start();
include("connectionDB.php");

// Add New Order
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {
    $username = $_POST['username'];
    $product_name = $_POST['product_name'];
    $quantity = $_POST['quantity'];
    $order_date = $_POST['order_date'];
    $total_amount = $_POST['total_amount'];

    // Fetch customerID based on username
    $query = "SELECT customerID FROM customers WHERE username = :username";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $customerID = $result['customerID'];

        $query = "SELECT productID FROM products WHERE product_name = :product_name";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':product_name', $product_name);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $productID = $result['productID'];
        $query = "INSERT INTO orders (customerID, productID, quantity, price, order_date, total_amount, addressID) VALUES (:customerID, :productID, :quantity, :price, :order_date, :total_amount, :addressID)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':customerID', $customerID);
        $stmt->bindParam(':productID', $productID);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':order_date', $order_date);
        $stmt->bindParam(':total_amount', $total_amount);
        $stmt->bindParam(':addressID', $addressID);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            header("Location: editOrders.php");
            exit;
        } else {
            echo "Failed to add order.";
        }
    } else {
        echo "Customer not found.";
    }
}
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>ACE GEAR</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="UTF-8">
        <link rel="stylesheet" href="CSS/styles.css">
        <link rel="stylesheet" href="CSS/signup.css">
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Sono&display=swap');
        </style>
        <script src="/js/main.js"></script>
        <script src="signup.js"></script>

    </head>
    <body>
        <header>
            <div id="logo-container">
                <!-- logo image -->
                <img id="logo" src="Images/Logo-no-bg.png" alt="Logo">
                <h1 id="nav-bar-text">ACE GEAR</h1>
            </div>
            <div id="search-container">
                <input type="text" id="search-bar" placeholder="Search...">
                <button id="search-button">Search</button>
            </div>
            <nav>
                <a href="index.php">Home</a>
                <a href="products.php">Products</a>
                <a href="about.php">About</a>
                <a href="members-blog.php">Blog</a>
                <a href="contact.php">Contact</a>
                <?php 
                if (isset($_SESSION['adminID'])) {
                    echo "<a href='Dashboard.php'>Dashboard</a>";
                    echo "<a href='logout.php'>Logout</a>";
                }
                ?>
            </nav>
            <?php
            // Initialize the total quantity variable
            $totalQuantity = 0;

            // Check if the user is logged in
            if (isset($_SESSION['customerID'])) {
                require_once("connectionDB.php"); // Adjust this path as necessary

                // Fetch the total quantity of items in the user's cart
                $stmt = $db->prepare("SELECT SUM(quantity) AS totalQuantity FROM cart WHERE customerID = :customerID");
                $stmt->execute(['customerID' => $_SESSION['customerID']]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result && $result['totalQuantity'] > 0) {
                    $totalQuantity = $result['totalQuantity'];
                }
            }else{
                // Fetch the total quantity of items in the guest's cart
                  if (isset($_SESSION['guest_shopping_cart'])) {
                      $totalQuantity = array_sum($_SESSION['guest_shopping_cart']);}
              
          } 
            ?>
            <div id="cart-container">
                <!-- cart icon image with link to cart page -->
                <a href="cart.php">
                    <img id="cart-icon" src="Images/cart-no-bg.png" alt="Cart">
                    <span id="cart-count"><?php echo $totalQuantity; ?></span>
                </a>
            </div>
        </header>

    <div class="content-container">
        <div class="signup-container">
                <h2>Add New Order</h2>
    <form method="post">
    <input type="text" id="username" name="username"  placeholder="Username">
    <span id="first-name-error"></span>

    <input type="text" id="product_name" name="product_name"  placeholder="Product Name">
    <span id="first-name-error"></span>

    <input type="text" id="quantity" name="quantity"  placeholder="Quantity">
    <span id="first-name-error"></span>

    <input type="text" id="total_amount" name="total_amount"  placeholder="Total Amount">
    <span id="username-error"></span>
    
    <input type="date" id="order_date" name="order_date"  placeholder="Order Data">
    <span id="password-error"></span>

    <input name="submit" type="submit" value="Add new Order">
    <input type="reset" value="Clear">
    <input type="hidden" name="signupsubmitted" value="TRUE">
    <span id="signup-error"></span>
</form>

            </div>
        </div>

        <footer>
            <div class="footer-container">
                <div class="footer-links">
                    <a href="reviews.php">Reviews</a>
                    <a href="contact.php">Contact Us</a>
                    <a href="about.php">About Us</a>
                    <a href="privacy-policy.php">Privacy Policy</a>
                </div>
            </div>
        </footer>
        
    </body>

</html>