<?php
session_start();
include("connectionDB.php");
//Asai's old code that did not work for the buttons 
// // Update Admin
// if(isset($_POST['update-btn'])) {
//     $query = "UPDATE admin SET adminID=:adminID, adminname=:adminname, password=:password WHERE adminID=:adminID";
//     $stmt = $db->prepare($query);
//     $adminID = $_POST['adminID']; 
//     $adminname = $_POST['adminname']; 
//     $stmt->bindParam(':adminID', $adminID);
//     $stmt->bindParam(':adminname', $adminname);
//     $stmt->bindParam(':password', $password); 
//     $stmt->execute();
//     exit;
// }


// // Delete admin
// if(isset($_POST['delete-btn'])) {
//     $adminID = $_POST['adminID'];

//     $query = "DELETE FROM admin WHERE adminID=:adminID";
//     $stmt = $db->prepare($query);
//     $stmt->bindParam(':adminID', $adminID);
//     $stmt->execute();
//     exit;
// }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin Management</title>
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
    <div class="admin-management-container">
        <h1>Admins Management</h1>

        <table>
            <tr>
                <th>Admin ID</th>
                <th>username</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>

                <th>Update</th>
                <th>Delete</th>
            </tr>

            <tr>
            <?php
            $query = "SELECT * FROM admin";
            
            $stmt = $db->query($query);
            while ($admin = $stmt->fetch(PDO::FETCH_ASSOC)) {
               // echo $admin['adminID']; Test
                echo "<form method='post' action='updateAdmin.php'>";
                    echo "<tr>";
                    echo "<input type='hidden' name='adminID' value='{$admin['adminID']}'>"; 
                    echo "<td>{$admin['adminID']}</td>";
                    echo "<td><input type='text' name='username' value='{$admin['username']}'>";
                    echo "<td><input type='text' name='first_name' value='{$admin['first_name']}' ></td>";
                    echo "<td><input type='text' name='last_name' value='{$admin['last_name']}' ></td>";
                    echo "<td><input type='text' name='email' value='{$admin['email']}' ></td>";
                    echo "<td><button type='submit' name='update' class='update-btn'>Update</button></td>";
                    echo "<td><button type='submit' name='delete' class='delete-btn'>Delete</button></td>";
                    echo "</tr>";
                echo "</form>";
            }
            ?>
        </table>
        <!-- Add admin Button -->
        <a href="addAdmin.php"><button id="addAdmin"> Add Admin</button> </a>
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

<!-- <script>

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

</script> -->


</body>
</html>