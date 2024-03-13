<?php
session_start();
include("connectionDB.php");

// Update Admin
if(isset($_POST['update-btn'])) {
    $requestData = json_decode(file_get_contents("php://input"), true);
    $query = "UPDATE admin SET adminID=:adminID, username=:username, password=:password WHERE adminID=:adminID";
    $stmt = $db->prepare($query);
    $adminID = $_POST['adminID']; 
    $username = $_POST['username']; 
    $stmt->bindParam(':adminID', $adminID);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password); 
    $stmt->execute();
    exit;
}


// Delete admin
if(isset($_POST['delete-btn'])) {
    $adminID = $_POST['adminID'];

    $query = "DELETE FROM admin WHERE adminID=:adminID";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':adminID', $adminID);
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
            $query = "SELECT * FROM admin";
            $stmt = $db->query($query);
            while ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr data-id='{$user['adminID']}'>"; 
                echo "<td><span class='editable' contenteditable='true' data-column='adminID' data-id='{$user['adminID']}'>{$user['adminID']}</span></td>";
                echo "<td><span class='editable' contenteditable='true' data-column='username' data-id='{$user['adminID']}'>{$user['username']}</span></td>";
                echo "<td>";
                echo "<button class='update-btn' data-id='{$user['adminID']}'>Update</button>"; 
                echo "<button class='delete-btn' data-id='{$user['adminID']}'>Delete</button>"; 
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </table>
        <!-- Add admin Button -->
        <button id="addAdmin">Add Admin</button>
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
        const adminID = button.dataset.id; 
        const row = button.parentNode.parentNode;
        const editableFields = row.querySelectorAll('.editable');
        const dataToUpdate = {};
        editableFields.forEach(field => {
            const column = field.dataset.column;
            const value = field.textContent.trim();
            dataToUpdate[column] = value;
        });
        if (confirm("Are you sure you want to update this admin?")) {
            updateAdminData(adminID, dataToUpdate);
        }
    });
});

    const deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach(button => {
        button.addEventListener('click', () => {
            const adminID = button.dataset.id; 
            if (confirm("Are you sure you want to delete this admin?")) {
                deleteAdminData(adminID);
            }
        });
    });

    function updateAdminData(adminID, data) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '', true); 
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                console.log(xhr.responseText);
                alert('admin data updated successfully.');
            } else {
                alert('Failed to update admin data.');
            }
        }
    };
    const requestData = { adminID: adminID, ...data };  
    xhr.send(JSON.stringify(requestData));
}



    function deleteAdminData(adminID) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '', true); 
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                console.log(xhr.responseText);
                
                const deletedRow = document.querySelector(`tr[data-id="${adminID}"]`);
                if (deletedRow) {
                    deletedRow.remove();
                }
            }
        };
        xhr.send(`delete-btn=true&adminID=${adminID}`);
    }
//Adding admin
document.addEventListener("DOMContentLoaded", function() {
        const addAdminButton = document.getElementById("addAdmin");

        addAdminButton.addEventListener("click", function() {
            window.location.href = "addAdmin.php";
        });
    });

</script>


</body>
</html>