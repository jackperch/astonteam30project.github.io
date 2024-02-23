<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products - ACE GEAR</title>
    <link rel="stylesheet" href="CSS/styles.css">
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



    <main>
        

        <?php
        
        require_once("connectionDB.php");  // Database connection

        // Fetch categories
        $categories = [];
        try {
            $stmt = $db->query("SELECT * FROM category");
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            exit;
        }




        // Fetch products functions

        function fetchFeaturedProducts($db) {
            $sql = "SELECT * FROM products WHERE is_featured = 1"; // Fetching products in productlisting table that are marked as featured
            try {
                $stmt = $db->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo "Error fetching featured products: " . $e->getMessage();
                return [];
            }
        }
        
        function fetchPopularProducts($db) {
            $sql = "SELECT * FROM products WHERE is_popular = 1"; // Fetch popular products
            try {
                $stmt = $db->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo "Error fetching popular products: " . $e->getMessage();
                return [];
            }
        }
        
        function fetchNewProducts($db) {
            $sql = "SELECT * FROM products WHERE is_new = 1"; // Fetching products in productlisting table that are marked as new
            try {
                $stmt = $db->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo "Error fetching new products: " . $e->getMessage();
                return [];
            }
        }
        

            $featuredProducts = fetchFeaturedProducts($db);
            $popularProducts = fetchPopularProducts($db);
            $newProducts = fetchNewProducts($db);

        
        ?>









    <!-- <section class="category-selector">
            <div class="arrow left-arrow" onclick="previousCategory()">&#x2190;</div>
            <div class="category">HOME FITNESS</div>
            <div class="category">GYM EQUIPMENT</div>
            <div class="category">COMBAT SPORTS</div>
            <div class="arrow right-arrow" onclick="nextCategory()">&#x2192;</div>
        </section> -->

        <section class="category-selector">
            <div class="arrow left-arrow" onclick="previousCategory()">&#x2190;</div>
            <?php foreach ($categories as $category): ?>
                <div class="category"><?php echo htmlspecialchars($category['name']); ?></div>
            <?php endforeach; ?>
            <div class="arrow right-arrow" onclick="nextCategory()">&#x2192;</div>
        </section>

        <section id="featured-products">
            <h2>Featured Products:</h2>
            <div class="product-grid">
                <?php foreach ($featuredProducts as $product): ?>
                    <div class="product">
                        <img src="Images/Product-Images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" class="product-image">
                        <h3 class="product-name"><?php echo htmlspecialchars($product['product_name']); ?></h3>
                        <p class="product-price">£<?php echo htmlspecialchars($product['price']); ?></p>
                        <div class="quantity-input">
                            <button class="quantity-decrease" onclick="changeQuantity(false, '<?= $product['productID'] ?>')">-</button>
                            <input type="number" id="quantity-<?= $product['productID'] ?>" name="quantity" value="1" min="1" class="quantity-field">
                            <button class="quantity-increase" onclick="changeQuantity(true, '<?= $product['productID'] ?>')">+</button>
                        </div>
                        <button class="add-to-cart-btn" onclick="addToCart('<?= $product['productID'] ?>')">Add to Cart</button>
                    </div>
                <?php endforeach; ?>
            </div>
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




