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
            <div id="cart-container">
                <!-- cart icon image with link to cart page -->
                <a href="cart.php">
                    <img id="cart-icon" src="Images/cart-no-bg.png" alt="Cart">
                    <span id="cart-count">0</span>
                </a>
            </div>
        </header>



        <div class="slideshow-container">

            <div class="mySlides">
                
                <img src="Images/index-img-1.jpg" alt="Image 1">
            </div>
        
            <div class="mySlides">
                <img src="Images/index-img-2.jpg" alt="Image 2">
            </div>
        
            <div class="mySlides">
                <img src="Images/index-img-3.jpg" alt="Image 3">
            </div>

            <div class="mySlides">
                <img src="Images/index-img-4.jpg" alt="Image 4">
            </div>

            <div class="mySlides">
                <img src="Images/index-img-5.jpg" alt="Image 5">
            </div>

            <div class = products-button-container>
                <!--<button onclick href="products.php" class="ProductsBtn" title="Go to Products Page">VIEW PRODUCTS</button>-->
                <a href="products.php" class="ProductsBtn" title="Go to Products Page">VIEW PRODUCTS</a> 
            </div>
            
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


        <script>
            let slideIndex = 0;
        
            function showSlides() {
                let i;
                const slides = document.getElementsByClassName("mySlides");
        
                for (i = 0; i < slides.length; i++) {
                    slides[i].style.display = "none";
                }
        
                slideIndex++;
        
                if (slideIndex > slides.length) {
                    slideIndex = 1;
                }
        
                slides[slideIndex - 1].style.display = "block";
                setTimeout(showSlides, 3000); // Change slide every 2 seconds (2000 milliseconds)
            }
        
            // Start the slideshow when the page loads
            document.addEventListener("DOMContentLoaded", showSlides);
        </script>


    </body>


</html>