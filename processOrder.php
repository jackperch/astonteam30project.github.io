<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout - ACE GEAR</title>
    <link rel="stylesheet" href="CSS/styles.css">
    <link rel="stylesheet" href="CSS/processOrder.css"> <!-- need to create this --> 
</head>
<body>
    <header>
        <div id="logo-container">
            <!-- logo image -->
            <img id="logo" src="Images/Logo-no-bg.png" alt="Logo">
            <h1 id="nav-bar-text">ACE GEAR</h1>
        </div>
        
        </div>
        <nav>
            <a href="index.php">Home</a>
            <a href="productsDisplay.php">Products</a>
            <a href="about.php">About</a>
            <a href="contact.php">Contact</a>


            <?php 
                session_start();
                if (isset($_SESSION['customerID'])) {
                    echo "<a href='members-blog.php'>Blog</a>";
                    echo "<a href='account.php'>Account</a>";
                    echo "<a href='logout.php'>Logout</a>";
                } elseif (isset($_SESSION['adminID'])) 
                {
        
                    echo "<a href='Dashboard.php'>Dashboard</a>";
                    echo "<a href='account.php'>Account</a>";
                    echo "<a href='logout.php'>Logout</a>";

                }else
                {
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
        }elseif(isset($_SESSION['adminID'])){
            require_once("connectionDB.php"); // Adjust this path as necessary
            $smt=$db->prepare("SELECT SUM(quantity) AS totalQuantity FROM cart WHERE  adminID = :adminID");
            $smt->execute(['adminID' => $_SESSION['adminID']]);
            $result = $smt->fetch(PDO::FETCH_ASSOC);
            if ($result && $result['totalQuantity'] > 0) {
                $totalQuantity = $result['totalQuantity'];
            }
        }else{
            echo "Erroro retriving  the user's  ID";
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

    <main>


        <?php
        require_once("connectionDB.php");
      //OLD CODE **************
        // // Check if the user is logged in
        // if (!isset($_SESSION['customerID'])) {
        //     // Redirect to login page if not logged in
        //     header('Location: login.php');
        //     exit;
        // }elseif(isset($_SESSION['adminID'])){
        //     // Redirect to login page if not logged in
        //     header('Location: adminLogin.php');
        //     exit;
        // }

        // $customerID = $_SESSION['customerID'];
        // $totalPrice = $_SESSION['totalPrice']; // Check if it is passed from the form
        // OLD CODE **************

        if (isset($_SESSION['customerID'])) {
            $customerID = $_SESSION['customerID'];
            $totalPrice = $_SESSION['totalPrice']; // Check if it is passed from the form

        // Customer Address details
        $address_line_1 = $_POST['address_line_1'];
        $address_line_2 = $_POST['address_line_2'];
        $city = $_POST['city'];
        $post_code = $_POST['post_code'];
        $country = $_POST['country'];

        //Customer Card details
        $card_type = $_POST['card_type'];
        $card_name = $_POST['card_name'];
        $card_number = $_POST['card_number'];
        $card_expiry = $_POST['card_expiry'];
        $card_cvv = $_POST['card_cvv'];

        // Retrievin  order details needed to be inserted for the  order, orders_products table 
        //$onGoing = 1;


        try {
            $db->beginTransaction();
            

             // Insert Payment Information
            $paymentSql = "INSERT INTO payment_information (customerID, card_type, card_number, expiry_date, CVV) VALUES (:customerID, :card_type, :card_number, :expiry_date, :CVV)";
            $paymentStmt = $db->prepare($paymentSql);
            $paymentStmt->execute([
                ':customerID' => $customerID,
                ':card_type' => $card_type,
                ':card_number' => $card_number,
                ':expiry_date' => $card_expiry,
                ':CVV' => $card_cvv
            ]);
            $paymentInfoID = $db->lastInsertId();



            // Fetch the addressID for the logged-in customer
            $addressQuery = "SELECT addressID FROM address WHERE customerID = :customerID LIMIT 1";
            $addressStmt = $db->prepare($addressQuery);
            $addressStmt->execute(['customerID' => $customerID]);
            $addressResult = $addressStmt->fetch(PDO::FETCH_ASSOC);
        
            if (!$addressResult) {
                throw new Exception("No address found for the customer.");
            }
        
            $addressID = $addressResult['addressID'];
            $order_status = "Processing";
            // Insert order details into orders table including the addressID
            $insertOrderQuery = "INSERT INTO orders (customerID, order_date, total_amount, addressID, paymentInfoID, order_status) VALUES (:customerID, CURDATE(), :total_amount, :addressID, :paymentInfoID, :order_status)";
            $stmtInsertOrder = $db->prepare($insertOrderQuery);
            $stmtInsertOrder->bindParam(':customerID', $customerID);
            $stmtInsertOrder->bindParam(':total_amount', $totalPrice);
            $stmtInsertOrder->bindParam(':addressID', $addressID);
            $stmtInsertOrder->bindParam(':paymentInfoID', $paymentInfoID);
            //$stmtInsertOrder->bindParam(':onGoing', $onGoing);
            $stmtInsertOrder->bindParam(':order_status', $order_status);

            $stmtInsertOrder->execute();


            // After the successful insertion of the order into the orders table
            $orderID = $db->lastInsertId();

            // Fetch all items from the user's cart
            $cartItemsStmt = $db->prepare("SELECT productID, quantity FROM cart WHERE customerID = :customerID");
            $cartItemsStmt->execute(['customerID' => $customerID]);
            $cartItems = $cartItemsStmt->fetchAll(PDO::FETCH_ASSOC);

            // For each cart item, insert into the orders_products table and update product stock
            foreach ($cartItems as $cartItem) {
                $productID = $cartItem['productID'];
                $quantity = $cartItem['quantity'];

                // Retrieve the price of the product
                $productStmt = $db->prepare("SELECT price FROM products WHERE productID = :productID");
                $productStmt->execute(['productID' => $productID]);
                $product = $productStmt->fetch(PDO::FETCH_ASSOC);
                $totalItemPrice = $product['price'] * $quantity;

                // Insert order-product association
                $insertOrderProductsStmt = $db->prepare("INSERT INTO orders_products (orderID, productID, quantity, total_price) VALUES (:orderID, :productID, :quantity, :total_price)");
                $insertOrderProductsStmt->execute([
                    'orderID' => $orderID,
                    'productID' => $productID,
                    'quantity' => $quantity,
                    'total_price' => $totalItemPrice
                ]);

                // Update product stock
                $updateStockStmt = $db->prepare("UPDATE products SET stock = stock - :quantity WHERE productID = :productID");
                $updateStockStmt->execute([
                    'quantity' => $quantity,
                    'productID' => $productID
                ]);
            }

            // Clear the user's cart
            $clearCartStmt = $db->prepare("DELETE FROM cart WHERE customerID = :customerID");
            $clearCartStmt->execute(['customerID' => $customerID]);

       

            // Redirect to a confirmation page or display the success message
           // echo "Order placed successfully. Your order number is: " . $orderID; Testing
            // A proper redirect after successful order placement is highly recommended
            // header('Location: orderConfirmation.php?orderID=' . $orderID);
            // exit;

            // Retrieve orderID
            // $retrieveOrderIDQuery = "SELECT orderID FROM orders WHERE customerID=:customerID  AND order_date=CURDATE()  AND total_amount=:total_amount AND addressID=:addressID AND paymentInfoID=:paymentInfoID AND order_completed=:onGoing";
            // $stmtRetrieveOrderID = $db->prepare($retrieveOrderIDQuery);
            // $stmtRetrieveOrderID->bindParam(':customerID', $customerID);
            // $stmtRetrieveOrderID->bindParam(':total_amount', $totalPrice);
            // $stmtRetrieveOrderID->bindParam(':addressID', $addressID);
            // $stmtRetrieveOrderID->bindParam(':paymentInfoID', $paymentInfoID);
            // $stmtRetrieveOrderID->bindParam(':onGoing', $onGoing);
            // $stmtRetrieveOrderID->execute();

            // $stmtRetrieveOrderID->execute();
            // $result = $stmtRetrieveOrderID->fetch(PDO::FETCH_ASSOC);
            // $orderID = $result['orderID'];

            // if ($result !== false && isset($result['orderID'])) {
            //     $orderID = $result['orderID'];
            // } else {
            //     // Handle the case where no orderID was found
            //     // For example, you could set a default value or display an error message
            //     echo "Error: No orderID found for the specified criteria.";
            // }

            // Insert into orders_products table
            // $insertOrderProductsQuery = "INSERT INTO orders_products (orderID, productID, quantity, total_price) VALUES (:orderID, :productID, :quantity, :total_price)";
            // $stmtInsertOrderProducts = $db->prepare($insertOrderProductsQuery);
            // $stmtInsertOrderProducts->bindParam(':orderID', $orderID);
            // $stmtInsertOrderProducts->bindParam(':productID', $productID);
            // $stmtInsertOrderProducts->bindParam(':quantity', $quantity);
            // $stmtInsertOrderProducts->bindParam(':total_price', $_total_item_price);
            // $stmtInsertOrderProducts->execute();
            // $db->commit();
            
            // Success message or redirect
            echo "<div class= container>";
                echo "<h1> <center>Thank you for your order! <center></h1> ";
                echo "<br>";
                echo "Order placed successfully. Your order will be shipped to your address.";
                echo "<br>";
                echo "Your order total is: £" . $totalPrice;
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
                echo "<a href='index.php'><center>or Click here to go to Home page<center></a>";
                echo "<br>";
            echo "</div>";


            //Decrements the stock level of the products in the order
            // if ($orderStmt) {
            //     $sql = "SELECT productID, quantity FROM orders WHERE customerID = :customerID";
            //     $stmt = $db->prepare($sql);
            //     $stmt->execute(['customerID' => $customerID]);
            //     $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //     foreach ($cartItems as $item) {
            //         $productID = $item['productID'];
            //         $quantity = $item['quantity'];
            //         $sql = "UPDATE products SET stock_level = stock_level - :quantity WHERE productID = :productID";
            //         $stmt = $db->prepare($sql);
            //         $stmt->execute(['quantity' => $quantity, 'productID' => $productID]);
            //     }
            // }
            
              // Clear the cart here code can go here
              $sqlEmptyCart = "DELETE FROM cart WHERE customerID = :customerID";
              $stmtEmptyCart = $db->prepare($sqlEmptyCart);
              $stmtEmptyCart->execute(['customerID' => $customerID]);
             //Commit the transaction
              $db->commit();
        
        } catch (Exception $e) {
            $db->rollBack();
            echo "Error placing order: " . $e->getMessage();
        }
    }elseif(isset($_SESSION['adminID'])){
        $adminID = $_SESSION['adminID'];
        $totalPrice = $_SESSION['totalPrice']; // Check if it is passed from the form

             // Admin Address details
             $address_line_1 = $_POST['address_line_1'];
             $address_line_2 = $_POST['address_line_2'];
             $city = $_POST['city'];
             $post_code = $_POST['post_code'];
             $country = $_POST['country'];
     
             //Admin Card details
             $card_type = $_POST['card_type'];
             $card_name = $_POST['card_name'];
             $card_number = $_POST['card_number'];
             $card_expiry = $_POST['card_expiry'];
             $card_cvv = $_POST['card_cvv'];
     
             // Retrivin  order details needed to be inserted for the  order, orders_products table 
            // $onGoing = 1;
     
     
             try {
                 $db->beginTransaction();
                 
                  $order_status="Processing";
                  // Insert Payment Information
                 $paymentSql = "INSERT INTO payment_information (adminID, card_type, card_number, expiry_date, CVV) VALUES (:adminID, :card_type, :card_number, :expiryy_date, :CVV)";
                 $paymentStmt = $db->prepare($paymentSql);
                 $paymentStmt->execute([
                     ':adminID' => $adminID,
                     ':card_type' => $card_type,
                     ':card_number' => $card_number,
                     ':expiryy_date' => $card_expiry,
                     ':CVV' => $card_cvv
                 ]);
                 $paymentInfoID = $db->lastInsertId();
    
                 // Fetch the addressID for the logged-in admin
                 $addressQuery = "SELECT addressID FROM address WHERE adminID = :adminID LIMIT 1";
                 $addressStmt = $db->prepare($addressQuery);
                 $addressStmt->execute(['adminID' => $adminID]);
                 $addressResult = $addressStmt->fetch(PDO::FETCH_ASSOC);
             
                 if (!$addressResult) {
                     throw new Exception("No address found for the admin.");
                 }
             
                 $addressID = $addressResult['addressID'];
             
                 // Insert order details into orders table including the addressID
                 $insertOrderQuery = "INSERT INTO orders (adminID, order_date, total_amount, addressID, paymentInfoID, order_status) VALUES (:adminID, CURDATE(), :total_amount, :addressID, :paymentInfoID, :order_status)";
                 $stmtInsertOrder = $db->prepare($insertOrderQuery);
                 $stmtInsertOrder->bindParam(':adminID', $adminID);
                 $stmtInsertOrder->bindParam(':total_amount', $totalPrice);
                 $stmtInsertOrder->bindParam(':addressID', $addressID);
                 $stmtInsertOrder->bindParam(':paymentInfoID', $paymentInfoID);
                 $stmtInsertOrder->bindParam(':order_status', $order_status);
                 $stmtInsertOrder->execute();
     
     
                 // After the successful insertion of the order into the orders table
                 $orderID = $db->lastInsertId();
     
                 // Fetch all items from the user's cart
                 $cartItemsStmt = $db->prepare("SELECT productID, quantity FROM cart WHERE adminID = :adminID");
                 $cartItemsStmt->execute(['adminID' => $adminID]);
                 $cartItems = $cartItemsStmt->fetchAll(PDO::FETCH_ASSOC);
     
                 // For each cart item, insert into the orders_products table and update product stock
                 foreach ($cartItems as $cartItem) {
                     $productID = $cartItem['productID'];
                     $quantity = $cartItem['quantity'];
     
                     // Retrieve the price of the product
                     $productStmt = $db->prepare("SELECT price FROM products WHERE productID = :productID");
                     $productStmt->execute(['productID' => $productID]);
                     $product = $productStmt->fetch(PDO::FETCH_ASSOC);
                     $totalItemPrice = $product['price'] * $quantity;
     
                     // Insert order-product association
                     $insertOrderProductsStmt = $db->prepare("INSERT INTO orders_products (orderID, productID, quantity, total_price) VALUES (:orderID, :productID, :quantity, :total_price)");
                     $insertOrderProductsStmt->execute([
                         'orderID' => $orderID,
                         'productID' => $productID,
                         'quantity' => $quantity,
                         'total_price' => $totalItemPrice
                     ]);
     
                     // Update product stock
                     $updateStockStmt = $db->prepare("UPDATE products SET stock = stock - :quantity WHERE productID = :productID");
                     $updateStockStmt->execute([
                         'quantity' => $quantity,
                         'productID' => $productID
                     ]);
                 }
     
         
     
               
     
                 // Redirect to a confirmation page or display the success message
             
                 echo "<div class= container>";
                     echo "<h1> <center>Thank you for your order! <center></h1> ";
                     echo "<br>";
                     echo "Order placed successfully. Your order will be shipped to your address.";
                     echo "<br>";
                     echo "Your order total is: £" . $totalPrice;
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
     
     

                   // Clear the cart here code can go here
                   $sqlEmptyCart = "DELETE FROM cart WHERE adminID = :adminID";
                   $stmtEmptyCart = $db->prepare($sqlEmptyCart);
                   $stmtEmptyCart->execute(['adminID' => $adminID]);
                //Commit the transaction
                   $db->commit();
       
                }catch (Exception $e) {
                    $db->rollBack();
                    echo "Error placing order: " . $e->getMessage();
                }

    }else //Was not able to retrieve the user's ID
    {
        echo "Error retriving the user's ID";
        exit;
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
 