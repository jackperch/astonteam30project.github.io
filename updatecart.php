<?php
    session_start();
    require_once("connectionDB.php");

    // Check if the user is logged in
    if (!isset($_SESSION['customerID'])) {
        // Handle the case where the customerID is not set
        header("Location: login.php");
        exit;
    }

    // Check if the action is set and the request is a POST request
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
        $customerID = $_SESSION['customerID']; 
        $productListingID = $_POST['productListingID'];
        $quantity = $_POST['quantity'];
        $action = $_POST['action'];

        // Input validation
        if ($action == 'update' && filter_var($quantity, FILTER_VALIDATE_INT) && $quantity > 0) {
            // Update logic
        } elseif ($action == 'remove') {
            // Remove logic
        } else {
            // Invalid action or quantity
            // Redirect back to cart page with an error message
            header("Location: cart.php?error=invalidinput");
            exit;
        }

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
            // Redirect back to cart page with an error message
            header("Location: cart.php?error=dberror");
            exit;
        }

        // Redirect back to cart page
        header("Location: cart.php");
        exit;
    } else {
        // Redirect back to cart page if the request is not a POST request or action is not set
        header("Location: cart.php");
        exit;
    }
?>
