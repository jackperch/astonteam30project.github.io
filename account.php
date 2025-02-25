<?php
    session_start();
    // The user is already logged in if they can see this section in the nav bar, so login validation is not required here

    require_once("connectionDB.php"); // database connection file
   
    $customerData = array(); // Initialize the variable
    $addressData=array();

    if (isset($_SESSION['customerID'])) {
        $customerID = $_SESSION['customerID'];
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
    }elseif(isset($_SESSION['adminID'])){
        $adminID = $_SESSION['adminID'];
        try {
            $query = $db->prepare('SELECT * FROM admin WHERE adminID = ?');
            $success = $query->execute([$adminID]);

            // Check if the query was successful
            if ($success) {
                $rowCount = $query->rowCount();

                // Check if any rows were returned
                if ($rowCount > 0) {
                    // Fetch customer data
                    $customerData = $query->fetch(PDO::FETCH_ASSOC);

                    // Add similar lines for other details you want to display
                } else {
                    echo "No matching admin found.";
                }
            } else {
                echo "Error executing the query.";
            }
        } catch (PDOException $ex) {
            echo("Failed to fetch customer data.<br>");
            echo($ex->getMessage());
            exit;
        }
    } else {
        header("Location: error.php?error=no_session");
        exit;
        ; 
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>ACE GEAR</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="UTF-8">
        <link rel="stylesheet" href="CSS/styles.css">
        <link rel="stylesheet" href="CSS/account.css">
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Sono&display=swap');
        </style>
        <script src="/js/main.js"></script>
        <link rel="stylesheet" href="CSS/styles.css">
        <link rel="stylesheet" href="CSS/account.css">
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
            <a href="members-blog.php">Blog</a>
            <a href="contact.php">Contact</a>
            <?php 
                if (isset($_SESSION['adminID'])) {
                    echo "<a href='Dashboard.php'>Dashboard</a>";
                    //echo "<a href='logout.php'>Logout</a>";
                }
                ?>
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
        }elseif(isset($_SESSION['adminID'])){
            require_once("connectionDB.php"); // Adjust this path as necessary
            $smt=$db->prepare("SELECT SUM(quantity) AS totalQuantity FROM cart WHERE  adminID = :adminID");
            $smt->execute(['adminID' => $_SESSION['adminID']]);
            $result = $smt->fetch(PDO::FETCH_ASSOC);
            if ($result && $result['totalQuantity'] > 0) {
                $totalQuantity = $result['totalQuantity'];
            }
        }else{
            echo "Error: customerID not found or adminID not found.";
            exit;
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
            <a href="editAccount.php">Edit Account Information</a>
        </section>

        <section class="my-orders">
            <h3>My Orders</h3>
            <ul>
                <?php
                //echo "Not implemented yet."
                //foreach ($orders as $order) {
                   // echo "<li><a href='order.php?id=" . $order['id'] . "'>Order #" . $order['id'] . "</a></li>";
                //}
                
                
                //For customer  users
                if (isset($_SESSION['customerID']))
                {
                    try 
                    {
                      //retrieves orderID from the orders table
                        $customerID = $_SESSION['customerID'];
                        $query = $db->prepare('SELECT * FROM orders WHERE customerID = ?');
                        $query->execute([$customerID]);
                        $retrievedOrders = $query->fetchAll(PDO::FETCH_ASSOC);
                        //var_dump($retrievedOrders);
                             //iterates through the retrieved orders fromt he tabler and displays ID, date and total amount
                             if (empty($retrievedOrders))
                             {
                                echo "<li> No orders found</li>";
                             }
                             
                           
                             foreach ($retrievedOrders as $order) 
                            {
                                echo "<li>Order ID: {$order['orderID']}</li>";
                                echo "<li>Order Date: {$order['order_date']}</li>";
                                echo "<li>Total amount: £{$order['total_amount']}</li>";
                                echo "<li>Order Status: {$order['order_status']}</li>";
                                
                                //retrieves productID, quantity and total price from the orders_products table
                                $retrieveProductQuery = $db->prepare('SELECT * FROM orders_products WHERE orderID = ?');
                                $retrieveProductQuery->execute([$order['orderID']]);
                                $order_products = $retrieveProductQuery->fetchAll(PDO::FETCH_ASSOC);
                             
                                //if it's not empty
                                if (!empty($order_products)) 
                                {
                                    // If products are  presenty it will loop through and display their details
                                    foreach ($order_products as $order_product) 
                                    {
                                        // Fetch product data
                                        $retrieveProductSQL = $db->prepare('SELECT * FROM products WHERE productID = ?');
                                        $retrieveProductSQL->execute([$order_product['productID']]);
                                        $product = $retrieveProductSQL->fetch(PDO::FETCH_ASSOC);
                            
                                        
                                        // Displays product details
                                        if ($product) 
                                        {
                                            echo "<div class='product-container'>";
                                            echo "{$product['product_name']}<br>";
                                            echo "<img src='Images/Product-Images/{$product['image']}' alt='{$product['product_name']}' width=80 height=80>";
                                            echo "<div class='product-details'>";
                                            echo "<p>Colour: {$product['colour']}</p>";
                                            echo "<p>Size: {$product['size']}</p>";
                                            echo "</div>";
                                            echo "<div class='product-description'>";
                                            echo "<p>Description: {$product['description']}</p>";
                                            echo "</div>";
                                            echo "</div>";
                                            echo "<hr class='hr-line'>";
                                        }
                                    }
                                } else 
                                {
                                    // If no products are found for the order, display a message
                                    echo "<li>No products found for this order.</li>";
                                }
                         
                            } 
                    }catch (PDOException $exception) 
                        {
                            echo "Error: " . $exception->getMessage();
                        }
                    //For admin users
                } elseif (isset($_SESSION['adminID'])) 
                {
                    try 
                    {
                        $adminID = $_SESSION['adminID'];
                        //retrieves orderID from the orders table
                        $query = $db->prepare('SELECT * FROM orders WHERE adminID = ?');
                        $query->execute([$adminID]);
                        $retrievedOrders = $query->fetchAll(PDO::FETCH_ASSOC);
                        //iterates through the retrieved orders fromt he tabler and displays ID, date and total amount
                        if (empty($retrievedOrders))
                             {
                                echo "<li> No orders found</li>";
                             }
                     
                        foreach ($retrievedOrders as $order) 
                         {
                            echo "<li>Order ID: {$order['orderID']}</li>";
                            echo "<li>Order Date: {$order['order_date']}</li>";
                            echo "<li>Total amount: {$order['total_amount']}</li>";
                            echo "<li>Order Status: {$order['order_status']}</li>";


                          //retrieves productID, quantity and total price from the orders_products table
                            $retrieveProductQuery = $db->prepare('SELECT * FROM orders_products WHERE orderID = ?');
                            $retrieveProductQuery->execute([$order['orderID']]);
                            $order_products = $retrieveProductQuery->fetchAll(PDO::FETCH_ASSOC);
                            
                            //if it's not empty
                            if (!empty($order_products)) 
                            {
                                // If products are  presenty it will loop through and display their details
                                foreach ($order_products as $order_product) 
                                {
                                    // Fetch product data
                                    $retrieveProductSQL = $db->prepare('SELECT * FROM products WHERE productID = ?');
                                    $retrieveProductSQL->execute([$order_product['productID']]);
                                    $product = $retrieveProductSQL->fetch(PDO::FETCH_ASSOC);
                        
                                    // Displays product details
                                    if ($product) 
                                    {
                                        echo "<div class='product-container'>";
                                        echo "{$product['product_name']}<br>";
                                        echo "<img src='Images/Product-Images/{$product['image']}' alt='{$product['product_name']}' width=80 height=80>";
                                        echo "<div class='product-details'>";
                                        echo "<p>Colour: {$product['colour']}</p>";
                                        echo "<p>Size: {$product['size']}</p>";
                                        echo "</div>";
                                        echo "<div class='product-description'>";
                                        echo "<p>Description: {$product['description']}</p>";
                                        echo "</div>";
                                        echo "</div>";
                                        echo "<hr class='hr-line'>";
                                    }
                                }
                            } else // no products found
                            {
                                echo "<li>No products found for this order.</li>";
                            }
                    
                        } 
                        //Catchers any database related errors
                    }catch (PDOException $exception) 
                        {
                            echo "Failed " ;
                            echo  $exception->getMessage();
                         }
                }
                ?>
            </ul>
        </section>

    </div>


    
</body>
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
</html>