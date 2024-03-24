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
                require_once("connectionDB.php"); // Database connection path

                // Fetch the total quantity of items in the user's cart
                $stmt = $db->prepare("SELECT SUM(quantity) AS totalQuantity FROM cart WHERE customerID = :customerID");
                $stmt->execute(['customerID' => $_SESSION['customerID']]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result && $result['totalQuantity'] > 0) {
                    $totalQuantity = $result['totalQuantity'];
                }
            }elseif(isset($_SESSION['adminID'])){
                require_once("connectionDB.php"); // Database connection path
                $smt=$db->prepare("SELECT SUM(quantity) AS totalQuantity FROM cart WHERE  adminID = :adminID");
                $smt->execute(['adminID' => $_SESSION['adminID']]);
                $result = $smt->fetch(PDO::FETCH_ASSOC);
                if ($result && $result['totalQuantity'] > 0) {
                    $totalQuantity = $result['totalQuantity'];
                }
            
            }elseif(isset($_SESSION['guest_shopping_cart']))
            {

                // Fetch the total quantity of items in the guest's cart
                    $totalQuantity = array_sum($_SESSION['guest_shopping_cart']);
            
            }
            ?>
            <div id="cart-container">
                <!-- cart icon image with link to cart page -->
                <a href="cart.php">
                    <img id="cart-icon" src="Images/cart-no-bg.png" alt="Cart">
                    <close id="cart-count"><?php echo $totalQuantity; ?></close>
                </a>
            </div>

        </header>


        <main>
            <h1 id="page-title"><center>Your Shopping Cart<center></h1>
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
                        $_total_item_price = $item['price'] * $item['quantity'];
                        echo "<div class='cart-item'>";
                        echo "<img src='Images/Product-Images/{$item['image']}' alt='{$item['productName']}' width=50 height=50>";
                        echo "<h2>{$item['productName']}</h2>";
                        echo "<p>Price: {$item['price']}</p>";
                        //echo "<p>Quantity: {$item['quantity']}</p>";
                        // Form for updating quantity or removing item
                        echo "<form method='post' action='updateCart.php'>";
                        echo "<input type='hidden' name='productID' value='{$item['productID']}'>";
                        echo "<input type='number' name='quantity' value='{$item['quantity']}' min='1'>";
                        echo "<button type='submit' name='action' value='update'>Update Quantity</button>";
                        echo "<button type='submit' name='action' value='remove'>Remove Item</button>";
                        echo "</form>";
                        echo "Item Total: £" . $_total_item_price;
                        echo "</div>";
                    }

                    if (empty($cartItems)) {
                        echo "<p>Your cart is empty</p>";
                    } else {
                        // Checkout button form
                        echo "<form action='checkout.php' method='get'>";
                        echo "<input type='submit' id='checkout-btn' value='Continue to Checkout' class='button'>";
                        echo "</form>";
                        //echo "</div>";
                    }
                //for admin users
                }elseif(isset($_SESSION['adminID'])){
                    $adminID = $_SESSION['adminID'];
                    // Function to fetch cart items
                    function fetchCartItems() {
                        global $db;
                        $adminID = $_SESSION['adminID']; // checking to see if user is logged by comparing adminID to value stored in session
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
                                c.adminID = :adminID;
                            ";

                            $stmt = $db->prepare($sql);
                            $stmt->bindParam(':adminID', $adminID, PDO::PARAM_INT);
                            $stmt->execute();

                            return $stmt->fetchAll(PDO::FETCH_ASSOC);
                        } catch(PDOException $ex) {
                            echo "Error fetching cart items: " . $ex->getMessage();
                            exit;
                        }
                    }

                    $cartItems = fetchCartItems();

                    foreach ($cartItems as $item) {
                        $_total_item_price = $item['price'] * $item['quantity'];
                        echo "<div class='cart-item'>";
                        echo "<img src='Images/Product-Images/{$item['image']}' alt='{$item['productName']}' width=50 height=50>";
                        echo "<h2>{$item['productName']}</h2>";
                        echo "<p>Price: {$item['price']}</p>";
                        //echo "<p>Quantity: {$item['quantity']}</p>";
                        // Form for updating quantity or removing item
                        echo "<form method='post' action='updateCart.php'>";
                        echo "<input type='hidden' name='productID' value='{$item['productID']}'>";
                        echo "<input type='number' name='quantity' value='{$item['quantity']}' min='1'>";
                        echo "<button type='submit' name='action' value='update'>Update Quantity</button>";
                        echo "<button type='submit' name='action' value='remove'>Remove Item</button>";
                        echo "</form>";
                        echo "Item Total: £" . $_total_item_price;
                        echo "</div>";
                    }

                    if (empty($cartItems)) {
                        echo "<p>Your cart is empty</p>";
                    } else {
                        // Checkout button form
                        echo "<form action='checkout.php' method='get'>";
                        echo "<input type='submit' id='checkout-btn' value='Continue to Checkout' class='button'>";
                        echo "</form>";
                        //echo "</div>";
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
                                $_total_item_price = $item['price'] * $quantity;
                                echo "<div class='cart-item'>";
                                echo "<img src='Images/Product-Images/{$item['image']}' alt='{$item['product_name']}' width=50 height=50>";
                                echo "<h2>{$item['product_name']}</h2>";
                                echo "<p>Price: {$item['price']}</p>";
                                echo "<p>Quantity: {$quantity}</p>";
                                echo "<form method='post' action='updateCart.php'>";
                                echo "<input type='hidden' name='productID' value='{$item['productID']}'>";
                                echo "<input type='number' name='quantity' value='{$quantity}' min='1'>";
                                echo "<button type='submit' name='action' value='updateGuest'>Update Quantity</button>";
                                echo "<button type='submit' name='action' value='removeGuest'>Remove Item</button>";
                                echo "</form>";
                                echo "Item Total: £" . $_total_item_price;
                                echo "</div>";
                            }                            
                        }
                        if (empty($guestItems)) {
                            echo "<p>Your cart is empty</p>";
                        } else {
                            // Checkout button form
                            //echo "<form action='checkout.php' method='get'>";
                            echo "<input type='submit' id='checkout-btn' value='Continue to Checkout' class='button'>";
                            echo "<div class='modal' id='modal'>";
                            echo "<div class='modal-content'>";
                            echo "<button type='submit'  name='action' id='checkOutAsGuest' class='style' value='checkOutAsGuest'>Checkout as Guest</button>";
                            echo "<br>";
                            echo "<button type='submit' name='action'  id='checkOutAsMember' class='style' value='checkOutAsMember'>Checkout as a Member</button>";
                            echo "<br>";
                            echo "<button type='submit' name='action'  id='signin'  class='style' value='signin'>Do you want to sign-up</button>";
                            echo "<close class='close'>&times;</close>";

                            echo "</div>";
                            //echo "</div>";
                           // echo "</form>";
                            echo "</div>";
                            echo "<div id='overlay'</div>";


                        }
                    }
                    
                }
            
                ?>
            </div>
            <!-- CSS for modal ( NEEDS IMPROVING!!!!) -->
            <style>
            .modal {
                display: none;
                position: fixed;
                z-index: 1;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                overflow: auto;
                background-color: rgba(0,0,0,0.4);
                border-color: greenyellow;
                border-radius: 50px;
                
            }
            
            .modal-content {
                background-color: #fefefe;
                margin: 15% auto;
                padding: 20px;
                border: 1px solid #888;
                width: 30%;
                border-radius: 50px;
            }
            
            .close {
                color: #aaa;
                float: right;
                font-size: 28px;
                font-weight: bold;
            }
            
            .close:hover,
            .close:focus {
                color: black;
                text-decoration: none;
                cursor: pointer;
            }

            
        .style{
            color:black;
        }       

        #overlay{
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 1;
            display: none;
        }
       
</style>
        </main>
    </body>
   
  
    <script>
        //This script is for the moodal
        
            document.addEventListener('DOMContentLoaded', function() {
                // Get the modal
                var modal = document.getElementById('modal');
            
                // Retrieves the button that opens the modal
                var checkOutButton = document.getElementById("checkout-btn");
            
                // Get the <close> element that closes the modal
                var close = document.getElementsByClassName("close")[0];
            
                // When the user clicks the button, open the modal 
                checkOutButton.onclick = function() {
                    modal.style.display = "block";
                }
            
                // When the user clicks on <close> (x), close the modal
                close.onclick = function() {
                    modal.style.display = "none";
                }
            
                // When the user clicks anywhere outside of the modal, close it
                window.onclick = function(event) {
                    if (event.target == modal) {
                        modal.style.display = "none";
                    }
                }

                //Retrieves and stores
                var checkOutAsGuest = document.getElementById("checkOutAsGuest");
                var checkOutAsMember = document.getElementById("checkOutAsMember");
                var signIn = document.getElementById("signin");

                //When its clicked, it redirects to the checkout page
                checkOutAsGuest.onclick = function() {
                    window.location.href = "guestOrder.php";

                }
                //When its clicked, it redirects to the login page
                checkOutAsMember.onclick = function() {
                    window.location.href = "login.php";

                }
                
                signIn.onclick = function() {
                    window.location.href = "signup.php";

                }
            });
            </script>

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

    <script>
        // JavaScript for toggling footer visibility on scroll
        window.addEventListener('scroll', function() {
            var footer = document.querySelector('footer');
            var main = document.querySelector('main');
            var scrollPosition = window.scrollY + window.innerHeight;
            var mainHeight = main.offsetHeight;

            if (scrollPosition >= mainHeight) {
                footer.style.opacity = '1';
            } else {
                footer.style.opacity = '0';
            }
        });
    </script>

</html>
