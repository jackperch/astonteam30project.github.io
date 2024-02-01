<?php
session_start();
require_once("connectionDB.php");

$customerID = $_SESSION['customerID']; 
$productListingID = $_POST['productListingID'];
$quantity = $_POST['quantity'];
$action = $_POST['action'];

try {
    $db->beginTransaction();

    if ($action == 'update') {
        $sql = "UPDATE basket SET quantity = :quantity WHERE customerID = :customerID AND productListingID = :productListingID";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
    } elseif ($action == 'remove') {
        $sql = "DELETE FROM basket WHERE customerID = :customerID AND productListingID = :productListingID";
        $stmt = $db->prepare($sql);
    }

    $stmt->bindParam(':customerID', $customerID, PDO::PARAM_INT);
    $stmt->bindParam(':productListingID', $productListingID, PDO::PARAM_INT);
    $stmt->execute();

    $db->commit();
} catch (PDOException $ex) {
    $db->rollBack();
    echo "Error updating cart: " . $ex->getMessage();
    exit;
}

// Redirect back to cart page
header("Location: cart.php");
exit;
?>
