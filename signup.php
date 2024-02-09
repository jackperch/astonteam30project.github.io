<?php
session_start();


// Sign-up code...
if (isset($_POST['signupsubmitted'])) {
    require_once("connectionDB.php");

    $newUsername = isset($_POST['username']) ? $_POST['username'] : false;
    $newPassword = isset($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : false;
    $newEmail = isset($_POST['email']) ? $_POST['email'] : false;
    //$newAddress = isset($_POST['address']) ? $_POST['address'] : false;

    $newFirstName = isset($_POST['first-name']) ? $_POST['first-name'] : false;
    $newLastName = isset($_POST['last-name']) ? $_POST['last-name'] : false;

    $newHouseNumber = isset($_POST['house-number']) ? $_POST['house-number'] : false;
    $newAddressLine1 = isset($_POST['address-line1']) ? $_POST['address-line1'] : false;
    $newAddressLine2 = isset($_POST['address-line2']) ? $_POST['address-line2'] : false;
    $newPostCode = isset($_POST['post-code']) ? $_POST['post-code'] : false;
    $newCity = isset($_POST['city']) ? $_POST['city'] : false;
    $newCountry = isset($_POST['country']) ? $_POST['country'] : false;


    if ($newUsername == false || $newPassword == false || $newEmail == false  ||  $newFirstName == false || $newLastName == false || $newHouseNumber == false || $newAddressLine1 == false || $newAddressLine2 == false || $newPostCode == false || $newCity == false || $newCountry == false) {
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
            $insertUserSQL = $db->prepare('INSERT INTO Customers (username, password, first_name, last_name,email) VALUES (?, ?, ?, ?,?)');
            $insertUserSQL->execute(array($newUsername, $newPassword, $newFirstName, $newLastName,$newEmail));
           
            $retrieveCustomerID = $db->prepare('SELECT customerID FROM Customers WHERE username = ?');
            $retrieveCustomerID->execute(array($newUsername));
            $customerID = $retrieveCustomerID->fetch(PDO::FETCH_ASSOC)['customerID'];

            $insertUserAddress = $db->prepare('INSERT INTO address (customerID, house_number, address_line_1, address_line_2, post_code, city, country) VALUES (?, ?, ?, ?, ?, ?, ?)');
            $insertUserAddress->execute(array($customerID, $newHouseNumber, $newAddressLine1, $newAddressLine2, $newPostCode, $newCity, $newCountry));
            
            
                   
             
         

           echo "Sign up successful! You can now log in.";
           if( $_SESSION["username"]=$_POST['username']);
           //loads these website
            header("Location:products.php"); 
            exit();
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
        <link rel="stylesheet" href="CSS/styles.css">
        <link rel="stylesheet" href="CSS/signup.css">
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Sono&display=swap');
        </style>
        <script src="/js/main.js"></script>
        <script src="signup.js"></script>

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
                <a href="login.php">Login</a>
            </nav>
            <div id="cart-container">
                <!-- cart icon image with link to cart page -->
                <a href="cart.php">
                    <img id="cart-icon" src="Images/cart-no-bg.png" alt="Cart">
                    <span id="cart-count">0</span>
                </a>
            </div>
        </header>

       
       
       <div class="content-container">
            <div class="signup-container">
                <h2>Sign Up</h2>
                <form action="signup.php" method="post">
                    
                    <input type="text" id="first-name" name="first-name"  placeholder="First Name"  onblur="validateFirstName()">
                    <span id="first-name-error"></span>

                    <input type="text" id="last-name" name="last-name"  placeholder="Last Name"   onblur="validateLastName()">
                    <span id="last-name-error"></span>

                    <input type="text" id="username" name="username"  placeholder="Username" onblur="validateUsername()">
                    <span id="username-error"></span>
                    
                    <input type="password" id="password" name="password"  placeholder="Password" onblur="validatePassword()">
                    <span id="password-error"></span>

                    <input type="text" id="email" name="email"  placeholder="E-mail" onblur="validateEmail()">
                   <span id="email-error"></span>

                    <input type="text" id="house-number" name="house-number" placeholder="house name or number" onblur="validateHouseNumber()">
                    <span id="house-number-error"></span>
                    <input type="text" id="address-line1" name="address-line1" placeholder="first line of address" onblur="validateAdressLine1()">
                    <span id="address-line1-error"></span>
                    <input type="text" id="address-line2" name="address-line2" placeholder="second line of address" onblur="validateAdressLine2()">
                    <span id="address-line2-error"></span>
                    <input type="text" id="post-code" name="post-code" placeholder="post code" onblur="validatePostCode()">
                    <span id="post-code-error"></span>
                    <input type="text" id="city" name="city" placeholder="city" onblur="validateCity()">
                    <span id="city-error"></span>
                    <input type="text" id="country" name="country" placeholder="country" onblur="validateCountry()">
                    <span id="country-error"></span>


                    <input onclick=" return validateForm()" type="submit" value="Register" >
                    <input type="reset" value="clear">
                    <input type="hidden" name="signupsubmitted" value="TRUE" >
                    <span id="signup-error"></span>
                
                </form>
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

    <script src="signup.js"></script>

</html>
