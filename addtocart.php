<?php
include 'connectionDB.php';

session_start();
$_SESSION['message'] = $message;
$productByCode = [];

foreach ($productByCode as $product) {

    $insertQuery = "INSERT INTO ProductOrderPlaced (orderID, customerID, productListingID)
    VALUES ('$orderID', '$customerID', '$prouctListingID')"; 
}
if (isset($_SESSION['customerID'])) {
    // If the customer is logged in
    $customerID = $_SESSION['customerID'];
    $db= "INSERT INTO ProductOrderDetails (productOrderDetailsID, orderID, prouctListingID, quantity, price, color, size, date_purcahsed) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
} else {
    $stmt = $db_handle->prepare("INSERT INTO guest_cart (productOrderDetailsID, orderID, productListingID, quantity, price, color, size, date_purchased) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
}

if (isset($_POST['prouctListingID'])) {
    $productListingID = $_POST['prouctListingID'];

    // Check if the product ID is valid
    if (is_numeric($productListingID) && $productListingID > 0) {

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }

        // Check if the product is already in the cart
        if (array_key_exists($productListingID, $_SESSION['cart'])) {
            $_SESSION['cart'][$productListingID]++;
        } else {
            $_SESSION['cart'][$productListingID] = 1;
        }

        $message = "Product added to cart successfully!";
    } else {
        $message = "Invalid product ID.";
    }
} else {
    $message ="Product ID is missing.";
}
?>
