<?php
 session_start();
if(isset($_POST['orderSubmitted'])){ // If there is a post request been sent by the form 
    require_once("connectionDB.php");
   
    //if it's empty, then it's false, otherwise it's the value
    
    $first_name = isset($_POST['first_name']) ? $_POST['first_name'] : false;
    $last_name = isset($_POST['last_name']) ? $_POST['last_name'] : false;
    $email = isset($_POST['email']) ? $_POST['email'] : false;
    $house_number = isset($_POST['house_number']) ? $_POST['house_number'] : false;
    $adress_line_1 = isset($_POST['address_line_1']) ? $_POST['address_line_1'] : false;
    $adress_line_2 = isset($_POST['address_line_2']) ? $_POST['address_line_2'] : false;
    $city = isset($_POST['city']) ? $_POST['city'] : false;
    $post_code = isset($_POST['postal_code']) ? $_POST['postal_code'] : false;
    $country = isset($_POST['country']) ? $_POST['country'] : false;
    $card_type = isset($_POST['card_type']) ? $_POST['card_type'] : false;
    $card_name = isset($_POST['card_name']) ? $_POST['card_name'] : false;
    $card_number = isset($_POST['card_number']) ? $_POST['card_number'] : false;
    $card_expiry = isset($_POST['card_expiry']) ? $_POST['card_expiry'] : false;
    $card_cvv = isset($_POST['card_cvv']) ? $_POST['card_cvv'] : false;
    $billing_house_number = isset($_POST['billing_house_number']) ? $_POST['billing_house_number'] : false;
    $billing_address_line_1 = isset($_POST['billing_address_line_1']) ? $_POST['billing_address_line_1'] : false;
    $billing_address_line_2 = isset($_POST['billing_address_line_2']) ? $_POST['billing_address_line_2'] : false;
    $billing_city = isset($_POST['billing_city']) ? $_POST['billing_city'] : false;
    $billing_post_code = isset($_POST['billing_post_code']) ? $_POST['billing_post_code'] : false;
    $billing_country = isset($_POST['billing_country']) ? $_POST['billing_country'] : false;
    $totalPrice = 0;

    //Checks if fields are empty
    if ($first_name == false || $last_name == false || $email == false || $house_number == false || $adress_line_1 == false || $city == false || $post_code == false || $country == false || $card_type == false || $card_name == false || $card_number == false || $card_expiry == false || $card_cvv == false ||$billing_house_number== false || $billing_address_line_1 == false || $billing_city == false || $billing_post_code == false || $billing_country == false) {
                echo "One or more fields are empty. Please enter valid values.";
                exit;
            }
            try {
                //Inserts the guest user into the customer table
                $insertUserSQL = $db->prepare('INSERT INTO Customers (first_name, last_name, email) VALUES (?, ?, ?)');
                $insertUserSQL->execute(array($first_name, $last_name, $email));

                //retrieves the customerID
                $retrieveCustomerID = $db->prepare('SELECT customerID FROM Customers WHERE email = ? && first_name = ? && last_name = ? LIMIT 1');
                $retrieveCustomerID->execute(array($email, $first_name, $last_name));
                $customerID = $retrieveCustomerID->fetch(PDO::FETCH_ASSOC)['customerID'];


                //if the customerID is not empty
                if ($customerID) {
                    //inserts guest's address into the address table
                    $insertUserAddress = $db->prepare('INSERT INTO address (customerID, house_number, address_line_1, address_line_2, city, post_code, country) VALUES (?, ?, ?, ?, ?, ?, ?)');
                    $insertUserAddress->execute(array($customerID, $house_number, $adress_line_1, $adress_line_2, $city, $post_code, $country));

                    //retrieves the addressID
                    $retrieveAddressID = $db->prepare('SELECT addressID FROM address WHERE customerID = ? LIMIT 1');
                    $retrieveAddressID->execute(array($customerID));
                    $addressID = $retrieveAddressID->fetch(PDO::FETCH_ASSOC)['addressID'];

                    //inserts guest's payment information into the payment_information table
                    $insertUserPayment = $db->prepare('INSERT INTO payment_information (customerID, card_type, name_on_card, card_number, expiry_date, CVV, house_number, address_line1, address_line2, post_code, city, country) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
                    $insertUserPayment->execute(array($customerID, $card_type, $card_name, $card_number, $card_expiry, $card_cvv, $billing_house_number, $billing_address_line_1, $billing_address_line_2, $billing_post_code, $billing_city, $billing_country));

                    //retrieves the guest's paymentInfoID
                    $retrievePaymentInfoID = $db->prepare('SELECT paymentInfoID FROM payment_information WHERE customerID = ? && card_number = ? LIMIT 1');
                    $retrievePaymentInfoID->execute(array($customerID, $card_number));
                    $paymentInfoID = $retrievePaymentInfoID->fetch(PDO::FETCH_ASSOC)['paymentInfoID'];

                    $order_status= "Processing";
                    //$insertOrder = $db->prepare('INSERT INTO orders (customerID, productID, quantity, price_of_product, total_amount, addressID, paymentInfoID,order_date) VALUES (?, ?, ?, ?, ?, ?, ?, CURDATE())');
                    $insertOrderQuery = "INSERT INTO orders (customerID, order_date, total_amount, addressID, paymentInfoID,order_status) VALUES (:customerID, CURDATE(), :total_amount, :addressID, :paymentInfoID,:order_status)";
                    $stmtInsertOrder = $db->prepare($insertOrderQuery);
                    $stmtInsertOrder->bindParam(':customerID', $customerID);
                    $stmtInsertOrder->bindParam(':total_amount', $totalPrice);
                    $stmtInsertOrder->bindParam(':addressID', $addressID);
                    $stmtInsertOrder->bindParam(':paymentInfoID', $paymentInfoID);
                    $stmtInsertOrder->bindParam(':order_status',$order_status);



                    if (isset($_SESSION['guest_shopping_cart'])) { //If it's not empty
                       
                        //Displays the items in the shopping cart array session varaible 
                        require_once("connectionDB.php");
                         foreach ($_SESSION['guest_shopping_cart'] as $productID => $quantity) {
                            $stmt = $db->prepare("SELECT * FROM products WHERE productID = :productID");
                            $stmt->execute(['productID' => $productID]);
                            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            
                             //Calculates the total price of the products 
                             $totalPrice += ($quantity * $product['price']);
                             $stmtInsertOrder->bindParam(':total_amount', $totalPrice);
                             $stmtInsertOrder->execute();
                             $orderID = $db->lastInsertId();
                              
                              $insertOrderProductsStmt = $db->prepare("INSERT INTO orders_products (orderID, productID, quantity, total_price) VALUES (:orderID, :productID, :quantity, :total_price)");
                              $insertOrderProductsStmt->execute([
                              'orderID' => $orderID,
                              'productID' => $productID,
                              'quantity' => $quantity,
                              'total_price' => $totalPrice
                            ]);
                              if($insertOrdersProducts){
                              $updateStock= "UPDATE products SET stock = stock - :quantity WHERE productID = :productID";
                              $stmt = $db->prepare($updateStock);
                              $stmt->execute(['quantity' => $quantity, 'productID' => $productID]);

                    


                         }
                            //  $insertOrder->execute(array($customerID, $product['productID'], $quantity, $product['price'], $totalPrice, $addressID, $paymentInfoID));
                            //  if($insertOrder){
                            //     $updateStock= "UPDATE products SET stock = stock - :quantity WHERE productID = :productID";
                            //     $stmt = $db->prepare($updateStock);
                            //     $stmt->execute(['quantity' => $quantity, 'productID' => $productID]);
            
                             }
                         
    
                        
                     }else{ // guest shopping cart is empty
                            echo "Failed to retrieve the products in the shopping cart.";
                     }
                   
                    //echo "Order placed successfully!";
                    header("Location:successfulOrder.php");
                } else {
                    echo "Failed to retrieve customer ID.";
                }
                // it will catch database errors
            } catch (PDOException $exception) {
                echo("Failed to connect to the database.<br>");
                echo($exception->getMessage());
                exit;
            }
        }
    
?>
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
        <h1>Checkout</h1>
        <div class="checkout-container">
                <section class="cart-summary">
                    <h2>Your Cart</h2>
                    <div id="cart-items">
                        <?php
                        // For the guest users 
                        $totalPrice = 0;
                        if (isset($_SESSION['guest_shopping_cart'])) {
                           //Displays the items in the shopping cart array session varaible 
                           require_once("connectionDB.php");
                            foreach ($_SESSION['guest_shopping_cart'] as $productID => $quantity) {
                                $stmt = $db->prepare("SELECT image,product_name, price FROM products WHERE productID = :productID");
                                $stmt->execute(['productID' => $productID]);
                                $product = $stmt->fetch(PDO::FETCH_ASSOC);
                                echo "<div class='cart-item'>";
                                echo "<img src='Images/Product-Images/{$product['image']}' alt='{$product['product_name']}' width=50 height=50>    {$product['product_name']} (Quantity: $quantity) - $" . ($quantity * $product['price']) . "</p>";
                                echo" </div>";
                                //Calculates the total price of the products 
                                $totalPrice += ($quantity * $product['price']);
                            }
                        }
                        ?>
                    </div>

                    <form action="guestOrder.php" method="post">
                            <section class="customer-information">
                                <h2>Customer Information</h2>
                                <input type="text" name="first_name" placeholder="First Name" value="" required>
                                <input type="text" name="last_name" placeholder="Last Name" value="" required>
                                <input type="email" name="email" placeholder="Email" value="" required>
                            </section>

                            <section class="shipping-information">
                                <h2>Shipping Information</h2>
                                <input type="text" name="house_number" placeholder="House Number" value="" required>
                                <input type="text" name="address_line_1" placeholder="Address Line 1" value="" required>
                                <input type="text" name="address_line_2" placeholder="Address Line 2" value="">
                                <input type="text" name="city" placeholder="City" value="" required>
                                <input type="text" name="postal_code" placeholder="Postal Code" value="" required>
                                <input type="text" name="country" placeholder="Country" value="" required>
                            </section>

                            <section class="payment-information">
                                <h2>Payment Information</h2>
                                <label for="card_type">Card Type: </label>
                                 <select name="card_type" required>
                                 <option value="visa">Visa</option>
                                <option value="mastercard">Mastercard</option>
                                <option value="amex">American Express</option>
                            </select>
                        
                            <section class="card-details">
                                <h2>Card Details</h2>
                                <input type="text" name="card_name" placeholder="Name on Card" required>
                                <input type="text" name="card_number" placeholder="Card Number" required>
                                <input type="text" name="card_expiry" placeholder="Expiry Date (MM/YY)" required>
                                <input type="text" name="card_cvv" placeholder="CVV" required>
                            </section>

                            <section class="Billing-information">
                                <h2>Billing Information</h2>
                                <input type="text" name="billing_house_number" placeholder="House Number" value="" required>
                                <input type="text" name="billing_address_line_1" placeholder="Address Line 1" value="" required>
                                <input type="text" name="billing_address_line_2" placeholder="Address Line 2" value="">
                                <input type="text" name="billing_city" placeholder="City" value="" required>
                                <input type="text" name="billing_post_code" placeholder="Post Code" value="" required>
                                <input type="text" name="billing_country" placeholder="Country" value="" required>
                            </section>
                <br>
                
                <input type='submit' name='orderSubmitted'value='Click here to place Order' class='button'>
                
            </form>
                <br>
        
        </div>
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
