<?php
    // error handling setup
    error_reporting(E_ALL | E_STRICT);
    ini_set('display_startup_errors', 'Off');   // syntax errors considered startup errors cos they run before the execution of the page render
    ini_set('display_errors', 'Off');
    ini_set('log_errors', 'On');
    ini_set('error_log', 'C:/Applications/XAMPP/apache/logs/SPF/SPF-error.log');

    include("session_handling.php");
    include("Connectdb.php");

    // retrieving cartID from GET header
    $cartID = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    //pulling the data from the cart table in the database
    $query = "SELECT
                Cart.cartID,  
                Product.pdName,
                Product.pdPrice,
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
        echo "Could not fetch cart items. <br/>";
        error_log("Cart page | Could not fetch cart items: ", $stmt->errno);
        // temporary
        // echo $stmt->errno;
    }

?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="style.css">

        <style>
            body {
                font-family: Arial;
                font-size: 17px;
                padding: 8px;
            }

            * {
                box-sizing: border-box;
            }

            .row {
                display: -ms-flexbox; /* IE10 */
                display: flex;
                -ms-flex-wrap: wrap; /* IE10 */
                flex-wrap: wrap;
                margin: 0 -16px;
            }

            .col-25 {
                -ms-flex: 25%; /* IE10 */
                flex: 25%;
            }

            .col-50 {
                -ms-flex: 50%; /* IE10 */
                flex: 50%;
            }

            .col-75 {
                -ms-flex: 75%; /* IE10 */
                flex: 75%;
            }

            .col-25,
            .col-50,
            .col-75 {
                padding: 0 16px;
            }

            .container {
                background-color: #f2f2f2;
                padding: 5px 20px 15px 20px;
                border: 1px solid lightgrey;
                border-radius: 3px;
            }

            input[type=text] {
                width: 100%;
                margin-bottom: 20px;
                padding: 12px;
                border: 1px solid #ccc;
                border-radius: 3px;
            }

            label {
                margin-bottom: 10px;
                display: block;
            }

            .icon-container {
                margin-bottom: 20px;
                padding: 7px 0;
                font-size: 24px;
            }

            .btn {
                background-color: #04AA6D;
                color: white;
                padding: 12px;
                margin: 10px 0;
                border: none;
                width: 100%;
                border-radius: 3px;
                cursor: pointer;
                font-size: 17px;
            }

            .btn:hover {
                background-color: #45a049;
            }

            a {
                color: #2196F3;
            }

            hr {
                border: 1px solid lightgrey;
            }

            span.price {
                float: right;
                color: grey;
            }

            /* Responsive layout - when the screen is less than 800px wide, make the two columns stack on top of each other instead of next to each other (also change the direction - make the "cart" column go on top) */
            @media (max-width: 800px) {
                .row {
                    flex-direction: column-reverse;
                }
                .col-25 {
                    margin-bottom: 20px;
                }
            }
        </style>
    </head>
    <body>
        <header>
            <nav>
                <label class="logo"><img src ="images/logo.png" width="190" height="90"></label>
                <ul>
                    <li><a href="index.php"><img src ="images/home.png" width="130" height="40"></a></li>
                    <li><a href="products_main.php"><img src ="images/p.png" width="130" height="40"></a></li>
                    <li><a href="feedback.php"><img src ="images/fb.png" width="130" height="40"></a></li>
                    <li><a href="about_us.php"><img src ="images/au.png" width="130" height="40"></a></li>
                    <li><a href="cart.php"><img src ="images/cart.png" width="100" height="50"></a></li>
                    <li><a href="logout.php"><img src ="images/logout.png" width="100" height="50"></a></li>
                </ul>
            </nav>

        </header><br>
        <div class="content-margin">
        <h2>Checkout</h2><br>
        <div class="row">
        <div class="col-75">
            <div class="container">
            <form action="pay_success.php" method="post" id="paymentForm">
            
                <div class="row">
                <div class="col-50">
                    <h3>Billing Address</h3>
                    <label for="fname"><i class="fa fa-user"></i> Full Name</label>
                    <input type="text" id="fname" name="firstname" placeholder="John Doe">
                    <label for="email"><i class="fa fa-envelope"></i> Email</label>
                    <input type="text" id="email" name="email" placeholder="johnd@example.com">
                    <label for="adr"><i class="fa fa-address-card-o"></i> Address</label>
                    <input type="text" id="adr" name="address">
                    <label for="city"><i class="fa fa-institution"></i> City</label>
                    <input type="text" id="city" name="city">

                    <div class="row">
                    <div class="col-50">
                        <label for="state">State</label>
                        <input type="text" id="state" name="state">
                        <label for="datemin">Date of Payment:</label>
                        <input type="date" id="datemin" name="datePay" min="<?php echo date('Y-m-d'); ?>" value="<?php echo date('Y-m-d'); ?>"><br><br>
                    </div>
                    <div class="col-50">
                        <label for="zip">Zip</label>
                        <input type="text" id="zip" name="zip" placeholder="10001">                
                    </div>
                    </div>
                </div>

                <div class="col-50">
                    <h3>Payment</h3>
                    <label for="fname">Accepted Cards</label>
                    <div class="icon-container">
                    <i class="fa fa-cc-visa" style="color:navy;"></i>
                    <i class="fa fa-cc-amex" style="color:blue;"></i>
                    <i class="fa fa-cc-mastercard" style="color:red;"></i>
                    <i class="fa fa-cc-discover" style="color:orange;"></i>
                    </div>
                    <label for="cname">Name on Card</label>
                    <input type="text" id="cname" name="cardname" placeholder="John Doe">
                    <label for="ccnum">Credit card number</label>
                    <input type="text" id="ccnum" name="cardnumber" placeholder="1111-2222-3333-4444">
                    <label for="expmonth">Exp Month</label>
                    <input type="text" id="expmonth" name="expmonth" placeholder="November">
                    <div class="row">
                    <div class="col-50">
                        <label for="expyear">Exp Year</label>
                        <input type="text" id="expyear" name="expyear" maxlength="4" pattern="\d{4}" placeholder="2026">   <!-- maxlength limits input to 4 CHARACTERS, pattern="\d{4}" restricts to 4 digits only -->
                    </div>
                    <div class="col-50">
                        <label for="cvv">CVV</label>
                        <input type="text" id="cvv" name="cvv" placeholder="123">
                    </div>
                    </div>
                </div>
                
                </div>
                <label>
                <input type="checkbox" checked="checked" name="sameadr"> Shipping address same as billing
                </label>
                <input type="submit" value="Pay" class="btn">
            </form>
            <!-- <button id="testBtn">Test</button> -->
            </div>
        </div>

        <!-- CART ITEMS PANE -->
        <div class="col-25">
            <div class="container">
                <?php
                    if ($result->num_rows > 0) {
                        // initialising total variable
                        $total = 0;

                        // initialising array to hold cartIDs
                        $cartIDs = [];

                        while ($row = $result->fetch_assoc()) {
                            // passing the current iteration's cartID into the array
                            $cartIDs[] = abs(htmlspecialchars($row['cartID']));
                            // pdName sanitisation
                            $pdName = htmlspecialchars($row['pdName'], ENT_NOQUOTES, 'UTF-8');
                            $catchHTMLentities = array('&#39;', '&#34;', '&amp;');  // catching ' and " HTML entities from pdName
                            $replacementEntities = array("'", '"', '&');  // setting the corresponding quotes to replace the HTML entities with
                            $bringBackQuotes = str_replace($catchHTMLentities, $replacementEntities, $pdName);  // replacing the quotes
                            $sanitisedPdName = preg_replace('/\\\\/','', $bringBackQuotes);
                            // subtotal per product
                            $subtotal = (float)$row['pdPrice'] * (int)$row['quantity'];
                            $total += $subtotal;

                            echo "<span style='display: flex; flex-direction: row; justify-content: space-between; width: 100%'>";
                            echo "  <p style='width: 140px'><a href='#'>" .$sanitisedPdName. "</a></p>";
                            echo "  <span class='price'>RM" .number_format((float)$subtotal, 2). "</span>";
                            echo "</span>";
                        }
                    }
                ?>
                <p style="margin-top: 15px">Total <span class="price" style="color:black"><b>RM<?php echo number_format((float)$total, 2); ?></b></span></p>
            </div>
        </div>
        </div>
        </div>

        <script>
            const cartIDs = <?php echo json_encode($cartIDs) ?>;  // retrieving $cartIDs[] array
            // const URLfriendlyCartIDs = encodeURIComponent(JSON.stringify(cartIDs));  // converting cartIDs to a URL-friendly format

            document.getElementById('paymentForm').addEventListener('submit', function(e) {
                e.preventDefault();  // preventing default submit first

                // formatting cartIDs array values for the URL (URL friendly format)
                const cartIDsJoin = cartIDs.join(',');

                // joining IDs to the action attribute (GET URL)
                this.action = `pay_success.php?ids=${encodeURIComponent(cartIDsJoin)}`;

                // debugging
                // const testURL = `pay_success.php?ids=${encodeURIComponent(cartIDsJoin)}`;
                // console.log(testURL);

                // resuming submit sequence
                this.submit();

                // redirecting to payment.php with cartIDs passed in
                // window.location.href = `payment.php?ids=${URLfriendlyCartIDs}`;
            });
        </script>
    </body>
</html>