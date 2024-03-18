<?php
// Assuming you have a file that handles your database connection.
// If not, you would need to set up your PDO connection to the database here.
require 'connectionDB.php'; // Replace with the actual path to your database connection script

// Collect POST data
$search = $_POST['search'] ?? '';
$category = $_POST['category'] ?? '';
$sort = $_POST['sort'] ?? '';

// Prepare the base query
$query = "SELECT * FROM products WHERE product_name LIKE :search";
$params = [':search' => '%' . $search . '%'];

// Filter by category if one was selected
if (!empty($category)) {
    $query .= " AND categoryID = :category";
    $params[':category'] = $category;
}

// Apply sorting if selected
switch ($sort) {
    case 'price_low_high':
        $query .= " ORDER BY price ASC";
        break;
    case 'price_high_low':
        $query .= " ORDER BY price DESC";
        break;
    case 'newest':
        $query .= " ORDER BY created_at DESC";
        break;
    case 'oldest':
        $query .= " ORDER BY created_at ASC";
        break;
    default:
        // No sort or an unrecognized sort option; apply a default sort if desired
        break;
}

// Execute the query
$stmt = $db->prepare($query);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Now generate the HTML for the products
foreach ($products as $product) {
    echo "<div class='product-container'>";

        // Make the product name a clickable link
        echo "<a href='productDetail.php?productID={$product['productID']}'>";
        echo "<img src='Images/Product-Images/{$product['image']}' alt='{$product['productName']}' width=80 height=80>";
        echo "<h2>{$product['productName']}</h2>";
        echo "</a>";

        

        echo "<div class='product-details'>";
        echo "<p>Colour: {$product['colour']}</p>";
        echo "<p>Size: {$product['size']}</p>";
        echo "<p>Category: {$product['categoryName']}</p>";
        echo "</div>";
        echo "<div class='product-description'>";
        echo "<p>Description: {$product['productDescription']}</p>";
        echo "</div>";

        if ($product['stock'] == 0) {
            echo "<p>Out of stock</p>";
        } else{
            
        
        echo "<form class='add-to-cart-form' method='post' action='updatecart.php'>";
        echo "<input type='hidden' name='productID' value='{$product['productID']}'>";
        echo "<div class='price'>£{$product['price']}</div>";
        echo "<button class='add-to-cart' onclick='displayAlert()'>Add to cart!</button>";
        echo "</form>";
        }
        echo "</div>"; 
}

// End of script
?>
