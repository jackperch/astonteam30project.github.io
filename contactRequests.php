<?php
require_once("connectionDB.php");
try {
    // SQL query to select all contact requests
    $sql = "SELECT contactID, name, email, message, is_member,request_status FROM contact_request ORDER BY contactID DESC";
    $stmt = $db->query($sql);

    // Fetch all the contact requests
    $contactRequests = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>ACE GEAR - Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <link rel="stylesheet" href="CSS/styles.css">
    <link rel="stylesheet" href="CSS/admin.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Sono&display=swap');
    </style>
    <script src="/js/main.js"></script>
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
            session_start();
            if (isset($_SESSION['adminID'])) {
                echo "<a href='Dashboard.php'>Dashboard</a>";
                echo "<a href='adminLogout.php'>Logout</a>";

            }else{
                echo "<a href='login.php'>Login</a>";
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
        }elseif(isset($_SESSION['adminID'])) {
            require_once("connectionDB.php"); // Adjust this path as necessary

            // Fetch the total quantity of items in the user's cart
            $stmt = $db->prepare("SELECT SUM(quantity) AS totalQuantity FROM cart WHERE adminID = :adminID");
            $stmt->execute(['adminID' => $_SESSION['adminID']]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result && $result['totalQuantity'] > 0) 
            {
                $totalQuantity = $result['totalQuantity'];
            }


        }elseif(isset($_SESSION['guest_shopping_cart']))
        {

            // Fetch the total quantity of items in the guest's cart
                $totalQuantity = array_sum($_SESSION['guest_shopping_cart']);
        
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

    <h1><center>Contact Requests</center></h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Message</th>
            <th>Member?</th>
            <th>Request Status</th>
            <th>Update</th>
            <th>Delete</th>

        </tr>
        <?php 
                if(!empty($contactRequests)){
                    foreach($contactRequests as $request){
                        echo "<form method='post' action='updateContactRequest.php'>";
                          
                        echo "<tr>";
                        echo "<input type='hidden' name='contactID' value='{$request['contactID']}'>"; 
                        echo "<td>{$request['contactID']}</td>";
                        echo "<td>"; 
                        echo "<input type='hidden' name='name' value='{$request['name']}'>"; 
                        echo "{$request['name']}"; 
                        echo "<td>"; 
                        echo "<input type='hidden' name='email' value='{$request['email']}'>"; 
                        echo "{$request['email']}"; 
                        echo "</td>"; 
                        echo "<td>"; 
                        echo "<input type='hidden' name='message' value='{$request['message']}'>"; 
                        echo "{$request['message']}"; 
                        echo "</td>"; 
                        echo "<td>";
                        echo "<input type='hidden' name='is_member' value='{$request['is_member']}'>"; 
                        echo "{$request['is_member']}"; 
                        echo "</td>";              
                        echo "<td>";
                         echo "<select name='request_status'>";
                    
                            $options = array( 'Waiting response', 'Responded');
                            foreach ($options as $option) {
                                echo "<option value='{$option}'"; 
                                if ($request['request_status'] == $option) { 
                                    echo " selected"; 
                                }
                                echo ">{$option}</option>"; 
                            }
                            echo "</select>";
                            echo "</td>";
                            echo "<td><button type='submit' name='update' class='update-btn'>Update</button></td>";
                            echo "<td><button type='submit' name='delete' class='delete-btn'>Delete</button></td>";
                            echo "</tr>";
                        echo "</form>";
                    echo "</tr>";
                        }
                    }else{
                        echo "<tr>";

                        echo "<td colspan='5'>No contact requests found.</td>";
                    echo "</tr>";
                    }
                    
        ?>

    </table>    



</body>