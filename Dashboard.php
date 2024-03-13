<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ACE GEAR</title>
    <link rel="stylesheet" href="CSS/styles.css">
    <link rel="stylesheet" href="CSS/index.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Sono&display=swap');
    </style>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script> 
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">ADMIN DASHBOARD</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                      <nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">ADMIN DASHBOARD</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            PRODUCTS
          </a>
          <ul class="dropdown-menu">
    <li><a class="dropdown-item" href="addProduct.php">Add Product</a></li>
    <li><a class="dropdown-item" href="editProducts.php">Edit Products</a></li>
          </ul>
        </li>
   <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            ORDERS
          </a>
          <ul class="dropdown-menu">
    <li><a class="dropdown-item" href="addOrder.php">Add Order</a></li>
    <li><a class="dropdown-item" href="editOrders.php">Edit Orders</a></li>
          </ul>
        </li>
  <li class="nav-item dropdown">
	  <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            USER
          </a>
          <ul class="dropdown-menu">
   <li><a class="dropdown-item" href="adduser.php">Add User</a></li>
    <li><a class="dropdown-item" href="editusers.php">Edit Users</a></li>
          </ul>
        </li>
	<li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            ADMIN
          </a>
          <ul class="dropdown-menu">
    <li><a class="dropdown-item" href="addAdmin.php">Add Admin</a></li>
    <li><a class="dropdown-item" href="editAdmins.php">Edit Admins</a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="adminLogout.php">Logout</a>
        </li>
			    
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <?php 
        session_start();
        if (isset($_SESSION['adminID'])) {
            echo "<a href='adminLogout.php'>Logout</a>";
        } else {
            echo "<a href='adminLogin.php'>Login</a>";
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
