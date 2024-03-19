<?php
 if(isset($_POST['contactSubmitted'])) { //If the form is submited then code below will run
    require_once("connectionDB.php"); //Connects to the database

    //Stores the values from the form ( post array)  to variables
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    //if its  ticked it will equal to member to be stored in the contact table of its not ticked its stored as not member
    if ($_POST['member?']==true){
        $member="member";
    }else{
        $member="not member";
    }

    //Looks if the fields are empty
    if ($name == false || $email == false || $message == false) { //Returns false if the varaibles are empty
        echo "One or more fields are empty. Please enter valid values.";
        exit;
    }
    // Tries to  insert data to the contact table
    try{
        $insertToContactTable = $db->prepare('INSERT INTO contact_request (name, email, message, is_member) VALUES (?, ?, ?, ?)');
        $insertToContactTable->execute(array($name, $email, $message, $member));


    }catch(PDOException $excption) {
        echo("Failed to connect to the database.<br>");
        echo($exception->getMessage());
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
        <link rel="stylesheet" href="CSS/contact.css">
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Sono&display=swap');
        </style>
      
        
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
                <?php 
                session_start();
                if (isset($_SESSION['customerID'])) {
                    echo "<a href='members-blog.php'>Blog</a>";
                    echo "<a href='account.php'>Account</a>";
                    echo "<a href='logout.php'>Logout</a>";
                } elseif (isset($_SESSION['adminID'])) 
                {
        
                    echo "<a href='Dashboard.php'>Dashboard</a>";
                    echo "<a href='account.php'>Account</a>";
                    echo "<a href='logout.php'>Logout</a>";

                }else
                {
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

    <h1><center>Contact Us<center></h1>

    <div class="contact-details-container">
        <div class="contact-info">
            <h2 id="sub-heading">Address:</h2>
            <p id="contact-detail">Ace Gear Sports</p>
            <p id="contact-detail">123 Fake Street</p>
            <p id="contact-detail">London</p>
            <p id="contact-detail">M40 AK7</p>
            <p id="contact-detail">United Kingdom</p>
        </div>
        <div class="contact-info">
            <h2 id="sub-heading">Phone:</h2>
            <p id="contact-detail">+(00)120 1258 5678</p>
        </div>
        <div class="contact-info">
            <h2 id="sub-heading">Email:</h2>
            <p id="contact-detail">info@acegear.com</p>
        </div>
    </div>

    <div class="contact-form-container">
        <h2>Or, Get in contact with us by filling out the form below :</h2>

        <form action="contact.php" method="post">

            <input type="text" id="name" name="name"   placeholder="name" onblur="validateName()">
            <span id="nameError"></span>

            <input type="email" id="email" name="email"  onblur="validateEmail()" placeholder="email">
            <span id="emailError"></span>

            <textarea id="message" name="message" rows="4"  onblur="validateMessage()" placeholder="message..."></textarea>
            <span id="messageError"></span>

            <label for="member?">Please Tick the box below if you are a member of our club</label>
            <input type="checkbox" id="member?" name="member?">
 
            <input  onclick=" return validateForm()" type="submit" name="contactSubmitted" value="Submit">
            <span id="submitError"></span>
        </form>
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
    <script src="contact.js"></script>
</body>
</html>

