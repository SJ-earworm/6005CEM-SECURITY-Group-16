<?php

    include("Connectdb.php");
    
    if ($_SERVER['REQUEST_METHOD']=="POST") {
        // // current product id passed in from `editproduct.php`
        // $pdID = $_GET['id'];

        // // form variables
        // $pdName = $_POST['pd-name'];
        // $pdPrice = $_POST['pd-price'];
        // $pdSize = $_POST['pd-size'];
        // $pdStockCount = $_POST['pd-stock-count'];
        // $pdDescription = $_POST['pd-description'];
        // $pdImg = $_FILES['pd-image'];
        // $pdImgCurrent = $_POST['pd-img-nochange'];
        // $userSelectedCategories = $_POST['category'];

        // //error handling for $_FILES
        // $imageFileError = $pdImg['error'];


        // //escaping the product description input
        // $escapedPDDescription = mysqli_real_escape_string($con, $pdDescription);


        // //if the user doesn't upload a new file, database will be populated with the existing file location
        // if ($imageFileError === UPLOAD_ERR_NO_FILE) {
        //     $uploadImage = $pdImgCurrent;
        // }
        // else {
        //     //retrievng file name
        //     $imageFileName = $pdImg['name'];

        //     //assigning temporary name of file to variable
        //     $imageFileTemp = $pdImg['tmp_name'];

        //     //separating the name of the file and the file type into separate strings e.g. image.jpg -> 'image' 'jpg'
        //     $filename_separate = explode('.', $imageFileName);

        //     //converting the file extension e.g. .JPG to lower case for consistency
        //     $file_extension = strtolower(end($filename_separate));

        //     //accepting these file types into the upload
        //     $extension = array('jpeg', 'jpg', 'png');



        //     if (in_array($file_extension, $extension)) {
        //         $uploadImage = 'images/' . $imageFileName;
        //         move_uploaded_file($imageFileTemp, $uploadImage);
        //     }
        // }


        // // updating the product data
        // $queryUpdate = "UPDATE product
        //           SET 
        //             pdName = '$pdName', 
        //             pdPrice = '$pdPrice', 
        //             pdSize = '$pdSize', 
        //             pdStockCount = '$pdStockCount', 
        //             pdDescription = '$escapedPDDescription', 
        //             pdImage = '$uploadImage' 
        //           WHERE pdID = $pdID";
        // $result = mysqli_query($con, $queryUpdate);

        // if (!$result) {
        //     die("Update product data SQL error: " . mysqli_error($con));
        // }


        // NEW SECURE CODE
        // current product id passed in from `editproduct.php`
        $pdID = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);  // retrieving pdID from the URL & sanitsing it
        $extraSanitisedPdId = abs($pdID);  // casting to positive number

        // form variables
        $pdName = filter_input(INPUT_POST, 'pd-name', FILTER_SANITIZE_STRING);
        $catchHTMLentities = array('&#39;', '&#34;', '&amp;');  // catching ' and " HTML entities from pdName
        $replacementEntities = array("'", '"', '&');  // setting the corresponding quotes to replace the HTML entities with
        $bringBackQuotes = str_replace($catchHTMLentities, $replacementEntities, $pdName);  // replacing the quotes
        // $sanitisedPdName = preg_replace('/\\\\/', '', $pdName);  // removing backslashes from pdName input
        $pdPrice = filter_input(INPUT_POST, 'pd-price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $almostFilteredPrice = preg_replace('/[^0-9.]/', '', $pdPrice);
        $filteredPrice = abs($almostFilteredPrice);
        $pdSize = filter_input(INPUT_POST, 'pd-size', FILTER_SANITIZE_SPECIAL_CHARS);
        $filteredSize = preg_replace('/[^0-9xcm ]/', '', $pdSize);
        $pdStockCount = filter_input(INPUT_POST, 'pd-stock-count', FILTER_SANITIZE_NUMBER_INT);
        $filteredStockCount = abs($pdStockCount);  // forcing positive number
        $pdDescription = filter_input(INPUT_POST, 'pd-description', FILTER_SANITIZE_STRING);
        $sanitisedPdDescription = preg_replace('/\\\\/', '', $pdDescription);
        $pdImg = $_FILES['pd-image'];

        $pdImgCurrent = $_POST['pd-img-nochange'];
        $userSelectedCategories = $_POST['category'];

        //error handling for $_FILES
        $imageFileError = $pdImg['error'];


        //escaping the product description input
        $escapedPDDescription = mysqli_real_escape_string($con, $pdDescription);


        //if the user doesn't upload a new file, database will be populated with the existing file location
        if ($imageFileError === UPLOAD_ERR_NO_FILE) {
            $uploadImage = $pdImgCurrent;
        }
        else {
            //retrievng file name
            $imageFileName = $pdImg['name'];

            //assigning temporary name of file to variable
            $imageFileTemp = $pdImg['tmp_name'];

            //separating the name of the file and the file type into separate strings e.g. image.jpg -> 'image' 'jpg'
            $filename_separate = explode('.', $imageFileName);

            //converting the file extension e.g. .JPG to lower case for consistency
            $file_extension = strtolower(end($filename_separate));

            // SANITISING FILE NAME: replacing all otther characters not in the preg_replace list with nothingness
            $sanitised_filename = preg_replace('/[^a-zA-Z0-9-_\.]/', '', pathinfo($imageFileName, PATHINFO_FILENAME));      // <-- NEW ADDITION

            //accepting these file types into the upload
            $extension = array('jpeg', 'jpg', 'png');



            if (in_array($file_extension, $extension)) {
                // NEW ADDITION
                // CHECKING FILE CONTENT, COMPATIBLE OR ACCEPTED FILE EXTENSIONS ANOT
                // checking & verifying file MIME type. if strpos returns false, means 'image/' is not in the MIME (e.g. image/jpeg, image/png, etc.), meaning that it could be
                // another file type
                $imageMIME = mime_content_type($imageFileTemp);
                if (strpos($imageMIME, 'image/') === false) {
                    echo "Invalid file type! <br/>";
                    die();
                }

                $uploadImage = 'images/' . $sanitised_filename . '.' . $file_extension;
                move_uploaded_file($imageFileTemp, $uploadImage);
            }
        }


        // updating the product data
        $queryUpdate = "UPDATE product
                  SET 
                    pdName = ?, 
                    pdPrice = ?, 
                    pdSize = ?, 
                    pdStockCount = ?, 
                    pdDescription = ?, 
                    pdImage = ? 
                  WHERE pdID = ?";
        
        $stmt = $con->prepare($queryUpdate);
        $stmt->bind_param("sdsissi", $pdName, $filteredPrice, $filteredSize, $filteredStockCount, $sanitisedPdDescription, $uploadImage, $extraSanitisedPdId);
        $stmt->execute();

        // If there were updates to be made but an error occured
        if ($stmt->errno) {
            echo "Couldn't update product";
            die();
            // note: if the input data is identical to the existing data, no updates will be made & affected rows remain at 0
            //       hence why we need to use $stmt->errno to detect problems with the insert instead of using $stmt->affected_rows
        }

        // closing current stmt
        $stmt->close();




        // ---------------------------------------------------------------------------------------------------------




        // clearing the product's product-category associations before inserting the new & updated ones
        $clearExistingUserCategories = "DELETE FROM pd_category_relationship WHERE pdID = ?";
        $stmt = $con->prepare($clearExistingUserCategories);
        $stmt->bind_param("i", $extraSanitisedPdId);
        $stmt->execute();

        // error handling
        if ($stmt->errno) {
            echo "Error clearing product-category associations before the category UPDATE process";
            // note: if the input data is identical to the existing data, no updates will be made & affected rows remain at 0
            //       hence why we need to use $stmt->errno to detect problems with the insert instead of using $stmt->affected_rows
            die();
        }

        // closing current stmt
        $stmt->close();

        // when the user selects a category
        // iterate through each user input and inserting product-category relationship into database
        foreach ($userSelectedCategories as $pdUserCategory) {
            // NEW ADDITION
            // sanitising catID
            $sanitisedPdUserCategory = filter_var($pdUserCategory, FILTER_SANITIZE_NUMBER_INT);

            $assignNewCategory = "INSERT INTO pd_category_relationship(pdID, catID) VALUES (? , (SELECT catID FROM product_category WHERE catID = ?))";
            $stmt = $con->prepare($assignNewCategory);
            $stmt->bind_param("ii", $extraSanitisedPdId, $sanitisedPdUserCategory);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                // redirect to `viewproducts.php`
                header("Location: aviewproducts.php");
            } else {
                echo "<p style='margin-left: 300px;'>An error occured, please try again</p>";
                // temporary
                // echo $stmt->error;
            }
        }


        $stmt->close();
    }
?>