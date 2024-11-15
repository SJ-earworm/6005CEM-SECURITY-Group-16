<!DOCTYPE html>
<html lang="utf=8">
    <head>
        <title>Santa's Plushie Factory</title>

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
                    <li><a href="login.php"><img src ="images/login.png" width="100" height="50"></a></li>
                </ul>
            </nav>
        </header>


        <h1 class="welcome-title">Welcome to Santa's Plushie Factory</h1>


        <!--carousel of promotions here-->
        <div class="carousel-container">

            <?php
                include("Connectdb.php");

                // OLD UNSECURE
                // $carouselQuery = "SELECT promoImage FROM carousel_promo";
                // $carouselResult = mysqli_query($con, $carouselQuery);

                // if(mysqli_num_rows($carouselResult) > 0) {
                //     $count = 0;

                //     while($tbrow = mysqli_fetch_assoc(($carouselResult))) {
                //         $carouselImg = $tbrow['promoImage'];

                //         // promotional images
                //         echo "<div class='promoSlide slide-fade' style='display: none;'>";
                //         echo "    <img src='$carouselImg' alt='blackandyellowblackandyellow' style='width: 100%; height: 400px;'>";
                //         echo "</div>";

                //         $count += 1;
                //     }
                // }

                // NEW SECURE CODE
                $carouselQuery = "SELECT promoImage FROM carousel_promo";
                $stmt = $con->prepare($carouselQuery);
                $stmt->execute();
                $carouselResult = $stmt->get_result();

                if($carouselResult->num_rows > 0) {
                    $count = 0;

                    while($tbrow = $carouselResult->fetch_assoc()) {
                        $carouselImg = $tbrow['promoImage'];

                        // promotional images
                        echo "<div class='promoSlide slide-fade' style='display: none;'>";
                        echo "    <img src='" .htmlspecialchars($carouselImg). "' alt='blackandyellowblackandyellow' style='width: 100%; height: 400px;'>";
                        echo "</div>";

                        $count += 1;
                    }
                } else {
                    echo "<p>No promotional material to show</p>";
                }


                // navigation arrows
                echo "<a class='prev' onclick='changeSlide(-1)'>&#10094;</a>";
                echo "<a class='next' onclick='changeSlide(1)''>&#10095;</a>";


                // navigation dots
                echo "<div class='dots'>";
                    for ($i = 0; $i < $count; $i++) {
                        echo "<span class='dot' onclick='currentSlide(i+1)'></span>";
                    }
                echo "</div>";
            ?>
            
        </div>


        <h1 style="text-align: center; font-size: 3rem;">Featured Products</h1>
        <!-- displaying featured products -->
        <div class="product-section">
            <!-- setting the category of items to display -->
            <?php $catID = 1; ?>

            <!-- displaying the products dynamically -->
            <?php include('db_productbox.php'); ?>
        </div>



        <!--javascript-->
        <script>
            // displaying the 1st slide by default
            let slideIndex = 1;
            displaySlide(slideIndex);

            // navigating between slides
            function changeSlide(n) {
                displaySlide(slideIndex += n);
            }

            // navigation dot controls
            function currentSlide(n) {
                displaySlide(slideIndex = n);
            }


            // displaying slide per user navigation
            function displaySlide(n) {
                let slides = document.getElementsByClassName("promoSlide");
                let dots = document.getElementsByClassName("dot");

                // making the slides go in a cycle
                if (n > slides.length) {
                    slideIndex = 1;
                }
                
                if (n < 1) {
                    slideIndex = slides.length;
                }

                // code for displaying the current slide
                // closing all slides
                for (i = 0; i < slides.length; i++) {
                    slides[i].style.display = "none";
                }
                // removing all active styles from the dots
                for (i = 0; i< dots.length; i++) {
                    dots[i].className = dots[i].className.replace(" active", "");
                }

                // displaying the current slide that has been called out
                slides[slideIndex - 1].style.display = "block";
                dots[slideIndex - 1].className += " active";
            }
        </script>        
    </body>
</html>