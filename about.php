<!DOCTYPE html>
<html lang="en">
    <head>
        <title>ACE GEAR</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="UTF-8">
        <link rel="stylesheet" href="CSS/styles.css">
        <link rel="stylesheet" href="CSS/about.css">
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
        <div class="about-container">
            <h1>About Us</h1>
        </div>
        <div class="about-content">
            <div class="content">
                <img src="Images/about-img-1.jpg" alt="content-image">
                <h1>Who we are</h1>
                <p>Welcome to ACE GEAR SPORTS, your premier destination for high-quality sports and workout 
                    equipment. At Ace Gear Sports, our passion lies in helping individuals pursue their fitness 
                    goals and embrace an active lifestyle. Whether you're a seasoned athlete or just starting, 
                    we've got the gear to elevate your performance.
                </p>
            </div>
            <div class="content">
            <img src="Images/about-img-2.jpg" alt="content-image">
                <h1>our mission</h1>
                <p>Our mission at Ace Gear Sports is to provide a one-stop online shop for a diverse range of sports and 
                    workout equipment, catering to the needs of fitness enthusiasts, athletes, and individuals seeking 
                    top-notch gear for their active pursuits. We believe that a well-equipped athlete is a confident and 
                    motivated one, and our curated selection reflects our commitment to quality and performance.
                </p>
            </div>
            <div class="content">
            <img src="Images/about-img-3.jpg" alt="content-image">
                <h1>Why choose us</h1>
                <p>At Ace Gear Sports, we prioritize Quality Assurance, handpicking each durable product. Our Diverse
                     Selection caters to cardio enthusiasts, weightlifters, yoga practitioners, and team sports players.
                      Benefit from Expert Advice; our fitness enthusiasts provide personalized recommendations. 
                      Experience Convenience with Ace Gear Sports - shop top-notch equipment from home, with direct 
                      shipping.
                </p>
                <!--<p>Quality Assurance: We understand the importance of reliable and durable equipment. That's why we 
                    handpick each product, ensuring it meets our stringent quality standards. When you shop with Ace Gear 
                    Sports, you can trust that you're investing in gear that stands the test of time.</p>
                <p>Diverse Selection: From cardio enthusiasts to weightlifters, yoga practitioners to team sports players, 
                    our inventory spans a wide range of activities. Explore our diverse selection of equipment and accessories 
                    tailored to various sports and workout routines.</p>
                <p>Expert Advice: Not sure which equipment is right for you? Our team of fitness enthusiasts is here to help. 
                    Feel free to reach out for personalized recommendations and expert advice to make informed decisions about 
                    your gear.</p>
                <p>Convenience: Ace Gear Sports brings the store to your doorstep. Enjoy the convenience of browsing and 
                    shopping for top-notch equipment from the comfort of your home. We ship directly to you, making the 
                    process seamless and hassle-free.</p>-->
            </div>
        </div>    
        <!--<h2>Soooooo what are you waiting for?</h2>
        <h2>Get up and Gear up with ACE GEAR SPORTS!</h2>-->
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