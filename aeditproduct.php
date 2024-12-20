<?php
    // error handling setup
    error_reporting(E_ALL | E_STRICT);
    ini_set('display_startup_errors', 'Off');   // syntax errors considered startup errors cos they run before the execution of the page render
    ini_set('display_errors', 'Off');
    ini_set('log_errors', 'On');
    ini_set('error_log', 'C:/Applications/XAMPP/apache/logs/SPF/SPF-error.log');

    require("session_handling.php");
    include("Connectdb.php");
    
    //retrieving user ID from the URL

    // OLD UNSECURE CODE
    // $pdID = $_GET['id'];

    // //pulling the current product's data from the database
    // $pdQuery = "SELECT pdName, pdPrice, pdSize, pdStockCount, pdDescription, pdImage FROM product WHERE pdID = $pdID";
    // $result = mysqli_query($con, $pdQuery);

    // if (!$result) {
    //     die('SQL query error: ' . mysqli_error($con));
    // }

        
    // //retrieving the row of data
    // $tbrow = mysqli_fetch_assoc($result);

    // //error message if product not found in the database
    // if (!$tbrow) {
    //     error_log("SQL query error: " . mysqli_error($con));
    //     die("Product not found.");
    // }

    // $pdImg = $tbrow['pdImage'];
    // $pdName = $tbrow['pdName'];
    // $pdPrice = $tbrow['pdPrice'];
    // $pdSize = $tbrow['pdSize'];
    // $pdStockCount = $tbrow['pdStockCount'];
    // $pdDescription = $tbrow['pdDescription'];


    // NEW SECURE CODE
    $message = "";  // initialising error essage variable

    // if db_aeditproduct.php fails
    if (isset($_GET['error'])) {
        // $returnResponse = explode(',', $_GET['id'], $_GET['error']);
        // $pdID = filter_var($returnResponse[0], FILTER_SANITIZE_NUMBER_INT);
        // $message = filter_var($returnResponse[1], FILTER_SANITIZE_STRING);
        $pdID = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $extraSanitisedPdId = abs($pdID);  // casting to positive number
        $message = filter_input(INPUT_GET, 'error', FILTER_SANITIZE_STRING);

        // debugging
        // echo "In the if block <br/>";
        // echo $extraSanitisedPdId . "<br/>";
        // echo $message . "<br/>";
        // die;
    } else {
        $pdID = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);  // retrieving pdID from the URL & sanitsing it
        $extraSanitisedPdId = abs($pdID);  // casting to positive number
    }

    // $pdID = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);  // retrieving pdID from the URL & sanitsing it
    // $extraSanitisedPdId = abs($pdID);  // casting to positive number

    //pulling the current product's data from the database
    try {
        $pdQuery = "SELECT pdName, pdPrice, pdSize, pdStockCount, pdDescription, pdImage FROM product WHERE pdID = ?";
        $stmt = $con->prepare($pdQuery);
        $stmt->bind_param("i", $extraSanitisedPdId);
        $stmt->execute();
        $stmt->bind_result($pdName, $pdPrice, $pdSize, $pdStockCount, $pdDescription, $pdImg);
        if (!$stmt->fetch()) {
            $message = "Could not fetch product";
        }

        $stmt->close();
    } catch (mysqli_sql_exception $e) {
        $message = "Could not fetch product. Please try again later.";
        error_log("Edit Product page | Could not fetch product" . $e->getMessage());
    }


    // ----------------------------------------------------------------------------------------------


    // querying for all product categories
    // $pdCategoriesQuery = "SELECT catID, catName FROM product_category WHERE catName <> 'featured'";
    // $PCQueryResult = mysqli_query($con, $pdCategoriesQuery);
    // // error handling
    // if (!$PCQueryResult) {
    //     die("Product categories SQL error:" . mysqli_error($con));
    // }

    // // array to hold all product category names from the database
    // $dbCategory = array();

    // if (mysqli_num_rows($PCQueryResult) > 0) {
    //     while($allCatRow = mysqli_fetch_assoc($PCQueryResult)) {
    //         // array to hold all product category names from the database
    //         $catData = array(
    //             "cat_id" => $allCatRow['catID'],
    //             "cat_name" => $allCatRow['catName']
    //         );

    //         $dbCategory[] = $catData;
    //     }
    // }

    // NEW CODE TO MATCH CONSISTENCY OF PREPARED STATEMENTS
    // initialising array for holding all product category names from the database
    $dbCategory = array();

    try {
        $pdCategoriesQuery = "SELECT catID, catName FROM product_category WHERE catName <> 'featured'";
        $stmt = $con->prepare($pdCategoriesQuery);
        $stmt->execute();
        $PCQueryResult = $stmt->get_result();
        // error handling
        if (!$PCQueryResult) {
            $message = "Could not fetch product categories";
            // die("Could not fetch product categories");
            // temporary
            // echo mysqli_error($con);
        } elseif ($PCQueryResult->num_rows > 0) {
            while($allCatRow = $PCQueryResult->fetch_assoc()) {
                // array to hold all product category names from the database
                $catData = array(
                    "cat_id" => $allCatRow['catID'],
                    "cat_name" => $allCatRow['catName']
                );

                $dbCategory[] = $catData;
            }
        }

        $stmt->close();

    } catch (mysqli_sql_exception $e) {
        $message = "Could not fetch product categories";
        error_log("Edit Product page | Could not fetch product categories" . $e->getMessage());
    }

// ---------------------------------------------------------------------------------------------------

    // querying for user selected product categories
    // OLD UNSECURE CODE
    // $pdUserCategoryQuery = "SELECT catID FROM pd_category_relationship WHERE pdID = $pdID";
    // $PUserCQueryResult = mysqli_query($con, $pdUserCategoryQuery);
    // // error handling
    // if (!$PUserCQueryResult) {
    //     die("Product associated category SQL error:" . mysqli_error($con));
    // }
    
    // // array holding existing product category assign
    // $checkedCategory = array();

    // if (mysqli_num_rows($PUserCQueryResult) > 0) {
    //     while($assocCatRow = mysqli_fetch_assoc($PUserCQueryResult)) {
    //         $checkedCategory[] = $assocCatRow['catID'];
    //     }
    // }

    // NEW SECURE CODE
    // initialising array for holding existing product category assign
    $checkedCategory = array();

    try {
        $pdUserCategoryQuery = "SELECT catID FROM pd_category_relationship WHERE pdID = ?";
        $stmt = $con->prepare($pdUserCategoryQuery);
        $stmt->bind_param("i", $extraSanitisedPdId);
        $stmt->execute();
        $PUserCQueryResult = $stmt->get_result();
        // error handling
        if (!$PUserCQueryResult) {
            $message = "Could not retrieve selected product categories.";
            // temporary
            // echo mysqli_error($con);
        }

        if ($PUserCQueryResult->num_rows > 0) {
            while($assocCatRow = $PUserCQueryResult->fetch_assoc()) {
                $checkedCategory[] = $assocCatRow['catID'];
            }
        }


        // closing database connection
        $stmt->close();

    } catch (mysqli_sql_exception $e) {
        $message = "Could not retrieve selected product categories.";
        error_log("Edit Product page | Could not retrieve selected product categories" . $e->getMessage());
    }
?>


<!DOCTYPE html>
<html lang="utf=8">



<head>
<title>Admin Edit Product</title>
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
			<a href="index.php">Statistic</a>
			<a href="index.php">Logout</a>
</div>

<!-- error message display section -->
<?php if (isset($message) && $message != ''): ?>
    <div class="error-message"><?php echo $message; ?></div>
<?php endif; ?>

<div class="layout">
<h1>Edit Product</h1>
    <!-- form sends over to `db_editproduct.php -->
	<form action="db_aeditproduct.php?id=<?php echo htmlspecialchars($extraSanitisedPdId); ?>" method="post" enctype="multipart/form-data">
		<p>Product name:</p>
		<input type="text" name="pd-name" value="<?php echo htmlspecialchars_decode(htmlspecialchars($pdName, ENT_NOQUOTES, 'UTF-8'), ENT_QUOTES); ?>" autocomplete="off" required>
		<p>Price:</p>
		<input type="text" name="pd-price" value="<?php echo htmlspecialchars($pdPrice); ?>" autocomplete="off" required>
        <div class="row">
            <div>
                <p>Product Size</p>
                <input type="text" name="pd-size" value="<?php echo htmlspecialchars($pdSize); ?>" autocomplete="off" required style="width: 50%;">
            </div>
            <div>
                <p>Quantity</p>
                <input type="text" name="pd-stock-count" value="<?php echo htmlspecialchars($pdStockCount); ?>" autocomplete="off" required style="width: 50%;">
            </div>
        </div>
		<p>Product Description:</p>
		<textarea name="pd-description"><?php echo htmlspecialchars_decode(htmlspecialchars($pdDescription, ENT_NOQUOTES, 'UTF-8'), ENT_QUOTES); ?></textarea>
		<p>Image:</p>
        <p>Current File - <?php echo htmlspecialchars($pdImg); ?></p>
		<input type="file" name="pd-image" id="files">
		<label for="files">Choose Image</label>
		<br/><br/>

        <!-- category -->
        <p>Category</p>
        <div class="row">
            <?php
                // going through each category in the database and printing out the checkboxes dynamically
                foreach ($dbCategory as $catData) {
                    $catID = $catData['cat_id'];
                    $catName = $catData['cat_name'];

                    echo "<div class='checkbox-grp'>";
                    echo "    <input type='checkbox' name='category[]' value='" .htmlspecialchars($catID). "'";

                        // if the category has already been checked before
                        if (in_array($catID, $checkedCategory)) {
                            echo " checked";
                        }

                    echo ">";
                    echo "    <label for='" .htmlspecialchars($catID). "'>" .htmlspecialchars($catName). "</label>";
                    echo "</div>";
                }
            ?>
        </div><br/><br/>

        <div class="checkbox-grp-feature">
        <?php
            echo "<input type='checkbox' name='category[]' value='1'";
                if (in_array(1, $checkedCategory)) {
                    echo " checked";
                }
            echo ">";
        ?>
            <label for="1">Feature on homepage</label>
        </div>

        <!-- hidden input field to pass $pdImg over to `db_editproduct.php` -->
        <input type="hidden" name="pd-img-nochange" value="<?php echo htmlspecialchars($pdImg); ?>">
		<button type="submit">Save</button>
	</form>
</div>

</body>
</html>