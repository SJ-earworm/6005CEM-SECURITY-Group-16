<?php
    error_reporting(E_ALL | E_STRICT);
    ini_set('display_startup_errors', 'Off');   // syntax errors considered startup errors cos they run before the execution of the page render
    ini_set('display_errors', 'Off');
    ini_set('log_errors', 'On');
    ini_set('error_log', 'C:/Applications/XAMPP/apache/logs/SPF/SPF-error.log');

    require("Connectdb.php");
    include("session_handling.php");

    // if ($_SERVER['REQUEST_METHOD']=="POST") {
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
        if (isset($_GET['cart-id'])) {
            // retrieving $pdID from confirmation button
            $cartID = filter_input(INPUT_GET, 'cart-id', FILTER_SANITIZE_NUMBER_INT);

            try {
                $query = "DELETE FROM cart WHERE cartID = ?";
                $stmt = $con->prepare($query);
                $stmt->bind_param("i", $cartID);
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    header("Location: cart.php");
                }
                else {
                    $jsonmessage = "Could not delete cart item. Please try again later.";
                    error_log("Delete Cart Item Backend file | Error deleting cart item: " . $stmt->errno);
                    header("Location: cart.php?error=" .urlencode($jsonmessage));  // urlencode sanitises the url, characters will immediately be encoded
                    die;
                    // echo "Could not delete product";
                    // temporary
                    // $stmt->errno;
                    // die();
                }
                $stmt->close();

            } catch (mysqli_sql_exception $e) {
                $jsonmessage = "Could not delete cart item. Please try again later.";
                error_log("Delete Cart Item Backend file | Error deleting cart item: " . $e->getMessage());
                header("Location: cart.php?error=" .urlencode($jsonmessage));  // urlencode sanitises the url, characters will immediately be encoded
                die;
            }
        }
    // }
?>