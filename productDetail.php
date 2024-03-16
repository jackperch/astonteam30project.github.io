<?php
require_once("connectionDB.php");
session_start();


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Details</title>
    <link rel="stylesheet" href="CSS/styles.css">
    <link rel="stylesheet" href="CSS/productDetail.css">
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
            <a href="productsDisplay.php">Products</a>
            <a href="about.php">About</a>
            <a href="contact.php">Contact</a>


            <?php 
                //session_start();
                if (isset($_SESSION['username'])) {
                    echo "<a href='members-blog.php'>Blog</a>";
                    echo "<a href='account.php'>Account</a>";
                    echo "<a href='logout.php'>Logout</a>";
                } else {
                    echo "<a href='login.php'>Login</a>";
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

<main>
    <div class="product-container">

        <?php
            require_once("connectionDB.php");

        if (isset($_GET['productID']) && is_numeric($_GET['productID'])) {
            $productID = $_GET['productID'];

            // Fetch product details
            $sql = "SELECT products.*, products.colour, products.size, c.name AS categoryName 
                    FROM products 
                    JOIN category c ON products.categoryID = c.categoryID
                    WHERE products.productID = ?";
                    
            $stmt = $db->prepare($sql);
            $stmt->execute([$productID]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($product) {
                echo "<div class='product-container'>"; // Container for the product
            
                // Product Image
                echo "<img src='Images/Product-Images/{$product['image']}' alt='{$product['product_name']}' class='product-image'>";
            
                // Product Details
                echo "<div class='product-details'>";
                echo "<h1 class='product-title'>{$product['product_name']}</h1>";
                echo "<p class='product-description'>Description: {$product['description']}</p>";
                echo "<p>Price: {$product['price']}</p>";
                echo "<p>Colour: {$product['colour']}</p>";
                echo "<p>Size: {$product['size']}</p>";
                echo "<p>Category: {$product['categoryName']}</p>";
                // Add to Cart Form
                echo "<form method='post' action='updatecart.php'>";
                echo "<input type='hidden' name='productID' value='{$productID}'>";
                echo "<input type='number' name='quantity' min='1' value='1' class='quantity-input'>";
                echo "<button type='submit' class='add-to-cart-btn'>Add to Cart</button>";
                echo "</form>";
                echo "<br>";
                echo "<br>";
                // Close product-details
                echo"<br>";
                //echo "<button type='submit' class='add-to-review-btn'>Add Review</button>";
                echo " <a href='addReview.php' class='signup-button'>";
                echo"<button>Add Review</button>";
                echo "</div>"; 

            // Close product-container
            echo "</div>";
            } 
            else {
                echo "Product not found.";
            }

               

               // Fetch product reviews    !!!!!! NEEDS DOING !!!!!!
            
        ?>
        </div>
    </main>

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
<?php
}
else {
    echo "Product not found.";
}