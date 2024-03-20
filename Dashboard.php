<!DOCTYPE html>
<html lang="en">
<head>
    <title>ACE GEAR - Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <link rel="stylesheet" href="CSS/styles.css">
    <link rel="stylesheet" href="CSS/dashboard.css">
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
            if (isset($_SESSION['adminID'])) {
                echo "<a href='account.php'>Account</a>";
                echo "<a href='Dashboard.php'>Dashboard</a>";
                echo "<a href='adminLogout.php'>Logout</a>";

            }else{
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
        <div id="cart-container">
            <!-- cart icon image with link to cart page -->
            <a href="cart.php">
                <img id="cart-icon" src="Images/cart-no-bg.png" alt="Cart">
                <span id="cart-count"><?php echo $totalQuantity; ?></span>
            </a>
        </div>

        
            
    </header>
        
    

    <div class="title">
        <h1 class="text-center">Welcome to the Admin Dashboard</h1>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card" style="width: 18rem;">
                    <img src="images/admin-icon.png" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Products</h5>
                        <p class="card-text">Add, Edit and Delete Products here:</p>
                        <a href="editProducts.php" class="btn btn-primary">Manage Products</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card" style="width: 18rem;">
                    <img src="images/admin-icon.png" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Orders</h5>
                        <p class="card-text">Manage Orders here:</p>
                        <a href="editOrders.php" class="btn btn-primary">Manage Orders</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card" style="width: 18rem;">
                    <img src="images/admin-icon.png" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Users</h5>
                        <p class="card-text">Manage Users here:</p>
                        <a href="editusers.php" class="btn btn-primary">Manage Users</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card" style="width: 18rem;">
                    <img src="images/admin-icon.png" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Admin</h5>
                        <p class="card-text">Manage Admins here:</p>
                        <a href="editAdmins.php" class="btn btn-primary">Manage Admins</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card" style="width: 18rem;">
                    <img src="images/admin-icon.png" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Stock Management</h5>
                        <p class="card-text">Manage items low on Stock here:</p>
                        <a href="stockManagement.php" class="btn btn-primary">Manage Stock</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card" style="width: 18rem;">
                    <img src="images/admin-icon.png" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Contact Requests</h5>
                        <p class="card-text">View Contact Requests here:</p>
                        <a href="contactRequests.php" class="btn btn-primary">Manage Contact Requests</a>
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
</body>
</html>
