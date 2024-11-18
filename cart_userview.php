<?php

    include("Connectdb.php");

    //pulling the data from the cart table in the database
    // $query = "SELECT
    //             Cart.cartID, 
    //             Product.pdImage, 
    //             Product.pdName, 
    //             Cart.quantity 
    //           FROM cart 
    //           JOIN product ON cart.pdID = product.pdID 
    //           WHERE userID = '$userID'";
    // $result = mysqli_query($con, $query);

    // //error message if result from query not found
    // if (!$result) {
    //     die('SQL query error: ' . mysqli_error($con));
    // }


    // // closing database connection
    // $con->close();

    // NEW CODE
    $query = "SELECT
                Cart.cartID, 
                Product.pdImage, 
                Product.pdName, 
                Cart.quantity 
              FROM cart 
              JOIN product ON cart.pdID = product.pdID 
              WHERE userID = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();

    //error message if result from query not found
    if ($stmt->errno) {
        echo "Could not retrieve cart items. <br/>";
        error_log("Could not retrieve cart items.", $stmt->error);
        // temporary
        // echo $stmt->errno;
    }


    // closing database connection
    $stmt->close();
?>


<!DOCTYPE html>
<html lang="utf=8">
    <head>
        <title>Shopping Cart</title>

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
                    <li><a href="feedback.html"><img src ="images/fb.png" width="130" height="40"></a></li>
                    <li><a href="au.html"><img src ="images/au.png" width="130" height="40"></a></li>

                    <li><a href="logout.php"><img src="images/logout.png" width="100" height="50"></a></li>

                </ul>
            </nav>
        </header>

        <div class="content-margin">
            <h1 style="font-size: 3rem; margin-bottom: 25px;">Shopping Cart</h1>
            
            <!-- section to display items in user's cart -->
            <?php
                if ($result->num_rows > 0) {
                    // intialising array for holding cartIDs (cos multiple cartID can be assigned to a user. that's how the system registers which cart item belongs to whose cart)
                    $cartIDs = [];

                    while ($tbrow = $result->fetch_assoc()) {
                        $cartIDs[] = $tbrow['cartID'];
                        $pdImg = $tbrow['pdImage'];
                        // $pdName = $tbrow['pdName'];
                        $pdName = htmlspecialchars($tbrow['pdName'], ENT_NOQUOTES, 'UTF-8');
                        $catchHTMLentities = array('&#39;', '&#34;', '&amp;');  // catching ' and " HTML entities from pdName
                        $replacementEntities = array("'", '"', '&');  // setting the corresponding quotes to replace the HTML entities with
                        $bringBackQuotes = str_replace($catchHTMLentities, $replacementEntities, $pdName);  // replacing the quotes
                        $sanitisedPdName = preg_replace('/\\\\/','', $bringBackQuotes);
                        $quantity = $tbrow['quantity'];
                        
                        // temp
                        // echo $cartID;

                        echo "<div class='cart-box'>";
                        echo "    <div class='cart-box-layout'>";
                        echo "        <table>";
                        echo "            <tr>";
                        echo "                <td><img src='" .htmlspecialchars($pdImg). "'></td>";
                        echo "                <td><p>" .$sanitisedPdName. "</p></td>";
                        echo "                <td><p>x " .(int)$quantity. "</p></td>";
                        echo "                <td class='row'>";
                        echo "                    <button>Edit</button>";
                        echo "                    <button>Delete</button>";
                        echo "                </td>";
                        echo "            </tr>";
                        echo "        </table>";
                        echo "    </div>";
                        echo "</div>";
                    }

                    $jsoncartIDs = json_encode($cartIDs);  // encoding cartIDs array into json to be passed over the URL properly
                                                                       // urlencode to convert $cartIDs to a URL friendly format
                    echo "<div style='width: 100%; margin-top: 20px; display: flex; flex-direction: row; justify-content: center'>";
                    echo "  <button id='checkoutBtn' style='width: 240px'>Check Out</button>";
                    echo "</div>";
                }
                else {
                    echo "<p style='margin-top: 10px; font-size: 1.5rem;'>Looks like your cart is empty...head over to the product section to get your plushie!</p>";
                }
            ?>

        </div>

        <!--javascript-->
        <script>
            const cartIDs = <?php echo json_encode($cartIDs) ?>;  // retrieving $cartIDs[] array
            const URLfriendlyCartIDs = encodeURIComponent(JSON.stringify(cartIDs));  // converting cartIDs to a URL-friendly format

            document.getElementById('checkoutBtn').addEventListener('click', function() {
                // redirecting to payment.php with cartIDs passed in
                window.location.href = `payment.php?ids=${URLfriendlyCartIDs}`;
            });
        </script>
    </body>
</html>