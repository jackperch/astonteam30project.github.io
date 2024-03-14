<?php
  session_start();
  // checks if the form has been submitted
  if (isset($_POST['adminLogin'])){
    require_once("connectionDB.php");  // connects to the dtb

    //check if it's empty, stored as a boolean
    //Checks if the username parameter is there in the post request  if it is it will get assigned to the variable $username if not it will be assigned to false
    $username=isset($_POST['username'])?$_POST['username']:false;
    
    //Checks if the password parameter is there in the post request  if it is  it will get assigned to the variable $password and get hashed if not it will be assigned to false

    $password = isset($_POST['password'])?$_POST['password']:false;

    try {
        // Query DB to find the matching username/password
        // using prepare/bindparameter to prevent SQL injection.
        $SQL = $db->prepare('SELECT password, adminID FROM admin WHERE username = ?');
        $SQL->execute(array($_POST['username']));

        // fetch the result row and check 
        if ($SQL->rowCount() > 0) { // matching username
            $row = $SQL->fetch();

            if (($_POST['password'] === $row['password'])) { // matching password with the user input password and database stored password

                // Makes the username accessible for other php 
                $_SESSION["username"] = $_POST['username'];
                $_SESSION["adminID"] = $row['adminID'];

                // loads these website
                 header("Location:dashboard.php"); 

                echo "<p>Welcome back Admin</p>";
                exit();

            } else {
                echo "<p>Error logging in, password does not match </p>";
            }
        } else {
            // else display an error
            echo "<p>Error logging in, Username not found </p>";
        }
    } // Catches the problem 
    catch (PDOException $ex) {
        echo("Failed to connect to the database.<br>");
        echo($ex->getMessage());
        exit;
    }

    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="CSS/styles.css">
    <link rel="stylesheet" href="CSS/login.css">
    <script src="loginValidation.js"></script>

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
                <a href="login.php">Login</a>
            </nav>
          
        </header>


    <div class="content-container">
        <div class="login-container">
            <h2>Admin Log In</h2>
            <form   action="adminLogin.php" method="post">

                <input type="text" id="username" name="username" onclick="validateUsername()" placeholder="USERNAME">
                <span id="usernameError"></span>


                <input type="password" id="password" name="password" onclick="validatePassword()" placeholder="PASSWORD">
                <span id="passwordError"></span>
                <input  onclick=" return validateForm()" type="submit" value="LOG IN" >
                <input type="hidden" id= "adminLogin" name="adminLogin" value="TRUE" />
                <span id="loginError"></span>
            </form>
            <br>
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
 <script src="loginValidation.js"></script>
</html>
