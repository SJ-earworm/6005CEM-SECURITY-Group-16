<?php
    require("Connectdb.php");

    // initialsing the query variable
    $query = "";

    // if user enters a search query
    if (!empty($userSearch)) {
        //retrieving relevant data from the database
        $query = "SELECT pdID, pdName, pdPrice, pdImage 
                FROM product 
                WHERE pdID IN (SELECT pdID FROM pd_category_relationship WHERE catID = ?) AND ";
        $query .= "pdName LIKE ?";

        $stmt = $con->prepare($query);
        $stmt->bind_param("is", $catID, $userSearch);
    }
    else {
        // if search field is empty
        //retrieving all data from the database
        $query = "SELECT pdID, pdName, pdPrice, pdImage FROM product WHERE pdID IN (SELECT pdID FROM pd_category_relationship WHERE catID = ?)";
        $stmt = $con->prepare($query);
        $stmt->bind_param("i", $catID);
    }

    // retrieving result from database\
    $stmt->execute();
    $result = $stmt->get_result();

    //error message if result from query not found
    if ($stmt->errno) {
        echo "Could not fetch products <br/>";
        error_log("Db_productbox module | Could not fetch products", $stmt->errno);
        // temporary
        // echo $stmt->errno;
    }

    //looping through the rows of data to dynamically generate the product box displaying each product saved in the database
    if ($result->num_rows > 0){
        while ($tbrow = $result->fetch_assoc()) {
            $pdID = $tbrow['pdID'];
            $pdImg = $tbrow['pdImage'];
            $pdName = $tbrow['pdName'];
            $pdPrice = $tbrow['pdPrice'];

            //creating the product box and displaying the content
            echo "<a href='product.php?id=" .(int)$pdID. "'>";
            echo "    <div class='product-box'>";
            echo "        <img src='" .htmlspecialchars($pdImg). "' alt='product image' width='300' height='300'>";
            echo "        <div class='product-name'><h3>" .htmlspecialchars_decode(htmlspecialchars($pdName, ENT_NOQUOTES, 'UTF-8'), ENT_QUOTES). "</h3></div>";
            echo "        <div class='product-price'>RM" .htmlspecialchars($pdPrice). "</div>";
            echo "    </div>";
            echo "</a>";
        }
    } else {
        echo "<p style='margin-top: 10%; font-size: 1.5rem;'>No products to fetch</p>";
    }


    $stmt->close();
?>