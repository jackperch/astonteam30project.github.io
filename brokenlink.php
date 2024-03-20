<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products - ACE GEAR</title>
    <link rel="stylesheet" href="CSS/styles.css">
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- <script src="js/products.js"></script>  need to create this -->
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
                    <a href="productsDisplay.php">Products</a>
                    <a href="about.php">About</a>
                    <a href="contact.php">Contact</a>
                    <?php 
                    session_start();
                    if (isset($_SESSION['username'])) {
                        echo "<a href='members-blog.php'>Blog</a>";
                        echo "<a href='account.php'>Account</a>";
                        echo "<a href='logout.php'>Logout</a>";
                    } elseif (isset($_SESSION['adminID'])) {
                        echo "<a href='Dashboard.php'>Login</a>";
                        echo "<a href='account.php'>Logout</a>";
                        echo "<a href='logout.php'>Logout</a>";
                        
                    }else{
                        echo "<a href='login.php'>Login</a>";
                    }
                    ?>
                </nav>
        <?php
        // Initialize the total quantity variable
        $totalQuantity = 0;

        // Check if the user is logged in
        if (isset($_SESSION['customerID'])) {
            require_once("connectionDB.php"); // Connects to the dtb

            // Fetch the total quantity of items in the user's cart
            $stmt = $db->prepare("SELECT SUM(quantity) AS totalQuantity FROM cart WHERE customerID = :customerID");
            $stmt->execute(['customerID' => $_SESSION['customerID']]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result && $result['totalQuantity'] > 0) {
                $totalQuantity = $result['totalQuantity'];
            }
        }elseif (isset($_SESSION['adminID'])) {
            require_once("connectionDB.php"); // Connects to the dtb
            $stmt = $db->prepare("SELECT SUM(quantity) AS totalQuantity FROM cart WHERE adminID = :adminID");
            $stmt->execute(['adminID' => $_SESSION['adminID']]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // Fetch the total quantity of items in the guest's cart
         } if (isset($_SESSION['guest_shopping_cart'])) {
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
        
            <body>
             <section class="content-container">

             
                <h1><center>404 Error<center></h1>
                <p><center>Sorry for the incovenience , the page you are looking for does not exist.</center></p>
            </section>
            </body>


    </main>


    
    <footer>
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
    </footer>
