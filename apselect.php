<?php
	// error handling setup
	error_reporting(E_ALL | E_STRICT);
    ini_set('display_startup_errors', 'Off');   // syntax errors considered startup errors cos they run before the execution of the page render
    ini_set('display_errors', 'Off');
    ini_set('log_errors', 'On');
    ini_set('error_log', 'C:/Applications/XAMPP/apache/logs/SPF/SPF-error.log');
	
	require("session_handling.php");
?>

<!DOCTYPE html>
<html lang="utf=8">



<head>
<title>Admin Product</title>
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

<div class="select">
<h1>Product</h1>
<div class="row">
	<div class="column">
	<a href="anewproduct.php"><img src ="images/add.png" width="300" height="300"></a>
</div>

	<div class="column">
	<a href="aviewproducts.php"><img src ="images/delete.png" width="300" height="300"></a>
</div>

	<div class="column">
	<a href="apromo.php"><img src ="images/promo.png" width="300" height="300"></a>
  </div>
</div>




</body>
</html>