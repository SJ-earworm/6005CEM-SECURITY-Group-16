<?php

    require("session_handling.php");
    include("Connectdb.php");

    $query = "SELECT promoImageID, promoImage, promoTitle FROM carousel_promo";
    $result = mysqli_query($con, $query);

    if (!$result) {
        die("Error fetching carousel images: " . mysqli_error($con));
    }

    // closing database connection
    $con->close();

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


        <div class="content-area">
            <!-- table displaying all products -->
            <table>
                <!-- automatically generating the table rows along with the associated product ID -->
                <?php
                    if (mysqli_num_rows($result) > 0) {

                        echo "<tr>";
                        echo "    <th>Image</th>";
                        echo "    <th>Promo Title</th>";
                        echo "    <th></th>";
                        echo "</tr>";

                        while ($tbrow = mysqli_fetch_assoc($result)) {
                            $promoImgID = $tbrow['promoImageID'];
                            $promoImg = $tbrow['promoImage'];
                            $promoTitle = $tbrow['promoTitle'];

                            echo "<tr>";
                            echo "    <td><img src='$promoImg' width='300px' height='120px'></td>";
                            echo "    <td><p style='white-space: nowrap;'>$promoTitle</p></td>";
                            echo "    <td>";
                            echo "        <button class='delete-btn delete' value='$promoImgID'>Delete</button>";
                            echo "    </td>";
                            echo "</tr>";
                        }

                        echo "<button id='freshPromo' class='fresh-promo-upload-btn'>Upload New</button>";
                    }
                    else {
                        echo "<p style='margin-top: 24%; font-size: 1.5rem;'>No promo material yet</p>";
                        echo "<button id='freshPromo' class='fresh-promo-upload-btn'>Upload New</button>";
                    }
                ?>


            </table>
        </div>


        <!-- grey background overlay when the pop-ups are displayed -->
        <div id="dimOverlay" class="dimmed-overlay" style="display: none;"></div>

        <!-- pop-up panel to allow user to upload new promo items -->
        <div id="upPopUp" class="upload-pop-up" style="display: none;">
            <div class="dismissUpload upload-pop-up-dismiss">X</div>
            <p>Upload item</p>
            <form action="db_uploadpromo.php" method="post" enctype="multipart/form-data">
                <input type="file" name="promo-image" required>
                <input type="text" name="promo-title" placeholder="Title" autocomplete="off" required>
                <button type="submit">Upload</button>
            </form>
        </div>




        <!-- pop-up alert to confirm if user wants to delete item -->
        <div id="confirmDel" class="confirm-del" style="display: none;">
            <p>Are you sure you want to delete this item?</p>
            <div class="confirm-del-btn">
                <form action="db_deletepromo.php" method="post">
                    <input type="hidden" name="product-id">
                    <button type="submit">Yes</button>
                </form>
                <button id="dismissDelete">No</button>
            </div>
        </div>


        <!-- javascript -->
        <script>
            // variables for uploading new promo file (if page is empty)
            const upNewPromo = document.getElementById("freshPromo"); // button to toggle uploading new items pop-up
            const uploadPopUp = document.getElementById("upPopUp"); // the pop-up for uploading new items
            const dismissUploadBtns = document.querySelectorAll(".dismissUpload");
            // variables for delete item verification
            // 'querySelectorAll' for selecting all elements that match the 'delete-btn' class
            let delButtons = document.querySelectorAll(".delete-btn");
            let dismissDelBtn = document.getElementById("dismissDelete");
            const dimmedBg = document.getElementById("dimOverlay");
            const alertBox = document.getElementById("confirmDel");



            // --------------------------------- Uploading Items ------------------------------------
            // display upload new item pop-up
            upNewPromo.addEventListener("click", function() {
                if (uploadPopUp.style.display === "none"){
                    dimmedBg.style.display = "block";
                    uploadPopUp.style.display = "block";
                }
                else {
                    dimmedBg.style.display = "none";
                    uploadPopUp.style.display = "none";
                }
            });
                

            
            // ----------------------------- Close Promo Input Pop-Up -------------------------------
            // close upload item pop-up
            dismissUploadBtns.forEach(function(disUpBtn) {
                disUpBtn.addEventListener("click", function() {
                    if (uploadPopUp.style.display === "block") {
                        dimmedBg.style.display = "none";
                        uploadPopUp.style.display = "none";
                    }
                });
            })



            // ---------------------------------- Deleting Items ------------------------------------
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
            dismissDelBtn.addEventListener("click", function() {
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