<?php
include 'connectionDB.php';

session_start();

// Assuming $productByCode is an array of products, replace it with your actual product array.
// This loop is just a placeholder, and you should adjust it based on your data retrieval logic.
$productByCode = []; // Replace this line with your actual data retrieval logic

foreach ($productByCode as $product) {

    $insertQuery = "INSERT INTO ProductOrderPlaced (orderID, customerID, productListingID) 
    VALUES ('$orderID', '$customerID', '$productListingID')"; 
    
    // Execute the insert query here
    // ...

}

if (isset($_POST['productID'])) {
    $productListingID = $_POST['productID'];

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

        echo "Product added to cart successfully!";
    } else {
        echo "Invalid product ID.";
    }
} else {
    echo "Product ID is missing.";
}
?>
