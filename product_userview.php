<!-- PHP SQL query -->
<?php

    include("Connectdb.php");

    //retrieving the product ID from the url
    // $pdID = $_GET['id'];

    // //retrieving the necessary data from the database
    // $query = "SELECT pdID, pdName, pdPrice, pdSize, pdStockCount, pdDescription, pdImage FROM product WHERE pdID = $pdID";
    // $result = mysqli_query($con, $query);

    // //error message if result from query not found
    // if (!$result) {
    //     error_log('SQL querry error: ' . mysqli_error($con));
    //     die('An error occured. Please try again later.');
    // }


    // //retrieving the row of data
    // $tbrow = mysqli_fetch_assoc($result);

    // //error message if product not found in database
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


    // $con->close();

    // NEW CODE
    $pdID = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    $extraSanitisedPdId = abs($pdID);

    //retrieving the necessary data from the database
    $query = "SELECT pdName, pdPrice, pdSize, pdStockCount, pdDescription, pdImage FROM product WHERE pdID = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $extraSanitisedPdId);
    $stmt->execute();
    $stmt->bind_result($pdName, $pdPrice, $pdSize, $pdStockCount, $pdDescription, $pdImg);
    // $stmt->fetch();

    if (!$stmt->fetch()) {
        echo "Product not found";
        error_log('Product page, product not found.', $stmt->errno);
        // temporary
        // echo $stmt->errno;
    } else if ($stmt->errno) {
        echo "Could not retrieve product.";
        error_log('Product page, could not retrieve product: ' . $stmt->errno);
        // temporary
        // echo $stmt->errno;
    }


    // formatting pdName & pdDescription for display
    $pdName = htmlspecialchars($pdName, ENT_NOQUOTES, 'UTF-8');
    $catchHTMLentities = array('&#39;', '&#34;', '&amp;');  // catching ' and " HTML entities from pdName
    $replacementEntities = array("'", '"', '&');  // setting the corresponding quotes to replace the HTML entities with
    $bringBackQuotes = str_replace($catchHTMLentities, $replacementEntities, $pdName);  // replacing the quotes
    $sanitisedPdName = preg_replace('/\\\\/','', $bringBackQuotes);

    $pdDescription = htmlspecialchars($pdDescription, ENT_NOQUOTES, 'UTF-8');
    $catchHTMLentities = array('&#39;', '&#34;', '&amp;');  // catching ' and " HTML entities from pdName
    $replacementEntities = array("'", '"', '&');  // setting the corresponding quotes to replace the HTML entities with
    $bringBackQuotes = str_replace($catchHTMLentities, $replacementEntities, $pdDescription);  // replacing the quotes
    $sanitisedPdDescription = preg_replace('/\\\\/','', $bringBackQuotes);
    //error message if product not found in database
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


    $stmt->close();
?>


<!-- beginning of HTML template -->
<!DOCTYPE html>
<html lang="utf=8">
    <head>
        <!-- generating the html page title dynamically -->
        <title><?php echo htmlspecialchars_decode(htmlspecialchars($pdName, ENT_NOQUOTES, 'UTF-8'), ENT_QUOTES) ?></title>

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

        <div class="content-margin">
        <!--product description here-->
            <div class="pd-pg-wrapper">
                <img src="<?php echo htmlspecialchars($pdImg) ?>" alt="Product Image" width="450rem" height="450rem;">
                <div class="pd-major-deets">
                    <h1 style="font-size: 3rem; flex-wrap: wrap;"><?php echo $sanitisedPdName ?></h1>
                    <h2 style="color: rgba(255, 0, 0, 0.858); margin-bottom: 15px"><?php echo $pdPrice ?></h2>
                    <h3>Size: <?php echo htmlspecialchars($pdSize) ?></h3>
                    <p>Stock count: <?php echo (int)$pdStockCount ?></p><br/>
                    <h2>Description</h2>
                    <p><?php echo $sanitisedPdDescription ?></p><br/><br/><br/>

                    <div>
                        <label style="font-weight: bold;">Quantity</label>
                        <select id="quantity-select" name="quantity-select">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                            <option value="13">13</option>
                            <option value="14">14</option>
                            <option value="15">15</option>
                        </select>
                    </div>
                    <button id="addToCartBtn" style="margin-top: 20px;">Add to cart</button>
                </div>
            </div>
            <!-- printing a success message when item(s) successfully added to cart -->
            <div id="successMessage" class="added-to-cart"></div>
        </div>


        <!-- javascript -->
        <script>
            //obtaining the ID of the current product + quantity that user wants to buy
            let userID = <?php echo $userID ?>;
            let productID = <?php echo $extraSanitisedPdId ?>;
            let productQuant = document.getElementById("quantity-select");

            // JavaScript object to hold all 3 data to pass over into `db_cart_insert.php`
            let pdSelectData = {
                userID: userID,
                productID: productID,
                quantity: null //setting the variable to null first to ensure an empty field before user clicks add-to-cart button
            };

            //setting an indicator for a successful request
            let requestSuccess = false;
            
            const cartButton = document.getElementById("addToCartBtn");
            cartButton.addEventListener("click", addToCart);

            function addToCart() {
                //updating the quantity selected by user after the button is clicked
                pdSelectData.quantity = productQuant.value;

                //sending HTTP POST request to the cart PHP script (adding to cart)
                fetch('db_cart_insert.php', {
                    method: 'POST',
                    body: JSON.stringify(pdSelectData),
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    //checking if response was successful
                    if (response.status === 200) {
                        //retrieving the text that `db_cart_insert.php` has returned
                        requestSuccess = true;
                        console.log("Response received from db_cart_insert.php");
                    }
                })
                .catch(error => {
                    console.error("Error: ", error);
                })
                .finally(() => {
                    if (requestSuccess) {
                        const successsMsg = document.getElementById("successMessage");
                        successsMsg.textContent = "Items added to cart";
                    }
                });
            }
        </script>
    </body>
</html>