<?php
// Assuming you have a file `connectionDB.php` with your database connection settings
require_once 'connectionDB.php';

// Define a function to fetch products by category if it's not defined elsewhere
function fetchProductsByCategory($db, $categoryId) {
    $sql = "SELECT productID, product_name, price, image, description FROM products WHERE categoryID = :categoryId";
    $stmt = $db->prepare($sql);
    $stmt->execute([':categoryId' => $categoryId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Check if categoryId is provided
if (!isset($_GET['categoryId'])) {
    $categoryId = 1; // Default category ID
} else {
    $categoryId = intval($_GET['categoryId']);
}
    // Fetch products for the given category
    $products = fetchProductsByCategory($db, $categoryId);

    // Check if products were found
    if (!empty($products)) {
        // Loop through each product and generate the HTML
        foreach ($products as $product) {
            echo "<div class='product'>";
            // Make the product image a clickable link
            echo "<a href='productDetail.php?productID={$product['productID']}'>";
            echo "<img src='Images/Product-Images/" . htmlspecialchars($product['image']) . "' alt='" . htmlspecialchars($product['product_name']) . "' class='product-image'>";
            echo "</a>";
            echo "<h2>{$product['product_name']}</h2>";
            echo "<p class='product-price'>£" . htmlspecialchars($product['price']) . "</p>";
            echo "<div class='quantity-input'>";
            echo "<button class='quantity-decrease' onclick='changeQuantity(false, \"" . $product["productID"] . "\")'>-</button>";
            echo "<input type='number' id='quantity-" . $product['productID'] . "' name='quantity' value='1' min='1' class='quantity-field'>";
            echo "<button class='quantity-increase' onclick='changeQuantity(true, '{$product['productID']}')'>+</button>";
            echo "</div>";
                    echo "<form class='add-to-cart-form' method='post' action='updatecart.php'>";
                    echo "<input type='hidden' name='productID' value='{$product['productID']}'>";
                    echo "<div class='price'>£{$product['price']}</div>";
                    echo "<button class='add-to-cart' onclick='displayAlert()'>Add to cart!</button>";
                    echo "</form>";
            echo "</div>";
        }
    } else {
        // No products found for the given category
        echo "<p>No products found in this category.</p>";
    }
// } else {
//     // categoryId parameter is missing
//     echo "<p>Error: Category ID is required.</p>";

?>