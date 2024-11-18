<!DOCTYPE html>
<html lang="utf=8">
    <head>
        <title>Products: Collaborations</title>

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


        <!-- search function -->
        <div class="search-container">
            <div class="search">
                <form action="pd_collaborations.php" method="post">
                    <input type="text" name="search-query" placeholder="Search product" autocomplete="off">
                    <input type="submit" name="search-btn">
                </form>
            </div>
        </div>


        <div class="content-margin">
            <h1 style="font-size: 3rem; text-align: center; margin: 40px 0px 70px 0;">Collaborations</h1>

            <!-- displaying featured products -->
            <div class="product-section">
                <!-- displaying the products dynamically -->
                <?php
                    // setting the category to display
                    $catID = 5;

                    if (isset($_POST['search-query'])) {
                        // cleaning the input from whitespace & special characters
                        $rawUserSearchTrim = trim($_POST['search-query']);
                        $sanitisedUserSearch = filter_var($rawUserSearchTrim, FILTER_SANITIZE_STRING);
                        $userSearch = "%$sanitisedUserSearch%";

                        include('db_productbox.php');
                    }
                    else {
                        include('db_productbox.php'); 
                    }
                    
                ?>
            </div>
        </div>



        <!--javascript-->
        
    </body>
</html>