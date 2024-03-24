<?php
session_start();
include("connectionDB.php");

// if(isset($_GET['id'])) {
//     $orderID = $_GET['id'];

//     $query = "SELECT op.productID, op.quantity, op.total_price, p.product_name 
//               FROM orders_products op 
//               JOIN products p ON op.productID = p.productID 
//               WHERE op.orderID = :orderID";
//     $stmt = $db->prepare($query);
//     $stmt->execute(['orderID' => $orderID]);
// }

// // Adding product
// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $productID = $_POST['productID'];
//     $quantity = $_POST['quantity'];

//     $query = "INSERT INTO orders_products (productID, quantity, total_price) VALUES (:productID, :quantity, :total_price)";
//     $stmt = $db->prepare($query);
//     $stmt->execute(['orderID' => $orderID, 'productID' => $productID, 'quantity' => $quantity, 'total_price' => $total_price]);
// }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Product Management</title>
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
            require_once("connectionDB.php"); // Database connection path

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
                <th>Order Product ID</th>
                <th>Order ID</th>
                <th>Product ID</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Update</th>
                <th>Delete</th>
            </tr>

            <?php
            $query = "SELECT * FROM orders_products";
            $stmt = $db->query($query);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {


                echo "<form method='post' action='updateOrderProducts.php'>";
                echo "<tr>"; 
                echo "<input type='hidden' name='ordersProductID' value='{$row['ordersProductID']}'>"; // Add a hidden input to store the productID
                echo "<td>{$row['ordersProductID']}</td>";
                echo "<td> <input type='text' name='orderID' value='{$row['orderID']}'></td>";
                echo "<td> <input type='text' name='productID' value='{$row['productID']}'></td>";
                echo "<td> <input type='text' name='quantity' value='{$row['quantity']}'></td>";
                echo "<td> <input type='text' name='total_price' value='{$row['total_price']}'></td>";
                echo "<td><button type='submit' name='update' class='update-btn'>Update</button></td>";
                echo "<td><button type='submit' name='delete' class='delete-btn'>Delete</button></td>";
                echo "</form>";        
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
                <label for="productID">Product ID:</label>
                <input type="text" id="productID" name="productID" required><br>
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


<script>
const addProductButton = document.getElementById('addProductButton');
const addProductFormContainer = document.getElementById('addProductFormContainer');

addProductButton.addEventListener('click', function() {
    addProductFormContainer.style.display = 'block';
});

// Form submission
const addProductForm = document.getElementById('addProductForm');
addProductForm.addEventListener('submit', function(event) {
    event.preventDefault();

    const productID = document.getElementById('productID').value; // Updated to get product ID
    const quantity = document.getElementById('quantity').value;

    const xhr = new XMLHttpRequest();
    xhr.open('POST', '<?php echo $_SERVER["PHP_SELF"]; ?>', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            console.log(xhr.responseText);
            alert('Product added successfully.');

            location.reload();
        } else {
            console.error('Error adding product:', xhr.statusText);
            alert('Failed to add product.');
        }
    };
    xhr.onerror = function() {
        console.error('Network error while adding product.');
        alert('Network error. Please try again.');
    };
    xhr.send(`productID=${productID}&quantity=${quantity}`); // Updated to send product ID
});
</script>
</body>
</html>
