<?php
// Retrieve the error code from the URL
$errorCode = isset($_GET['error']) ? $_GET['error'] : '';

// Define error messages based on error codes
if ($errorCode === 'no_session') {
    $errorMsg = 'Sorry, you are not logged in. Please log in to continue.';
} elseif($errorCode ==='cartIssue'){
    $errorMsg = 'Sorry, there was an issue with your cart. Please try again later.';
}

elseif ($errorCode === 'unexpected_error') {
    $errorMsg = 'An unexpected error occurred. Please try again later.';
}elseif($errorCode ==='dtbError'){
    $errorMsg = 'Sorry, there was an issue with the database. Please try again later.';
}


else {
    $errorMsg = 'An unknown error occurred.';
}

// HTML code to display the error message
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <link rel="stylesheet" href="CSS/styles.css">
    <link rel="stylesheet" href="CSS/error.css">
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
                if (isset($_SESSION['adminID'])) {
                    echo "<a href='Dashboard.php'>Dashboard</a>";
                    echo "<a href='logout.php'>Logout</a>";
                }
                ?>
        <?php
        // Initialize the total quantity variable
        $totalQuantity = 0;

        if(isset($_SESSION['adminID'])){
            require_once("connectionDB.php"); 
            $smt=$db->prepare("SELECT SUM(quantity) AS totalQuantity FROM cart WHERE  adminID = :adminID");
            $smt->execute(['adminID' => $_SESSION['adminID']]);
            $result = $smt->fetch(PDO::FETCH_ASSOC);
            if ($result && $result['totalQuantity'] > 0) {
                $totalQuantity = $result['totalQuantity'];
            }
        }else{
            header("Location: error.php?error=no_session");
            exit;
        }
        ?>
        </nav>
        <div id="cart-container">
            <!-- cart icon image with link to cart page -->
            <a href="cart.php">
                <img id="cart-icon" src="Images/cart-no-bg.png" alt="Cart">
                <span id="cart-count"><?php echo $totalQuantity; ?></span>
            </a>
        </div>
    </header>
    <style>

        .container {
            width: 80%;
            margin: 0 auto;
            text-align: center;
            padding-top: 50px;
        }

        h1 {
            color: #333;
        }

        p {
            color: white;
        }

        a {
            color: #c2ee0a;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

    </style>

    <div class="container">
        <h1>Error</h1>
        <p><?php echo $errorMsg; ?></p>
        <?php if ($errorCode === 'no_session'){
           echo" <p>Please <a href='login.php'>'log in'</a> to continue.</p>";
        }elseif ($errorCode === 'cartIssue'){
            echo" <p>Please <a href='cart.php'>'try again'</a> to continue.</p>";
        }elseif("errorCode === 'dtbError'"){
            echo" <p>There is something wrong with connecting to the database please try again later or <a href='contact.php'>'contact'</a> an admin.</p>";
        }
         ?>
    </div>
</body>
</html>