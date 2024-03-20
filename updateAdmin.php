<?php
require_once("connectionDB.php"); 

    if(isset($_SERVER['REQUEST_METHOD'])) 
    {
              
         $adminID = $_POST['adminID'];
        $username = $_POST['username'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
       

        // Prepare the SQL statement
        try{
        $query = "UPDATE admin SET username=:username, first_name=:first_name, last_name=:last_name, email=:email WHERE adminID=:adminID";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':adminID', $adminID);
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
            header("Location: editAdmins.php?status=added_success");
        } else {
            // Handle failure
            echo "Error updating the admin";
        }
    }else{
        // Redirect or inform the user of failure
        header("Location: editAdmins.php?status=added_failed");
    }



    // Check if the delete button was clicked
    if (isset($_POST['delete'])) {
        //$adminID = $_POST['adminID'];
    
        // SQL statement to delete the product
        $sql = "DELETE FROM admin  WHERE adminID = :adminID";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':adminD', $adminID, PDO::PARAM_INT);
    
        if ($stmt->execute()) {
            // Redirect back to the editProducts page with a success message
            header("Location: editAdmins.php?status=deleted");
            exit;   
        } else {
            // Handle failure
            echo "Error deleting customer";
            header("Location: editAdmins.php?status=delete_failed");
            exit;
        }
    }


?>