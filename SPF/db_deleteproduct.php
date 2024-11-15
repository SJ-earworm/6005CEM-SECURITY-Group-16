<?php
    require("Connectdb.php");

    if ($_SERVER['REQUEST_METHOD']=="POST") {
        // if (isset($_POST['product-id'])) {
        //     // retrieving $pdID from confirmation button
        //     $pdId = $_POST['product-id'];

        //     $query = "DELETE FROM product WHERE pdID = $pdId";
        //     $result = mysqli_query($con, $query);

        //     if ($result) {
        //         header("Location: aviewproducts.php");
        //     }
        //     else {
        //         die("Delete product SQL error: " . mysqli_error($con));
        //     }
        // }

        // NEW SECURE CODE
        if (isset($_POST['product-id'])) {
            // retrieving $pdID from confirmation button
            $pdId = $_POST['product-id'];
            $extraSanitisedPdId = filter_var($pdId, FILTER_SANITIZE_NUMBER_INT);

            $query = "DELETE FROM product WHERE pdID = ?";
            $stmt = $con->prepare($query);
            $stmt->bind_param("i", $extraSanitisedPdId);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                header("Location: aviewproducts.php");
            }
            else {
                echo "Could not delete product";
                // temporary
                // $stmt->errno;
                die();
            }
        }
    }
?>