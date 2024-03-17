<?php
session_start();
include("connectionDB.php");

if(isset($_GET['id'])) {
    $orderID = $_GET['id'];

    $query = "SELECT op.productID, op.quantity, op.total_price, p.product_name 
              FROM orders_products op 
              JOIN products p ON op.productID = p.productID 
              WHERE op.orderID = :orderID";
    $stmt = $db->prepare($query);
    $stmt->execute(['orderID' => $orderID]);
}

// Adding product
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productName = $_POST['productName'];
    $quantity = $_POST['quantity'];

    // Fetch productID from products table
    $stmt = $db->prepare("SELECT productID FROM products WHERE product_name = :productName");
    $stmt->execute(['productName' => $productName]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $productID = $row['productID'];

    // Insert product into orders_products table
    $query = "INSERT INTO orders_products (productID, quantity, total_price) VALUES (:productID, :quantity, :total_price)";
    $stmt = $db->prepare($query);
    $stmt->execute(['orderID' => $orderID, 'productID' => $productID, 'quantity' => $quantity, 'total_price' => $total_price]);
}
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
        <h1>Products Management</h1>

        <table>
            <tr>
                <th>Product ID</th>
                <th>Quantity</th>
                <th>Total Price</th>
            </tr>

            <?php
            $query = "SELECT * FROM orders_products";
            $stmt = $db->query($query);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr data-id='{$row['ordersProductID']}'>"; 
                echo "<td>{$row['productID']}</td>";
                echo "<td>{$row['quantity']}</td>";
                echo "<td>{$row['total_price']}</td>";
                echo "<td>";
                echo "<button class='update-btn' data-id='{$row['ordersProductID']}'>Update</button>"; 
                echo "<button class='delete-btn' data-id='{$row['ordersProductID']}'>Delete</button>"; 
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </table>
        
        <!-- Add Product Button -->
        <button id="addProductButton">Add Product</button>

        <!-- Add Product Form -->
        <div id="addProductFormContainer" style="display: none;">
            <h2>Add Product</h2>
            <form id="addProductForm">
                <label for="productName">Product Name:</label>
                <input type="text" id="productName" name="productName" required><br>
                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" required><br>
                <button type="submit">Add Product</button>
            </form>
        </div>
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

<!-- Script for form submission -->
<script>
const addProductButton = document.getElementById('addProductButton');
const addProductFormContainer = document.getElementById('addProductFormContainer');

addProductButton.addEventListener('click', function() {
    addProductFormContainer.style.display = 'block';
});

// Form submission
const addProductForm = document.getElementById('addProductForm');
addProductForm.addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent default form submission

    const productName = document.getElementById('productName').value;
    const quantity = document.getElementById('quantity').value;

    // AJAX to fetch product ID and insert into orders_products table
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '<?php echo $_SERVER["PHP_SELF"]; ?>', true); // PHP_SELF refers to the current script
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            // Handle successful response
            console.log(xhr.responseText);
            alert('Product added successfully.');
            // You might want to reload the page or update the product list after adding a new product
            location.reload();
        } else {
            // Handle error response
            console.error('Error adding product:', xhr.statusText);
            alert('Failed to add product.');
        }
    };
    xhr.onerror = function() {
        // Handle network errors
        console.error('Network error while adding product.');
        alert('Network error. Please try again.');
    };
    xhr.send(`productName=${productName}&quantity=${quantity}`);
});
</script>
</body>
</html>
