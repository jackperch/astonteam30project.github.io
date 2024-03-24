<?php
session_start();
include("connectionDB.php");

//Asia's old code that did not work 
// Update User
// if(isset($_POST['update-btn'])) {
//     $query = "UPDATE customers SET customerID=:customerID, username=:username, password=:password, first_name=:first_name, last_name=:last_name, email=:email WHERE customerID=:customerID";
//     $stmt = $db->prepare($query);
//     $customerID = $_POST['customerID']; 
//     $username = $_POST['username'];
//     $password = $_POST['password'];
//     $first_name = $_POST['first_name'];
//     $last_name = $_POST['last_name'];
//     $email = $_POST['email'];
    
//     $stmt->execute();
//     exit;
// }


// // Delete User
// if(isset($_POST['delete-btn'])) {
//     $customerID = $_POST['customerID'];

//     $query = "DELETE FROM customers WHERE customerID=:customerID";
//     $stmt = $db->prepare($query);
//     $stmt->bindParam(':customerID', $customerID);
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
    <link rel="stylesheet" href="CSS/admin.css">>
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
                if (isset($_SESSION['adminID'])) {
                    echo "<a href='Dashboard.php'>Dashboard</a>";
                    echo "<a href='logout.php'>Logout</a>";
                }
                ?>
        <?php
        // Initialize the total quantity variable
        $totalQuantity = 0;

        if(isset($_SESSION['adminID'])){
            require_once("connectionDB.php"); // Database connection path
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
        <h1>Users Management</h1>

        <table>
            <tr>
                <th>Customer ID</th>
                <th>Username</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email Address</th>
                <th>Update</th>
                <th>Delete</th>
            
            </tr>

            
            <?php
            $query = "SELECT * FROM customers";
            $stmt = $db->query($query);
            while ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<form method='post' action='updateUsers.php'>";
                    echo "<tr>";
                    echo "<input type='hidden' name='customerID' value='{$user['customerID']}'>"; 
                    echo "<td>{$user['customerID']}</td>";
                    echo "<td><input type='text' name='username' value='{$user['username']}'>";
                    echo "<td><input type='text' name='first_name' value='{$user['first_name']}' ></td>";
                    echo "<td><input type='text' name='last_name' value='{$user['last_name']}' ></td>";
                    echo "<td><input type='text' name='email' value='{$user['email']}' ></td>";
                    echo "<td><button type='submit' name='update' class='update-btn'>Update</button></td>";
                    echo "<td><button type='submit' name='delete' class='delete-btn'>Delete</button></td>";
                    echo "</tr>";
                echo "</form>";

            }
            ?>
        </table>
        <!-- Add User Button -->
        <a href="adduser.php"><button id="addUser"> Add customer</button> </a>

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


    <!-- Asais old code that did not work -->
<!-- <script>
const updateButtons = document.querySelectorAll('.update-btn');
updateButtons.forEach(button => {
    button.addEventListener('click', () => {
        const customerID = button.dataset.id; 
        const row = button.parentNode.parentNode;
        const editableFields = row.querySelectorAll('.editable');
        const dataToUpdate = {};
        editableFields.forEach(field => {
            const column = field.dataset.column;
            const value = field.textContent.trim();
            dataToUpdate[column] = value;
        });
        if (confirm("Are you sure you want to update this user?")) {
            updateUserData(customerID, dataToUpdate);
        }
    });
});

    // Delete User Functionality
    const deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach(button => {
        button.addEventListener('click', () => {
            const customerID = button.dataset.id; 
            if (confirm("Are you sure you want to delete this user?")) {
                deleteUserData(customerID);
            }
        });
    });

    function updateUserData(customerID, data) {
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
    
    xhr.send(JSON.stringify({ customerID, data }));
}


    function deleteUserData(customerID) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '', true); 
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                console.log(xhr.responseText);
                
                const deletedRow = document.querySelector(`tr[data-id="${customerID}"]`);
                if (deletedRow) {
                    deletedRow.remove();
                }
            }
        };
        xhr.send(`delete-btn=true&CustomerID=${customerID}`);
    }
//Adding user
document.addEventListener("DOMContentLoaded", function() {
        const addUserButton = document.getElementById("addUser");

        addUserButton.addEventListener("click", function() {
            window.location.href = "adduser.php";
        });
    });

</script> -->


</body>
</html>