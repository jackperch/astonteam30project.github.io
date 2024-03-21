<?php
require_once("connectionDB.php"); 

    if(isset($_SERVER['REQUEST_METHOD'])) 
    {
              
         $adminID = $_POST['adminID'];
        $username = $_POST['username'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
     
        

              // Check if the delete button was clicked
            if (isset($_POST['delete'])) {
                if (isset($_POST['delete'])) {
                    try {
                     
                       
                        $deleteAdminOrder = $db->prepare("DELETE FROM orders WHERE adminID = :adminID");
                        $deleteAdminOrder->bindParam(':adminID', $adminID);
                        $deleteAdminOrder->execute();
            
                        $deleteAdminReview = $db->prepare("DELETE FROM review WHERE adminID = :adminID");
                        $deleteAdminReview->bindParam(':adminID', $adminID);
                        $deleteAdminReview->execute();
                        
                        $deleteAdminAddress = $db->prepare("DELETE FROM address WHERE adminID = :adminID");
                        $deleteAdminAddress->bindParam(':adminID', $adminID);
                        $deleteAdminAddress->execute();

                        $deleteAdminPaymentInformation = $db->prepare("DELETE FROM payment_information WHERE adminID = :adminID");
                        $deleteAdminPaymentInformation = $db->prepare("DELETE FROM admin WHERE adminID = :adminID");
                        $deleteAdminPaymentInformation->bindParam(':adminID', $adminID);
                        $deleteAdminPaymentInformation->execute();
                        

                        $deleteAdminAccount = $db->prepare("DELETE FROM address WHERE adminID = :adminID");
                        $deleteAdminAccount->bindParam(':adminID', $adminID);
                        $deleteAdminAccount->execute();
                    
                         if($deleteAdminAccount->execute()){
                            header("Location: editAdmins.php?status=deleted");
                            exit;

                         }
            
                        
                    } catch (PDOException $e) {
                        // Handle database errors
                        echo "Error: " . $e->getMessage();
                        exit;
                    }
                }

            }






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



    



    

?>