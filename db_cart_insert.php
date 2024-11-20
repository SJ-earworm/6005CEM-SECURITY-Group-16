<?php
    // error handling setup
    error_reporting(E_ALL | E_STRICT);
    ini_set('display_startup_errors', 'Off');   // syntax errors considered startup errors cos they run before the execution of the page render
    ini_set('display_errors', 'Off');
    ini_set('log_errors', 'On');
    ini_set('error_log', 'C:/Applications/XAMPP/apache/logs/SPF/SPF-error.log');
    
    require("Connectdb.php");

    // NEW ADDITION
    header('Content-Type: application/json');
    $response = []; // response variable to send back to frontend

    if ($_SERVER['REQUEST_METHOD']=="POST") {
        //retrieving the JSON stringified data from the request body
        $JSONData = file_get_contents('php://input'); //'php://input' reads the raw JSON data from the request body

        //decoding the JSON data into a PHP object
        $pdSelectData = json_decode($JSONData);

        if ($pdSelectData !== null) {
            //extracting the values held inside the object
            $userID = $pdSelectData->userID;
            $pdID = $pdSelectData->productID;
            $pdQuantity = $pdSelectData->quantity;

            //inserting the user's product selection detalis into the cart table in the database
            // $query = "INSERT INTO cart(userID, pdID, quantity) VALUES ('$userID', '$pdID', '$pdQuantity')";
            // $result = mysqli_query($con, $query);

            // NEW SECURE CODE
            try {
                $query = "INSERT INTO cart(userID, pdID, quantity) VALUES (?, ?, ?)";
                $stmt = $con->prepare($query);
                $stmt->bind_param("iii", $userID, $pdID, $pdQuantity);
                
                if (!$stmt->execute()) {
                    $response = [
                        'status' => 'fail',
                        'message' => 'Error inserting item to cart. Please try again later.'
                    ];
                    // send response back to frontend javascript reponse
                    echo json_encode($response);
                    error_log("DB CART INSERT PAGE | Error inserting item to cart: " . $e->getMessage());
                    exit;
                } else {
                    $response = [
                        'status' => 'success'
                        // 'message' => 'Error inserting item to cart. Please try again later.'
                    ];
                    // send response back to frontend javascript reponse
                    echo json_encode($response);
                    exit;
                }

                $stmt->close();

            } catch (mysqli_sql_exception $e) {
                $response = [
                    'status' => 'fail',
                    'message' => 'Error inserting item to cart. Please try again later.'
                ];
                // send response back to frontend javascript reponse
                echo json_encode($response);
                error_log("DB CART INSERT PAGE | Error inserting item to cart: " . $e->getMessage());
                exit;
            }

            // OLD CODE
            // if (!$result) {
            //     echo "Error: " . mysqli_error($con);
            // }
        } else {
            $response['status'] = 'fail';
            $response['message'] = "Could not add to cart. Please try again later.";
            // send response back to frontend javascript response
            echo json_encode($response);
            error_log("DB CART INSERT PAGE | pdSelectData variable is null");
            exit;
        }

    }
?>