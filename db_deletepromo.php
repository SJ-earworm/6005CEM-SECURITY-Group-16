<?php
    require("Connectdb.php");

    if ($_SERVER['REQUEST_METHOD']=="POST") {
        if (isset($_POST['product-id'])) {
            // // retrieving $pdID from confirmation button
            // $pdId = $_POST['product-id'];

            // $query = "DELETE FROM carousel_promo WHERE promoImageID = $pdId";
            // $result = mysqli_query($con, $query);

            // if ($result) {
            //     header("Location: apromo.php");
            // }
            // else {
            //     die("Delete product SQL error: " . mysqli_error($con));
            // }

            // NEW CODE
            // retrieving $pdID from confirmation button
            $pdId = filter_input(INPUT_POST, 'product-id', FILTER_SANITIZE_NUMBER_INT);
            $extraSanitisedPdId = abs($pdId);

            $query = "DELETE FROM carousel_promo WHERE promoImageID = ?";
            $stmt = $con->prepare($query);
            $stmt->bind_param("i", $extraSanitisedPdId);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                header("Location: apromo.php");
            }
            else {
                echo "Could not delete promo.";
                // temporary
                // echo $stmt->errno;
            }
        }
    }
?>