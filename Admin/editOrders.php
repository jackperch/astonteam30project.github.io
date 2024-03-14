<?php
session_start();
include("connectionDB.php");

// Update order
if(isset($_POST['update-btn'])) {
    $query = "UPDATE orders SET productID=:productID, customerID=:customerID, quantity=:quantity, price_of_product=:price_of_product, order_date=:order_date, total_amount=:total_amount, addressID=:addressID, paymentInfoID=:paymentInfoID WHERE orderID=:orderID";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':orderID', $orderID);
    $stmt->bindParam(':productID', $productID);
    $stmt->bindParam(':customerID', $customerID);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->bindParam(':price_of_product', $price_of_product);
    $stmt->bindParam(':order_date', $order_date);
    $stmt->bindParam(':total_amount', $total_amount);
    $stmt->bindParam(':addressID', $addressID);
    $stmt->bindParam(':aymentInfoID', $aymentInfoID);
    $stmt->execute();
    exit;
}



// Delete order
if(isset($_POST['delete-btn'])) {
    $orderID = $_POST['orderID'];

    $query = "DELETE FROM orders WHERE orderID=:orderID";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':orderID', $orderID);
    $stmt->execute();
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="CSS/styles.css">
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
        <h1>Orders Management</h1>

        <table>
            <tr>
                <th>Order ID</th>
                <th>Customer ID</th>
                <th>Product ID</th>
                <th>Quantity</th>
                <th>Price of product</th>
                <th>Order Date</th>
                <th>Total Amount</th>
                <th>Adress ID</th>
                <th>Payment ID</th>
                <th>Action</th>
            </tr>

            <tr>
            <?php
            $query = "SELECT * FROM orders";
            $stmt = $db->query($query);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr data-id='{$row['orderID']}'>"; 
                echo "<td><span class='editable' contenteditable='true' data-column='orderID' data-id='{$row['orderID']}'>{$row['orderID']}</span></td>";
                echo "<td><span class='editable' contenteditable='true' data-column='customerID' data-id='{$row['orderID']}'>{$row['customerID']}</span></td>";
                echo "<td><span class='editable' contenteditable='true' data-column='productID' data-id='{$row['orderID']}'>{$row['productID']}</span></td>";
                echo "<td><span class='editable' contenteditable='true' data-column='quantity' data-id='{$row['orderID']}'>{$row['quantity']}</span></td>";
                echo "<td><span class='editable' contenteditable='true' data-column='price_of_product' data-id='{$row['orderID']}'>{$row['price_of_product']}</span></td>";
                echo "<td><span class='editable' contenteditable='true' data-column='order_date' data-id='{$row['orderID']}'>{$row['order_date']}</span></td>";
                echo "<td><span class='editable' contenteditable='true' data-column='total_amount' data-id='{$row['orderID']}'>{$row['total_amount']}</span></td>";
                echo "<td><span class='editable' contenteditable='true' data-column='addressID' data-id='{$row['orderID']}'>{$row['addressID']}</span></td>";
                echo "<td><span class='editable' contenteditable='true' data-column='paymentInfoID' data-id='{$row['orderID']}'>{$row['paymentInfoID']}</span></td>";
                echo "<td>";
                echo "<button class='update-btn' data-id='{$row['orderID']}'>Update</button>"; 
                echo "<button class='delete-btn' data-id='{$row['orderID']}'>Delete</button>"; 
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </table>
        <!-- Add Order Button -->
        <button id="addOrder">Add Order</button>
    </div>
</div>

<footer style=" bottom: 0; margin-top: auto; position: absolute;">
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
const updateButtons = document.querySelectorAll('.update-btn');
updateButtons.forEach(button => {
    button.addEventListener('click', () => {
        const orderID = button.dataset.id; 
        const row = button.parentNode.parentNode;
        const editableFields = row.querySelectorAll('.editable');
        const dataToUpdate = {};
        editableFields.forEach(field => {
            const column = field.dataset.column;
            const value = field.textContent.trim();
            dataToUpdate[column] = value;
        });
        if (confirm("Are you sure you want to update this order?")) {
            updateOrderData(orderID, dataToUpdate);
        }
    });
});

    // Delete order Functionality
    const deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach(button => {
        button.addEventListener('click', () => {
            const orderID = button.dataset.id; 
            if (confirm("Are you sure you want to delete this order?")) {
                deleteOrderData(orderID);
            }
        });
    });

    function updateOrderData(orderID, data) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '', true); 
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                console.log(xhr.responseText);
                alert('Order data updated successfully.');
            } else {
                alert('Failed to update order data.');
            }
        }
    };
    xhr.send(JSON.stringify({ orderID, data }));
}


function deleteOrderData(orderID) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '', true); 
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            console.log(xhr.responseText);
            
            const deletedRow = document.querySelector(`tr[data-id="${orderID}"]`);
            if (deletedRow) {
                deletedRow.remove();
            }
        }
    };
    xhr.send(`delete-btn=true&orderID=${orderID}`);
}

//Adding order
document.addEventListener("DOMContentLoaded", function() {
        const addOrderButton = document.getElementById("addOrder");

        addOrderButton.addEventListener("click", function() {
            window.location.href = "addOrder.php";
        });
    });

</script>


</body>
</html>