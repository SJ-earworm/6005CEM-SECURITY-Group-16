<?php
    // error handling setup
    error_reporting(E_ALL | E_STRICT);
    ini_set('display_startup_errors', 'Off');   // syntax errors considered startup errors cos they run before the execution of the page render
    ini_set('display_errors', 'Off');
    ini_set('log_errors', 'On');
    ini_set('error_log', 'C:/Applications/XAMPP/apache/logs/SPF/SPF-error.log');

    include("session_handling.php");
    include("Connectdb.php");
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="style.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>

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
        </nav><br><br>

        <center>
            <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // // Retrieve form data
                    // $billName = $_POST["billName"];
                    // $email = $_POST["email"];
                    // $address = $_POST["address"];
                    // $city = $_POST["city"];
                    // $state = $_POST["state"];
                    // $zip = $_POST["zip"];
                    // $datePay = $_POST["datePay"];
                    // $cardName = $_POST["cardName"];
                    // $cardNo = $_POST["cardNo"];
                    // $expMonth = $_POST["expMonth"];
                    // $expYear = $_POST["expYear"];


                    // // Display confirmation message
                    // echo "<h2>Payment Successful</h2>";
                    // echo "<p>Bill Name: $billName</p>";
                    // echo "<p>Email: $email</p>";
                    // echo "<p>Address: $address</p>";
                    // echo "<p>City: $city</p>";
                    // echo "<p>State: $state</p>";
                    // echo "<p>Zip: $zip</p>";
                    // echo "<p>Date of Payment: $datePay</p>";
                    

                    // $servername = "localhost";
                    // $dbUsername = "root";
                    // $dbPassword = "";
                    // $database = "con_db";

                    // // Create connection
                    // $conn = new mysqli($servername, $dbUsername, $dbPassword, $database);

                    // // Check connection
                    // if ($conn->connect_error) {
                    //     die("Connection failed: " . $conn->connect_error);
                    // }

                    // // Prepare the SQL statement
                    // $sql = "INSERT INTO payment (billName, email, address, city, state, zip, datePay) VALUES ('$billName', '$email', '$address', '$city', '$state', '$zip', '$datePay')";

                    // // Execute the query
                    // if ($conn->query($sql) === TRUE) {
                    //     echo "Payment submitted successfully!";
                    // } else {
                    //     echo "Error: " . $sql . "<br>" . $conn->error;
                    // }

                    // // Close the database connection
                    // $conn->close();

                    // NEW CODE
                    // Retrieving cartIDs from GET header
                    if (isset($_GET['ids'])) {
                        // separating the URL IDs from their joined state
                        $cartIDs = explode(',', $_GET['ids']);

                        // debugging
                        // echo "Raw ids: " . $_GET['ids'];
                        // exit;
                    }
                    // $cartID = filter_input(INPUT_GET, 'ids', FILTER_SANITIZE_NUMBER_INT);

                    // Retrieve form data
                    $billName = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_SPECIAL_CHARS);
                    $email = $_POST["email"];
                    $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
                    $city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING);
                    $state = filter_input(INPUT_POST, 'state', FILTER_SANITIZE_STRING);
                    $zip = abs(filter_input(INPUT_POST, 'zip', FILTER_SANITIZE_NUMBER_INT));  // filtering user input for special characters + casting to positive integers one shot
                    $datePay = filter_input(INPUT_POST, 'datePay', FILTER_SANITIZE_STRING);
                    // $cardName = filter_input(INPUT_POST, 'cardName', FILTER_SANITIZE_STRING);
                    // $cardNo = $_POST["cardNo"];
                    // $sanitisedCardNo = preg_replace('/[^0-9 ]/', '', $cardNo);
                    // $expMonth = $_POST["expMonth"];
                    // $sanitisedExpMonth = preg_replace('/[^A-Za-z]/', '', $expMonth);
                    $expYear = $_POST["expyear"];
                    $sanitisedExpYear = preg_replace('/[^0-9]/', '', $expYear);

                    // VALIDATING IF EMAIL IS LEGIT EMAIL INPUT
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        echo "Invalid email! Please re-enter details";
                        die();
                    }

                    // VALIDATING IF DATE FIELD CARRIES ONLY VERIFIED DATE
                    // note: DateTime::createFromFormat attempts to create a DateTime object from the $date input.
                    // if successful creation, means the input format is correct. then, if there are no errors from the
                    // process, then the warning count will return 0. so we need that '0' to move on.
                    if (!DateTime:: createFromFormat('Y-m-d', $datePay) && DateTime::getLastErrors()['warning_count'] > 0) {
                        echo "Invalid date entered! <br/>";
                        // temporary
                        // var_dump(DateTime::getLastErrors());
                        die();
                    }

                    // VALIDATING YEAR FIELD TO ONLY ALLOW 4 DIGITS TO PASS
                    if (!preg_match('/^\d{4}/', $sanitisedExpYear)) {
                        echo "Year field only accepts 4 digits!";
                        die();
                    }


                    // initialising $cartID variable for appending to the next header URL
                    $cartID = 0;

                    foreach ($cartIDs as $crtID) {
                        // sanitising cartID
                        $cartID = filter_var($crtID, FILTER_SANITIZE_NUMBER_INT);

                        // Prepare the SQL statement
                        $sql = "INSERT INTO payment (billName, email, address, city, state, zip, datePay, cartID) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                        $stmt = $con->prepare($sql);
                        $stmt->bind_param("sssssisi", $billName, $email, $address, $city, $state, $zip, $datePay, $cartID);
                        $stmt->execute();

                        // Execute the query
                        if ($stmt->errno) {
                            echo "Error inserting payment details.";
                            error_log("Payment page | Error inserting payment details.");
                            break;
                            die();
                        } else {
                            // Close the database connection
                            $stmt->close();
                        }
                    }
                    
                    // redirecting to payment success page
                    header("Location: payment_successmsg.php?ids=" .$_GET['ids']);
                    exit;
                    
                }
            ?>
        </center>

    </body>
</html>