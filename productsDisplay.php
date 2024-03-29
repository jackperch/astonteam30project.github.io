
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
                
                <nav>
                    <a href="index.php">Home</a>
                    <a href="productsDisplay.php">Products</a>
                    <a href="about.php">About</a>
                    <a href="contact.php">Contact</a>
                    <?php 
                    session_start();
                    if (isset($_SESSION['username'])) {
                        echo "<a href='members-blog.php'>Blog</a>";
                        echo "<a href='account.php'>Account</a>";
                        echo "<a href='logout.php'>Logout</a>";
                    } elseif (isset($_SESSION['adminID'])) {
                        echo "<a href='Dashboard.php'>Login</a>";
                        echo "<a href='account.php'>Logout</a>";
                        echo "<a href='logout.php'>Logout</a>";
                        
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
            require_once("connectionDB.php"); // Connects to the dtb

            // Fetch the total quantity of items in the user's cart
            $stmt = $db->prepare("SELECT SUM(quantity) AS totalQuantity FROM cart WHERE customerID = :customerID");
            $stmt->execute(['customerID' => $_SESSION['customerID']]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result && $result['totalQuantity'] > 0) {
                $totalQuantity = $result['totalQuantity'];
            }
        }elseif (isset($_SESSION['adminID'])) {
            require_once("connectionDB.php"); // Connects to the dtb
            $stmt = $db->prepare("SELECT SUM(quantity) AS totalQuantity FROM cart WHERE adminID = :adminID");
            $stmt->execute(['adminID' => $_SESSION['adminID']]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result && $result['totalQuantity'] > 0) {
                $totalQuantity = $result['totalQuantity'];
            }

            // Fetch the total quantity of items in the guest's cart
         } if (isset($_SESSION['guest_shopping_cart'])) {
                  $totalQuantity = array_sum($_SESSION['guest_shopping_cart']);}
          
      
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
        $categories = []; // Initialize as empty, will be populated based on category
       
        // Fetch categories
        try {
            $stmt = $db->query("SELECT * FROM category");
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $categoryId = $categories[0]['categoryID'];
            $currentCategoryId = !empty($_GET['categoryId']) ? $_GET['categoryId'] : (isset($categories[0]) ? $categories[0]['categoryID'] : null);

            //print_r($categories);
            //echo "Category ID: " . $categoryId;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            header ("Location: error.php?error=dtbError");
            exit;
        }



        $categoryProducts = []; // Initialize as empty, will be populated based on category

            

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


        <?php

                function fetchProductsByCategory($db, $categoryId) {
            try {
                $stmt = $db->prepare("SELECT * FROM products WHERE categoryID = :categoryId");
                $stmt->execute(['categoryId' => $categoryId]);
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                error_log("Error fetching products by category: " . $e->getMessage());
                header ("Location: error.php?error=dtbError");
                exit;
            }
        }
        //$categoryProducts = fetchProductsByCategory($db, $categoryId);
        ?>

        <?php //foreach ($categoryProducts as $product) ?>
         <section id="products">
            <!-- Products will be loaded here -->
            <div id="category-product-grid" class="product-grid">
            </div>
        </section>

        





        <?php
        // // Fetch Popular New and Featured products functions
        // function fetchFeaturedProducts($db) {
        //     $sql = "SELECT * FROM products WHERE is_featured = 1"; // Fetching products in productlisting table that are marked as featured
        //     try {
        //         $stmt = $db->prepare($sql);
        //         $stmt->execute();
        //         return $stmt->fetchAll(PDO::FETCH_ASSOC);
        //     } catch (PDOException $e) {
        //         echo "Error fetching featured products: " . $e->getMessage();
        //         return [];
        //     }
        // }

        // function fetchPopularProducts($db) {
        //     $sql = "SELECT * FROM products WHERE is_popular = 1"; // Fetch popular products
        //     try {
        //         $stmt = $db->prepare($sql);
        //         $stmt->execute();
        //         return $stmt->fetchAll(PDO::FETCH_ASSOC);
        //     } catch (PDOException $e) {
        //         echo "Error fetching popular products: " . $e->getMessage();
        //         return [];
        //     }
        // }

        // function fetchNewProducts($db) {
        //     $sql = "SELECT * FROM products WHERE is_new = 1"; // Fetching products in productlisting table that are marked as new
        //     try {
        //         $stmt = $db->prepare($sql);
        //         $stmt->execute();
        //         return $stmt->fetchAll(PDO::FETCH_ASSOC);
        //     } catch (PDOException $e) {
        //         echo "Error fetching new products: " . $e->getMessage();
        //         return [];
        //     }
        // }


        
        // $featuredProducts = fetchFeaturedProducts($db);
        // $popularProducts = fetchPopularProducts($db);
        // $newProducts = fetchNewProducts($db);

        // Handling form submission for sorting
        $sortOption = isset($_POST['sort_option']) ? $_POST['sort_option'] : '';

        // Determine the ORDER BY clause based on the sorting option
        switch ($sortOption) {
            case 'price_low_high':
                $orderBy = 'price ASC'; // Sort by price in ascending order
                break;
            case 'price_high_low':
                $orderBy = 'price DESC'; // Sort by price in descending order
                break;
            case 'newest':
                $orderBy = 'productID ASC'; // USind the auto incrementing productID as a reference for the newest product
                break;
            case 'oldest':
                $orderBy = 'productID DESC'; // using the auto incrementing productID as a reference for the oldest product
                break;
            default:
                $orderBy = 'product_name ASC'; // Default sorting
        }

        // Define functions to fetch featured, popular, and new products with dynamic sorting
        function fetchFeaturedProducts($db, $orderBy) {
            $query = "SELECT * FROM products WHERE is_featured = 1 ORDER BY $orderBy"; // Fetching products in productlisting table that are marked as featured
            return fetchProducts($db, $query);
        }

        function fetchPopularProducts($db, $orderBy) {
            $query = "SELECT * FROM products WHERE is_popular = 1 ORDER BY $orderBy"; // Fetch popular products
            return fetchProducts($db, $query);
        }

        function fetchNewProducts($db, $orderBy) {
            $query = "SELECT * FROM products WHERE is_new = 1 ORDER BY $orderBy"; // Fetching products in productlisting table that are marked as new
            return fetchProducts($db, $query);
        }

        function fetchProducts($db, $query) {
            $stmt = $db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        function fetchAllProducts($db, $orderBy) {
            $query = "SELECT * FROM products ORDER BY $orderBy"; // Fetch all products
            return fetchProducts($db, $query);
        }

        // Fetch the products
        $featuredProducts = fetchFeaturedProducts($db, $orderBy);
        $popularProducts = fetchPopularProducts($db, $orderBy);
        $newProducts = fetchNewProducts($db, $orderBy);
        $allProducts = fetchAllProducts($db, $orderBy);

        ?>

        <!-- Sorting form -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="sorting-form">
            <select id="sort-option" name="sort_option" onchange="this.form.submit()">
                <option value="">Sort by</option>
                <option value="price_low_high" <?php echo (isset($_POST['sort_option']) && $_POST['sort_option'] == 'price_low_high') ? 'selected' : ''; ?>>Price Low to High</option>
                <option value="price_high_low" <?php echo (isset($_POST['sort_option']) && $_POST['sort_option'] == 'price_high_low') ? 'selected' : ''; ?>>Price High to Low</option>
                <option value="newest" <?php echo (isset($_POST['sort_option']) && $_POST['sort_option'] == 'newest') ? 'selected' : ''; ?>>Newest</option>
                <option value="oldest" <?php echo (isset($_POST['sort_option']) && $_POST['sort_option'] == 'oldest') ? 'selected' : ''; ?>>Oldest</option>
            </select>
        </form>

        <!-- Featured products will be loaded here -->
        <section id="featured-products">
            <h2><center>Featured Products:</center></h2>
            <div class="product-grid">
                <?php foreach ($featuredProducts as $product): ?>
                    <div class="product">
                        <?php // Make the product image a clickable link
                        echo "<a href='productDetail.php?productID={$product['productID']}'>";
                        echo "<img src='Images/Product-Images/" . htmlspecialchars($product['image']) . "' alt='" . htmlspecialchars($product['product_name']) . "' class='product-image'>";
                        echo "</a>"; ?>                       
                        <h3 class="product-name"><?php echo htmlspecialchars($product['product_name']); ?></h3>
                        <!-- <p class="product-price">£<?//php echo htmlspecialchars($product['price']); ?></p> -->
                        <!-- <div class="quantity-input">
                            <button class="quantity-decrease" onclick="changeQuantity(false, '<?//= $product['productID'] ?>')">-</button>
                            <input type="number" id="quantity-<?//= $product['productID'] ?>" name="quantity" value="1" min="1" class="quantity-field">
                            <button class="quantity-increase" onclick="changeQuantity(true, '<?//= $product['productID'] ?>')">+</button>
                        </div> -->
                        <?php
                         if ($product['stock'] == 0) {
                            echo "<p>Out of stock</p>"; // // Checks the stock availability
                        } else{
                            echo "<form class='add-to-cart-form' method='post' action='updatecart.php'>";
                            echo "<input type='hidden' name='productID' value='{$product['productID']}'>";
                            echo "<div class='price'>£{$product['price']}</div>";
                            echo "<button class='add-to-cart' onclick='displayAlert()'>Add to cart!</button>";
                            echo "</form>";
                        }
                        ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>



        <!-- Popular products will be loaded here -->
        <section id="popular-products">
            <h2><center>Popular Products:</center></h2>
            <div class="product-grid">
                <?php foreach ($popularProducts as $product): ?>
                    <div class="product">
                        <?php // Make the product image a clickable link
                            echo "<a href='productDetail.php?productID={$product['productID']}'>";
                            echo "<img src='Images/Product-Images/" . htmlspecialchars($product['image']) . "' alt='" . htmlspecialchars($product['product_name']) . "' class='product-image'>";
                            echo "</a>"; ?>                        
                        <h3 class="product-name"><?php echo htmlspecialchars($product['product_name']); ?></h3>
                        <!-- <p class="product-price">£<?//php echo htmlspecialchars($product['price']); ?></p> -->
                        <!-- <div class="quantity-input">
                            <button class="quantity-decrease" onclick="changeQuantity(false, ' $product['productID'] ?>')">-</button>
                            <input type="number" id="quantity- //$product['productID']" name="quantity" value="1" min="1" class="quantity-field">
                            <button class="quantity-increase" onclick="changeQuantity(true, '<//?= $product['productID'] ?>')">+</button>
                        </div> -->
                        <?php
                         if ($product['stock'] == 0) {
                            echo "<p>Out of stock</p>"; //// Checks the stock availability
                        } else{
                            echo "<form class='add-to-cart-form' method='post' action='updatecart.php'>";
                            echo "<input type='hidden' name='productID' value='{$product['productID']}'>";
                            echo "<div class='price'>£{$product['price']}</div>";
                            echo "<button class='add-to-cart' onclick='displayAlert()'>Add to cart!</button>";
                            echo "</form>";
                        }
                        ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>



        <!-- New products will be loaded here -->
        <section id="new-products">
            <h2><center>New Products:</center></h2>
            <div class="product-grid">
                <?php foreach ($newProducts as $product): ?>
                    <div class="product">
                    <?php // Make the product image a clickable link
                        echo "<a href='productDetail.php?productID={$product['productID']}'>";
                        echo "<img src='Images/Product-Images/" . htmlspecialchars($product['image']) . "' alt='" . htmlspecialchars($product['product_name']) . "' class='product-image'>";
                        echo "</a>"; ?>
                        <h3 class="product-name"><?php echo htmlspecialchars($product['product_name']); ?></h3>
                        <!-- <p class="product-price">£<?//php echo htmlspecialchars($product['price']); ?></p> -->
                        <!-- <div class="quantity-input">
                            <button class="quantity-decrease" data-product-id="< $product['productID']?>">-</button>
                            <input type="number" id="quantity- $product['productID'] ?>" name="quantity" value="1" min="1" class="quantity-field">
                            <button class="quantity-increase" data-product-id="<$product['productID']?>">+</button>
                        </div> -->
                        <?php 
                         if ($product['stock'] == 0) { // Checks the stock availability
                            echo "<p>Out of stock</p>";
                        } else{
                            echo "<form class='add-to-cart-form' method='post' action='updatecart.php'>";
                            echo "<input type='hidden' name='productID' value='{$product['productID']}'>";
                            echo "<div class='price'>£{$product['price']}</div>";
                            echo "<button class='add-to-cart' data-product-id='{$product['productID']}' onclick='displayAlert()'>Add to cart!</button>";
                            echo "</form>";
                        }
                        ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>


        <section id="all-products">
            <h2><center>All Products:</center></h2>
            <div class="product-grid">
                <?php foreach ($allProducts as $product): ?>
                    <div class="product">
                        <?php // Make the product image a clickable link
                        echo "<a href='productDetail.php?productID={$product['productID']}'>";
                        echo "<img src='Images/Product-Images/" . htmlspecialchars($product['image']) . "' alt='" . htmlspecialchars($product['product_name']) . "' class='product-image'>";
                        echo "</a>"; ?>
                        <h3 class="product-name"><?php echo htmlspecialchars($product['product_name']); ?></h3>
                        <!-- <p class="product-price">£<?//php echo htmlspecialchars($product['price']); ?></p> -->
                        <!-- <div class="quantity-input">
                            <button class="quantity-decrease" data-product-id="<?//= $product['productID'] ?>">-</button>
                            <input type="number" id="quantity-<?//= $product['productID'] ?>" name="quantity" value="1" min="1" class="quantity-field">
                            <button class="quantity-increase" data-product-id="<?//= $product['productID'] ?>">+</button>
                        </div> -->
                        <?php 
                         if ($product['stock'] == 0) {
                            echo "<p>Out of stock</p>"; // Checks the stock availability
                        } else{
                            echo "<form class='add-to-cart-form' method='post' action='updatecart.php'>";
                            echo "<input type='hidden' name='productID' value='{$product['productID']}'>";
                            echo "<div class='price'>£{$product['price']}</div>";
                            echo "<button class='add-to-cart' data-product-id='{$product['productID']}' onclick='displayAlert()'>Add to cart!</button>";
                            echo "</form>";
                        }
                        ?>
                    </div>
                <?php endforeach; ?>
        </section>

    </main>




    
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

    const sortOption = document.getElementById('sort-option');

    sortOption.addEventListener('focus', () => {
        sortOption.style.transform = 'scale(1.1)';
    });

    sortOption.addEventListener('blur', () => {
        sortOption.style.transform = 'scale(1)';
    });   


    $(document).ready(function() {
        // Initial setup
        //var categories = $('.category');
        //var currentCategoryIndex = 0; // Starts with the first category selected

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
        // categories.click(function() {
        //     var categoryId = $(this).data('category-id');
        //     fetchProductsForCategory(categoryId);
        // });
    });

                    

     var currentCategoryIndex = 0;  // setting it at 0 to start with 
     var categories = $(".category"); // Selecting all the categories from the array

    // Show the first category's products initially or on page load
    fetchProductsForCategory($(categories[currentCategoryIndex]).data("category-id"));

    // Function to fetch and display products for a given category
    function fetchProductsForCategory(categoryId) {
        $.ajax({
            url: 'fetchProducts.php', // PHP script to return products HTML
            type: 'GET',
            data: {categoryId: categoryId},
            success: function(response) {
                // Assuming response contains the HTML for the products
                $('#category-product-grid').html(response);
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", status, error);
            }
        });
    }

    // Previous category button
    $('.left-arrow').click(function() {
        currentCategoryIndex = (currentCategoryIndex) % categories.length;
        var categoryId = $(categories[currentCategoryIndex]).data("category-id");
        fetchProductsForCategory(categoryId);
        console.log("Category ID: " + categoryId);
    });

    // Next category button
    $('.right-arrow').click(function() {
        currentCategoryIndex = (currentCategoryIndex) % (categories.length + 1);
        var categoryId = $(categories[currentCategoryIndex]).data("category-id");
        fetchProductsForCategory(categoryId);
        console.log("Category ID: " + categoryId);
    });

 

    $(document).ready(function() {
        // Increase quantity
        $(".quantity-increase").click(function() {
            var productId = $(this).data("product-id");
            updateQuantity(productId, "increase");
        });

        // Decrease quantity
        $(".quantity-decrease").click(function() {
            var productId = $(this).data("product-id");
            updateQuantity(productId, "decrease");
        });

        // Add to cart
        $(".add-to-cart-btn").click(function() {
            var productId = $(this).data("product-id");
            var quantity = $("#quantity-" + productId).val();
            addToCart(productId, quantity);
        });

        function updateQuantity(productId, action) {
            var quantityInput = $("#quantity-" + productId);
            var currentQuantity = parseInt(quantityInput.val());
            var newQuantity = action === "increase" ? currentQuantity + 1 : currentQuantity - 1;

            if (newQuantity < 1) newQuantity = 1; // Ensure quantity never goes below 1
            quantityInput.val(newQuantity);

            // Optionally, send an AJAX request to update the session/cart
            // $.post('updatecart.php', { productID: productId, quantity: newQuantity, action: 'update', ajax: 1 }, function(response) {
            //     console.log(response); // Handle response
            // }, 'json');
        }

        function addToCart(productId, quantity) {
            $.post('updatecart.php', { productID: productId, quantity: quantity, action: 'add', ajax: 1 }, function(response) {
                console.log(response); // Handle response
                alert('Product added to cart.');
                // Update cart count or redirect as needed
            }, 'json');
        }
    });


    </script>

</body>
<!--  </html> -->
</html>
