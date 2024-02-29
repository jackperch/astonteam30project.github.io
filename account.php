<?php
    session_start();
    // The user is already logged in if they can see this section in the nav bar, so login validation is not required here

    require_once("connectionDB.php"); // database connection file
   
    $customerData = array(); // Initialize the variable
    $addressData=array();

    if (isset($_SESSION['customerID'])) {
        $customerID = $_SESSION['customerID'];
    }
    
    try {
        $query = $db->prepare('SELECT * FROM customers WHERE customerID = ?');
        $success = $query->execute([$customerID]);

        // Check if the query was successful
        if ($success) {
            $rowCount = $query->rowCount();

            // Check if any rows were returned
            if ($rowCount > 0) {
                // Fetch customer data
                $customerData = $query->fetch(PDO::FETCH_ASSOC);

                // Add similar lines for other details you want to display
            } else {
                echo "No matching customer found.";
            }
        } else {
            echo "Error executing the query.";
        }
    } catch (PDOException $ex) {
        echo("Failed to fetch customer data.<br>");
        echo($ex->getMessage());
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>ACE GEAR</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="UTF-8">
        <link rel="stylesheet" href="CSS/about.css">
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
            <a href="index.php">Home</a>
            <a href="products.php">Products</a>
            <a href="about.php">About</a>
            <a href="members-blog.php">Blog</a>
            <a href="contact.php">Contact</a>
            <a href="logout.php">Logout</a>
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



    <h1>My Account</h1>


    <div class="my-account-container">
       <!--  <h2>My Account</h2> -->

        <section class="my-details">
            <h3>My Details</h3>
            <ul>
                <li><strong>Username:</strong> <?php echo $customerData['username']; ?></li>
                <li><strong>First Name:</strong> <?php echo $customerData['first_name']; ?></li>
                <li><strong>Last Name:</strong> <?php echo $customerData['last_name']; ?></li>
                <li><strong>Email:</strong> <?php echo $customerData['email']; ?></li>
            </ul>
            <a href="editAccount.php">Edit Account</a>
        </section>

        <section class="my-orders">
            <h3>My Orders</h3>
            <ul>
                <?php
                //echo "Not implemented yet."
                //foreach ($orders as $order) {
                   // echo "<li><a href='order.php?id=" . $order['id'] . "'>Order #" . $order['id'] . "</a></li>";
                //}
                 try
                 {
                    $query = $db->prepare('SELECT * FROM orders WHERE customerID = ?');
                    $query->execute([$customerID]);
                    $customerID = $_SESSION['customerID'];
                    $rowCount = $query->rowCount();
                    // Check if any rows were returned
                    if ($rowCount > 0) 
                    {
                        // Fetch customer data
                        $orders = $query->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($orders as $order)
                        {
                            echo  "<p>Order ID:  {$order['orderID']};</p>";
                            echo "<p>Quantity: {$order['quantity']};</p>";
                            echo "<p>Price of Product: {$order['price_of_product']};</p>";
                            echo "<p>Order Date: {$order['order_date']};</p>";
                            echo "<p>Total Amount: {$order['total_amount']};</p>";

                            // Fetch product data
                            $retrivedPoductID=$order['productID'];
                            $retrieveProduct = $db->prepare('SELECT * FROM products WHERE productID = ?');
                            $retrieveProduct->execute([$retrivedPoductID]);
                            $products = $retrieveProduct->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($products as $product) 
                            {
                                echo "<p>Product Name: {$product['product_name']};</p>";
                                echo "<p>Price of Product: {$order['price_of_product']};</p>";
                          
                            }
                                // echo "<p>Colour: {$product['colour']}</p>";

                        }
                    } else 
                        {
                        echo "No matching orders found.";
                        }
                    }catch (PDOException $ex) 
                    {
                        echo("Failed to fetch order data.<br>");
                        echo($ex->getMessage());
                        exit;
                }
            
                ?>
            </ul>
        </section>

    </div>


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