<?php
require_once("connectionDB.php"); 

    if(isset($_SERVER['REQUEST_METHOD'])) {
       $orderID= $_POST['orderID'];
       $customerID= $_POST['customerID'];
       $order_date= $_POST['order_date'];
       $total_amount= $_POST['total_amount'];
       $addressID= $_POST['addressID'];
       $paymentInfoID= $_POST['paymentInfoID'];
       $order_completed= $_POST['order_completed'];



        // Prepare the SQL statement
        try{
        $query ="UPDATE orders SET customerID=:customerID, order_date=:order_date, total_amount=:total_amount, addressID=:addressID, order_completed=:order_completed WHERE orderID=:orderID";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':orderID', $orderID);
        $stmt->bindParam(':customerID', $customerID);
        $stmt->bindParam(':order_date', $order_date);
        $stmt->bindParam(':total_amount', $total_amount);
        $stmt->bindParam(':addressID', $addressID);
        //$stmt->bindParam(':paymentInfoID', $paymentID);
        $stmt->bindParam(':order_completed', $order_completed);
        $stmt->execute();
        }catch(PDOException $e){
            echo "Error: " . $e->getMessage();
            exit;

        } 
        // Execute the update
        if($stmt->execute()) {
            // Redirect or inform the user of success
            header("Location: editOrders.php?status=update-success");
        } else {
            // Handle failure
            echo "Error updating product";
        }
    }else{
        // Redirect or inform the user of failure
        header("Location: editProducts.php?status=update-failed");
    }




    if (isset($_POST['delete'])) {
        $orderID = $_POST['orderID'];
    
        // SQL statement to delete the product
        $sql = "DELETE FROM orders WHERE orderID = :orderID";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':orderID', $orderID, PDO::PARAM_INT);
    
        if ($stmt->execute()) {
            // Redirect back to the editProducts page with a success message
            header("Location: editOrders.php?status=delete-success");
        } else {
            // Handle failure
            echo "Error deleting product";
            header("Location: editOrders.php?status=delete-failed");
        }
        
   }
