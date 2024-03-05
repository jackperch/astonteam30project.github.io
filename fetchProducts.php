<?php
    // Assuming you have a file `connectionDB.php` with your database connection settings
    require_once 'connectionDB.php';
    
    // Check if categoryId is provided
    if (isset($_GET['categoryId'])) {
        $categoryId = intval($_GET['categoryId']);
        $categoryProducts = fetchProductsByCategory($db, $categoryId);

        // Prepare the SQL statement to fetch products from the given category
        $sql = "SELECT productID, product_name, price, image, description FROM products WHERE categoryID = ?";
        $stmt = $db->prepare($sql);
        
        // Execute the query with the categoryId
        $stmt->execute([$categoryId]);
        
        // Fetch all the matching products
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Check if products were found
        if ($products) {
            // Loop through each product and generate the HTML
            echo "<section id='category-products'>";
            foreach ($products as $product) {
                //echo "<h3>" . $categoryId . "</h3>";
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
            echo "</section>";
        } else {
            // No products found for the given category
            echo "<p>No products found in this category.</p>";
        }
    } else {
        // categoryId parameter is missing
        echo "<p>Error: Category ID is required.</p>";
    }
?>