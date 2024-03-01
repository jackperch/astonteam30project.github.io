<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products - ACE GEAR</title>
    <link rel="stylesheet" href="CSS/styles.css">
    <link rel="stylesheet" href="CSS/productsDisplay.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- <script src="js/products.js"></script>  need to create this -->
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
            $categoryId = $categories[0]['categoryID'];
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            exit;
        }




        // Fetch products functions

        function fetchProductsByCategory($db, $categoryId) {
            $sql = "SELECT * FROM products WHERE categoryID = :categoryId"; // Fetching products in productlisting table that have current category id
            try {
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
                $stmt->execute();
                echo "Fetching products for category ID: " . $categoryId;
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo "Error fetching products: " . $e->getMessage();
                return [];
            }
        }

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


            

            // Fetch products by category
            $products = fetchProductsByCategory($db, $categoryId);
        ?>






        <h1><center>CATEGORIES</center></h1>

        <section class="category-selector">
            <div class="arrow left-arrow" onclick="previousCategory()">&#x2190;</div>
            <?php foreach ($categories as $category): ?>
                <div class="category" data-category-id="<?= $category['categoryID'] ?>"><?php echo htmlspecialchars($category['name']); ?></div>
            <?php endforeach; ?>
            <div class="arrow right-arrow" onclick="nextCategory()">&#x2192;</div>
        </section>





        <section id="products">
            <h2><center>Products:</center></h2>
            <div class="product-grid">
                <!-- Products will be loaded here -->
                <?php echo count($products); ?>
            </div>
            <?php
           // if(isset($_GET['categoryId'])) {
             //   $categoryId = $_GET['categoryId'];
                //$products = fetchProductsByCategory($db, $categoryId);

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
          //  }
            ?>



        <section id="featured-products">
            <h2><center>Featured Products:</center></h2>
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
            <h2><center>Popular Products:</center></h2>
            <!-- Popular products will be loaded here -->
            <div class="product-grid">
                <?php foreach ($popularProducts as $product): ?>
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



        <section id="new-products">
            <h2><center>New Products:</center></h2>
            <!-- New products will be loaded here -->
            <div class="product-grid">
                <?php foreach ($newProducts as $product): ?>
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


    $(document).ready(function() {
        // Initial setup
        var categories = $('.category');
        var currentCategoryIndex = 0; // Starts with the first category selected

        // Function to show only the current category
        function showCurrentCategory() {
            categories.hide(); // Hide all categories
            $(categories[currentCategoryIndex]).show(); // Show only the current category
        }

        // Call showCurrentCategory on page load to ensure only the current category is displayed
        showCurrentCategory();
        // Fetch products for the current category when it changes
        function fetchProductsForCategory(categoryId) {
                // AJAX call to fetch and display products for the given category ID
                console.log("Fetching products for category ID:", categoryId); // Print the category ID
                // Need to Replace with actual AJAX call
            }

        // Function to show the previous category
        window.previousCategory = function() {
            // Decrease currentCategoryIndex, loop to the last if at the first item
            currentCategoryIndex = (currentCategoryIndex - 1 + categories.length) % categories.length;
            showCurrentCategory();
        };

        // Function to show the next category
        window.nextCategory = function() {
            // Increase currentCategoryIndex, loop to the first if at the last item
            currentCategoryIndex = (currentCategoryIndex + 1) % categories.length;
            showCurrentCategory();
        };

        // Optional: Fetch products for the current category when it changes
        function fetchProductsForCategory(categoryId) {
            // AJAX call to fetch and display products for the given category ID
            console.log("Fetching products for category ID:", categoryId); // Need to Replace with actual AJAX call
        }

        // Call fetchProductsForCategory when a category is clicked
        categories.click(function() {
            var categoryId = $(this).data('category-id');
            fetchProductsForCategory(categoryId);
        });
    });

                    


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
            <?php 
                if ($categoryId > 0) {
                    $categoryId--;
                } else {
                    $categoryId = count($categories) - 1;
                }
            ?>
        });

        document.querySelector('.right-arrow').addEventListener('click', () => {
            currentCategoryIndex = (currentCategoryIndex < categories.length - 1) ? currentCategoryIndex + 1 : 0;
            updateCategoryDisplay();
            <?php 
                if ($categoryId > count($categories) - 1) {
                    $categoryId++;
                } else {
                    $categoryId = 0;
                }
            ?>
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


    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.category').forEach(function(categoryElement) {
            categoryElement.addEventListener('click', function() {
                var categoryId = this.getAttribute('data-category-id'); // Assume each category div has a data-category-id attribute
                fetchProductsForCategory(categoryId);
            });
        });
    });

    function fetchProductsForCategory(categoryId) {
        $.ajax({
            url: 'fetchProducts.php', // PHP script to fetch products by category
            type: 'GET',
            data: { categoryId: categoryId },
            success: function(data) {
                console.log(data)
                //the PHP script should return the product HTML
                document.querySelector('.product-grid').innerHTML = data;
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('AJAX error: ' + textStatus + ' : ' + errorThrown);
            }
        });
    }

    </script>

</body>
</html>




