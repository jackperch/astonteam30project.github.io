<?php
    session_start();

    // The user is already logged in if they can see this section in the nav bar, so login validation is not required here

    require_once("connectionDB.php"); // database connection file

    // Fetch customer details from the database
    $customerID = $_SESSION['customerID'];
    $customerData = array(); // Initialize the variable

    try {
        $query = $db->prepare('SELECT * FROM customers WHERE CustomerID = ?');
        $success = $query->execute([$customerID]);

        // Check if the query was successful
        if ($success) {
            $rowCount = $query->rowCount();

            // Check if any rows were returned
            if ($rowCount > 0) {
                // Fetch customer data
                $customerData = $query->fetch(PDO::FETCH_ASSOC);

                // Display customer details
                if (isset($customerData['username'])) {
                    echo "Username: " . $customerData['username'] . "<br>";
                } else {
                    echo "Username not available.<br>";
                }

                if (isset($customerData['first_name'])) {
                    echo "First Name: " . $customerData['first_name'] . "<br>";
                } else {
                    echo "First Name not available.<br>";
                }

                if (isset($customerData['last_name'])) {
                    echo "Last Name: " . $customerData['last_name'] . "<br>";
                } else {
                    echo "Last Name not available.<br>";
                }

                if (isset($customerData['email'])) {
                    echo "Email: " . $customerData['email'] . "<br>";
                } else {
                    echo "Email not available.<br>";
                }

                // Add similar lines for other details you want to display
            } else {
                echo "No matching customer found.";
            }
        } else {
            echo "Error executing the query.";
        }
    } catch (PDOException $ex) {
        echo("Failed to fetch customer data.<br>");
        echo($ex->getMessage());
        exit;
    }
?>






<!DOCTYPE html>
<html lang="en">
    <head>
        <title>ACE GEAR</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="UTF-8">
        <link rel="stylesheet" href="CSS/about.css">
        <link rel="stylesheet" href="CSS/styles.css">
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
        <div id="search-container">
            <input type="text" id="search-bar" placeholder="Search...">
            <button id="search-button">Search</button>
        </div>
        <nav>
            <a href="index.php">Home</a>
            <a href="products.php">Products</a>
            <a href="about.php">About</a>
            <a href="members-blog.php">Blog</a>
            <a href="contact.php">Contact</a>
            <a href="logout.php">Logout</a>
        </nav>
        <div id="cart-container">
            <!-- cart icon image with link to cart page -->
            <a href="cart.php">
                <img id="cart-icon" src="Images/cart-no-bg.png" alt="Cart">
                <span id="cart-count">0</span>
            </a>
        </div>

    </header>



    <h1>My Account</h1>


    <div class="my-account-container">
       <!--  <h2>My Account</h2> -->

        <section class="my-details">
            <h3>My Details</h3>
            <ul>
                <li><strong>Username:</strong> <?php echo $customerData['username']; ?></li>
                <li><strong>First Name:</strong> <?php echo $customerData['first_name']; ?></li>
                <li><strong>Last Name:</strong> <?php echo $customerData['last_name']; ?></li>
                <li><strong>Email:</strong> <?php echo $customerData['email']; ?></li>
            </ul>
        </section>

        <section class="my-orders">
            <h3>My Orders</h3>
            <ul>
                <?php
                foreach ($orders as $order) {
                    echo "<li><a href='order.php?id=" . $order['id'] . "'>Order #" . $order['id'] . "</a></li>";
                }
                ?>
            </ul>
        </section>

    </div>


<footer>
        <div class="footer-container">
            <div class="footer-links">
                <a href="reviews.php">Reviews</a>
                <a href="contact.html">Contact Us</a>
                <a href="about.html">About Us</a>
                <a href="privacy-policy.html">Privacy Policy</a>
            </div>
        </div>
    </footer>
</body>
</html>