 <!DOCTYPE html>
<html lang="en">
    <head>
        <title>ACE GEAR</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="UTF-8">
        <link rel="stylesheet" href="CSS/products.css">
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

<?php

require_once("connectionDB.php");
function fetchProducts() {
    global $db;

    try {
         // database connection code 
         require_once("connectionDB.php");
        // SQL query
        $sql = "
        SELECT
            pl.productListingID,
            pl.image,
            pl.productName,
            pl.price,
            pl.description AS productDescription,
            pli.color,
            pli.size,
            c.name AS categoryName
        FROM
            ProductListing pl
        JOIN
            ProductListingInfo pli ON pl.productListingID = pli.productListingID
        JOIN
            Category c ON pl.categoryID = c.categoryID;
        ";

        // Prepare and execute the query
        $SQLEXECUTE = $db->prepare($sql);
        $SQLEXECUTE->execute();

        // Fetch results
        $products = $SQLEXECUTE->fetchAll(PDO::FETCH_ASSOC);

        // Close the database connection
        //$db = null;
        return $products; // Return the fetched product

    }//Catches the problem
      catch(PDOException $ex) {
        echo("Failed to connect to the database.<br>");
        echo($ex->getMessage());
        exit;
      } 
     
}

$allOfTheProducts = fetchProducts();
?>

    <?php
    // Display fetched product details
    //The allOfTheProducts is an array of all the products  that  is going to be iterated through and the product is the current product that is being iterated through
    //Display each product
    foreach ($allOfTheProducts as $product) {
        echo "<div class='product-container'>";
        echo "<img src='Images/{$product['image']}' alt='{$product['productName']}' width=50 height=50>";
        echo "<h2>{$product['productName']}</h2>";
        echo "<p>Price: {$product['price']}</p>";
        echo "<p>Description: {$product['productDescription']}</p>";
        echo "<p>Colour: {$product['color']}</p>";
        echo "<p>Size: {$product['size']}</p>";
        echo "<p>Category: {$product['categoryName']}</p>";

        //a form with a button to add the product to the cart
        echo "<form method='post' action='addtocart.php'>";
        echo "<input type='hidden' name='productListingID' value='{$product['productListingID']}'>";
        echo "<input type='submit' value='Add to Cart'>";
        echo "</form>";

        echo "</div>";
        echo "<hr>";
    }
    ?>

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
        
        <!-- <script>
        var message = "<?php session_start(); if(isset($_SESSION['alert_message'])) { echo $_SESSION['alert_message']; unset($_SESSION['alert_message']); } ?>";
        if(message !== "") {
            alert(message);
        }
    </script> -->

</body>
</html>

