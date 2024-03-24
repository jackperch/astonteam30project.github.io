<!DOCTYPE html>
<html lang="en">
    <head>
        <title>ACE GEAR</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="UTF-8">
        <link rel="stylesheet" href="CSS/styles.css">
        <link rel="stylesheet" href="CSS/productsDisplay.css">
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Sono&display=swap');
        </style>
        <!--<script src="/js/main.js"></script> -->
    </head>


    <body>
        <header>
            <div id="logo-container">
                <!-- logo image -->
                <img id="logo" src="Images/Logo-no-bg.png" alt="Logo">
                <h1 id="nav-bar-text">ACE GEAR</h1>
            </div>
            
            <nav>
                <a href="index.php">Home</a>
                <a href="productsDisplay.php">Products</a>
                <a href="about.php">About</a>
                <a href="contact.php">Contact</a>
                <?php 
                session_start();
                if (isset($_SESSION['username'])) {
                    echo "<a href='members-blog.php'>Blog</a>";
                    echo "<a href='account.php'>Account</a>";
                    echo "<a href='logout.php'>Logout</a>";
                } else {
                    echo "<a href='login.php'>Login</a>";
                }
                ?>
                <?php 
                if (isset($_SESSION['adminID'])) {
                    echo "<a href='Dashboard.php'>Dashboard</a>";
                }
                ?>
            </nav>
            <?php
            // Initialize the total quantity variable
            $totalQuantity = 0;

            // Check if the user is logged in
            if (isset($_SESSION['customerID'])) {
                require_once("connectionDB.php"); // Database connection path

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

        <?php
// categoryProducts.php
require_once("connectionDB.php");

// Initialize an empty array to hold the products
$products = [];

// Check if categoryId is posted
if (isset($_POST['categoryId'])) {
    $categoryId = $_POST['categoryId'];

    // Prepare the SQL statement
    $stmt = $db->prepare("SELECT * FROM products WHERE categoryID = ?");
    $stmt->execute([$categoryId]);
    
    // Fetch all the products
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
    echo "<h1 class='title'><center>Products<center></h1>";


    echo "<div class='product-grid'>";
    foreach ($products as $product) {
        echo "<div class='product'>";
        echo "<img src='Images/Product-Images/{$product['image']}' alt='{$product['product_name']}' width=80 height=80>";
        echo "<h3>{$product['product_name']}</h3>";
        echo "<p>{$product['description']}</p>";
        if ($product['stock'] == 0) {
            echo "<p>Out of stock</p>"; // Checks the stock availability
        } else{
            echo "<form class='add-to-cart-form' method='post' action='updatecart.php'>";
            echo "<input type='hidden' name='productID' value='{$product['productID']}'>";
            echo "<div class='price'>£{$product['price']}</div>";
            echo "<button class='add-to-cart' onclick='displayAlert()'>Add to cart!</button>";
            echo "</form>";
        }
        echo "</div>";
    }
    echo "</div>";

?>
    </body>
</html>