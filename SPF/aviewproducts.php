<?php

    require("session_handling.php");
    include("Connectdb.php");

    // initialising $query
    // $query = "";

    // // if the user uses the search filter
    // if (isset($_POST['search-query'])) {
    //     // cleaning the input from whitespace & special characters
    //     $userSearch = $_POST['search-query'];
    //     $userSearch = trim($userSearch);
    //     $userSearch = addslashes($userSearch);  // not secure enough, only escapes a limited amount of characters

    //     // database query
    //     $query = "SELECT pdID, pdName, pdPrice, pdSize, pdStockCount, pdImage FROM product WHERE ";
    //     $query .= "pdName LIKE '%$userSearch%'";
    // }
    // else {
    //     // display all items if search filter is empty
    //     $query = "SELECT pdID, pdName, pdPrice, pdSize, pdStockCount, pdImage FROM product";
    // }

    // // retrieving query result
    // $result = mysqli_query($con, $query);

    // //error message if result from query not found
    // if (!$result) {
    //     die('SQL query error: ' . mysqli_error($con));
    // }

    // NEW SECURE CODE
    // initialising $query
    $query = "";

    // if the user uses the search filter
    if (isset($_POST['search-query'])) {
        // cleaning the input from whitespace & special characters
        $rawUserSearchTrim = trim($_POST['search-query']);
        $sanitisedUserSearch = filter_var($rawUserSearchTrim, FILTER_SANITIZE_STRING);
        $userSearch = "%$sanitisedUserSearch%";
        

        // database query
        $query = "SELECT pdID, pdName, pdPrice, pdSize, pdStockCount, pdImage FROM product WHERE ";
        $query .= "pdName LIKE ?";

        $stmt = $con->prepare($query);
        $stmt->bind_param("s", $userSearch);
    }
    else {
        // display all items if search filter is empty
        $query = "SELECT pdID, pdName, pdPrice, pdSize, pdStockCount, pdImage FROM product";
        
        $stmt = $con->prepare($query);
    }

    // if statement could not execute properly
    if (!$stmt->execute()) {
        error_log("Admin Products Page | Could not fetch results", $stmt->errno);
    }
    $result = $stmt->get_result();


    // closing database connection
    $stmt->close();

?>


<!DOCTYPE html>
<html lang="utf=8">
    <head>
        <title>Admin View Products</title>
        <link rel="stylesheet" href="astyle.css">
    </head>
    <body>

        <div class="sidebar">
            <img src ="images/logo.png" width="160" height="100">
                <img src="images/profile.png" class="profile">
                    <a href="admin.php">Dashboard</a>
                    <a href="apselect.php">Product</a>
                        <?php if ($_SESSION['role'] == 'admin') { ?>
						<a href="auser.php">Users</a>
						<?php } ?>
                    <a href="areport.php">Statistic</a>
                    <a href="logout.php">Logout</a>
        </div>


        <!-- search function -->
        <div class="search-container">
            <div class="search">
                <form action="#" method="post">
                    <input type="text" name="search-query" placeholder="Search product" autocomplete="off">
                    <input type="submit" name="search-btn">
                </form>
            </div>
        </div>


        <div class="content-area">
            <!-- table displaying all products -->
            <table>
                <!-- automatically generating the table rows along with the associated product ID -->
                <?php
                    // if (mysqli_num_rows($result) > 0) {

                    //     echo "<tr>";
                    //     echo "    <th></th>";
                    //     echo "    <th>Product Name</th>";
                    //     echo "    <th>Price</th>";
                    //     echo "    <th>Size</th>";
                    //     echo "    <th>Stock</th>";
                    //     echo "    <th></th>";
                    //     echo "</tr>";

                    //     while ($tbrow = mysqli_fetch_assoc($result)) {
                    //         $pdID = $tbrow['pdID'];
                    //         $pdImg = $tbrow['pdImage'];
                    //         $pdName = $tbrow['pdName'];
                    //         $pdPrice = $tbrow['pdPrice'];
                    //         $pdSize = $tbrow['pdSize'];
                    //         $pdStockCount = $tbrow['pdStockCount'];

                    //         echo "<tr>";
                    //         echo "    <td><img src='$pdImg' width='150px' height='150px'></td>";
                    //         echo "    <td><p>$pdName</p></td>";
                    //         echo "    <td><p>RM$pdPrice</p></td>";
                    //         echo "    <td><p>$pdSize</p></td>";
                    //         echo "    <td><p>$pdStockCount</p></td>";
                    //         echo "    <td>";
                    //         echo "        <a href='aeditproduct.php?id=$pdID'>";
                    //         echo "            <button class='delete'>Edit</button>";
                    //         echo "        </a>";
                    //         echo "        <button class='delete-btn delete' value='$pdID'>Delete</button>";
                    //         echo "    </td>";
                    //         echo "</tr>";
                    //     }
                    // }
                    // else {
                    //     echo "<p style='margin-top: 24%; font-size: 1.5rem;'>Couldn't find the product</p>";
                    // }

                    // NEW SECURE CODE
                    if ($result->num_rows > 0) {

                        echo "<tr>";
                        echo "    <th></th>";
                        echo "    <th>Product Name</th>";
                        echo "    <th>Price</th>";
                        echo "    <th>Size</th>";
                        echo "    <th>Stock</th>";
                        echo "    <th></th>";
                        echo "</tr>";

                        while ($tbrow = $result->fetch_assoc()) {
                            $pdID = $tbrow['pdID'];
                            $pdImg = $tbrow['pdImage'];
                            $pdName = $tbrow['pdName'];
                            $pdPrice = $tbrow['pdPrice'];
                            $pdSize = $tbrow['pdSize'];
                            $pdStockCount = $tbrow['pdStockCount'];

                            echo "<tr>";
                            echo "    <td><img src='" .htmlspecialchars($pdImg). "' width='150px' height='150px'></td>";
                            echo "    <td><p>" .htmlspecialchars_decode(htmlspecialchars($pdName, ENT_NOQUOTES, 'UTF-8'), ENT_QUOTES). "</p></td>";
                            echo "    <td><p>RM" .htmlspecialchars($pdPrice). "</p></td>";
                            echo "    <td><p>" .htmlspecialchars($pdSize). "</p></td>";
                            echo "    <td><p>" .htmlspecialchars($pdStockCount). "</p></td>";
                            echo "    <td>";
                            echo "        <a href='aeditproduct.php?id=" .(int)$pdID. "'>";
                            echo "            <button class='delete'>Edit</button>";
                            echo "        </a>";
                            echo "        <button class='delete-btn delete' value='" .(int)$pdID. "'>Delete</button>";
                            echo "    </td>";
                            echo "</tr>";
                        }
                    }
                    else {
                        echo "<p style='margin-top: 24%; font-size: 1.5rem;'>Couldn't find the product</p>";
                    }
                ?>


            </table>
        </div>


        <!-- pop-up alert to confirm if user wants to delete item -->
        <div id="dimOverlay" class="dimmed-overlay" style="display: none;"></div>

        <div id="confirmDel" class="confirm-del" style="display: none;">
            <p>Are you sure you want to delete this item?</p>
            <div class="confirm-del-btn">
                <form action="db_deleteproduct.php" method="post">
                    <input type="hidden" name="product-id">
                    <button type="submit">Yes</button>
                </form>
                <button id="dismissBtn">No</button>
            </div>
        </div>


        <!-- javascript -->
        <script>
            // 'querySelectorAll' for selecting all elements that match the 'delete-btn' class
            let delButtons = document.querySelectorAll(".delete-btn");
            let dismissBtn = document.getElementById("dismissBtn");
            const dimmedBg = document.getElementById("dimOverlay");
            const alertBox = document.getElementById("confirmDel");

            // if user clicks on "Yes", perform steps to send product ID into `db_deleteproduct.php`
            delButtons.forEach(function(delBtn) {
                delBtn.addEventListener("click", function() {
                    // retrieving the user ID from the button
                    let productID = delBtn.getAttribute("value");

                    // displaying the alertbox
                    if (alertBox.style.display === "none") {
                        dimmedBg.style.display = "block";
                        alertBox.style.display = "block";
                    }
                    else {
                        dimmedBg.style.display = "none";
                        alertBox.style.display = "none";
                    }

                    // locating the hidden input form field & passing productID into it
                    document.querySelector("input[name='product-id']").value = productID;
                });
            })



            // if user click son "No"
            dismissBtn.addEventListener("click", function() {
                // displaying the alert box
                if (alertBox.style.display === "none") {
                    dimmedBg.style.display = "block";
                    alertBox.style.display = "block";
                }
                else {
                    dimmedBg.style.display = "none";
                    alertBox.style.display = "none";
                }
            });
            
        </script>
    </body>
</html>