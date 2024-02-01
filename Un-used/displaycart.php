<?php
session_start();
include 'connectionDB.php';



// Check if the user is logged
if (isset($_SESSION['customerID']) && isset($_SESSION['cart'])) {
    $customerID = $_SESSION['customerID'];
    $cartItems = $_SESSION['cart'];

    $productDetails = array();
    foreach ($cartItems as $productID => $quantity) {
        $stmt = $db_handle->prepare("SELECT * FROM ProductListing WHERE productListingID = ?");
        $stmt->bind_param("i", $productID);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        $productDetails[] = [
            'productID' => $product['productListingID'],
            'productName' => $product['productName'],
            'quantity' => $quantity,
            'price' => $product['price'],
        ];
    }

    // Display the products in the cart for logged-in customers
    foreach ($productDetails as $product) {
        echo '<div>';
        echo '<h4>' . $product['productName'] . '</h4>';
        echo '<img src="' . $product['image_url'] . '" alt="' . $product['name'] . '">';
        echo '<p>Quantity: ' . $product['quantity'] . '</p>';
        echo '<p>Price: $' . $product['price'] . '</p>';
        echo '</div>';
    }
} else {
    $guestProductDetails = array();
    foreach ($guestCartItems as $productID => $quantity) {
        $stmt = $db_handle->prepare("SELECT * FROM ProductListing WHERE productListingID = ?");
        $stmt->bind_param("i", $productID);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        $guestProductDetails[] = [
            'productID' => $product['productListingID'],
            'productName' => $product['productName'],
            'quantity' => $quantity,
            'price' => $product['price'],
        ];
    }

    // Display the products in the guest cart
    foreach ($guestProductDetails as $product) {
        echo '<div>';
        echo '<h4>' . $product['productName'] . '</h4>';
        echo '<img src="' . $product['image_url'] . '" alt="' . $product['name'] . '">';
        echo '<p>Quantity: ' . $product['quantity'] . '</p>';
        echo '<p>Price: $' . $product['price'] . '</p>';
        echo '</div>';
    }
}
?>
