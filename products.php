<!DOCTYPE html>
<html lang="en">
    <head>
        <title>ACE GEAR</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="UTF-8">
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
            <a href="index.html">Home</a>
            <a href="products.html">Products</a>
            <a href="about.html">About</a>
            <a href="contact.html">Contact</a>
            <?php 
                session_start();
                if (isset($_SESSION['Username'])) {
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


    <h2>Products</h2>
        <p>Product 1</p>
        <p>Product 2</p>
        <p>Product 3</p>




        <footer>
            <div class="footer-container">
                <div class="footer-links">
                    <a href="reviews.html">Reviews</a>
                    <a href="contact.html">Contact Us</a>
                    <a href="about.html">About Us</a>
                    <a href="privacy-policy.html">Privacy Policy</a>
                </div>
            </div>
        </footer>
</body>
</html>

