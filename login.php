<?php
  session_start();
  //if the form has been submitted
  if (isset($_POST['loginsubmitted'])){
    require_once("connectionDB.php");  // connects to the dtb

    //check if it's empty, stored as a boolean
    
    $username=isset($_POST['username'])?$_POST['username']:false;
    $password=isset($_POST['password'])?password_hash($_POST['password'],PASSWORD_DEFAULT):false;
    if ($username==false){ #If it's empty
        echo "Usernasme is empty please enter your username";
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

          //Makes the username accessible for other php
           if( $_SESSION["username"]=$_POST['username']);
           //loads these website
            header("Location:products.php"); 
            //header("Location:.php");
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



    <div class="login-container">
        <h2>Login</h2>
        <form action="login.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <input type="submit" value="Login">
            <input type="hidden" name="loginsubmitted" value="TRUE" />
        </form>
    </div>


</body>
</html>