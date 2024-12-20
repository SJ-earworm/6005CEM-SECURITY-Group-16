<?php
	// error handling setup
	error_reporting(E_ALL | E_STRICT);
    ini_set('display_startup_errors', 'Off');   // syntax errors considered startup errors cos they run before the execution of the page render
    ini_set('display_errors', 'Off');
    ini_set('log_errors', 'On');
    ini_set('error_log', 'C:/Applications/XAMPP/apache/logs/SPF/SPF-error.log');

	require("session_handling.php");
	require("Connectdb.php");

	// initialising variables
	$message = "";
	$result = "";

	try {
		$query = "SELECT 
					payment.id, 
					payment.billName, 
					payment.email, 
					payment.address, 
					payment.city,
					payment.state, 
					payment.zip, 
					-- cart.pid,
					-- cart.quantity,
					-- cart.price,
					payment.datePay 
				FROM payment";
				// JOIN cart ON payment.id = cart.id
		$stmt = $con->prepare($query);
		$stmt->execute();
		$result = $stmt->get_result();

	} catch (mysqli_sql_exception $e) {
		$message = "Error fetching report list.";
		error_log("Admin Report page | " . $e->getMessage());
	}

?>


<!DOCTYPE html>
<html lang="utf=8">



<head>
<title>Purchase Report</title>
<link rel="stylesheet" href="astyle.css">
</head>
<body>

<div class="sidebar">
	<img src ="images/logo.png" width="160" height="100">
		<img src="images/profile.png" class="profile">
			<a href="admin.php">Dashboard</a>
			<a href="apselect.php">Product</a>
			<?php if ($_SESSION['role'] == 'admin') { ?>
			<a href="auser.php">Users</a>
			<?php } ?>
			<a href="areport.php">Statistic</a>
			<a href="logout.php">Logout</a>
</div>


<!-- error message display section -->
<?php if (isset($message) && $message != ''): ?>
	<div class="error-message"><?php echo $message; ?></div>
<?php else: ?>
<table>
	<tr>
		<th>Id No.</th>
		<th>Purchased By</th>
		<th>Email</th>
		<th>Address</th>
		<th>City</th>
		<th>State</th>
		<th>Zip</th>
		<!-- <th>Product Id</th>
		<th>Quantity</th>
		<th>Price</th> -->
		<th>Date of Purchase</th>
	</tr>
	
<?php

// $query = "SELECT 
// 			payment.id, 
// 			payment.billName, 
// 			payment.email, 
// 			payment.address, 
// 			payment.city,
// 			payment.state, 
// 			payment.zip, 
// 			cart.pid,
// 			cart.quantity,
// 			cart.price,
// 			payment.datePay 
// 		  FROM payment
// 		  JOIN cart ON payment.id = cart.id";
// $result = mysqli_query($con, $query);

// if (mysqli_num_rows($result) > 0) {
// 	while ($row = mysqli_fetch_assoc($result)){
// 	echo "<tr><td>". $row["id"] .  "</td><td>" . $row["billName"] .  "</td><td>" . $row["email"] . "</td><td>" . $row["address"] . "</td><td>" . 
// 	$row["city"] . "</td><td>" . $row["state"] . "</td><td>" . $row["zip"] . "</td><td>" . $row["pid"] . "</td><td>" . $row["quantity"] ."</td><td>RM" . 
// 	$row["price"] . "</td><td>" . $row["datePay"] . "</td><tr>" ;


// 	}
// 	echo"</table>";
// }
// else {
	
// echo"0 result";		
// }

// $con-> close();

// NEW CODE
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()){
			echo "<tr>";
			echo "	<td>". $row["id"] .  "</td>";
			echo "	<td>" . $row["billName"] .  "</td>";
			echo "	<td>" . $row["email"] . "</td>";
			echo "	<td>" . $row["address"] . "</td>";
			echo "	<td>" . $row["city"] . "</td>";
			echo "	<td>" . $row["state"] . "</td>";
			echo "	<td>" . $row["zip"] . "</td>";
			// echo "	<td>" . $row["pid"] . "</td>";
			// echo "	<td>" . $row["quantity"] ."</td>";
			// echo "	<td>RM" . $row["price"] . "</td>";
			echo "	<td>" . $row["datePay"] . "</td>";
			echo "<tr>" ;


		}
		echo"</table>";
	}
	else {
		echo "0 result";		
	}

	$stmt-> close();
?>
 </table>
<?php endif; ?>
<br/><br/>
</body>
</html>
