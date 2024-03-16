<?php
session_start();
include("connectionDB.php");

$sql = "SELECT * FROM products ORDER BY stock ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="CSS/styles.css">
    <link rel="stylesheet" href="CSS/admin.css">
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
                if (isset($_SESSION['username'])) {
                    echo "<a href='members-blog.php'>Blog</a>";
                    echo "<a href='account.php'>Account</a>";
                    echo "<a href='logout.php'>Logout</a>";
                } else {
                    echo "<a href='login.php'>Login</a>";
                }
                ?>
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
        </nav>
        <div id="cart-container">
            <!-- cart icon image with link to cart page -->
            <a href="cart.php">
                <img id="cart-icon" src="Images/cart-no-bg.png" alt="Cart">
                <span id="cart-count"><?php echo $totalQuantity; ?></span>
            </a>
        </div>
    </header>

<div class="content-container">
    <div class="user-management-container">
        <h1>Products sorted by stock</h1>

        <table>
            <tr>
                <th>Product ID</th>
                <th>Image</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Description</th>
                <th>Category ID</th>
                <th>Colour</th>
                <th>Size</th>
                <th>Is Featured</th>
                <th>Is New</th>
                <th>Is Popular</th>
                <th>Stock</th>
            </tr>

            <tr>
            <?php
            $query = "SELECT * FROM products";
            $stmt = $db->query($query);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr data-id='{$row['productID']}'>"; 
                echo "<td><span class='editable' contenteditable='true' data-column='productID' data-id='{$row['productID']}'>{$row['productID']}</span></td>";
                echo "<td><span class='editable' contenteditable='true' data-column='image' data-id='{$row['productID']}'>{$row['image']}</span></td>";
                echo "<td><span class='editable' contenteditable='true' data-column='product_name' data-id='{$row['productID']}'>{$row['product_name']}</span></td>";
                echo "<td><span class='editable' contenteditable='true' data-column='price' data-id='{$row['productID']}'>{$row['price']}</span></td>";
                echo "<td><span class='editable' contenteditable='true' data-column='price_of_product' data-id='{$row['productID']}'>{$row['description']}</span></td>";
                echo "<td><span class='editable' contenteditable='true' data-column='categoryID' data-id='{$row['productID']}'>{$row['categoryID']}</span></td>";
                echo "<td><span class='editable' contenteditable='true' data-column='colour' data-id='{$row['productID']}'>{$row['colour']}</span></td>";
                echo "<td><span class='editable' contenteditable='true' data-column='size' data-id='{$row['productID']}'>{$row['size']}</span></td>";
                echo "<td><span class='editable' contenteditable='true' data-column='stock' data-id='{$row['productID']}'>{$row['stock']}</span></td>";
                echo "<td><span class='editable' contenteditable='true' data-column='is_featured' data-id='{$row['productID']}'>{$row['is_featured']}</span></td>";
                echo "<td><span class='editable' contenteditable='true' data-column='is_new' data-id='{$row['productID']}'>{$row['is_new']}</span></td>";
                echo "<td><span class='editable' contenteditable='true' data-column='is_popular' data-id='{$row['productID']}'>{$row['is_popular']}</span></td>";
                echo "<td>";
                echo "<button class='update-btn' data-id='{$row['productID']}'>Update</button>"; 
                echo "<button class='delete-btn' data-id='{$row['productID']}'>Delete</button>"; 
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </table>
        <!-- Add Order Button -->
        <button id="addProduct">Add Product</button>
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