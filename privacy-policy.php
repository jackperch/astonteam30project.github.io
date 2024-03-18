<!DOCTYPE html>
<html lang="en">
    <head>
        <title>ACE GEAR</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="UTF-8">
        <link rel="stylesheet" href="CSS/styles.css">
        <link rel="stylesheet" href="CSS/index.css">
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
            }elseif(isset($_SESSION['adminID'])) {
                require_once("connectionDB.php"); // Adjust this path as necessary
    
                // Fetch the total quantity of items in the user's cart
                $stmt = $db->prepare("SELECT SUM(quantity) AS totalQuantity FROM cart WHERE adminID = :adminID");
                $stmt->execute(['adminID' => $_SESSION['adminID']]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
                if ($result && $result['totalQuantity'] > 0) 
                {
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
                    <span id="cart-count"><?php echo $totalQuantity; ?></span>
                </a>
            </div>
        </header>


        <div class="content-container">
            <h2>Privacy Policy</h2>
            <br>
            <h3>Last Updated: 29/11/2023</h3>
            <br>
            <p>This page informs you of our policies regarding the collection, use, and disclosure of personal 
                data when you use our Service and the choices you have associated with that data.</p>
            <p>We use your data to provide and improve the Service. By using the Service, you agree to the 
                collection and use of information in accordance with this policy.</p>
            <br>
            <h3>Information Collection & Use</h3>
            <p>We collect several different types of information for various purposes to provide and improve our 
                Service to you.</p>
            <br> 
            <h3>Types of Data Collected</h3>
            <p>Personal Data</p>
            <p>While using our Service, we may ask you to provide us with certain personally identifiable information 
                that can be used to contact or identify you ("Personal Data"). Personally identifiable information may 
                include, but is not limited to:</p>
                <p>- Email Address</p>
                <p>- First and Last name</p>
                <p>- Address</p>
                <p>- Cookies and Useage Data</p>
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