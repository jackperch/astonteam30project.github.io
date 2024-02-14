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
        <h1>Checkout</h1>
        <div class="checkout-container">
            <form action="processOrder.php" method="post"> <!-- !!!!!!! Need to Replace with processing page script !!!!!! -->
                <section class="cart-summary">
                    <h2>Your Cart</h2>
                    <div id="cart-items">
                        <?php
                        require_once("connectionDB.php");
                        $customerID = $_SESSION['customerID']; // Using customerID is stored in session

                        // Fetch cart items for the user
                        $stmt = $db->prepare("SELECT c.productID, p.product_name, c.quantity, p.price FROM cart c JOIN products p ON c.productID = p.productID WHERE c.customerID = :customerID");
                        $stmt->execute(['customerID' => $customerID]);
                        $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        $totalPrice = 0;
                        foreach ($cartItems as $item) {
                            echo "<div class='cart-item'>";
                            echo "<p>{$item['product_name']} (Quantity: {$item['quantity']}) - $" . ($item['quantity'] * $item['price']) . "</p>";
                            echo "</div>";
                            $totalPrice += ($item['quantity'] * $item['price']);
                        }
                        echo "<p>Total Price: $$totalPrice</p>";


                        // Initialize variables to hold address details
                        $address_line_1 = $address_line_2 = $city = $state = $postal_code = $country = "";

                        // Check if user is logged in
                        if (isset($_SESSION['customerID'])) {
                            $customerID = $_SESSION['customerID'];
                            
                            // Fetch address details for the logged-in user
                            $sql = "SELECT * FROM address WHERE customerID = :customerID LIMIT 1"; // Adjust table/column names as per your database schema
                            $stmt = $db->prepare($sql);
                            $stmt->execute(['customerID' => $customerID]);
                            $address = $stmt->fetch(PDO::FETCH_ASSOC);

                            if ($address) {
                                // Set variables if address exists
                                $address_line_1 = $address['address_line_1'];
                                $address_line_2 = $address['address_line_2'];
                                $city = $address['city'];
                                $state = $address['state']; // Assume there's a state column; adjust as necessary
                                $postal_code = $address['postal_code'];
                                $country = $address['country'];
                            }
                        }
                        ?>
                    </div>
                </section>

                <section class="shipping-information">
                    <h2>Shipping Information</h2>
                    <input type="text" name="address_line_1" placeholder="Address Line 1" value="<?php echo htmlspecialchars($address_line_1); ?>" required>
                    <input type="text" name="address_line_2" placeholder="Address Line 2" value="<?php echo htmlspecialchars($address_line_2); ?>">
                    <input type="text" name="city" placeholder="City" value="<?php echo htmlspecialchars($city); ?>" required>
                    <input type="text" name="state" placeholder="State" value="<?php echo htmlspecialchars($state); ?>" required>
                    <input type="text" name="postal_code" placeholder="Postal Code" value="<?php echo htmlspecialchars($postal_code); ?>" required>
                    <input type="text" name="country" placeholder="Country" value="<?php echo htmlspecialchars($country); ?>" required>
                </section>

                <section class="payment-information">
                    <h2>Payment Information</h2>
                    <!-- Placeholder for payment information. Could use a payment gateway's integration code here. -->
                    <input type="text" name="card_name" placeholder="Name on Card" required>
                    <input type="text" name="card_number" placeholder="Card Number" required>
                    <input type="text" name="card_expiry" placeholder="Expiry Date (MM/YY)" required>
                    <input type="text" name="card_cvv" placeholder="CVV" required>
                </section>

                <input type="submit" value="Place Order">
            </form>
        </div>
    </main>

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
