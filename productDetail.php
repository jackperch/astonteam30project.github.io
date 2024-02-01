<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Details</title>
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
            <a href="products.php">Products</a>
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
        </nav>
        <div id="cart-container">
            <!-- cart icon image with link to cart page -->
            <a href="cart.php">
                <img id="cart-icon" src="Images/cart-no-bg.png" alt="Cart">
                <span id="cart-count">0</span>
            </a>
        </div>
    </header>

<main>
    <div class="product-container">
        <?php
        require_once("connectionDB.php");

        if (isset($_GET['productListingID']) && is_numeric($_GET['productListingID'])) {
            $productListingID = $_GET['productListingID'];

            // Fetch product details
            $sql = "SELECT pl.*, pli.color, pli.size, c.name AS categoryName 
                    FROM ProductListing pl
                    JOIN ProductListingInfo pli ON pl.productListingID = pli.productListingID
                    JOIN Category c ON pl.categoryID = c.categoryID
                    WHERE pl.productListingID = ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$productListingID]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($product) {
                echo "<div class='product-container'>"; // Container for the product
            
                // Product Image
                echo "<img src='Images/Product-Images/{$product['image']}' alt='{$product['productName']}' class='product-image'>";
            
                // Product Details
                echo "<div class='product-details'>";
                echo "<h1 class='product-title'>{$product['productName']}</h1>";
                echo "<p class='product-description'>Description: {$product['description']}</p>";
                echo "<p>Price: {$product['price']}</p>";
                echo "<p>Colour: {$product['color']}</p>";
                echo "<p>Size: {$product['size']}</p>";
                echo "<p>Category: {$product['categoryName']}</p>";
                echo "<button class='add-to-cart-btn'>Add to Cart</button>"; // Example add-to-cart button
                echo "</div>"; // Close product-details
            
                echo "</div>"; // Close product-container
            } 
            else {
                echo "Product not found.";
            }
        }
            
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

