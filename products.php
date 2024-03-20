 <!DOCTYPE html>
<html lang="en">
    <head>
        <title>ACE GEAR</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="UTF-8">
        <link rel="stylesheet" href="CSS/styles.css">
        <link rel="stylesheet" href="CSS/products.css">
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Sono&display=swap');
        </style>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
                if (isset($_SESSION['customerID'])) {
                    echo "<a href='members-blog.php'>Blog</a>";
                    echo "<a href='account.php'>Account</a>";
                    echo "<a href='logout.php'>Logout</a>";
                } elseif (isset($_SESSION['adminID'])) 
                {
        
                    echo "<a href='Dashboard.php'>Dashboard</a>";
                    echo "<a href='account.php'>Account</a>";
                    echo "<a href='logout.php'>Logout</a>";

                }else
                {
                    echo "<a href='login.php'>Login</a>";

                }
                //echo 'cutomer Id is ',$_SESSION['customerID'];
                //echo 'username is ',$_SESSION['username'];
                
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
        }else{
            // Fetch the total quantity of items in the guest's cart
              if (isset($_SESSION['guest_shopping_cart'])) {
                  $totalQuantity = array_sum($_SESSION['guest_shopping_cart']);}
          
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

<?php

require_once("connectionDB.php");
function fetchProducts() {
    global $db;

    try {
         // database connection code 
         require_once("connectionDB.php");
        // SQL query
        $sql = "
                SELECT
                p.productID,
                p.image,
                p.product_name AS productName,
                p.price,
                p.description AS productDescription,
                p.colour,
                p.size,
                p.stock,
                c.name AS categoryName
                FROM
                products p
                JOIN
                category c ON p.categoryID = c.categoryID;
                    ";

        // Prepare and execute the query
        $SQLEXECUTE = $db->prepare($sql);
        $SQLEXECUTE->execute();

        // Fetch results
        $products = $SQLEXECUTE->fetchAll(PDO::FETCH_ASSOC);
    

        // Close the database connection
        //$db = null;
        return $products; // Return the fetched product

    }//Catches the problem
      catch(PDOException $ex) {
        echo("Failed to connect to the database.<br>");
        echo($ex->getMessage());
        exit;
      } 
     
}

$allOfTheProducts = fetchProducts();
?>


<!-- Sorting form -->
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <select id="sort-option" name="sort_option" onchange="this.form.submit()">
        <option value="">Sort by</option>
        <option value="price_low_high" <?php echo (isset($_POST['sort_option']) && $_POST['sort_option'] == 'price_low_high') ? 'selected' : ''; ?>>Price Low to High</option>
        <option value="price_high_low" <?php echo (isset($_POST['sort_option']) && $_POST['sort_option'] == 'price_high_low') ? 'selected' : ''; ?>>Price High to Low</option>
        <option value="newest" <?php echo (isset($_POST['sort_option']) && $_POST['sort_option'] == 'newest') ? 'selected' : ''; ?>>Newest</option>
        <option value="oldest" <?php echo (isset($_POST['sort_option']) && $_POST['sort_option'] == 'oldest') ? 'selected' : ''; ?>>Oldest</option>
    </select>
</form>


<?php
// Assuming you have a database connection established in $db
$sortOption = isset($_POST['sort_option']) ? $_POST['sort_option'] : '';

$orderBy = "product_name"; // Default order by
$orderDirection = "ASC"; // Default order direction

switch ($sortOption) {
    case 'price_low_high':
        $orderBy = "price";
        $orderDirection = "ASC";
        break;
    case 'price_high_low':
        $orderBy = "price";
        $orderDirection = "DESC";
        break;
    case 'aplphabetical':
        $orderBy = "product_name";
        $orderDirection = "ASC";
        break;
    case 'oldest':
        $orderBy = "productID";
        $orderDirection = "ASC";
        break;
}

$query = "SELECT * FROM products ORDER BY $orderBy $orderDirection";
$stmt = $db->prepare($query);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($products as $product) {
    // Display your products
    echo "<div class='product'>";
    // Product details here
    echo "<a href='productDetail.php?productID={$product['productID']}'>";
                            echo "<img src='Images/Product-Images/" . htmlspecialchars($product['image']) . "' alt='" . htmlspecialchars($product['product_name']) . "' class='product-image'>";
                            echo "</a>"; ?>                        
                        <h3 class="product-name"><?php echo htmlspecialchars($product['product_name']); ?></h3>
                        <p class="product-price">£<?php echo htmlspecialchars($product['price']); ?></p>
    <?php echo "</div>";
}
?>

    <?php
    // Display fetched product details
    //The allOfTheProducts is an array of all the products  that  is going to be iterated through and the product is the current product that is being iterated through
    //Display each product
    foreach ($allOfTheProducts as $product) {
        echo "<div class='product-container'>";

        // Make the product name a clickable link
        echo "<a href='productDetail.php?productID={$product['productID']}'>";
        echo "<img src='Images/Product-Images/{$product['image']}' alt='{$product['product_name']}' width=80 height=80>";
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
         
        echo "<div class='quantity'>";
        echo "<button class='plus-btn' type='button' name='button'>";
        //echo "<img src='minus.svg' alt='' />";
        echo "+";
        echo "</button>";
        echo "<input type='text' name='name' value='1'>";

      
        
        echo "<button class='minus-btn' type='button' name='button'>";
        //echo "<img src='minus.svg' alt='' />";
        echo "-";
        echo "</button>";

        
        echo "</div>";
        echo "</form>";
        }
        echo "</div>"; 
        echo "<hr class='hr-line'>";
        
      
    }
    ?>

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
        
    <!-- <script>
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        var message = "<?php 
            session_start();
            if(isset($_SESSION['alert_message'])) {
                echo $_SESSION['alert_message'];
                unset($_SESSION['alert_message']);
            } else {
                echo ''; // Echo an empty string if no message is set
            }
        ?>";

        function displayAlert() {
            if (message !== "") {
                alert(message);
            }
        }
    </script> -->


    <!-- Pop-up Modal -->
        <!-- Make two classes a hide and show and switch between them when you want to display the pop up -->
        <!-- <div id="cart-summary-modal" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <h2>Cart Summary</h2>
            <p id="cart-items-summary">Your cart items will be listed here...</p>
            <div class="modal-actions">
            <button id="continue-shopping">Continue Shopping</button>
            <button id="go-to-cart">Go to Cart</button>
            </div>
        </div>
        </div> -->


        <!-- <script>
                $(document).ready(function() {
                // When the user clicks on the button, open the modal
                $('.add-to-cart').on('click', function() {
                    // fetchCartSummary() is a function that returns the summary of the cart
                    // You should replace this with actual logic to fetch cart summary
                    var cartSummary = fetchCartSummary();
                    function fetchCartSummary() {
                        // Make an AJAX request to updatecart.php to fetch the cart summary
                        $.ajax({
                            url: 'updatecart.php',
                            type: 'GET',
                            dataType: 'html',
                            success: function(response) {
                                $('#cart-items-summary').html(response); // Assuming response contains the summary HTML
                                $('#cart-summary-modal').show();
                            },
                            error: function() {
                                console.log('Error fetching cart summary.');
                            }
                        });
                    }
                    $('#cart-items-summary').html(cart_summary);
                    $('#cart-summary-modal').show();
                });

                // // When the user clicks on <span> (x), close the modal
                // $('.close-btn').on('click', function() {
                //     $('#cart-summary-modal').hide();
                // });

                // // When the user clicks anywhere outside of the modal, close it
                // $(window).on('click', function(event) {
                //     if ($(event.target).is('#cart-summary-modal')) {
                //     $('#cart-summary-modal').hide();
                //     }
                // });

                // // Continue shopping button
                // $('#continue-shopping').on('click', function() {
                //     $('#cart-summary-modal').hide();
                // });

                // // Go to cart button
                // $('#go-to-cart').on('click', function() {
                //     window.location.href = 'cart.php'; // Adjust the link as necessary
                // });

                // Example function to fetch cart summary
                function fetchCartSummary() {
                    // Placeholder for cart summary. Replace this with actual dynamic content.
                    return "2 items in your cart. Total: $100.00";
                }
                });
            </script> -->
    <script>
        function filterProducts() {
            var searchQuery = $('#search-input').val();
            var categoryId = $('#filter-category').val();
            var sortBy = $('#sort-option').val();

            $.ajax({
                url: 'fetchFilteredProducts.php', // This is the PHP script that will return filtered and sorted products
                type: 'POST',
                data: {
                    search: searchQuery,
                    category: categoryId,
                    sort: sortBy
                },
                success: function(response) {
                    // Assuming the response is the HTML content of filtered and sorted products
                    $('#product-container').html(response);
                },
                error: function(error) {
                    console.error("Error fetching products:", error);
                }
            });
        }
    </script>
</body>

</html>

