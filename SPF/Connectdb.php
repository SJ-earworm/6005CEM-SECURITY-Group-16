<?php

// initialising the server details variables
$dbServer = "localhost";
$dbUsername = "root";
$dbPassword = "";

// establishing connection with localhost phpMyAdmin
$con = mysqli_connect($dbServer, $dbUsername, $dbPassword);

// check connection
if (!$con) {
	die("Connection failed: " . mysqli_connect_error());
}


$checkDatabaseExist = "SHOW DATABASES LIKE 'santasplushiefactory_db'";
$checkDatabaseResult = mysqli_query($con, $checkDatabaseExist);

if (mysqli_num_rows($checkDatabaseResult) > 0) {
	// if database exists, do nothing
	// else, perform the else block
}
else {
	// create database
	$dbQuery = "CREATE DATABASE IF NOT EXISTS `santasplushiefactory_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";
	$result = mysqli_query($con, $dbQuery);

	if ($result) {
		echo "Database created successfully! <br/>";
	}
	else {
		echo "Error creating database: " . mysqli_error($con);
	}


	// close connection before reconnecting to localhost & locating the specific server
	$con->close();
}





// ------------------------------------------- creating the database tables ----------------------------------------------




// reconnecting to localhost and selecting the `con_db` database
$con = mysqli_connect($dbServer, $dbUsername, $dbPassword, 'santasplushiefactory_db');

// check connection
if (!$con) {
	die("Connection failed: " . mysqli_connect_error());
}


// creating the tables

$checkTableExist = "SHOW TABLES LIKE 'db'";
$checkTableResult = mysqli_query($con, $checkTableExist);

if (mysqli_num_rows($checkTableResult) > 0){
	// if table exists, do nothing
	// else, perform the else block
}
else {
	// user table
	$query = "CREATE TABLE IF NOT EXISTS `db` (
    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `email` varchar(255) NOT NULL,
    `name` varchar(255) NOT NULL,
    `pw` varchar(255) NOT NULL,
    `role` ENUM('admin', 'sub_admin', 'user') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB";

	$result = mysqli_query($con, $query);

	// table creation validation
	if ($result) {
	echo "'db' table created successfully <br/>";
	}
	else {
	echo "Error creating 'db' table: " . mysqli_error($con);
	}
}





$checkTableExist = "SHOW TABLES LIKE 'product'";
$checkTableResult = mysqli_query($con, $checkTableExist);

if (mysqli_num_rows($checkTableResult) > 0) {
	// if table exists, do nothing
	// else, perform the else block
}
else {
	// product table
	$query = "CREATE TABLE IF NOT EXISTS `product` (
		`pdID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`pdName` varchar(255) NOT NULL,
		`pdPrice` decimal(5,2) NOT NULL,
		`pdSize` varchar(255) NOT NULL,
		`pdStockCount` int(11) DEFAULT NULL,
		`pdDescription` text,
		`pdImage` varchar(255)
	) ENGINE=InnoDB";

	$result = mysqli_query($con, $query);

	// table creation validation
	if ($result) {
	echo "'product' table created successfully <br/>";
	}
	else {
	echo "Error creating 'product' table: " . mysqli_error($con);
	}





	// product category table
	$query = "CREATE TABLE IF NOT EXISTS `product_category` (
		`catID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`catName` varchar(255) NOT NULL
	) ENGINE=InnoDB";

	$result = mysqli_query($con, $query);

	// table creation validation
	if ($result) {
		echo "'product_category' table created successfully <br/>";
	}
	else {
		echo "Error creating 'product_category' table: " . mysqli_error($con);
	}
	
	// pre-load product category table with data
	$insertPDQuery = "INSERT INTO `product_category` (`catName`) VALUES ('Featured'), ('Animals'), ('Fun'), ('Festive'), ('Collaborations')";
	$insertPDResult = mysqli_query($con, $insertPDQuery);

	if ($insertPDResult) {
		echo "Product categories inserted successfully.";
	}
	else {
		echo "Error inserting product categories into 'product_category': " . mysqli_error($con);
	}
}





$checkTableExist = "SHOW TABLES LIKE 'pd_category_relationship'";
$checkTableResult = mysqli_query($con, $checkTableExist);

if (mysqli_num_rows($checkTableResult) > 0) {
	// if table exists, do nothing
	// else, perform the else block
}
else {
	// product-category relationship table
	$query = "CREATE TABLE IF NOT EXISTS `pd_category_relationship` (
		`pdID` bigint(20) UNSIGNED NOT NULL,
		`catID` bigint(20) UNSIGNED NOT NULL,
		FOREIGN KEY (`pdID`) REFERENCES `product` (`pdID`) ON DELETE CASCADE ON UPDATE CASCADE,
		FOREIGN KEY (`catID`) REFERENCES `product_category` (`catID`) ON DELETE CASCADE ON UPDATE CASCADE
	) ENGINE=InnoDB";

	$result = mysqli_query($con, $query);

	// table creation validation
	if ($result) {
		echo "'pd_category_relationship' table created successfully <br/>";
	}
	else {
		echo "Error creating 'pd_category_relationship' table: " . mysqli_error($con);
	}	
}





$checkTableExist = "SHOW TABLES LIKE 'carousel_promo'";
$checkTableResult = mysqli_query($con, $checkTableExist);

if (mysqli_num_rows($checkTableResult) > 0) {
	// if table exists, do nothing
	// else, perform the else block
}
else {
	// carousel/promotional material table
	$query = "CREATE TABLE IF NOT EXISTS `carousel_promo` (
		`promoImageID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`promoImage` varchar(255) NOT NULL,
		`promoTitle` varchar(255) NOT NULL
	) ENGINE=InnoDB";

	$result = mysqli_query($con, $query);

	// table creation validation
	if ($result) {
		echo "'carousel_promo' table created successfully <br/>";
	}
	else {
		echo "Error creating 'carousel_promo' table: " . mysqli_error($con);
	}
}





$checkTableExist = "SHOW TABLES LIKE 'cart'";
$checkTableResult = mysqli_query($con, $checkTableExist);

if (mysqli_num_rows($checkTableResult) > 0) {
	// if table exists, do nothing
	// else, perform the else block
}
else {
	// cart table
	$query = "CREATE TABLE IF NOT EXISTS `cart` (
		`cartID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`userID` bigint(20) UNSIGNED NOT NULL,
		`pdID` bigint(20) UNSIGNED NOT NULL,
		`quantity` int(11) NOT NULL,
		FOREIGN KEY (`userID`) REFERENCES `db` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
		FOREIGN KEY (`pdID`) REFERENCES `product` (`pdID`) ON DELETE CASCADE ON UPDATE CASCADE
	) ENGINE=InnoDB";

	$result = mysqli_query($con, $query);

	// table creation validation
	if ($result) {
		echo "'cart' table created successfully <br/>";
	}
	else {
		echo "Error creating 'cart' table: " . mysqli_error($con);
	}
}





$checkTableExist = "SHOW TABLES LIKE 'payment'";
$checkTableResult = mysqli_query($con, $checkTableExist);

if (mysqli_num_rows($checkTableResult) > 0) {
	// if table exists, do nothing
	// else, perform the else block
}
else {
	// payment table
	$query = "CREATE TABLE IF NOT EXISTS `payment` (
		`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`billName` varchar(255) NOT NULL,
		`email` varchar(255) NOT NULL,
		`address` varchar(255) NOT NULL,
		`city` text NOT NULL,
		`state` text NOT NULL,
		`zip` int(5) NOT NULL,
		`datePay` date NOT NULL,
		`cartID` bigint(20) UNSIGNED NOT NULL
		-- FOREIGN KEY (`cartID`) REFERENCES `cart` (`cartID`) ON DELETE CASCADE ON UPDATE CASCADE
	) ENGINE=InnoDB";

	$result = mysqli_query($con, $query);

	// table creation validation
	if ($result) {
		echo "'payment' table created successfully <br/>";
	}
	else {
		echo "Error creating 'payment' table: " . mysqli_error($con);
	}
}

?>