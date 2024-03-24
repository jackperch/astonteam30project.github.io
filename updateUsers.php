<?php
require_once("connectionDB.php"); 

    if(isset($_SERVER['REQUEST_METHOD'])) {
        $customerID = $_POST['customerID'];
        $username = $_POST['username'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
       

        // Prepare the SQL statement
        try{
        $query = "UPDATE customers SET username=:username, first_name=:first_name, last_name=:last_name, email=:email WHERE customerID=:customerID";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':customerID', $customerID);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        }catch(PDOException $e){
            echo "Error: " . $e->getMessage();
            exit;

        } 
        // Execute the update
        if($stmt->execute()) {
            // Redirect or inform the user of success
            header("Location: editusers.php?status=success");
        } else {
            // Handle failure
            echo "Error updating the customer";
        }
    }else{
        // Redirect or inform the user of failure
        header("Location: editusers.php?status=failed");
    }



    // Check if the delete button was clicked

    if (isset($_POST['delete'])) {
        $customerID = $_POST['customerID'];
    
        // SQL statement to delete the product
        $sql = "DELETE FROM customers  WHERE customerID = :customerID";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':customerID', $customerID, PDO::PARAM_INT);
    
        if ($stmt->execute()) {
            // Redirect back to the editProducts page with a success message
            header("Location: editusers.php?status=deleted");
        } else {
            // Handle failure
            echo "Error deleting customer";
            header("Location: error.php?status=delete_failed");
        }
    }
