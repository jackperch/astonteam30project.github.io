<?php
// database connection code 
//require_once("connectionDB.php");



function getProducts() {
    // Replace this with your actual query to retrieve products from the database
    // For example:
    
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "acegear";


    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // SQL query
        $sql = "
        SELECT
            pl.productListingID,
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
            Category c ON pl.ctategoryID = c.categoryID;
        ";

        // Prepare and execute the query
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // Fetch results
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $products; // Return the fetched product

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    // Close the database connection
    $conn = null;
}

    // $query = "SELECT * FROM ProductListing";
    // $result = $db->query($query);
    // return $result->fetchAll(PDO::FETCH_ASSOC);

    // For demonstration purposes, a sample array is returned
   // return [
   //     ['productName' => 'Product 1', 'price' => 19.99, 'description' => 'Description 1'],
   //     ['productName' => 'Product 2', 'price' => 29.99, 'description' => 'Description 2'],
        // Add more products as needed
   // ];


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


        



    <?php
    // Display fetched product details
    foreach ($products as $product) {
        echo "<div>";
        echo "<h2>{$product['productName']}</h2>";
        echo "<p>Price: {$product['price']}</p>";
        echo "<p>Description: {$product['productDescription']}</p>";
        echo "<p>Color: {$product['color']}</p>";
        echo "<p>Size: {$product['size']}</p>";
        echo "<p>Category: {$product['categoryName']}</p>";
        echo "</div>";
        echo "<hr>";
    }
    ?>



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

