<?php
session_start();

require_once("connectionDB.php");
//echo "Connected to the database";
// Check if the user is logged in and the request is a POST request
if (isset($_SESSION['customerID']) && $_SERVER['REQUEST_METHOD'] == 'POST') {

    $customerID = $_SESSION['customerID'];
    $productID = $_POST['productID'];
    $action = $_POST['action'];
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1; // Default to 1 if quantity is not specified
    

    try {
        // Check if the item already exists in the cart
        $stmt = $db->prepare("SELECT quantity FROM cart WHERE customerID = :customerID AND productID = :productID");
        $stmt->bindParam(':customerID', $customerID, PDO::PARAM_INT);
        $stmt->bindParam(':productID', $productID, PDO::PARAM_INT);
        $stmt->execute();

        $existingItem = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingItem) {
            // Item already in cart, update quantity
            $newQuantity = $existingItem['quantity'] + $quantity;
            $stmt = $db->prepare("UPDATE cart SET quantity = :quantity WHERE customerID = :customerID AND productID = :productID");
            $stmt->bindParam(':quantity', $newQuantity, PDO::PARAM_INT);
        } else {
            // Item not in cart, insert new record
            $stmt = $db->prepare("INSERT INTO cart (customerID, productID, quantity) VALUES (:customerID, :productID, :quantity)");
        }

        if ($action === 'remove') {
            // Remove item from cart
            $sql = "DELETE FROM cart WHERE customerID = :customerID AND productID = :productID";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':customerID', $customerID, PDO::PARAM_INT);
            $stmt->bindParam(':productID', $productID, PDO::PARAM_INT);
            $stmt->execute();

            header("Location: cart.php?status=removed");
            exit;
        }


        $productName = $product['product_name']; 
        $itemCost = $product['price'] * $quantity;

        $cartTotal =  0;
        foreach ($cartItems as $item) {
            echo "<div class='cart-item'>";
            echo "<p>{$item['product_name']} (Quantity: {$item['quantity']}) - $" . ($item['quantity'] * $item['price']) . "</p>";
            echo "</div>";
            $cartTotal += ($item['quantity'] * $item['price']);
        }

        $_SESSION['cart_summary'] = [
            'productName' => $productName,
            'quantity' => $quantity,
            'itemCost' => $itemCost,
            'cartTotal' => $cartTotal
        ];

       
        $stmt->bindParam(':customerID', $customerID, PDO::PARAM_INT);
        $stmt->bindParam(':productID', $productID, PDO::PARAM_INT);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->execute();


        // Redirect to cart page or display a success message
        header("Location: products.php?addedToCart={$productID}");
        echo "Item added to cart";
        exit;




    } catch (PDOException $ex) {
        // Handle errors, maybe log them and show user-friendly message
        echo "Error updating cart: " . $ex->getMessage();
        // Redirect to product page or display error
        header("Location: product.php?id=" . $productID . "&error=cart-update-failed");
        exit;
    }
} else {
    // Ensure that the action is set and valid
    if (isset($_POST['action']) && $_POST['action'] === "removeGuest") {
        // Ensure that the productID is provided and valid
        
        if (isset($_POST['productID'])) {
            $productID = $_POST['productID'];
            // Removes the item from the guest shopping cart session variable

            if (isset($_SESSION["guest_shopping_cart"][$productID])) {
                unset($_SESSION["guest_shopping_cart"][$productID]);
                header("Location: cart.php?status=removed");
                exit;
            }
        }else{
            echo "Product ID not set"; //testing
        }

    } elseif(isset($_POST['action']) && $_POST['action'] === "updateGuest"){
        // Ensure that the productID is provided and valid
        if (isset($_POST['productID'])) {
            $productID = $_POST['productID'];
            // Update the item in the guest shopping cart session variable
            if (isset($_SESSION["guest_shopping_cart"][$productID])) {
                $_SESSION["guest_shopping_cart"][$productID] = $_POST['quantity'];
                header("Location: cart.php?status=updated");
                exit;
            }
        }else{
            echo "Product ID not set"; //testing
        }
    }else{
        //If user does not click  the remove and update button then user will add a product to the cart 
        $productID = $_POST['productID'];
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1; // Default to 1 if quantity is not specified
        $_SESSION["guest_shopping_cart"][$productID] = $quantity;
        
        header("Location: products.php"); // Redirect to the products page after adding a product to cart for a guest user
        exit;
    }
}
//echo "Not Logged in or not a POST request";
//echo 'cutomer Id is ',$_SESSION['customerID'];

