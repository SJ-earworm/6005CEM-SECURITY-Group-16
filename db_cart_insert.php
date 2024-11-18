<?php
    require("Connectdb.php");

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
            $query = "INSERT INTO cart(userID, pdID, quantity) VALUES ('$userID', '$pdID', '$pdQuantity')";
            $result = mysqli_query($con, $query);

            if (!$result) {
                echo "Error: " . mysqli_error($con);
            }
        }

    }
?>