<?php
require_once("connectionDB.php"); 

    if(isset($_SERVER['REQUEST_METHOD'])) {
        $contactID = $_POST['contactID'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $message = $_POST['message'];
        $is_member = $_POST['is_member'];
        $request_status = $_POST['request_status'];


       
            
        // Prepare the SQL statement
        try{
        $query = " UPDATE contact_request SET name=:name, email=:email, message=:message, is_member=:is_member, request_status=:request_status WHERE contactID=:contactID";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':contactID', $contactID);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':message', $message);
        $stmt->bindParam(':is_member', $is_member);
        $stmt->bindParam(':request_status', $request_status);


      
            
        $stmt->execute();
        }catch(PDOException $e){
            echo "Error: " . $e->getMessage();
            exit;

        } 
        // Execute the update
        if($stmt->execute()) {
            // Redirect or inform the user of success
            header("Location: contactRequests.php?status=update-success");
        } else {
            // Handle failure
            echo "Error updating product";
        }
        
  
    
    
    }else{
        // Redirect or inform the user of failure
        header("Location: contactRequests.php?status=failed");
    }






if (isset($_POST['delete'])) {
    $contactID = $_POST['contactID'];

    // SQL statement to delete the product
    $sql = "DELETE FROM contact_request WHERE contactID = :contactID";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':contactID', $contactID, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Redirect back to the editProducts page with a success message
        header("Location: contactRequests.php?status=deleted");
    } else {
        // Handle failure
        echo "Error deleting product";
    }
}
?>