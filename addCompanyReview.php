<?php
session_start(); // Start the session

if (isset($_POST['addReview'])) {
    echo "Review submitted";
       // $customerID = isset($_SESSION['customerID']) ? $_SESSION['customerID'] : null; // Get the customer ID from the session; if it's not set, it will be null
        
       if(isset($_SESSION['customerID']))
       {
            $customerID = $_SESSION['customerID'];
            if (!is_null($customerID)) 
            {
                if (isset($_POST['review']))
                 {
                        $review = $_POST['review'];
                        try
                    {
                        require_once("connectionDB.php");
                        $sql = "INSERT INTO review (customerID, review, review_date) VALUES ( ?, ?, CURDATE())";
                        $stmt = $db->prepare($sql);
                        $stmt->execute([$customerID, $review]);
                        header("Location: reviews.php");
                        exit(); // Ensure that script execution stops after redirection
                    } catch (PDOException $exception) 
                    {
                        echo "Error: " . $exception->getMessage();
                    }
                 } else 
                    {
                        echo "Review is empty";
                    }
            } else 

            {
                echo "CustomerID is null";
                exit;
            }




         }elseif(isset($_SESSION['adminID'])){
            
                $adminID = $_SESSION['adminID'];
                if (!is_null($adminID)) 
                {
                    if (isset($_POST['review']))
                     {
                            $review = $_POST['review'];
                            try
                        {
                            require_once("connectionDB.php");
                            $sql = "INSERT INTO review (adminID, review, review_date) VALUES ( ?, ?, CURDATE())";
                            $stmt = $db->prepare($sql);
                            $stmt->execute([$adminID, $review]);
                            header("Location: reviews.php");
                            exit(); // Ensure that script execution stops after redirection
                        } catch (PDOException $exception) 
                        {
                            echo "Error: " . $exception->getMessage();
                        }
                     } else 
                        {
                            echo "Review is empty";
                        }
                } else 
    
                {
                    echo "Admin is null";
                    exit;
                }

         }else{
            //Guest Review
            if (isset($_POST['firstName'], $_POST['lastName'], $_POST['review']) && !empty($_POST['firstName']) && !empty($_POST['lastName']) && !empty($_POST['review'])) {
                $firstName = $_POST['firstName'];
                $lastName = $_POST['lastName'];
                $review = $_POST['review'];

                try 
                {
                    require_once("connectionDB.php");
                    $sql = "INSERT INTO review (review, review_date, first_name, last_name) VALUES (?, CURDATE(), ?, ?)";
                    $stmt = $db->prepare($sql);
                    $stmt->execute([$review, $firstName, $lastName]);
                    header("Location: reviews.php");
                    exit(); // Ensure that script execution stops after redirection
                } catch (PDOException $exception) 
                {
                    echo "Error: " . $exception->getMessage();
                }
            } else {
                echo "First name, last name, or review is empty";
            }
        }
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Product Details</title>
    <link rel="stylesheet" href="CSS/styles.css">
    <link rel="stylesheet" href="CSS/addReview.css">
    </head>
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
                    if (isset($_SESSION['adminID'])) {
                        echo "<a href='Dashboard.php'>Dashboard</a>";
                        echo "<a href='logout.php'>Logout</a>";
                    }
                    ?>
                    <?php 
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
                <?php
                //REPEATED CODE
    //     // Initialize the total quantity variable
    //     $totalQuantity = 0;

    //     // Check if the user is logged in
    //     if (isset($_SESSION['customerID'])) {
    //         require_once("connectionDB.php"); // Adjust this path as necessary

    //         // Fetch the total quantity of items in the user's cart
    //         $stmt = $db->prepare("SELECT SUM(quantity) AS totalQuantity FROM cart WHERE customerID = :customerID");
    //         $stmt->execute(['customerID' => $_SESSION['customerID']]);
    //         $result = $stmt->fetch(PDO::FETCH_ASSOC);

    //         if ($result && $result['totalQuantity'] > 0) {
    //             $totalQuantity = $result['totalQuantity'];
    //         }
    //     }else{
    //         // Fetch the total quantity of items in the guest's cart
    //           if (isset($_SESSION['guest_shopping_cart'])) {
    //               $totalQuantity = array_sum($_SESSION['guest_shopping_cart']);}
          
    //   } 
        ?>
        <div id="cart-container">
            <!-- cart icon image with link to cart page -->
            <a href="cart.php">
                <img id="cart-icon" src="Images/cart-no-bg.png" alt="Cart">
                <span id="cart-count"><?php echo $totalQuantity; ?></span>
            </a>
        </div>
    </header>
<body>
    <p><center>Ace Gear Review</center></p>
    <div class="container">    
        <form action="addCompanyReview.php" method="post">
         <?php
            if (!isset($_SESSION['customerID']) && !isset($_SESSION['adminID']) ){
                echo "<label for='firstName'>Enter your first name</label>";
                echo "<input type='text' id='firstName' name='firstName'>";
                echo "<label for='lastName'>Enter your last name</label>";
                echo "<input type='text' id='lastName' name='lastName'>";

            }

         ?>
            <label for="review">Review:</label>
            <textarea id="review" name="review" rows="4" cols="50"></textarea>
            <input type="submit" value="Submit">
            <input type="hidden" name="addReview" value="true">
        </form>
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