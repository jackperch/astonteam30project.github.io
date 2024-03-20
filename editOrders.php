<?php
session_start();
include("connectionDB.php");

// Update order
// if(isset($_POST['update-btn'])) {
//     $query = "UPDATE orders SET customerID=:customerID, order_date=:order_date, total_amount=:total_amount, addressID=:addressID, paymentInfoID=:paymentInfoID, order_completed=:order_completed WHERE orderID=:orderID";
//     $stmt = $db->prepare($query);
//     $stmt->bindParam(':orderID', $orderID);
//     $stmt->bindParam(':customerID', $customerID);
//     $stmt->bindParam(':order_date', $order_date);
//     $stmt->bindParam(':total_amount', $total_amount);
//     $stmt->bindParam(':addressID', $addressID);
//     $stmt->bindParam(':paymentInfoID', $paymentInfoID);
//     $stmt->bindParam(':order_completed', $order_completed);
//     $stmt->execute();
//     exit;
// }



// Delete order
// if(isset($_POST['delete-btn'])) {
//     $orderID = $_POST['orderID'];

//     $query = "DELETE FROM orders WHERE orderID=:orderID";
//     $stmt = $db->prepare($query);
//     $stmt->bindParam(':orderID', $orderID);
//     $stmt->execute();
//     exit;
// }
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

        <nav>
            <a href="index.php">Home</a>
            <a href="products.php">Products</a>
            <a href="about.php">About</a>
            <a href="contact.php">Contact</a>
             <?php 

                if (isset($_SESSION['adminID'])) {
                    echo "<a href='Dashboard.php'>Dashboard</a>";
                    echo "<a href='logout.php'>Logout</a>";
                }else{
                    exit;
                }
                ?>
        <?php
        // Initialize the total quantity variable
        $totalQuantity = 0;

        if(isset($_SESSION['adminID'])){
            require_once("connectionDB.php"); // Adjust this path as necessary
            $smt=$db->prepare("SELECT SUM(quantity) AS totalQuantity FROM cart WHERE  adminID = :adminID");
            $smt->execute(['adminID' => $_SESSION['adminID']]);
            $result = $smt->fetch(PDO::FETCH_ASSOC);
            if ($result && $result['totalQuantity'] > 0) {
                $totalQuantity = $result['totalQuantity'];
            }
        }else{
            header("Location: error.php?error=no_session");
            exit;
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
                <th>Order Date</th>
                <th>Order Total £</th>
                <th>Adress ID</th>
                <th>Payment ID</th>
                <th>Order Completed</th>
                <th>Update</th>
                <th>Delete</th>
                <th>View Order Products</th>
            </tr>

            
            <?php
            $query = "SELECT * FROM orders";
            $stmt = $db->query($query);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                echo "<form method='post' action='updateOrders.php'>";
                echo "<tr>"; 
                echo "<input type='hidden' name='orderID' value='{$row['orderID']}'>"; // Add a hidden input to store the productID
                echo "<td>{$row['orderID']}</td>";
                echo "<td> <input type='text' name='customerID' value='{$row['customerID']}'></td>";
                echo "<td> <input type='text'name='order_date' value='{$row['order_date']}'></td>";
                echo "<td> <input type='text'name='total_amount' value='{$row['total_amount']}'></td>";
                echo "<td><input type='text' name='addressID' value='{$row['addressID']}'></td>";
                echo "<input type='hidden' name='paymentInfoID' value='{$row['paymentInfoID']}'>"; // Add a hidden input to store the productID
                echo "<td>{$row['paymentInfoID']}</td>";
                //echo "<td> <input type='text'name='paymentInfoID' value='{$row['paymentInfoID']}'></td>";
                echo "<td><input type='text' name='order_completed' value='{$row['order_completed']}'></td>";
                echo "<td><button type='submit' name='update' class='update-btn'>Update</button></td>";
                echo "<td><button type='submit' name='delete' class='delete-btn'>Delete</button></td>";
                echo "</form>";        
                echo "<td><button class='products-btn' data-id='{$row['orderID']}' onclick=\"window.location.href='orderProducts.php?id={$row['orderID']}'\">View Products</button></td";
                echo "</tr>";
                      









                echo "</td>";
                echo "</tr>";
            }
            ?>
        </table>
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

</script>


</body>
</html>