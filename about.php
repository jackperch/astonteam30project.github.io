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
        <div id="cart-container">
            <!-- cart icon image with link to cart page -->
            <a href="cart.php">
                <img id="cart-icon" src="Images/cart-no-bg.png" alt="Cart">
                <span id="cart-count">0</span>
            </a>
        </div>
    </header>




    <h1>About Us</h1>
    <div class="left">
        <h2 >Who are we?</h2>
            <p>We are ACE GEAR SPORTS, your one and only destination for high-quality sports and workout equipment. 
                At Ace Gear Sports, we are passionate about helping individuals pursue their fitness goals and enjoy 
                an active lifestyle. Whether you're a seasoned athlete or just starting your fitness journey, we've 
                got the gear you need to elevate your performance.</p>
    </div>
    <div class="right">
        <h2>Whats our mission?</h2>
            <p>Our mission at Ace Gear Sports is to provide a one-stop online shop for a diverse range of sports and 
                workout equipment, catering to the needs of fitness enthusiasts, athletes, and individuals seeking 
                top-notch gear for their active pursuits. We believe that a well-equipped athlete is a confident and 
                motivated one, and our curated selection reflects our commitment to quality and performance.</p>
    </div>
    <div class="left">
        <h2>Why choose us?</h2>
        <p>Quality Assurance: We understand the importance of reliable and durable equipment. That's why we 
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
            process seamless and hassle-free.</p>
    </div>    
    <h2>Soooooo what are you waiting for?</h2>
    <h2>Get up and Gear up with ACE GEAR SPORTS!</h2>



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