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

    <main>
        <h1>Checkout</h1>
        <div class="checkout-container">
            <form action="processOrder.php" method="post"> <!-- !!!!!!! Need to Replace with processing page script !!!!!! -->
                <section class="cart-summary">
                    <h2>Your Cart</h2>
                    <div id="cart-items">
                        <?php
                        require_once("connectionDB.php");
                        if(isset($_SESSION['customerID'])){ 
                        $customerID = $_SESSION['customerID']; // Using customerID is stored in session

                        // Fetch cart items for the user
                        $stmt = $db->prepare("SELECT c.productID, p.product_name, c.quantity, p.price FROM cart c JOIN products p ON c.productID = p.productID WHERE c.customerID = :customerID");
                        $stmt->execute(['customerID' => $customerID]);
                        $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        $totalPrice = 0;
                        foreach ($cartItems as $item) {
                            echo "<div class='cart-item'>";
                            $price = $item['price'];
                            $quantity = $item['quantity'];
                            $_total_item_price = $price * $quantity;
                            echo "<p>{$item['product_name']} (Quantity: {$item['quantity']}) - $" . ($item['quantity'] * $item['price']) . "</p>";
                            echo "</div>";
                            $totalPrice += ($item['quantity'] * $item['price']);
                        }
                        $_SESSION['totalPrice'] = $totalPrice;
                        echo "<p>Total Price: £$totalPrice</p>";
                    }else{
                        // For the guest users 
                        $totalPrice = 0;
                        if (isset($_SESSION['guest_shopping_cart'])) {
                           //Displays the items in the shopping cart array session varaible 
                            foreach ($_SESSION['guest_shopping_cart'] as $productID => $quantity) {
                                $stmt = $db->prepare("SELECT product_name, price FROM products WHERE productID = :productID");
                                $stmt->execute(['productID' => $productID]);
                                $product = $stmt->fetch(PDO::FETCH_ASSOC);
                                echo "<div class='cart-item'>";
                                echo "<p>{$product['product_name']} (Quantity: $quantity) - $" . ($quantity * $product['price']) . "</p>";
                                echo "</div>";
                                //Calculates the total price of the products 
                                $totalPrice += ($quantity * $product['price']);
                            }
                        }
                    }


                        // Initialize variables to hold address details
                        $house_number = $address_line_1 = $address_line_2 = $city = $state = $postal_code = $country = "";

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
                                $house_number = $address['house_number'];
                                $address_line_1 = $address['address_line_1'];
                                $address_line_2 = $address['address_line_2'];
                                $city = $address['city'];
                                $post_code = $address['post_code'];
                                $country = $address['country'];
                            }
                        }
                        ?>
                    </div>
                </section>

                <section class="shipping-information">
                    <h2>Shipping Information</h2>
                    <input type="text" name="house_number" placeholder="House Number" value="<?php echo htmlspecialchars($house_number); ?>" required>
                    <input type="text" name="address_line_1" placeholder="Address Line 1" value="<?php echo htmlspecialchars($address_line_1); ?>" required>
                    <input type="text" name="address_line_2" placeholder="Address Line 2" value="<?php echo htmlspecialchars($address_line_2); ?>">
                    <input type="text" name="city" placeholder="City" value="<?php echo htmlspecialchars($city); ?>" required>
                    <input type="text" name="postal_code" placeholder="Postal Code" value="<?php echo htmlspecialchars($post_code); ?>" required>
                    <input type="text" name="country" placeholder="Country" value="<?php echo htmlspecialchars($country); ?>" required>
                </section>

                <section class="payment-information">
                    <h2>Payment Information</h2>
                    <!-- Placeholder for payment information. Could use a payment gateway's integration code here. -->
                    <label for="card_type">Card Type: </label>
                    <select name="card_type" required>
                        <option value="visa">Visa</option>
                        <option value="mastercard">Mastercard</option>
                        <option value="amex">American Express</option>
                    </select>
                    <input type="text" name="card_name" placeholder="Name on Card" required>
                    <input type="text" name="card_number" placeholder="Card Number" required>
                    <input type="text" name="card_expiry" placeholder="Expiry Date (MM/YY)" required>
                    <input type="text" name="card_cvv" placeholder="CVV" required>
                </section>

                <section class="Billing-information">
                    <h2>Billing Information</h2>
                    <input type="text" name="house_number" placeholder="House Number" value="<?php echo htmlspecialchars($house_number); ?>" required>
                    <input type="text" name="address_line_1" placeholder="Address Line 1" value="<?php echo htmlspecialchars($address_line_1); ?>" required>
                    <input type="text" name="address_line_2" placeholder="Address Line 2" value="<?php echo htmlspecialchars($address_line_2); ?>">
                    <input type="text" name="city" placeholder="City" value="<?php echo htmlspecialchars($city); ?>" required>
                    <input type="text" name="post_code" placeholder="Post Code" value="<?php echo htmlspecialchars($post_code); ?>" required>
                    <input type="text" name="country" placeholder="Country" value="<?php echo htmlspecialchars($country); ?>" required>
                </section>

                <?php 
                echo "<form action='processOrder.php' method='post'>";
                echo "<input type='submit' value='Click here to place Order' class='button'>";
                echo "</form>";
                ?>
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
