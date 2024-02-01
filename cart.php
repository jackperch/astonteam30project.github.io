<!DOCTYPE html>
<html lang="en">
    <head>
        <title>ACE GEAR</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="UTF-8">
        <link rel="stylesheet" href="CSS/cart.css">
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

       <!-- <h1> Log in or Sign up now to get these exclusive benefits.</h1>
       <button type="button" class="btn btn-primary btn-lg" href="login.php">Log In</button>
<button type="button" class="btn btn-secondary btn-lg" href="signup.php">Sign Up</button> -->


<main>
    <h1>Your Shopping Cart</h1>
    <div id="cart-items">

        <?php
       session_start();
        require_once("connectionDB.php");

      //  if (!isset($_SESSION['customerID'])) {
      //      // Redirect to login page or handle the case where customerID is not set
      //      header("Location: login.php");
      //      exit;
      //  }
        
        $customerID = $_SESSION['customerID'];

        // Function to fetch cart items
        function fetchCartItems() {
            global $db;
            $customerID = $_SESSION['customerID']; // checking to see if user is logged by comparing customerID to value stored in session

            try {
                $sql = "
                SELECT
                    p.productName,
                    p.price,
                    b.quantity,
                    p.image
                FROM
                    basket b
                JOIN
                    productlisting p ON b.productListingID = p.productListingID
                WHERE
                    b.customerID = :customerID;
                ";

                $stmt = $db->prepare($sql);
                $stmt->bindParam(':customerID', $customerID, PDO::PARAM_INT);
                $stmt->execute();

                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch(PDOException $ex) {
                echo "Error fetching cart items: " . $ex->getMessage();
                exit;
            }
        }

        $cartItems = fetchCartItems();

        foreach ($cartItems as $item) {
            echo "<div class='cart-item'>";
            echo "<img src='Images/Product-Images/{$item['image']}' alt='{$item['productName']}' width=50 height=50>";
            echo "<h2>{$item['productName']}</h2>";
            echo "<p>Price: {$item['price']}</p>";
            echo "<p>Quantity: {$item['quantity']}</p>";
            // Form for updating quantity or removing item
            echo "<form method='post' action='updateCart.php'>";
            echo "<input type='hidden' name='productListingID' value='{$item['productListingID']}'>";
            echo "<input type='number' name='quantity' value='{$item['quantity']}' min='1'>";
            echo "<button type='submit' name='action' value='update'>Update</button>";
            echo "<button type='submit' name='action' value='remove'>Remove</button>";
            echo "</form>";
            echo "</div>";
        }
        ?>

    </div>
</main>