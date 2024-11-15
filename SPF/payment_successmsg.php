<?php

    include("session_handling.php");
    include("Connectdb.php");

    // debugging
    // echo "entered payment_successmsg.php <br/>";

    // retrieving cartID from GET header
    if(isset($_GET['ids'])) {
        // debugging
        // echo "GET IDs set. <br/>";

        // separating the URL IDs from their joined state
        $cartIDs = explode(',', $_GET['ids']);
        $cartID = $cartIDs[0];  // for pulling of user details for payment success message, we only need 1 cartID from the same transaction to extract the one-for-all details

        // debugging
        // echo "Raw ids: " . $_GET['ids'] . "<br/>";
        // echo "cartID from [0]: " . $cartID;
    }
    // $cartID = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    //pulling the data from the cart table in the database
    $query = "SELECT billName, email, address, city, state, zip, datePay FROM payment WHERE cartID = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $cartID);
    $stmt->execute();
    $stmt->bind_result($billName, $email, $address, $city, $state, $zip, $datePay);
    // $stmt->fetch();

    //error message if result from query not found
    if (!$stmt->fetch()) {
        echo "Could not fetch payment success message details. <br/>";
        error_log("Payment Success Message page | Could not fetch success message details: ", $stmt->errno);
        // temporary
        // echo $stmt->errno;
    }

    // close stmt
    $stmt->close();

?>
<!DOCTYPE html>
<html lang="utf=8">
    <head>
        <title>Santa's Plushie Factory</title>

        <!--css stylesheet-->
        <link rel="stylesheet" type="text/css" href="style.css">

        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        <header>
            <nav>
                <a href="index.php">
                    <div class="logo"><img src ="images/logo.png" width="190" height="90"></div>
                </a>
                
                <ul>
                    <li><a href="index.php"><img src ="images/home.png" width="130" height="40"></a></li>
                    <li><a href="products_main.php"><img src ="images/p.png" width="130" height="40"></a></li>
                    <li><a href="feedback.php"><img src ="images/fb.png" width="130" height="40"></a></li>
                    <li><a href="about_us.php"><img src ="images/au.png" width="130" height="40"></a></li>

                    <li><a href="cart.php"><img src="images/cart.png" width="40" height="40"></a></li>
                    <li><a href="logout.php"><img src="images/logout.png" width="100" height="50"></a></li>
                </ul>
            </nav>
        </header>


            <!-- Display confirmation message -->
            <div style="height: 300px; width: 500px; margin: 150px auto; display: flex; flex-direction: column; justify-content: space-between; font-size: 18px">
                <h2 style="font-size: 30px; margin-bottom: 10px">Payment Successful</h2>
                <p><b>Bill Name:</b> <?php echo htmlspecialchars_decode(htmlspecialchars($billName, ENT_NOQUOTES, 'UTF-8'), ENT_QUOTES) ?></p>
                <p><b>Email:</b> <?php echo htmlspecialchars($email) ?></p>
                <p><b>Address:</b> <?php echo htmlspecialchars_decode(htmlspecialchars($address, ENT_NOQUOTES, 'UTF-8'), ENT_QUOTES) ?></p>
                <p><b>City:</b> <?php echo htmlspecialchars_decode(htmlspecialchars($city, ENT_NOQUOTES, 'UTF-8'), ENT_QUOTES) ?></p>
                <p><b>State:</b> <?php echo htmlspecialchars_decode(htmlspecialchars($state, ENT_NOQUOTES, 'UTF-8'), ENT_QUOTES) ?></p>
                <p><b>Zip:</b> <?php echo htmlspecialchars($zip) ?></p>
                <p><b>Date of Payment:</b> <?php echo htmlspecialchars($datePay)?></p>
            </div>


            <?php
                // DELETING CART FROM DB ONCE TRANSACTION DONE
                foreach ($cartIDs as $crtID) {
                    $query = "DELETE FROM Cart WHERE cartID = ?";
                    $stmt = $con->prepare($query);
                    $stmt->bind_param("i", $crtID);
                    $stmt->execute();

                    if ($stmt->affected_rows <= 0) {
                        echo "Could not delete cart items.";
                        error_log("Payment Success Msg page | Could not delete cart items.", $stmt->errno);
                        // temporary
                        // echo $stmt->errno;
                        die();
                    }

                    $stmt->close();
                }
            ?>


        <!--javascript-->
        <script>
        </script>
    </body>
</html>