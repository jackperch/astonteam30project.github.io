
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>ACE GEAR</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="UTF-8">
        <link rel="stylesheet" href="CSS/styles.css">
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
            <div id="search-container">
                <input type="text" id="search-bar" placeholder="Search...">
                <button id="search-button">Search</button>
            </div>
            <nav>
                <a href="index.html">Home</a>
                <a href="products.html">Products</a>
                <a href="#">About</a>
                <a href="contact.html">Contact</a>
                <a href="login.php">Login</a>
            </nav>
            <div id="cart-container">
                <!-- cart icon image -->
                <img id="cart-icon" src="Images/white-cart-icon-no-bg.jpg" alt="Cart">
                <span id="cart-count">0</span>
            </div>
        </header>

        <button onclick href="products.html" id="ProductsBtn" title="Go to Products Page">View Products</button>
        <form action="signup.php" method="post">
            
            <label>Username</label>
            <input type="text" name="username" size="15" maxlength="25" />
            <br>
            <br>
            <label>Password:</label>

            <input type="password" name="password" size="15" maxlength="25" />
            <br>
            <br>
            <label> Email </label>
            <input type="text" name="email"  size="15" maxlength="25"/>
            <br>
            <br>
            <input type="submit" value="Register" />
            <input type="reset" value="clear"/>
            <input type="hidden" name="submitted" value="TRUE" />
        
        </form>
        

    </body>


</html>
