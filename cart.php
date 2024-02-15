<!DOCTYPE html>
<html lang="en">
    <head>
        <title>ACE GEAR</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="UTF-8">
        <link rel="stylesheet" href="CSS/styles.css">
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
            <h1>Your Shopping Cart</h1>
            <div id="cart-items">

                <?php
                    require_once("connectionDB.php");

                //  if (!isset($_SESSION['customerID'])) {
                //      // Redirect to login page or handle the case where customerID is not set
                //      header("Location: login.php");
                //      exit;
                //  }
                if (isset($_SESSION['customerID'])) {
                    $customerID = $_SESSION['customerID'];

                    // Function to fetch cart items
                    function fetchCartItems() {
                        global $db;
                        $customerID = $_SESSION['customerID']; // checking to see if user is logged by comparing customerID to value stored in session
                        //echo 'cutomer Id is ',$_SESSION['productID'];
                        try {
                            $sql = "
                            SELECT
                                pr.productID,
                                pr.product_name AS productName,
                                pr.price,
                                c.quantity,
                                pr.image
                            FROM
                                cart c
                            JOIN
                                products pr ON c.productID = pr.productID
                            WHERE
                                c.customerID = :customerID;
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
                        echo "<input type='hidden' name='productID' value='{$item['productID']}'>";
                        echo "<input type='number' name='quantity' value='{$item['quantity']}' min='1'>";
                        echo "<button type='submit' name='action' value='update'>Update</button>";
                        echo "<button type='submit' name='action' value='remove'>Remove</button>";
                        echo "</form>";
                    }

                    if (empty($cartItems)) {
                        echo "<p>Your cart is empty</p>";
                    } else {
                        // Checkout button form
                        echo "<form action='checkout.php' method='get'>";
                        echo "<input type='submit' value='Continue to Checkout' class='button'>";
                        echo "</form>";
                        echo "</div>";
                    }

                }else{
                    require_once("connectionDB.php");

                    function fetchGuestItems($db, $productID){
                        try{
                            $sql = "SELECT * FROM products WHERE productID = :productID";
                            $stmt = $db->prepare($sql);
                            $stmt->bindParam(':productID', $productID, PDO::PARAM_INT);
                            $stmt->execute();
                            return $stmt->fetchAll(PDO::FETCH_ASSOC);
                        }
                        catch(PDOException $ex){
                            echo "Error fetching  items: " . $ex->getMessage();
                            exit;
                        }
                    }
                    
                    if (isset($_SESSION["guest_shopping_cart"])) {
                        $guest_shopping_cart = $_SESSION["guest_shopping_cart"];
                        foreach ($guest_shopping_cart as $productID => $quantity) {
                           // echo "Product ID: " . $productID . ", Quantity: " . $quantity . "<br>"; Testing
                            $guestItems = fetchGuestItems($db, $productID);
                            foreach ($guestItems as $item) {
                                echo "<div class='cart-item'>";
                                echo "<img src='Images/Product-Images/{$item['image']}' alt='{$item['product_name']}' width=50 height=50>";
                                echo "<h2>{$item['product_name']}</h2>";
                                echo "<p>Price: {$item['price']}</p>";
                                echo "<p>Quantity: {$quantity}</p>";
                                echo "<form method='post' action='updateCart.php'>";
                                echo "<input type='hidden' name='productID' value='{$item['productID']}'>";
                                echo "<input type='number' name='quantity' value='{$quantity}' min='1'>";
                                echo "<button type='submit' name='action' value='updateGuest'>Update</button>";
                                echo "<button type='submit' name='action' value='removeGuest'>Remove</button>";
                                echo "</form>";
                            }                            
                        }
                    }
                    
            }
            
                ?>

            </div>
        </main>
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