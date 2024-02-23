<?php
session_start();
include("connectionDB.php");

$result=mysqli_query($con,"select customerID,username,password,first_name,last_name, email, from customers where customerID='$customerID'")or die ("query 1 incorrect.......");

list($customerID,$first_name,$last_name,$email,$password,$username)=mysqli_fetch_array($result);



mysqli_query($con,"update customers set first_name='$first_name', last_name='$last_name', email='$email', password='$password', username='$username' where customerID='$customerID'")or die("Query 2 is inncorrect..........");

header("location: manageuser.php");
mysqli_close($con);
}
?>
      <header>
            <div id="logo-container">
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
                <a href="login.php">Login</a>
                <h5 class="title">Edit User</h5>
            </nav>
              <form action="edituser.php" name="form" method="post" enctype="multipart/form-data">
              <div class="card-body">
                
                  <input type="hidden" name="user_id" id="user_id" value="<?php echo $customerID;?>" />
                    <div class="col-md-12 ">
                      <div class="form-group">
                        <label>First name</label>
                        <input type="text" id="first_name" name="first_name"  class="form-control" value="<?php echo $first_name; ?>" >
                      </div>
                    </div>
                    <div class="col-md-12 ">
                      <div class="form-group">
                        <label>Last name</label>
                        <input type="text" id="last_name" name="last_name" class="form-control" value="<?php echo $last_name; ?>" >
                      </div>
                    </div>
                    <div class="col-md-12 ">
                      <div class="form-group">
                        <label for="exampleInputEmail1">Email address</label>
                        <input type="email"  id="email" name="email" class="form-control" value="<?php echo $email; ?>">
                      </div>
                    </div>
                    <div class="col-md-12 ">
                      <div class="form-group">
                        <label >Password</label>
                        <input type="text" name="password" id="password" class="form-control" value="<?php echo $password; ?>">
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

          </div>
         
          
        </div>
      </div>
      <?php
?>