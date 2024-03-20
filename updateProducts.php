<?php
require_once("connectionDB.php"); 

    if(isset($_SERVER['REQUEST_METHOD'])) {
        $productID = $_POST['productID'];
        $image = $_POST['image'];
        $product_name = $_POST['product_name'];
        $price = $_POST['price'];
        $description = $_POST['description'];
        $categoryID = $_POST['categoryID'];
        $colour = $_POST['colour'];
        $size = $_POST['size'];
        $stock = $_POST['stock'];
        $is_featured = $_POST['is_featured'];
        $is_new = $_POST['is_new'];
        $is_popular = $_POST['is_popular'];

        // Prepare the SQL statement
        try{
        $query = "UPDATE products SET image=:image, product_name=:product_name, price=:price, description=:description, categoryID=:categoryID, colour=:colour, size=:size, stock=:stock, is_featured=:is_featured, is_new=:is_new, is_popular=:is_popular WHERE productID=:productID";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':productID', $productID);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':product_name', $product_name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':categoryID', $categoryID);
        $stmt->bindParam(':colour', $colour);
        $stmt->bindParam(':size', $size);
        $stmt->bindParam(':stock', $stock);
        $stmt->bindParam(':is_featured', $is_featured);
        $stmt->bindParam(':is_new', $is_new);
        $stmt->bindParam(':is_popular', $is_popular);
        $stmt->execute();
        }catch(PDOException $e){
            echo "Error: " . $e->getMessage();
            exit;

        } 
        // Execute the update
        if($stmt->execute()) {
            // Redirect or inform the user of success
            header("Location: editProducts.php?status=success");
        } else {
            // Handle failure
            echo "Error updating product";
        }
    }else{
        // Redirect or inform the user of failure
        header("Location: editProducts.php?status=failed");
    }



    // Check if the delete button was clicked

    if (isset($_POST['delete'])) {
        $productID = $_POST['productID'];
    
        // SQL statement to delete the product
        $sql = "DELETE FROM products WHERE productID = :productID";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':productID', $productID, PDO::PARAM_INT);
    
        if ($stmt->execute()) {
            // Redirect back to the editProducts page with a success message
            header("Location: editProducts.php?status=deleted");
        } else {
            // Handle failure
            echo "Error deleting product";
        }
    }
?>