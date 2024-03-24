<?php
session_start();
require_once("connectionDB.php");
if (!isset($_SESSION['adminID'])) {
    header('Location: adminLogin.php');
    exit;
}
// Fetch categories
try {
    $query = "SELECT categoryID, name, description FROM category ORDER BY categoryID ASC";
    $stmt = $db->query($query);
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC); // Use a different variable name here
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}

// Add new category
if (isset($_POST['addCategory'])) {
    // Sanitize input data
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);

    try {
        $stmt = $db->prepare("INSERT INTO category (name, description) VALUES (:name, :description)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->execute();
        
        header('Location: manageCategory.php'); // Redirect to manage categories page
        exit;
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Delete category
if (isset($_POST['delete']) && isset($_POST['category_id'])) {
    $categoryId = $_POST['category_id'];

    try {
        $stmt = $db->prepare("DELETE FROM category WHERE categoryID = :categoryId"); // Ensure column name matches your schema
        $stmt->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
        $stmt->execute();

        header('Location: manageCategory.php?status=deleted');
        exit;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

// Edit category
// if (isset($_POST['edit']) && isset($_POST['category_id'])) {
//     $categoryId = $_POST['category_id'];
//     $_SESSION['category_id'] = $categoryId;
//     header('Location: editCategory.php');
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
    <link rel="stylesheet" href="CSS/adminCategory.css">
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

<body>
    <h1><center>Manage Categories</center></h1>
    <div class="category-table">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $category): ?> 
                    <tr>
                        <td><?php echo htmlspecialchars($category['categoryID']); ?></td>
                        <td><?php echo htmlspecialchars($category['name']); ?></td>
                        <td><?php echo htmlspecialchars($category['description']); ?></td>
                        <td>
                            <form action="editCategory.php" method="POST">
                                <input type="hidden" id="category_id" name="category_id" value="<?php echo $category['categoryID']; ?>"> 
                                <button type="submit" name="edit">Edit</button>
                            </form>
                        </td>
                        <td>
                            <form action="manageCategory.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                <input type="hidden" name="category_id" value="<?php echo $category['categoryID']; ?>"> 
                                <button type="submit" name="delete">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>


    <div class="add-category">  
        <h1><center>Add New Category</center></h1>
        <form action="manageCategory.php" method="post"> <!-- Form action corrected -->
            <label for="name">Category Name:</label>
            <input type="text" id="name" name="name" required>
            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea>
            <button type="submit" name="addCategory">Add Category</button>
        </form>
    </div>
    
</body>

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

</html>

