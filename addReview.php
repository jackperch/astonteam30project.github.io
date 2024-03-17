<?php
    session_start();

    $productID = isset($_GET['productID']) ? $_GET['productID'] : null; // Get the product ID from the URL if its not set it will be null

    if (isset($_POST['addReview'])) 
    {
        if (isset($productID) && is_numeric($productID)) 
        {
            $customerID = $_SESSION['customerID'];
        
            if (!empty($customerID)) 
            {
                if (isset($_POST['review'])) 
                {
                    $review = $_POST['review'];
                    try 
                    {
                        require_once("connectionDB.php");
                        $sql = "INSERT INTO review (customerID, productID, review) VALUES (?, ?, ?)";
                        $stmt = $db->prepare($sql);
                        $stmt->execute([$customerID, $productID, $review]);
                        header("Location: productDetail.php?productID=$productID");
                        exit(); // Ensure that script execution stops after redirection
                    } 
                    catch (PDOException $exception) 
                    {
                        echo "Error: " . $exception->getMessage();
                    }
                } 
                else 
                {
                    echo "Review is empty";
                }
            }
            else 
            {
                echo "Customer ID is empty";
            }
        }
        else 
        {
            echo "Invalid product ID";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Product Details</title>
    <link rel="stylesheet" href="CSS/styles.css">
    <link rel="stylesheet" href="CSS/productDetail.css">
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
                }
                ?>
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
<body>
    <p><center>Write a review for this product</center></p>
    <div class="container">    
        <form action="addReview.php?productID=<?php echo $productID; ?>" method="post">
            <label for="review">Review:</label>
            <textarea id="review" name="review" rows="4" cols="50"></textarea>
            <input type="submit" value="Submit">
            <input type="hidden" name="addReview" value="true">
        </form>
    </div>
</body>
</html>