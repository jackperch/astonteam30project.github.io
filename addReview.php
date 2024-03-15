
<?php

    if (isset($_POST['addReview'])) 
    {
        session_start();
        if (isset($_GET['productID']) && is_numeric($_GET['productID'])) 
        {
            $productID = $_GET['productID'];
            echo $productID; //Testing if it get the productID
            $customerID = $_SESSION['customerID'];
        
            if (!empty($productID) && !empty($customerID)) 
            {
                echo "Product ID: $productID, Customer ID: $customerID";
                if (isset($_POST['review'])) 
                   {
                     $review = $_POST['review'];
                     echo $review; //Testing if it get the review
                     try 
                      {
                        require_once("connectionDB.php");
                        $sql = "INSERT INTO review (productID, customerID, review) VALUES (?, ?, ?)";
                        $stmt = $db->prepare($sql);
                        $stmt->execute([$productID, $customerID, $review]);
                        header("Location: productDetail.php?productID=$productID");
                      }catch (PDOException $e) 
                     {
                        echo "Error: " . $e->getMessage();
                     }

                    } else 
                      {
                        echo "Product ID or Customer ID is empty";
                      }

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
     <linkk rel="stylesheet" href"CSS/addReview.css">
    <!-- <link rel="stylesheet" href="CSS/productDetail.css">  -->
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
                //echo 'cutomer Id is ',$_SESSION['customerID'];
                //echo 'username is ',$_SESSION['username'];
                
                ?>
        </nav>
        <br>
        <p> <Center> Writing  a review for this product</Center></p>
        <div class="container">    
        <form action="addReview.php" method="post">
            <label for="review">Review:</label>
            <textarea id="review" name="review" rows="4" cols="50"></textarea>
            <input type="submit" value="Submit">
        </form>
        </div>






</body>
</html>
