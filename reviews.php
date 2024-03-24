<!DOCTYPE html>
<html lang="en">
    <head>
        <title>ACE GEAR</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="UTF-8">
        <link rel="stylesheet" href="CSS/styles.css">
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
            }elseif(isset($_SESSION['adminID'])) {
                require_once("connectionDB.php"); // Database connection path

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




    <h1 class="title"><center>Reviews<center></h1>

    <div class="container">
        <div class="review">
            <?php
                try
                {
                        require_once("connectionDB.php");
                        $retrieveReviewsSQL = "SELECT * FROM review WHERE productID IS NULL ORDER BY review_date DESC";
                        $stmt1 = $db->prepare($retrieveReviewsSQL);
                        $stmt1->execute();
                        $reviews = $stmt1->fetchAll(PDO::FETCH_ASSOC);
                        if ($reviews) {
                            foreach ($reviews as $review) 
                            {
                                if ($review['customerID'] !== null ) 
                                {
                                    $retrieveCustomerSQL = "SELECT username FROM customers WHERE customerID = ?";
                                    $stmt2 = $db->prepare($retrieveCustomerSQL);
                                    $stmt2->execute([$review['customerID']]);
                                    $loggedInCustomer = $stmt2->fetch(PDO::FETCH_ASSOC);
                                   // echo "<div class='review'>";
                                    $reviewDate = strtotime($review['review_date']); // Convert UNIX timestamp
                                    $formattedDate = date('d F, Y', $reviewDate); // Format timestamp to date month year
                                    echo "<p>  By: {$loggedInCustomer['username']} on $formattedDate </p>";
                                    echo "<p>  {$review['review']}</p>";
                                } elseif ($review['adminID'] !== null) 
                                {
                                    $retrieveCustomerSQL = "SELECT username FROM admin WHERE adminID = ?";
                                    $stmt2 = $db->prepare($retrieveCustomerSQL);
                                    $stmt2->execute([$review['adminID']]);
                                    $loggedInAdmin = $stmt2->fetch(PDO::FETCH_ASSOC);
                                   // echo "<div class='review'>";
                                    $reviewDate = strtotime($review['review_date']); // Convert UNIX timestamp
                                    $formattedDate = date('d F, Y', $reviewDate); // Format timestamp to date month year
                                    echo "<p>  By: {$loggedInAdmin['username']} on $formattedDate </p>";
                                    echo "<p>  {$review['review']}</p>";


                                }else
                                {

                                    $reviewDate = strtotime($review['review_date']); // Convert UNIX timestamp
                                    $formattedDate = date('d F, Y', $reviewDate); // Format timestamp to date month year
                                    echo "<p>  Guest User: {$review['first_name']} {$review['last_name']} on $formattedDate</p>";
                                    echo "<p>  {$review['review']}</p>";
                                }

                                //echo "</div>";
                            
                            }
                        } else 
                        {
                            echo "<p>No reviews found.</p>";
                        }
                }catch(PDOException $exception)
                {
                    echo "Error: " . $exception->getMessage();
                }
            
            
            ?>
            <a href='addCompanyReview.php'><button>Add Review</button></a>
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

</body>
</html>

