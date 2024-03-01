<?php
    session_start();
    require_once("connectionDB.php");

    if(isset($_GET['categoryId'])) {
        $categoryId = $_GET['categoryId'];
        $products = fetchProductsByCategory($db, $categoryId);

        foreach ($products as $product) {
            echo "<h3>" . $categoryId . "</h3>";
            echo "<div class='product'>";
            echo "<img src='Images/Product-Images/" . htmlspecialchars($product['image']) . "' alt='" . htmlspecialchars($product['product_name']) . "' class='product-image'>";
            echo "<h3 class='product-name'>" . htmlspecialchars($product['product_name']) . "</h3>";
            echo "<p class='product-price'>£" . htmlspecialchars($product['price']) . "</p>";
            echo "<div class='quantity-input'>";
            echo "<button class='quantity-decrease' onclick='changeQuantity(false, \"" . $product["productID"] . "\")'>-</button>";
            echo "<input type=\"number\" id=\"quantity-" . $product['productID'] . "\" name=\"quantity\" value=\"1\" min=\"1\" class=\"quantity-field\">";
            echo "<button class=\"quantity-increase\" onclick=\"changeQuantity(true, '{$product['productID']}')\">+</button>";
            echo "</div>";
            echo "<button class='add-to-cart-btn' onclick='addToCart(\"{$product['productID']}\")'>Add to Cart</button>";
            echo "</div>";
        }
    }
