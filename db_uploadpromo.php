<?php
    require("Connectdb.php");

    if ($_SERVER['REQUEST_METHOD']=="POST") {

        // user input
        // $promoImg = $_FILES['promo-image'];
        // $promoTitle = $_POST['promo-title'];

           
        // //handling the image for insertion into database
        // $imageFileName = $promoImg['name'];
    
        // //error handling
        // $imageFileError = $promoImg['error'];
    
        // //assigning temporary name of file to variable
        // $imageFileTemp = $promoImg['tmp_name'];
    
        // //separating the name of the file and the file type into separate strings e.g. image.jpg -> 'image' 'jpg'
        // $filename_separate = explode('.', $imageFileName);
    
        // //converting the file extension e.g. .JPG to lower case for consistency
        // $file_extension = strtolower(end($filename_separate));
    
        // //accepting these file types into the upload
        // $extension = array('jpeg', 'jpg', 'png');
    
    
        // if (in_array($file_extension, $extension)) {
        //     $uploadImage = 'images/' . $imageFileName;
        //     move_uploaded_file($imageFileTemp, $uploadImage);
    
        //     $query = "INSERT INTO carousel_promo(promoImage, promoTitle) VALUES ('$uploadImage', '$promoTitle')";
        //     $result = mysqli_query($con, $query);
    
        //     if ($result) {
        //         echo "Promo image added successfully <br/>";
        //         header("Location: apromo.php");
        //         die;
        //     }
        //     else {
        //         die("Error: " . mysqli_error($con));
        //     }
        // }


        // NEW CODE
        $promoImg = $_FILES['promo-image'];
        $promoTitle = filter_input(INPUT_POST, 'promo-title', FILTER_SANITIZE_STRING);  // sanitising user input promo title

           
        //handling the image for insertion into database
        $imageFileName = $promoImg['name'];
    
        //error handling
        $imageFileError = $promoImg['error'];
    
        //assigning temporary name of file to variable
        $imageFileTemp = $promoImg['tmp_name'];
    
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
            // checking & verifying file MIME type. if strpos returns false, means 'image/' is not in the MIME (e.g. image/jpeg, image/png, etc.),
            // meaning that it could be another file type
            $imageMIME = mime_content_type($imageFileTemp);
            if (strpos($imageMIME, 'image/') === false) {
                echo "Invalid file type! <br/>";
                die();
            }

            $uploadImage = 'images/' . $sanitised_filename . '.' . $file_extension;  // using sanitised file name as the directory name
            move_uploaded_file($imageFileTemp, $uploadImage);  // moving the image to the sanitised directory
    
            $query = "INSERT INTO carousel_promo(promoImage, promoTitle) VALUES (?, ?)";
            $stmt = $con->prepare($query);
            $stmt->bind_param("ss", $uploadImage, $promoTitle);
            $stmt->execute();
    
            if ($stmt->affected_rows > 0) {
                echo "Promo image added successfully <br/>";
                header("Location: apromo.php");
                die;
            }
            else {
                echo "Error adding image. <br/>";
                // temporary
                // echo $stmt->errno;
            }
        } else {
            echo "Invalid file type!";
            // temporary
            // echo $stmt->errno;
        }
    }
?>