<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products - ACE GEAR</title>
    <link rel="stylesheet" href="CSS/productsDisplay.css">
    <script src="js/products.js"></script> <!-- need to create this -->
</head>
<body>

<header>
<div id="logo-container">
                <!-- logo image -->
                <img id="logo" src="Images/Logo-no-bg.png" alt="Logo">
                <h1 id="nav-bar-text">ACE GEAR</h1>
            </div>
            <div id="search-container">
                <input type="text" id="search-bar" placeholder="Search...">
                <button id="search-button">Search</button>
            </div>
            <nav>
                <a href="index.php">Home</a>
                <a href="products.php">Products</a>
                <a href="about.php">About</a>
                <a href="contact.php">Contact</a>
                <?php 
                session_start();
                if (isset($_SESSION['username'])) {
                    echo "<a href='members-blog.php'>Blog</a>";
                    echo "<a href='account.php'>Account</a>";
                    echo "<a href='logout.php'>Logout</a>";
                } else {
                    echo "<a href='login.php'>Login</a>";
                }
                ?>
            </nav>
            <div id="cart-container">
                <!-- cart icon image with link to cart page -->
                <a href="cart.php">
                    <img id="cart-icon" src="Images/cart-no-bg.png" alt="Cart">
                    <span id="cart-count">0</span>
                </a>
            </div>
</header>

<main>
    

    <?php
        //session_start();
        require_once("connectionDB.php");

        // Fetch categories
        $categories = [];
        try {
            $stmt = $db->query("SELECT * FROM category");
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            exit;
        }

        // Define functions to fetch featured, popular, and new products
        function fetchFeaturedProducts($db) {
            // Your SQL query to fetch featured products
        }

        function fetchPopularProducts($db) {
            // Your SQL query to fetch popular products
        }

        function fetchNewProducts($db) {
            // Your SQL query to fetch new products
        }
    ?>









    <section class="category-selector">
        <div class="arrow left-arrow" onclick="previousCategory()">&#x2190;</div>
        <div class="category">HOME FITNESS</div>
        <div class="category">GYM EQUIPMENT</div>
        <div class="category">COMBAT SPORTS</div>
        <div class="arrow right-arrow" onclick="nextCategory()">&#x2192;</div>
    </section>

    <section id="featured-products">
        <h2>Featured Products:</h2>
        <!-- Featured products will be loaded here -->
    </section>

    <section id="popular-products">
        <h2>Popular Products:</h2>
        <!-- Popular products will be loaded here -->
    </section>

    <section id="new-products">
        <h2>New Products:</h2>
        <!-- New products will be loaded here -->
    </section>
</main>









<footer>
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
</footer>





<script>

let currentCategoryIndex = 0;
const categories = document.querySelectorAll('.category');

function updateCategoryDisplay() {
    categories.forEach((category, index) => {
        category.style.display = index === currentCategoryIndex ? 'block' : 'none';
    });
}

document.addEventListener('DOMContentLoaded', () => {
    updateCategoryDisplay();
    document.querySelector('.left-arrow').addEventListener('click', () => {
        currentCategoryIndex = (currentCategoryIndex > 0) ? currentCategoryIndex - 1 : categories.length - 1;
        updateCategoryDisplay();
    });

    document.querySelector('.right-arrow').addEventListener('click', () => {
        currentCategoryIndex = (currentCategoryIndex < categories.length - 1) ? currentCategoryIndex + 1 : 0;
        updateCategoryDisplay();
    });
});

document.addEventListener('keydown', (event) => {
    if (event.key === 'ArrowLeft') {
        currentCategoryIndex = (currentCategoryIndex > 0) ? currentCategoryIndex - 1 : categories.length - 1;
        updateCategoryDisplay();
    } else if (event.key === 'ArrowRight') {
        currentCategoryIndex = (currentCategoryIndex < categories.length - 1) ? currentCategoryIndex + 1 : 0;
        updateCategoryDisplay();
    }
});

</script>

</body>
</html>




