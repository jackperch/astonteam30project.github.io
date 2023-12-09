<!DOCTYPE html>
<html lang="en">
    <head>
        <title>ACE GEAR</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="UTF-8">
        <link rel="stylesheet" href="CSS/reviews.css">
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
                <a href="members-blog.php">Blog</a>
                <a href="contact.php">Contact</a>
                <?php 
                session_start();
                if (isset($_SESSION['username'])) {
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





    <h1>Reviews</h1>

    <div class="container">
        <div class="review">
            <p class="author">John Smith</p>
            <p class="date">November 1, 2023</p>
            <p class="content"> This is review 1.</p>
        </div>

        <div class="review">
            <p class="author">Jane Smith</p>
            <p class="date">September 5, 2023</p>
            <p class="content">This is review 2.</p>
        </div>

        <div class="review">
            <p class="author">Sam Johnson</p>
            <p class="date">June 10, 2023</p>
            <p class="content">This is review 3.</p>
        </div>
    </div>





    <footer>
        <div class="footer-container">
            <div class="footer-links">
                <a href="reviews.php">Reviews</a>
                <a href="contact.html">Contact Us</a>
                <a href="about.html">About Us</a>
                <a href="privacy-policy.html">Privacy Policy</a>
            </div>
        </div>
    </footer>

</body>
</html>

