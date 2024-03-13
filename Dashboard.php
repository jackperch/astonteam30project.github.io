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
                <h1 id="nav-bar-text">ADMIN DASHBOARD</h1>
            </div>
            <div id="search-container">
                <input type="text" id="search-bar" placeholder="Search...">
                <button id="search-button">Search</button>
            </div>
            <nav>
                <a href="addProduct.php">Add Product</a>
                <a href="adduser.php">Add User</a>
                <a href="addAdmin.php">Add Admin</a>
		<a href="editAdmins.php">Edit Admins</a>
                <a href="addOrder.php">Add Order</a>
		<a href="editOrders.php">Edit Orders</a>
		<a href="editProducts.php">Edit Products</a>
		<a href="editusers.php">Edit Users</a>
                <?php 
                session_start();
                if (isset($_SESSION['adminID'])) {
                    echo "<a href='adminLogout.php'>Logout</a>";
                } else {
                    echo "<a href='adminLogin.php'>Login</a>";
                }
                ?>
            </nav>
		
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
		
