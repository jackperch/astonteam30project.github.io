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


    //if ($username==false){ #If it's empty
     //  echo "<p>Username is empty please enter your username</p>";
   //  exit;
   // }
   // elseif ($password==false){#If it's empty
    //  echo "<p>Password is empty please enter your password</p>";
      //exit;
    // }

    try {
      //Query DB to find the matching username/password
      //using prepare/bindparameter to prevent SQL injection.
        $SQL = $db->prepare('SELECT password, customerID FROM customers WHERE username = ?');
        $SQL->execute(array($_POST['username']));
          
        // fetch the result row and check 
        if ($SQL->rowCount()>0){  // matching username
          $row=$SQL->fetch();



          //echo $row['username']; 
  
          if (password_verify($_POST['password'], $row['password'])){ //matching password with the user input password and database stored password

            //Makes the username accessible for other php 
            $_SESSION["username"] = $_POST['username'];
            $_SESSION["customerID"] = $row['customerID'];
            
           // $_SESSION['customerID'] = $db->prepare('SELECT CustomerID FROM Customers WHERE username = $_SESSION["username"]'); // Set the customerID in the session

        


            //loads these website
            header("Location:productsDisplay.php"); 
            
            echo "Log in sucessful";
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
        header("Location: error.php?error=dtbError"); // Redirect to error page
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
            
            <nav>
                <a href="index.php">Home</a>
                <a href="productsDisplay.php">Products</a>
                <a href="about.php">About</a>
                <a href="contact.php">Contact</a>
                <a href="login.php">Login</a>
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
            }else{
                // Fetch the total quantity of items in the guest's cart
                  if (isset($_SESSION['guest_shopping_cart'])) {
                      $totalQuantity = array_sum($_SESSION['guest_shopping_cart']);}
              
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


    <div class="content-container">
        <div class="login-container">
            <h2>Log In</h2>
            <form   action="login.php" method="post">

                <input type="text" id="username" name="username" oninput="validateUsername()" placeholder="USERNAME">
                <span id="usernameError"></span>


                <input type="password" id="password" name="password" oninput="validatePassword()" placeholder="PASSWORD">
                <span id="passwordError"></span>
                <input  onclick=" return validateForm()" type="submit" value="LOG IN" >
                <input type="hidden" id= "loginsubmitted" name="loginsubmitted" value="TRUE" />
                <span id="loginError"></span>
            </form>
            <br>
            <h2>Don't have an account?</h2>
            <a href="signup.php" class="signup-button">
                <button>SIGN UP</button>
            </a>
            <br>
            <h2> Admin Log In</h2>
            <a href="adminLogin.php" class="admin-button">
                <button>ADMIN LOG IN</button>
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
