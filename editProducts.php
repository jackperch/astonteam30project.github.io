<?php
session_start();
include("connectionDB.php");


// Update product
// if(isset($_POST['update-btn'])) {
//     $productID = $_POST['productID'];
//     $image = $_POST['image'];
//     $product_name = $_POST['product_name'];
//     $price = $_POST['price'];
//     $description = $_POST['description'];
//     $categoryID = $_POST['categoryID'];
//     $colour = $_POST['colour'];
//     $size = $_POST['size'];
//     $stock = $_POST['stock'];
//     $is_featured = $_POST['is_featured'];
//     $is_new = $_POST['is_new'];
//     $is_popular = $_POST['is_popular'];

//     try {
//         $query = "UPDATE products SET image=:image, product_name=:product_name, price=:price, description=:description, categoryID=:categoryID, colour=:colour, size=:size, stock=:stock, is_featured=:is_featured, is_new=:is_new, is_popular=:is_popular WHERE productID=:productID";
//         $stmt = $db->prepare($query);
//         $stmt->bindParam(':productID', $productID);
//         $stmt->bindParam(':image', $image);
//         $stmt->bindParam(':product_name', $product_name);
//         $stmt->bindParam(':price', $price);
//         $stmt->bindParam(':description', $description);
//         $stmt->bindParam(':categoryID', $categoryID);
//         $stmt->bindParam(':colour', $colour);
//         $stmt->bindParam(':size', $size);
//         $stmt->bindParam(':stock', $stock);
//         $stmt->bindParam(':is_featured', $is_featured);
//         $stmt->bindParam(':is_new', $is_new);
//         $stmt->bindParam(':is_popular', $is_popular);
//         $stmt->execute();
//     } catch(PDOException $e) {
//         echo "Error: " . $e->getMessage();
//         exit;
//     } 
//     exit;
// }

// // Update product
// if(isset($_POST['update-btn'])) {
//     $productID = $_POST['productID'];
//     $image = $_POST['image'];
//     $product_name = $_POST['product_name'];
//     $price = $_POST['price'];
//     $description = $_POST['description'];
//     $categoryID = $_POST['categoryID'];
//     $colour = $_POST['colour'];
//     $size = $_POST['size'];
//     $stock = $_POST['stock'];
//     $is_featured = $_POST['is_featured'];
//     $is_new = $_POST['is_new'];
//     $is_popular = $_POST['is_popular'];

// echo "The new prie is"+$price;

//     try{

//     $query = "UPDATE products SET image=:image, product_name=:product_name, price=:price, description=:description, categoryID=:categoryID, colour=:colour, size=:size, stock=:stock, is_featured=:is_featured, is_new=:is_new, is_popular=:is_popular WHERE productID=:productID";
//     $stmt = $db->prepare($query);
//     $stmt->bindParam(':productID', $productID);
//     $stmt->bindParam(':image', $image);
//     $stmt->bindParam(':product_name', $product_name);
//     $stmt->bindParam(':price', $price);
//     $stmt->bindParam(':description', $description);
//     $stmt->bindParam(':categoryID', $categoryID);
//     $stmt->bindParam(':colour', $colour);
//     $stmt->bindParam(':size', $size);
//     $stmt->bindParam(':stock', $stock);
//     $stmt->bindParam(':is_featured', $is_featured);
//     $stmt->bindParam(':is_new', $is_new);
//     $stmt->bindParam(':is_popular', $is_popular);
//     $stmt->execute();
//     }catch(PDOException $e){
//         echo "Error: " . $e->getMessage();
//         exit;

//     } 
//   //  $db->commit();
//     exit;
// }

     
//     $query = "UPDATE products SET productID=:productID, image=:image, product_name=:product_name, price=:price, description=:description, categoryID=:categoryID, colour=:colour, size=:size, stock=:stock, is_featured=:is_featured, is_new=:is_new, is_popular=:is_popular WHERE productID=:productID";
//     $stmt = $db->prepare($query);
//     $stmt->bindParam(':productID', $productID);
//     $stmt->bindParam(':image', $image);
//     $stmt->bindParam(':product_name', $product_name);
//     $stmt->bindParam(':price', $price);
//     $stmt->bindParam(':description', $description);
//     $stmt->bindParam(':total_amount', $categoryID);
//     $stmt->bindParam(':colour', $colour);
//     $stmt->bindParam(':size', $size);
//     $stmt->bindParam(':is_featured', $is_featured);
//     $stmt->bindParam(':is_new', $is_new);
//     $stmt->bindParam(':is_popular', $is_popular);
//     $stmt->bindParam(':stock', $stock);
//     $stmt->execute();
//     exit; 

// }



// Delete product
// if(isset($_POST['delete-btn'])) {
//     $productID = $_POST['productID'];

//     $query = "DELETE FROM products WHERE productID=:productID";
//     $stmt = $db->prepare($query);
//     $stmt->bindParam(':productID', $productID);
//     $stmt->execute();
//     exit;
// }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="CSS/styles.css">
    <link rel="stylesheet" href="CSS/admin.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Sono&display=swap');
    </style>
</head>
<body>
<header>
        <div id="logo-container">
            <!-- logo image -->
            <img id="logo" src="Images/Logo-no-bg.png" alt="Logo">
            <h1 id="nav-bar-text">ACE GEAR</h1>
        </div>
        <nav>
            <a href="index.php">Home</a>
            <a href="productsDisplay.php">Products</a>
            <a href="about.php">About</a>
            <a href="contact.php">Contact</a>
            <?php 

            if (isset($_SESSION['adminID'])) {
                echo "<a href='Dashboard.php'>Dashboard</a>";
                echo "<a href='logout.php'>Logout</a>";
                }else{
                    exit;
                    
                }
?>
        <?php
        // Initialize the total quantity variable
        $totalQuantity = 0;

        if(isset($_SESSION['adminID'])){
            require_once("connectionDB.php"); // Adjust this path as necessary
            $smt=$db->prepare("SELECT SUM(quantity) AS totalQuantity FROM cart WHERE  adminID = :adminID");
            $smt->execute(['adminID' => $_SESSION['adminID']]);
            $result = $smt->fetch(PDO::FETCH_ASSOC);
            if ($result && $result['totalQuantity'] > 0) {
                $totalQuantity = $result['totalQuantity'];
            }
        }else{
            header("Location: error.php?error=no_session");
            exit;
        }
        ?>
        </nav>
        <div id="cart-container">
            <!-- cart icon image with link to cart page -->
            <a href="cart.php">
                <img id="cart-icon" src="Images/cart-no-bg.png" alt="Cart">
                <span id="cart-count"><?php echo $totalQuantity; ?></span>
            </a>
        </div>
    </header>




<div class="content-container">
    <div class="user-management-container">
        <h1>Products Management</h1>

        <table>
            <tr>
                <th>Product ID</th>
                <th>Image</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Description</th>
                <th>Colour</th>
                <th>Category ID</th>
                <th>Size</th>
                <th>Is Featured</th>
                <th>Is New</th>
                <th>Is Popular</th>
                <th>Stock</th>
            </tr>

            
            <?php
            $query = "SELECT * FROM products";
            $stmt = $db->query($query);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<form method='post' action='updateProducts.php'>";
                    echo "<tr>"; 
                    echo "<input type='hidden' name='productID' value='{$row['productID']}'>"; // Add a hidden input to store the productID
                    echo "<td>{$row['productID']}</td>";
                    echo "<td><input type='text' name='product_name' value='{$row['product_name']}'></td>";
                    echo "<td><input type='text' name='image' value='{$row['image']}'></td>";
                    echo "<td><input type='text' name='price' value='{$row['price']}'></td>";
                    echo "<td><input type='text' name='description' value='{$row['description']}'></td>";
                    echo "<td><input type='text' name='colour' value='{$row['colour']}'></td>";
                    echo "<td><input type='text' name='categoryID' value='{$row['categoryID']}'></td>";
                    echo "<td><input type='text' name='size' value='{$row['size']}'></td>";
                    echo "<td><input type='text' name='is_featured' value='{$row['is_featured']}'></td>";
                    echo "<td><input type='text' name='is_new' value='{$row['is_new']}'></td>";
                    echo "<td><input type='text' name='is_popular' value='{$row['is_popular']}'></td>";
                    echo "<td><input type='text' name='stock' value='{$row['stock']}'></td>";
                    echo "<td><button type='submit' name='update' class='update-btn'>Update</button></td>";
                    echo "<td><button type='submit' name='delete' class='delete-btn'>Delete</button></td>";
                    echo "</tr>";
                echo "</form>";
            }
            ?>        
        </table>

        <!-- Add Product Button -->
        <button id="addProduct">Add Product</button>
        <a href="manageCategory.php">
            <button id="manageCategory">Manage Categories</button>
        </a>
    </div>
</div>

<footer>
    <div class="footer-container">
        <div class="footer-links">
            <a href="reviews.php">Reviews</a>
            <a href="contact.php">Contact Us</a>
            <a href="about.php">About Us</a>
            <a href="privacy-policy.php">Privacy Policy</a>
        </div>
    </div>
</footer>

<script>
const updateButtons = document.querySelectorAll('.update-btn');
updateButtons.forEach(button => {
    button.addEventListener('click', () => {
        const productID = button.dataset.id; 
        const row = button.parentNode.parentNode;
        const editableFields = row.querySelectorAll('.editable');
        const dataToUpdate = {};
        editableFields.forEach(field => {
            const column = field.dataset.column;
            const value = field.textContent.trim();
            dataToUpdate[column] = value;
        });
        if (confirm("Are you sure you want to update this product?")) {
            updateProductData(productID, dataToUpdate); // Changed function name from 'updateOrderData' to 'updateProductData'
        }
    });
});

// Delete product Functionality
const deleteButtons = document.querySelectorAll('.delete-btn');
deleteButtons.forEach(button => {
    button.addEventListener('click', () => {
        const productID = button.dataset.id; 
        if (confirm("Are you sure you want to delete this product?")) {
            deleteProductData(productID);
        }
    });
});

// function updateProductData(productID, dataToUpdate) {
//     const xhr = new XMLHttpRequest();
//     xhr.open('POST', window.location.href, true); // If the handler is the current script
//     xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded'); // Since we're sending form data
//     xhr.onreadystatechange = function () {
//         if (xhr.readyState === XMLHttpRequest.DONE) {
//             if (xhr.status === 200) {
//                 console.log(xhr.responseText);
//                 alert('Product data updated successfully.');
//             } else {
//                 alert('Failed to update product data.');
//             }
//         }
//     };
//     // Construct form data string
//     let formData = `update-btn=true&productID=${productID}`;
//     for (const [key, value] of Object.entries(dataToUpdate)) {
//         formData += `&${encodeURIComponent(key)}=${encodeURIComponent(value)}`;
//     }
//     xhr.send(formData);
// }


function deleteProductData(productID) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '', true); 
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            console.log(xhr.responseText);
            
            const deletedRow = document.querySelector(`tr[data-id="${productID}"]`);
            if (deletedRow) {
                deletedRow.remove();
            }
        }
    };
    xhr.send(`delete-btn=true&productID=${productID}`);
}

//Adding product
document.addEventListener("DOMContentLoaded", function() {
        const addProductButton = document.getElementById("addProduct");

        addProductButton.addEventListener("click", function() {
            window.location.href = "addProduct.php";
        });
    });

</script>


</body>
</html>