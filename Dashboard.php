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
<div class="dropdown">
  <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
    Dashboard Options
  </button>
  <ul class="dropdown-menu">
    <li><a class="dropdown-item" href="addProduct.php">Add Product</a></li>
    <li><a class="dropdown-item" href="editProducts.php">Edit Products</a></li>
    <li><a class="dropdown-item" href="adduser.php">Add User</a></li>
    <li><a class="dropdown-item" href="editusers.php">Edit Users</a></li>
    <li><a class="dropdown-item" href="addAdmin.php">Add Admin</a></li>
    <li><a class="dropdown-item" href="editAdmins.php">Edit Admins</a></li>
    <li><a class="dropdown-item" href="addOrder.php">Add Order</a></li>
    <li><a class="dropdown-item" href="editOrders.php">Edit Orders</a></li>
  </ul>
</div>
                <?php 
                session_start();
                if (isset($_SESSION['adminID'])) {
                    echo "<a href='adminLogout.php'>Logout</a>";
                } else {
                    echo "<a href='adminLogin.php'>Login</a>";
                }
                ?>
</header>
		
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
		
