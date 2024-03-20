<?php
session_start();
if (isset($_POST['btnRegister'])) {

    $username = $_POST['UserName'];
    $password = $_POST['Password'];

    //    validations
    //    Username
    if (!preg_match('/^[A-Z][a-z]{1,15}$/', $username))
        echo "<script>alert('Username should be capital & length should be between 2-15 characters!');</script>";
    //    password
    elseif (strlen($password) < 5 || strlen($password) > 8)
        echo "<script>alert('Length of Password should be between 5 to 8 characters!');</script>";

    else {
        require_once("connectionDB.php");
        $sql = "SELECT * FROM Admin WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0)
            echo "<script>alert('Username is already registered. Try with different Username!');</script>";
        else {
            $sql = "INSERT INTO Admin(username,password) VALUES(?,?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sssss', $username, $password);
            $password = md5($password);
            $stmt->execute();
            if ($stmt)
                echo "<script>alert('Registration Successful!');window.location.replace('./adminLogin.php');</script>";
            else
                echo "<script>alert('Registration Unsuccessful!');</script>";
        }
    }
}
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>ACE GEAR ADMIN</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>
    <h1 align="center">ACE GEAR Admin Registration Form</h1>
    <hr>
    <form method="POST">
        <!-- Username input -->
        <div class="form-outline mb-3">
            <input type="text" name="username" class="form-control" required="" />
            <label class="form-label" for="form2Example1">First Name</label>
        </div>

        <!-- Password input -->
        <div class="form-outline mb-3">
            <input type="password" name="password" class="form-control" required="" />
            <label class="form-label" for="form2Example2">Password</label>
        </div>

        <!-- Submit button -->
        <button type="submit" name="btnRegister" class="btn btn-primary btn-block mb-4">Sign Up</button>

        <!-- Register buttons -->
        <div class="text-center">
            <p>Already registered? <a href="./adminLogin.php">Login</a></p>
        </div>
    </form>

</body>

</html>