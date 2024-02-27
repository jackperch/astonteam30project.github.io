<?php
session_start();
include("connectionDB.php");

// Add New User
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {
    $CustomerID = $_POST['CustomerID'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query = "INSERT INTO customers (CustomerID, first_name, last_name, email, username, password) VALUES (:CustomerID, :first_name, :last_name, :email, :username, :password)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':CustomerID', $CustomerID);
    $stmt->bindParam(':first_name', $first_name);
    $stmt->bindParam(':last_name', $last_name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Redirect to edituser.php after adding the user
        header("Location: editusers.php");
        exit;
    } else {
        echo "Failed to add user.";
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
        <div class="signup-container">
                <h2>ADD New User</h2>
    <form method="post">
    <input type="text" id="first-name" name="first_name"  placeholder="First Name"  onblur="validateFirstName()">
    <span id="first-name-error"></span>

    <input type="text" id="last-name" name="last_name"  placeholder="Last Name"   onblur="validateLastName()">
    <span id="last-name-error"></span>

    <input type="text" id="username" name="username"  placeholder="Username" onblur="validateUsername()">
    <span id="username-error"></span>
    
    <input type="password" id="password" name="password"  placeholder="Password" onblur="validatePassword()">
    <span id="password-error"></span>

    <input type="text" id="email" name="email"  placeholder="E-mail" onblur="validateEmail()">
    <span id="email-error"></span>

    <input name="submit" type="submit" value="Add new user">
    <input type="reset" value="Clear">
    <input type="hidden" name="signupsubmitted" value="TRUE">
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

</html>