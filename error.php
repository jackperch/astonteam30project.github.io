<?php
// Retrieve the error code from the URL
$errorCode = isset($_GET['error']) ? $_GET['error'] : '';

// Define error messages based on error codes
if ($errorCode === 'no_session') {
    $errorMsg = 'Sorry, you are not logged in. Please log in to continue.';
} elseif($errorCode ==='cartIssue'){
    $errorMsg = 'Sorry, there was an issue with your cart. Please try again later.';
}

elseif ($errorCode === 'unexpected_error') {
    $errorMsg = 'An unexpected error occurred. Please try again later.';
}elseif($errorCode ==='dtbError'){
    $errorMsg = 'Sorry, there was an issue with the database. Please try again later.';
}


else {
    $errorMsg = 'An unknown error occurred.';
}

// HTML code to display the error message
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <style>
        /* Your CSS styles */
    </style>
</head>
<body>
    <div class="container">
        <h1>Error</h1>
        <p><?php echo $errorMsg; ?></p>
        <?php if ($errorCode === 'no_session'){
           echo" <p>Please <a href='login.php'>'log in'</a> to continue.</p>";
        }elseif ($errorCode === 'cartIssue'){
            echo" <p>Please <a href='cart.php'>'try again'</a> to continue.</p>";
        }
         ?>
    </div>
</body>
</html>