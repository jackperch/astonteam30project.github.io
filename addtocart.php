<?php
session_start();
include 'connectionDB.php';




$stmtFetchProducts = $db->query("SELECT * FROM ProductListing");
$productByCode = $stmtFetchProducts->fetchAll(PDO::FETCH_ASSOC);

foreach ($productByCode as $product) {
    // defined $orderID, $customerID, and $productListingID .
    $insertQuery = "INSERT INTO ProductOrderPlaced (orderID, customerID, productListingID)
    VALUES ('$orderID', '$customerID', '$productListingID')";
    $db->query($insertQuery);
}

if (isset($_SESSION['customerID'])) {
    // If the customer is logged in
    $customerID = $_SESSION['customerID'];

    // PDO connection called $db_.
    $stmt = $db->prepare("INSERT INTO ProductOrderDetails (productOrderDetailsID, orderID, productListingID, quantity, price, color, size, date_purchased)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
     
} else {
    //a PDO connection called $db.
    $stmt = $db->prepare("INSERT INTO guest_cart (guestCartID, orderID, productListingID, quantity, price, color, size, date_purchased)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
}

if (isset($_POST['productListingID'])) {
    $productListingID = $_POST['productListingID'];

    // Fetch necessary details based on productListingID from the database
    $stmtFetchDetails = $db->prepare("SELECT orderID, quantity, price, color, size, date_purchased FROM ProductListing WHERE productListingID = :productListingID");
    $stmtFetchDetails->bindParam(":productListingID", $productListingID);
    $stmtFetchDetails->execute();
    $result = $stmtFetchDetails->fetchAll(PDO::FETCH_ASSOC);

    if (count($result) === 1) {
        $details = $result[0];

        $stmt->bindParam(":orderID", $details['orderID']);
        $stmt->bindParam(":productListingID", $productListingID);
        $stmt->bindParam(":quantity", $details['quantity']);
        $stmt->bindParam(":price", $details['price']);
        $stmt->bindParam(":color", $details['color']);
        $stmt->bindParam(":size", $details['size']);
        $stmt->bindParam(":date_purchased", $details['date_purchased']);
        $stmt->execute();

        echo("Product added to cart successfully!");

    } else {
        $message = "Invalid product ID.";
    }
} else {
    $message = "Product ID is missing.";
}
$_SESSION['alert_message'] = $message;
header("Location: display_alert.html");
    exit();
?>
