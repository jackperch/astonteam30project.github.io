<?php
session_start();
include("connectionDB.php");

// Update User
if(isset($_POST['update-btn'])) {
    $query = "UPDATE customers SET CustomerID= $CustomerID, username= $username, password= $password, first_name= $first_name, last_name= $last_name, email= $email WHERE CustomerID= $CustomerID";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':CustomerID', $CustomerID);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':first_name', $first_name);
    $stmt->bindParam(':last_name', $last_name);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    exit;
}


// Delete User
if(isset($_POST['delete-btn'])) {
    $CustomerID = $_POST['CustomerID'];

    $query = "DELETE FROM customers WHERE CustomerID=:CustomerID";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':CustomerID', $CustomerID);
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
            <a href="products.php">Products</a>
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
        <h1>Users Management</h1>

        <table>
            <tr>
                <th>Customer ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email Address</th>
                <th>Username</th>
                <th>Action</th>
            </tr>

            <tr>
            <?php
            $query = "SELECT * FROM customers";
            $stmt = $db->query($query);
            while ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr data-id='{$user['CustomerID']}'>"; 
                echo "<td><span class='editable' contenteditable='true' data-column='CustomerID' data-id='{$user['CustomerID']}'>{$user['CustomerID']}</span></td>";
                echo "<td><span class='editable' contenteditable='true' data-column='first_name' data-id='{$user['CustomerID']}'>{$user['first_name']}</span></td>";
                echo "<td><span class='editable' contenteditable='true' data-column='last_name' data-id='{$user['CustomerID']}'>{$user['last_name']}</span></td>";
                echo "<td><span class='editable' contenteditable='true' data-column='email' data-id='{$user['CustomerID']}'>{$user['email']}</span></td>";
                echo "<td><span class='editable' contenteditable='true' data-column='username' data-id='{$user['CustomerID']}'>{$user['username']}</span></td>";
                echo "<td>";
                echo "<button class='update-btn' data-id='{$user['CustomerID']}'>Update</button>"; 
                echo "<button class='delete-btn' data-id='{$user['CustomerID']}'>Delete</button>"; 
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </table>
        <!-- Add User Button -->
        <button id="addUser">Add User</button>
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
        const CustomerID = button.dataset.id; 
        const row = button.parentNode.parentNode;
        const editableFields = row.querySelectorAll('.editable');
        const dataToUpdate = {};
        editableFields.forEach(field => {
            const column = field.dataset.column;
            const value = field.textContent.trim();
            dataToUpdate[column] = value;
        });
        if (confirm("Are you sure you want to update this user?")) {
            updateUserData(CustomerID, dataToUpdate);
        }
    });
});

    // Delete User Functionality
    const deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach(button => {
        button.addEventListener('click', () => {
            const CustomerID = button.dataset.id; 
            if (confirm("Are you sure you want to delete this user?")) {
                deleteUserData(CustomerID);
            }
        });
    });

    function updateUserData(CustomerID, data) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '', true); 
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                console.log(xhr.responseText);
                alert('User data updated successfully.');
            } else {
                alert('Failed to update user data.');
            }
        }
    };
    xhr.send(JSON.stringify({ CustomerID, data }));
}


    function deleteUserData(CustomerID) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '', true); 
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                console.log(xhr.responseText);
                
                const deletedRow = document.querySelector(`tr[data-id="${CustomerID}"]`);
                if (deletedRow) {
                    deletedRow.remove();
                }
            }
        };
        xhr.send(`delete-btn=true&CustomerID=${CustomerID}`);
    }
//Adding user
document.addEventListener("DOMContentLoaded", function() {
        const addUserButton = document.getElementById("addUser");

        addUserButton.addEventListener("click", function() {
            window.location.href = "adduser.php";
        });
    });

</script>


</body>
</html>