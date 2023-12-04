<?php
include 'connectionDB.php';

session_start();

foreach ($productByCode as $product) {

    $insertQuery = "INSERT INTO ProductOrderPlaced (orderID, customerID, productListingID) 
    VALUES ('$orderID', '$customerID', '$prouctListingID')"; 
}
if (isset($_SESSION['customerID'])) {
    // If the customer is logged in
    $customerID = $_SESSION['customerID'];
    $stmt = $db_handle->prepare("INSERT INTO ProductOrderDetails (productOrderDetailsID, orderID, productID, quantity, price, color, size, date_purcahsed) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
} else {
    $stmt = $db_handle->prepare("INSERT INTO guest_cart (productOrderDetailsID, orderID, productListingID, quantity, price, color, size, date_purcahsed) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
}

if(isset($_POST['productID'])) {
    $productID = $_POST['productID'];
    
    // Check if the product ID is valid
    if(is_numeric($productID) && $productID > 0) {

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }

        // Check if the product is already in the cart
        if (array_key_exists($productID, $_SESSION['cart'])) {
            $_SESSION['cart'][$productID]++;
        } else {
            $_SESSION['cart'][$productID] = 1;
        }

        echo "Product added to cart successfully!";
    } else {
        echo "Invalid product ID.";
    }
} else {
    echo "Product ID is missing.";
}
?>
