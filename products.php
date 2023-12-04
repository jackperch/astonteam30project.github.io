<?php
// Include your database connection code here
// For example, if your connection file is named connectionDB.php, include it like this:
// require_once("connectionDB.php");

// Assuming you have a function to fetch products from the database
function getProducts() {
    // Replace this with your actual query to retrieve products from the database
    // For example:
    // $query = "SELECT * FROM ProductListing";
    // $result = $db->query($query);
    // return $result->fetchAll(PDO::FETCH_ASSOC);

    // For demonstration purposes, a sample array is returned
    return [
        ['productName' => 'Product 1', 'price' => 19.99, 'description' => 'Description 1'],
        ['productName' => 'Product 2', 'price' => 29.99, 'description' => 'Description 2'],
        // Add more products as needed
    ];
}

$products = getProducts();
?>











<!DOCTYPE html>
<html lang="en">
    <head>
        <title>ACE GEAR</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="UTF-8">
        <link rel="stylesheet" href="CSS/styles.css">
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Sono&display=swap');
        </style>
        <script src="/js/main.js"></script>
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
            <a href="index.html">Home</a>
            <a href="products.php">Products</a>
            <a href="about.html">About</a>
            <a href="contact.html">Contact</a>
            <?php 
                session_start();
                if (isset($_SESSION['username'])) {
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


    <h2>Products</h2>
        <p>Product 1</p>
        <p>Product 2</p>
        <p>Product 3</p>


        <div class="product-container">
        <?php foreach ($products as $product): ?>
            <div class="product">
                <h2><?php echo $product['productName']; ?></h2>
                <p><strong>Price:</strong> $<?php echo $product['price']; ?></p>
                <p><?php echo $product['description']; ?></p>
                <!-- Add more details or styling as needed -->
            </div>
        <?php endforeach; ?>
    </div>



        <footer>
            <div class="footer-container">
                <div class="footer-links">
                    <a href="reviews.html">Reviews</a>
                    <a href="contact.html">Contact Us</a>
                    <a href="about.html">About Us</a>
                    <a href="privacy-policy.html">Privacy Policy</a>
                </div>
            </div>
        </footer>
</body>
</html>

