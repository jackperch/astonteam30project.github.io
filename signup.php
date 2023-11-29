<?php
session_start();


// Sign-up code...
if (isset($_POST['signupsubmitted'])) {
    require_once("connectionDB.php");

    $newUsername = isset($_POST['newUsername']) ? $_POST['newUsername'] : false;
    $newPassword = isset($_POST['newPassword']) ? password_hash($_POST['newPassword'], PASSWORD_DEFAULT) : false;
    $newEmail = isset($_POST['newEmail']) ? $_POST['newEmail'] : false;
    $newAddress = isset($_POST['newAddress']) ? $_POST['newAddress'] : false;

    if ($newUsername == false || $newPassword == false || $newEmail == false || $newAddress == false) {
        echo "One or more fields are empty. Please enter valid values.";
        exit;
    }

    try {
        // Check if the username already exists
        $checkUsernameSQL = $db->prepare('SELECT * FROM Customers WHERE username = ?');
        $checkUsernameSQL->execute(array($newUsername));

        if ($checkUsernameSQL->rowCount() > 0) {
            echo "Username already exists. Please choose a different username.";
        } else {
            // Insert new user into the database
            $insertUserSQL = $db->prepare('INSERT INTO Customers (username, password, email, address) VALUES (?, ?, ?, ?)');
            $insertUserSQL->execute(array($newUsername, $newPassword, $newEmail, $newAddress));

            echo "Sign up successful! You can now log in.";
        }
    } catch (PDOException $ex) {
        echo("Failed to connect to the database.<br>");
        echo($ex->getMessage());
        exit;
    }
}
?>




<!DOCTYPE html>
<html lang="en">
    <head>
        <title>ACE GEAR</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="UTF-8">
        <link rel="stylesheet" href="CSS/signup.css">
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
                <a href="index.html">Home</a>
                <a href="products.html">Products</a>
                <a href="about.html">About</a>
                <a href="contact.html">Contact</a>
                <a href="login.php">Login</a>
            </nav>
            <div id="cart-container">
                <!-- cart icon image -->
                <img id="cart-icon" src="Images/cart-no-bg.png" alt="Cart">
                <span id="cart-count">0</span>
            </div>
        </header>

       
       
       <div class="content-container">
            <div class="signup-container">
                <h2>Sign Up</h2>
                <form action="signup.php" method="post">
                    
                    <label>Enter your First Name:</label>
                    <input type="text" id="first-name" name="first-name"  placeholder="Enter your First Name here" required>

                    <label>Enter your Last Name:</label>
                    <input type="text" id="last-name" name="last-name"  placeholder="Enter your Last Name here" required>

                    <label>Enter your Username:</label>
                    <input type="text" id="username" name="username"  placeholder="Enter your Username here" required>
                    
                    <label>Enter your Password:</label>
                    <input type="password" id="password" name="password"  placeholder="Enter your Password here" required>
                
                    <label>Enter your Email: </label>
                    <input type="text" id="email" name="email"  placeholder="Enter your E-mail here" required>
                   

                    <label>Enter your Address: </label>
                    <input type="text" id="adddress" name="address" placeholder="Enter your full address here" required>

                    <input type="submit" value="Register" >
                    <input type="reset" value="clear">
                    <input type="hidden" name="submitted" value="TRUE" >
                
                </form>
            </div>
        </div>



        <footer>
            <div class="footer-container">
                <div class="footer-links">
                    <a href="reviews.html">Reviews</a>
                    <a href="contact.html">Contact Us</a>
                    <a href="about.html">About Us</a>
                    <a href="privacy-policy.html">Privacy Policy</a>
                </div>
            </div>
        </footer>
        

    </body>


</html>
