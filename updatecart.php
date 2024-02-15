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

        if ($action === "removeGuest") {
            $productID = $_POST['productID'];
            unset($_SESSION["guest_shopping_cart"][$productID]);
            header("Location: cart.php?status=removed");
            exit;
        }

        $stmt->bindParam(':customerID', $customerID, PDO::PARAM_INT);
        $stmt->bindParam(':productID', $productID, PDO::PARAM_INT);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->execute();

        // Redirect to cart page or display a success message
        header("Location: cart.php");
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
    //If the user is not logged in, redirect to the login page
    //header("Location: login.php");
    $productID = $_POST['productID'];
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1; // Default to 1 if quantity is not specified
    $_SESSION["guest_shopping_cart"][$productID] = $quantity;
   
    //TESTING IF PRODUCTS ARE ADDED ONTO THE SESSION VARAIBLE GUEST SHOPPING CART

    // if (isset($_SESSION["guest_shopping_cart"])) {
        // Print the content of the "guest_shopping_cart" session variable
      //  echo "<pre>";
       // print_r($_SESSION["guest_shopping_cart"]);
      //  echo "</pre>";
  //  } else {
   //     echo "The shopping cart is empty.";
   // }

   header("Location: products.php"); //  Directs to the products page after a product is added to cart for a guest user
   //echo "Item added to cart"; Testing if product is added to the cart
    
    //exit;
}
//echo "Not Logged in or not a POST request";
//echo 'cutomer Id is ',$_SESSION['customerID'];
