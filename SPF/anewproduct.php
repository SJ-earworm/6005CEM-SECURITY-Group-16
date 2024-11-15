<?php

    require("session_handling.php");
    include("Connectdb.php");
    if ($_SERVER['REQUEST_METHOD']=="POST") {

        $pdName = $_POST['pd-name'];
        $pdPrice = $_POST['pd-price'];
        $pdSize = $_POST['pd-size'];
        $pdStockCount = $_POST['pd-stock-count'];
        $pdDescription = $_POST['pd-description'];
        $pdImg = $_FILES['pd-image'];

        //escaping the product description input
        $escapedPDDescription = mysqli_real_escape_string($con, $pdDescription);



        //handling the image for insertion into database
        $imageFileName = $pdImg['name'];

        //error handling
        $imageFileError = $pdImg['error'];

        //assigning temporary name of file to variable
        $imageFileTemp = $pdImg['tmp_name'];

        //separating the name of the file and the file type into separate strings e.g. image.jpg -> 'image' 'jpg'
        $filename_separate = explode('.', $imageFileName);

        //converting the file extension e.g. .JPG to lower case for consistency
        $file_extension = strtolower(end($filename_separate));

        //accepting these file types into the upload
        $extension = array('jpeg', 'jpg', 'png');


        if (in_array($file_extension, $extension)) {
            $uploadImage = 'images/' . $imageFileName;
            move_uploaded_file($imageFileTemp, $uploadImage);

            $query = "INSERT INTO product(pdName, pdPrice, pdSize, pdStockCount, pdDescription, pdImage) VALUES ('$pdName', '$pdPrice', '$pdSize', '$pdStockCount', '$escapedPDDescription', '$uploadImage')";
            $result = mysqli_query($con, $query);

            if ($result) {
                echo "Product added successfully <br/>";
                header("Location: aviewproducts.php");
                die;
            }
            else {
                echo "Error: " . mysqli_error($con);
            }
        }
        else {
            echo "Please fill up all details";
        }


        //  closing database connection
        $con->close();
    }
?>


<!DOCTYPE html>
<html lang="utf=8">



<head>
<title>Admin Add Product</title>
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

<div class="layout">
<h1>Add Product</h1>
	<form action="#" method="post" enctype="multipart/form-data">
		<p>Product name:</p>
		<input type="text" name="pd-name" placeholder="Name of product" autocomplete="off" required>
		<p>Price:</p>
		<input type="text" name="pd-price" placeholder="RM0.00" autocomplete="off" required>
        <div class="row">
            <div>
                <p>Product Size</p>
                <input type="text" name="pd-size" placeholder="0cm X 0cm X 0cm" autocomplete="off" required style="width: 80%;">
            </div>
            <div>
                <p>Quantity</p>
                <input type="text" name="pd-stock-count" placeholder="0" autocomplete="off" required style="width: 50%;">
            </div>
        </div>
		<p>Product Description:</p>
		<textarea name="pd-description" placeholder="Product description"></textarea>
		<p>Image:</p>
		<input type="file" name="pd-image" id="files">
		<label for="files">Choose Image (jpeg, jpg or png file types only)</label>
		<br/><br/>
		<button type="submit">Add Product</button>
	</form>
</div>


</body>
</html>