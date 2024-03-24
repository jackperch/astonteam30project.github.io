<?php
require_once("connectionDB.php");
session_start();

// Check if the user is logged in
if (!isset($_SESSION['adminID'])) {
    header('Location: adminlogin.php');
    exit;
}

$categoryID = null;
$category = ['name' => '', 'description' => '']; // Default empty category

// Update category logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['category_id'])) {
    // Sanitize input
    $categoryID = $_POST['category_id'];

    // Fetch the category to edit
    $stmt = $db->prepare("SELECT categoryID, name, description FROM category WHERE categoryID = :categoryID");
    $stmt->bindParam(':categoryID', $categoryID, PDO::PARAM_INT);
    $stmt->execute();
    $category = $stmt->fetch(PDO::FETCH_ASSOC);

}

if (!$category) {
    echo "Category not found.";
    exit;
}

// Update category
// Handle the update action
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updateCategory'])) {
    $categoryID = $_POST['category_id'];
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);

    // Update the category details
    try {
        $updateStmt = $db->prepare("UPDATE category SET name = :name, description = :description WHERE categoryID = :categoryID");
        $updateStmt->bindParam(':name', $name);
        $updateStmt->bindParam(':description', $description);
        $updateStmt->bindParam(':categoryID', $categoryID);
        $updateStmt->execute();

        // Redirect to manage categories page with a success message
        header('Location: manageCategory.php?status=success');
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
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

<h1>Edit Category</h1>

<div class="category-table">
    <form action="editCategory.php" method="post">
        <input type="hidden" name="category_id" value="<?php echo htmlspecialchars($categoryID); ?>">
        
        <label for="name">Category Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($category['name']); ?>" required>
        
        <label for="description">Description:</label>
        <textarea id="description" name="description" required><?php echo htmlspecialchars($category['description']); ?></textarea>
        
        <button type="submit" name="updateCategory">Update Category</button>
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
