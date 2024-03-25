<?php
require_once("connectionDB.php"); 

try {
    // Fetch all stock items ordered by stock quantity
    $sql = "SELECT productID, product_name, stock FROM products ORDER BY stock ASC";
    $stmt = $db->query($sql);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>ACE GEAR - Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <link rel="stylesheet" href="CSS/styles.css">
    <link rel="stylesheet" href="CSS/stockManagement.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Sono&display=swap');
    </style>
    <script src="/js/main.js"></script>
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
            session_start();
            if (isset($_SESSION['adminID'])) {
                echo "<a href='Dashboard.php'>Dashboard</a>";
                echo "<a href='adminLogout.php'>Logout</a>";

            }else{
                echo "<a href='login.php'>Login</a>";
            }


            ?>
        </nav>
        <?php
        // Initialize the total quantity variable
        $totalQuantity = 0;

        // Check if the user is logged in
        if (isset($_SESSION['customerID'])) {
            require_once("connectionDB.php"); // Adjust this path as necessary

            // Fetch the total quantity of items in the user's cart
            $stmt = $db->prepare("SELECT SUM(quantity) AS totalQuantity FROM cart WHERE customerID = :customerID");
            $stmt->execute(['customerID' => $_SESSION['customerID']]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result && $result['totalQuantity'] > 0) {
                $totalQuantity = $result['totalQuantity'];
            }
        }elseif(isset($_SESSION['adminID'])) {
            require_once("connectionDB.php"); // Adjust this path as necessary

            // Fetch the total quantity of items in the user's cart
            $stmt = $db->prepare("SELECT SUM(quantity) AS totalQuantity FROM cart WHERE adminID = :adminID");
            $stmt->execute(['adminID' => $_SESSION['adminID']]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result && $result['totalQuantity'] > 0) 
            {
                $totalQuantity = $result['totalQuantity'];
            }


        }elseif(isset($_SESSION['guest_shopping_cart']))
        {

            // Fetch the total quantity of items in the guest's cart
                $totalQuantity = array_sum($_SESSION['guest_shopping_cart']);
        
        }
        ?>
        <div id="cart-container">
            <!-- cart icon image with link to cart page -->
            <a href="cart.php">
                <img id="cart-icon" src="Images/cart-no-bg.png" alt="Cart">
                <span id="cart-count"><?php echo $totalQuantity; ?></span>
            </a>
        </div>
            
    </header>

    <h1>Stock Overview</h1>
    <table>
        <tr>
            <th>Product ID</th>
            <th>Product Name</th>
            <th>Stock</th>
            <th>Manage</th>
        </tr>
        <?php foreach ($products as $product): ?>
            <tr class="<?php
                echo $product['stock'] == 0 ? 'stock-red' : 
                     ($product['stock'] < 5 ? 'stock-orange' : 
                     ($product['stock'] < 10 ? 'stock-amber' : 
                     ($product['stock'] > 9 ? 'stock-green' :'')));
            ?>">
                <td><?php echo htmlspecialchars($product['productID']); ?></td>
                <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                <td><?php echo htmlspecialchars($product['stock']); ?></td>
                <td><a href="editProducts.php?productID=<?php echo $product['productID']; ?>">Manage</a></td>
            </tr>
        <?php endforeach; ?>
    </table>


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