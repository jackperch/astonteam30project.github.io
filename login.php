<?php
  session_start();
  // checks if the form has been submitted
  if (isset($_POST['loginsubmitted'])){
    require_once("connectionDB.php");  // connects to the dtb

    //check if it's empty, stored as a boolean
    //Checks if the username parameter is there in the post request  if it is it will get assigned to the variable $username if not it will be assigned to false
    $username=isset($_POST['username'])?$_POST['username']:false;
    
    //Checks if the password parameter is there in the post request  if it is  it will get assigned to the variable $password and get hashed if not it will be assigned to false

    $password=isset($_POST['password'])?password_hash($_POST['password'],PASSWORD_DEFAULT):false;


    if ($username==false){ #If it's empty
        echo "Username is empty please enter your username";
        exit;
    }
    elseif ($password==false){#If it's empty
       echo "Password is empty please enter your password";
        exit;
    }

    try {
      //Query DB to find the matching username/password
      //using prepare/bindparameter to prevent SQL injection.
        $SQL = $db->prepare('SELECT password FROM Customers WHERE username = ?');
        $SQL->execute(array($_POST['username']));
          
        // fetch the result row and check 
        if ($SQL->rowCount()>0){  // matching username
          $row=$SQL->fetch();
  
          if (password_verify($_POST['password'], $row['password'])){ //matching password with the user input password and database stored password

            $_SESSION['customerID'] = $customerID; // Set the customerID in the session

          //Makes the username accessible for other php 
           if( $_SESSION["username"]=$_POST['username']);

           //loads these website
            header("Location:products.php"); 
            
            echo "Log in sucessfull";
            exit();
          
          } else {
           echo "<p>Error logging in, password does not match </p>";
             }
          } else {
         //else display an error
          echo "<p>Error logging in, Username not found </p>";
          }
      }//Catches the problem 
      catch(PDOException $ex) {
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
    <link rel="stylesheet" href="CSS/login.css">
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
        <div class="login-container">
            <h2>Login</h2>
            <form action="login.php" method="post">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <input type="submit" value="Login">
                <input type="hidden" id= "loginsubmitted" name="loginsubmitted" value="TRUE" />
            </form>
            <a href="signup.php" class="signup-button">Dont have an account? Click here to Sign Up</a>
        </div>
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
