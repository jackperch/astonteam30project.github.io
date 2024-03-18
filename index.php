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
                <a href="productsDisplay.php">Products</a>
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

            <!-- <div class = products-button-container>
                <a href="productsDisplay.php" class="ProductsBtn" title="Go to Products Page">VIEW PRODUCTS</a> 
            </div> -->
            
        </div>


        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="card" style="width: 18rem;">
                        <img src="images/admin-icon.png" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Racket Sports</h5>
                            <p class="card-text">View Racket Sport products here:</p>
                            <a href="editProducts.php" class="btn btn-primary">Go to Products</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card" style="width: 18rem;">
                        <img src="images/admin-icon.png" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Combat sports</h5>
                            <p class="card-text">View Combat Sport products here:</p>
                            <a href="editOrders.php" class="btn btn-primary">Go to Orders</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card" style="width: 18rem;">
                        <img src="images/admin-icon.png" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">XYZ</h5>
                            <p class="card-text">Manage XYZ here:</p>
                            <a href="editusers.php" class="btn btn-primary">Go to XYZ</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card" style="width: 18rem;">
                        <img src="images/admin-icon.png" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">XYZ</h5>
                            <p class="card-text">Manage XYZ here:</p>
                            <a href="editAdmins.php" class="btn btn-primary">Go to XYZ</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card" style="width: 18rem;">
                        <img src="images/admin-icon.png" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">XYZ</h5>
                            <p class="card-text">Manage XYZ here:</p>
                            <a href="editAdmins.php" class="btn btn-primary">Go to XYZ</a>
                        </div>
                    </div>
                </div>
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