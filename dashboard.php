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
                <?php 
                session_start();
                if (isset($_SESSION['adminID'])) {
		require_once("connectionDB.php");
		echo "<a href='dashboard.php'>Dashboard</a>";
                echo "<a href='editProducts.php'>Edit Products</a>";
                echo "<a href='editusers.php'>Edit Users</a>";
                echo "<a href='addProduct.php'>Add Product</a>";
		echo "<a href='editOrders.php'>Edit Orders</a>";
		echo "<a href='addOrder'>Add Order</a>";
                 echo "<a href='logout.php'>Logout</a>";
                } else {
                    echo "<a href='login.php'>Login</a>";
                }
                ?>
            <?php
  

} else {
	echo "Please <a href='./adminLogin.php'>Login</a> first!";
}
?>

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
