<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout - ACE GEAR</title>
    <link rel="stylesheet" href="CSS/styles.css">
    <link rel="stylesheet" href="CSS/checkout.css"> <!-- need to create this --> 
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
                  // Fetch the total quantity of items in the guest's cart
                    if (isset($_SESSION['guest_shopping_cart'])) {
                        unset($_SESSION['guest_shopping_cart']);
                        $totalQuantity = array_sum($_SESSION['guest_shopping_cart']);}
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
        <?php
            echo "<div class= container>";
            echo "<h1> <center>Thank you for your order! <center></h1> ";
            echo "<br>";
            echo "Order placed successfully. Your order will be shipped to your address.";
            echo "<br>";
            echo "Your order total is: £" . $totalcost;
            echo "<br>";
            echo "Your payment details have been saved.";
            echo "<br>";
            echo "Your order number is: " .  $orderID;
            echo "<br>";
            echo "You will receive an email confirmation shortly.";
            echo "<br>";
            echo "<br>";
            echo "<br>";
            echo "<br>";
            echo "<a href='account.php'><center>Click here to view your orders<center></a>";
            echo "<br>";
            echo "or";
            echo "<br>";
            echo "<a href='index.php'><center>Click here to go to Home page<center></a>";
            echo "<br>";
        echo "</div>";
        ?>

                   
        
    </main>
    <br>
    <footer>
        <!-- Footer content -->
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
