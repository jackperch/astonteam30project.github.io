<?php
session_start();
include("connectionDB.php");

// Add New User
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {
    $productID = $_POST['productID'];
    $image = $_POST['image'];
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $categoryID = $_POST['categoryID'];
    $colour = $_POST['colour'];
    $size = $_POST['size'];
    $stock = $_POST['stock'];
    $is_featured = $_POST['is_featured'];
    $is_new = $_POST['is_new'];
    $is_popular = $_POST['is_popular'];

    $query = "INSERT INTO products (productID, image, product_name, price, description, categoryID, colour, size, stock, is_featured, is_new, is_popular) VALUES (:productID, :image, :product_name, :price, :description, :categoryID, :colour, :size, :stock, :is_featured, :is_new, :is_popular)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':productID', $productID);
    $stmt->bindParam(':image', $image);
    $stmt->bindParam(':product_name', $product_name);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':categoryID', $categoryID);
    $stmt->bindParam(':colour', $colour);
    $stmt->bindParam(':size', $size);
    $stmt->bindParam(':is_featured', $is_featured);
    $stmt->bindParam(':is_new', $is_new);
    $stmt->bindParam(':is_popular', $is_popular);
    $stmt->bindParam(':stock', $stock);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Redirect to editProducts.php after adding the product
        header("Location: editProducts.php");
        exit;
    } else {
        echo "Failed to add product.";
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
        <link rel="stylesheet" href="CSS/addProducts.css">
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
            
            <nav>
                <a href="index.php">Home</a>
                <a href="productsDisplay.php">Products</a>
                <a href="about.php">About</a>
                <a href="members-blog.php">Blog</a>
                <a href="contact.php">Contact</a>
                <?php 
                if (isset($_SESSION['adminID'])) {
                    echo "<a href='Dashboard.php'>Dashboard</a>";
                    echo "<a href='logout.php'>Logout</a>";
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
        <div class="Products-Container">
            <h2>Add New Product</h2>
            <form method="post">
                <!-- <input type="text" id="productID" name="productID"  placeholder="product ID"  onblur="validateFirstName()">
                <span id="first-name-error"></span> -->

                <input type="text" id="product_name" name="product_name"  placeholder="product name"  onblur="validateFirstName()">
                <span id="first-name-error"></span>

                <input type="text" id="categoryID" name="categoryID"  placeholder="category ID"  onblur="validateFirstName()">
                <span id="first-name-error"></span>

                <input type="text" id="image" name="image"  placeholder="enter image file name"  onblur="validateFirstName()">

                <input type="text" id="price" name="price"  placeholder="price"   onblur="validateLastName()">
                <span id="last-name-error"></span>

                <input type="text" id="description" name="description"  placeholder="description" onblur="validateUsername()">
                <span id="username-error"></span>

                <input type="text" id="colour" name="colour"  placeholder="colour" onblur="validateEmail()">
                <span id="email-error"></span>

                <input type="text" id="size" name="size"  placeholder="size" onblur="validateEmail()">
                <span id="email-error"></span>

                <input type="text" id="is_featured" name="is_featured"  placeholder="is featured" onblur="validateEmail()">
                <span id="email-error"></span>

                <input type="text" id="is_new" name="is_new"  placeholder="is new" onblur="validateEmail()">
                <span id="email-error"></span>

                <input type="text" id="is_popular" name="is_popular"  placeholder="is popular" onblur="validateEmail()">
                <span id="email-error"></span>

                <input type="text" id="stock" name="stock"  placeholder="stock" onblur="validateEmail()">
                <span id="email-error"></span>

                <input name="submit" type="submit" value="Add New Product">
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
