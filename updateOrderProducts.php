<?php
require_once("connectionDB.php"); 

    if(isset($_SERVER['REQUEST_METHOD'])) {
        $ordersProductID = $_POST['ordersProductID'];
        $orderID = $_POST['orderID'];
        $quantity = $_POST['quantity'];
        $total_price = $_POST['total_price'];

       
            
        // Prepare the SQL statement
        try{
        $query = " UPDATE orders_products SET orderID=:orderID, quantity=:quantity, total_price=:total_price WHERE ordersProductID=:ordersProductID";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':ordersProductID', $ordersProductID);
        $stmt->bindParam(':orderID', $orderID);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':total_price', $total_price);

      
            
        $stmt->execute();
        }catch(PDOException $e){
            echo "Error: " . $e->getMessage();
            exit;

        } 
        // Execute the update
        if($stmt->execute()) {
            // Redirect or inform the user of success
            header("Location: orderProducts.php?status=update-success");
        } else {
            // Handle failure
            echo "Error updating product";
        }
        
  
    
    
    }else{
        // Redirect or inform the user of failure
        header("Location: orderProducts.php?status=failed");
    }




if (isset($_POST['delete'])) {
    $ordersProductID = $_POST['ordersProductID'];

    // SQL statement to delete the product
    $sql = "DELETE FROM orders_products WHERE ordersProductID = :ordersProductID";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':ordersProductID', $ordersProductID, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Redirect back to the editProducts page with a success message
        header("Location: orderProducts.php?status=deleted");
    } else {
        // Handle failure
        echo "Error deleting product";
    }
}

