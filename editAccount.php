<?php
    session_start();
    if (isset($_POST['editAccountSubmitted'])){
       //if it's empty it's set to false
        $userName=isset($_POST['username'])?$_POST['username']:false;
        $newPassword=isset($_POST['password'])?password_hash($_POST['password'], PASSWORD_DEFAULT):false;
        $first_name=isset($_POST['first_name'])?$_POST['first_name']:false;
        $last_name=isset($_POST['last_name'])?$_POST['last_name']:false;
        $email=isset($_POST['email'])?$_POST['email']:false;
        $houseNumber=isset($_POST['house_number'])?$_POST['house_number']:false;
        $addressLine1=isset($_POST['address_line_1'])?$_POST['address_line_1']:false;
        $addressLine2=isset($_POST['address_line_2'])?$_POST['address_line_2']:false;
        $postCode=isset($_POST['postcode'])?$_POST['postcode']:false;
        $city=isset($_POST['city'])?$_POST['city']:false;
        $country=isset($_POST['country'])?$_POST['country']:false;
        // echo $houseNumber;
        // if (is_null($houseNumber)){
        //     echo "house number is null";
        
        if ($userName==false || $newPassword==false || $first_name==false || $last_name==false || $email==false || $houseNumber==false || $addressLine1==false || $addressLine2==false || $postCode==false || $city==false || $country==false){
            // echo $userName;
            // echo'<br>';
            // echo $newPassword;
            // echo'<br>';
            // echo $first_name;
            // echo'<br>';
            // echo $last_name;
            // echo'<br>';

            // echo $email;
            // echo'<br>';
            
            // echo $houseNumber;
            // echo'<br>';

            // echo $addressLine1;
            // echo'<br>';

            // echo $addressLine2;
            // echo'<br>';

            // echo $postCode;
            // echo'<br>';

            // echo $city;
            // echo'<br>';

            // echo $country;
            // echo'<br>';

            // echo "Please fill in all the fields";
            
            exit;
        }else{
            if (isset($_SESSION['customerID']))
            {
                $customerID = $_SESSION['customerID'];
                try
                {
                    require_once("connectionDB.php");  // connects to the dtb
                    $updateCustomerSQL = $db->prepare('UPDATE customers SET username = ?, password = ?, first_name = ?, last_name = ?, email = ? WHERE customerID = ?');
                    $updateCustomerSQL->execute(array($userName, $newPassword, $first_name, $last_name, $email,$customerID ));
                    $updateAddressSQL = $db->prepare('UPDATE address SET house_number = ?, address_line_1 = ?, address_line_2 = ?, post_code = ?, city = ?, country = ? WHERE customerID = ?');
                    $updateAddressSQL->execute(array($houseNumber, $addressLine1, $addressLine2, $postCode, $city, $country, $_SESSION['customerID']));
                    echo "Account updated successfully";
                
                    $retrieveNewUsername= $db->prepare('SELECT username FROM customers WHERE customerID = ?');
                    $retrieveNewUsername->execute([$customerID]);
                    $newUsername = $retrieveNewUsername->fetch(PDO::FETCH_ASSOC);
                    $_SESSION['username'] = $newUsername['username'];
                    header("Location:account.php");

                }catch(PDOException $exception)
                  {
                        echo("Failed to connect to the database.<br>");
                       echo($exception->getMessage());
                      exit;
                  }
             }elseif  (isset($_SESSION['adminID']))  
             {
                $adminID = $_SESSION['adminID'];
                try
                {
                    require_once("connectionDB.php");  // connects to the dtb
                    $addressID = $adminAddressData['addressID'];

                    $updateAdminSQL ='UPDATE admin SET username = ?, password = ?, first_name = ?, last_name = ?, email = ? WHERE adminID = ?';
                    $smt= $db ->prepare($updateAdminSQL);
                    $smt->execute([$userName, $newPassword, $first_name, $last_name, $email,$adminID ]);
                    $updateAddressSQL = 'UPDATE address SET house_number = ?, address_line_1 = ?, address_line_2 = ?, post_code = ?, city = ?, country = ? WHERE adminID = ?';
                    $smt= $db ->prepare($updateAddressSQL);
                    $smt->execute([$houseNumber, $addressLine1, $addressLine2, $postCode, $city, $country, $adminID]);
            
                    echo "Account updated successfully";
                
                    $retrieveNewUsername= $db->prepare('SELECT username FROM admin WHERE adminID = ?');
                    $retrieveNewUsername->execute([$adminID]);
                    $newUsername = $retrieveNewUsername->fetch(PDO::FETCH_ASSOC);
                    $_SESSION['username'] = $newUsername['username'];
                    header("Location:account.php");


                }catch(PDOException $exception)
                {
                        echo("Failed to connect to the database.<br>");
                    echo($exception->getMessage());
                    exit;
                }


            }else{
                echo "You are not logged in";
                exit;
            }

        }
    
    }
    // The user is already logged in if they can see this section in the nav bar, so login validation is not required here

    require_once("connectionDB.php"); // database connection file

    $customerData = array(); // Initialize the variable


    if (isset($_SESSION['customerID'])) 
    {
        $customerID = $_SESSION['customerID'];
        try {
            $query = $db->prepare('SELECT * FROM customers WHERE customerID = ?');
            $query->execute([$customerID]);
            $rowCount = $query->rowCount();

                // Check if any rows were returned
                if ($rowCount > 0) {
                    // Fetch customer data
                    $customerData = $query->fetch(PDO::FETCH_ASSOC);
                    $retrieveCustomerID = $customerData['customerID'];
                    $retrieveAddressquery = $db->prepare('SELECT * FROM address WHERE customerID = ?');
                    $success = $retrieveAddressquery->execute([$retrieveCustomerID]);
                    $rowCount = $retrieveAddressquery->rowCount();
                    if ($rowCount > 0) {
                        $addressData = $retrieveAddressquery->fetch(PDO::FETCH_ASSOC);
                        $addressID = $addressData['addressID'];

                    }

                } else {
                    echo "No matching customer found.";
                }
            }  catch (PDOException $ex) 
            {
                echo("Failed to fetch customer data.<br>");
                echo($ex->getMessage());
                exit;
            }
        }elseif(isset($_SESSION['adminID'])){ // if the user is an admin
            $adminID = $_SESSION['adminID'];
            try {
                $query = $db->prepare('SELECT * FROM admin WHERE adminID = ?');
                $query->execute([$adminID]);
                $rowCount = $query->rowCount();
                //echo $rowCount; Testing
    
                    // Check if any rows were returned
                    if ($rowCount > 0) {
                        // Fetch admin data
                        $adminData = $query->fetch(PDO::FETCH_ASSOC);
                        $retrieveAdminID = $adminData['adminID'];
                        $adminAddressData = array(); 

                       // echo $retrieveAdminID; Testing

                        //retrives address of the admin
                        $retrieveAddresID = $adminData['addressID'];
                        $retrieveAddressquery = $db->prepare('SELECT * FROM address WHERE addressID = ?');
                        $retrieveAddressquery->execute([$retrieveAddresID]);
                        $rowCount = $retrieveAddressquery->rowCount();
                        if ($rowCount > 0) {
                            $adminAddressData = $retrieveAddressquery->fetch(PDO::FETCH_ASSOC);
                        
                        }
    
                    } else {
                        echo "No matching customer found.";
                    }
                }  catch (PDOException $ex) 
                {
                    echo("Failed to fetch customer data.<br>");
                    echo($ex->getMessage());
                    exit;
                }


        } else {
            echo "You are not logged in";
            exit;
        }
        
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <title>ACE GEAR</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="UTF-8">
        <link rel="stylesheet" href="CSS/styles.css">
        <link rel="stylesheet" href="CSS/editAccount.css">
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
            <a href="productsDisplay.php">Products</a>
            <a href="about.php">About</a>
            <a href="members-blog.php">Blog</a>
            <a href="contact.php">Contact</a>
            <?php
            if (isset($_SESSION['adminID'])) {
                echo "<a href='Dashboard.php'>Dashboard</a>";
            }
            ?>
            <a href="logout.php">Logout</a>
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

    <h1>My Account</h1>

    <div class="my-account-container">
       <!--  <h2>My Account</h2> -->

        <section class="my-details">
            <h3> My Current Details</h3>
            <ul>
                <li><strong>Username:</strong> <?php 
                if (isset($_SESSION['adminID'])){
                    echo $adminData['username'];
                    echo "<li><strong>First Name:</strong> " . $adminData['first_name'] . "</li>";
                    echo "<li><strong>Last Name:</strong> " . $adminData['last_name'] . "</li>";
                    echo "<li><strong>Email:</strong> " . $adminData['email'] . "</li>";
                    echo "<li><strong>Address:</strong> " . $adminAddressData['house_number'] . ' ' . $adminAddressData['address_line_1'] . ' ' . $adminAddressData['address_line_2'] . ' ' . $adminAddressData['post_code'] . ' ' . $adminAddressData['city'] . ' ' . $adminAddressData['country'] . "</li>";

                }elseif(isset($_SESSION['customerID'])){
                    echo $customerData['username'];
                    echo "<li><strong>First Name:</strong> " . $customerData['first_name'] . "</li>";
                    echo "<li><strong>Last Name:</strong> " . $customerData['last_name'] . "</li>";
                    echo "<li><strong>Email:</strong> " . $customerData['email'] . "</li>";
                    echo "<li><strong>Address:</strong> " . $addressData['house_number'] . ' ' . $addressData['address_line_1'] . ' ' . $addressData['address_line_2'] . ' ' . $addressData['post_code'] . ' ' . $addressData['city'] . ' ' . $addressData['country'] . "</li>";
                }

                ?>
            </ul>
        </section>


        <section class="update-my-details">
            <h3> Update  my  current details</h3>
            <form action="editAccount.php" method="post">

            <?php
            if (isset($_SESSION['adminID'])){
                echo "<label for='username'>Username:</label>";
                echo "<input type='text' id='username' name='username' value='" . $adminData['username'] . "' required>";
                echo "<label for='password'>Password:</label>";
                echo "<input type='password' id='password' name='password' required>";
                echo "<label for='first_name'>First Name:</label>";
                echo "<input type='text' id='first_name' name='first_name' value='" . $adminData['first_name'] . "' required>";
                echo "<label for='last_name'>Last Name:</label>";
                echo "<input type='text' id='last_name' name='last_name' value='" . $adminData['last_name'] . "' required>";

                echo "<label for='email'>Email:</label>";
                echo"<input type='email' id='email' name='email' value='". $adminData['email'] . "' required>";
                echo"<label for = 'address'>Address:</label>";
                echo "<input type='text' id='house_number' name='house_number' value='House number " . $adminAddressData['house_number'] . "' required>";
                echo "<input type='text' id='address_line_1' name='address line 1' value='Address line 1:" . $adminAddressData['address_line_1']. "' required>";
                echo"<input type='text' id='address_line_2' name='address line 2' value='Address line 2:" . $adminAddressData['address_line_2']. "' required>";
                echo"<input type='text' id='postcode' name='postcode' value='Post code:". $adminAddressData['post_code']."' required>";
                echo"<input type='text' id='city' name='city' value='City: ".$adminAddressData['city']."' required>";
                echo"<input type='text' id='country' name='country' value='Country:". $adminAddressData['country'] ."' required>";
                echo"<br>";
                echo"<input type='submit' value='Update'>";
               echo" <input type='hidden' id= 'editAccountSubmitted' name='editAccountSubmitted' value='TRUE' />";
               echo"</form>";
                



            }elseif(isset($_SESSION['customerID'])){
                echo "<label for='username'>Username:</label>";
                echo "<br>";
                echo "<input type='text' id='username' name='username' value='" . $customerData['username'] . " required>";
                echo "<br>";
                echo "<label for='password'>Password:</label>";
                echo "<br>";
                echo "<input type='password' id='password' name='password' required>";
                echo "<br>";
                echo "<label for='first_name'>First Name:</label>";
                echo "<br>";
                echo "<input type='text' id='first_name' name='first_name' value='" . $customerData['first_name'] . "' required>";
                echo "<br>";
                echo "<label for='last_name'>Last Name:</label>";
                echo "<br>";
                echo "<label for='email'>Email:</label>";
                echo"<br>";
                echo"<input type='email' id='email' name='email' value='". $customerData['email'] . "' required>";
                echo"<br>";
                echo"<label for = 'address'>Address:</label>";
                echo"<br>";
                echo"<input type='text' id='house_number' name=house number' value=' House number" . $addressData['house_number'] ."' required>";
                echo "<input type='text' id='address_line_1' name='address line 1' value='Address line 1:" . $addressData['address_line_1']. "' required>";
                echo"<input type='text' id='address_line_2' name='address line 2' value='Address line 2:" . $addressData['address_line_2']. "' required>";
                echo"<input type='text' id='postcode' name='postcode' value='Post code:". $addressData['post_code']."' required>";
                echo"<input type='text' id='city' name='city' value='City: ".$addressData['city']." required>";
                echo"<input type='text' id='country' name='country' value='Country:". $addressData['country'] ."' required>";
                echo"<br>";
    
                echo"<input type='submit' value='Update'>";
               echo" <input type='hidden' id= 'editAccountSubmitted' name='editAccountSubmitted' value='TRUE' />";
                

            }

            ?>
            </form>
        </section>
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