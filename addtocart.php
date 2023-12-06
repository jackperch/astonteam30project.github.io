<?php
include 'connectionDB.php';

session_start();
$productByCode = [];


foreach ($productByCode as $product) {

    // Fetch product details from the database based on the product ID
    $stmt = $db->prepare("SELECT * FROM ProductListing WHERE productListingID = ?");
    $stmt->execute([$productListingID]);
    $productDetails = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the product details were retrieved successfully
    if ($productDetails) {
        // You can now use $productDetails array to access specific details
        $productName = $productDetails['productName'];
        $price = $productDetails['price'];
        $description = $productDetails['description'];

        // Rest of your code to insert into ProductOrderPlaced or guest_cart
        if (isset($_SESSION['customerID'])) {
            // If the customer is logged in
            $customerID = $_SESSION['customerID'];
            $stmt = $db->prepare("INSERT INTO ProductOrderDetails (orderID, customerID, productListingID, quantity, price, color, size, date_purchased) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        } else {
            // For guest users
            $stmt = $db->prepare("INSERT INTO guest_cart (orderID, customerID, productListingID, quantity, price, color, size, date_purchased) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        }

        // Add your bindings and execute the statement
        $stmt->execute([$orderID, $customerID, $productListingID, $quantity, $price, $color, $size, $date_purchased]);

        // Display success message or handle further logic
        echo "Product added to cart successfully!";
    } else {
        echo "Error: Product details not found.";
    }








    $insertQuery = "INSERT INTO ProductOrderPlaced (orderID, customerID, productListingID)
    VALUES ('$orderID', '$customerID', '$prouctListingID')"; 
}
if (isset($_SESSION['customerID'])) {
    // If the customer is logged in
    $customerID = $_SESSION['customerID'];
    $db= "INSERT INTO ProductOrderDetails (productOrderDetailsID, orderID, prouctListingID, quantity, price, color, size, date_purcahsed) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
} else {
    $stmt = $db->prepare("INSERT INTO guest_cart (productOrderDetailsID, orderID, productListingID, quantity, price, color, size, date_purchased) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
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
