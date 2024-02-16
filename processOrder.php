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
        <br>
        <h1> <center>Thank you for your order! <center></h1> 
        <br>
        <h1> <center>Your Order has been placed! <center></h1> 
        <br>
        <a href="account.php"><center>Click here to view your order<center></a> 
        <br>
        <a href="index.php"><center>or Click here to go to Home page<center></a> 
        <br>


        <?php
        require_once("connectionDB.php");

        // Check if the user is logged in
        if (!isset($_SESSION['customerID'])) {
            // Redirect to login page if not logged in
            header('Location: login.php');
            exit;
        }

        $customerID = $_SESSION['customerID'];
        $totalPrice = $_SESSION['totalPrice']; // Check if it is passed from the form

        //Customer Address details
        //$address_line_1 = $_POST['address_line_1'];
        //$address_line_2 = $_POST['address_line_2'];
        //$city = $_POST['city'];
        //$post_code = $_POST['post_code'];
        //$country = $_POST['country'];

        //Customer Card details
        $card_name = $_POST['card_name'];
        $card_number = $_POST['card_number'];
        $card_expiry = $_POST['card_expiry'];
        $card_cvv = $_POST['card_cvv'];


        try {
            $db->beginTransaction();
        
            // Fetch the addressID for the logged-in customer
            $addressQuery = "SELECT addressID FROM address WHERE customerID = :customerID LIMIT 1";
            $addressStmt = $db->prepare($addressQuery);
            $addressStmt->execute(['customerID' => $customerID]);
            $addressResult = $addressStmt->fetch(PDO::FETCH_ASSOC);
        
            if (!$addressResult) {
                throw new Exception("No address found for the customer.");
            }
        
            $addressID = $addressResult['addressID'];
        
            // Insert order details into orders table including the addressID
            $orderSql = "INSERT INTO orders (customerID, addressID, total_amount, payment_details) VALUES (:customerID, :addressID, :total_amount, :payment_details)";
            $payment_details = "Card Name: $card_name, Card Number: $card_number, Expiry: $card_expiry, CVV: $card_cvv";
            
            $orderStmt = $db->prepare($orderSql);
            $orderStmt->execute([
                ':customerID' => $customerID,
                ':addressID' => $addressID,
                ':total_amount' => $totalPrice,
                ':payment_details' => $payment_details
            ]);
        
            $db->commit();
            
            // Success message or redirect
            echo "Order placed successfully. Your order will be shipped to your address.";

            
            // Clear the cart here code can go here
        
        } catch (Exception $e) {
            $db->rollBack();
            echo "Error placing order: " . $e->getMessage();
        }
        
        ?>


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
