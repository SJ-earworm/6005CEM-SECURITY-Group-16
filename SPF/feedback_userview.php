<!DOCTYPE html>
<html>
    <head>
        <title>Santa's Plushie Factory</title>
        <link rel="stylesheet" href="style.css">
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
        </nav>
        
        <div class="layout">
            <h1>Feedback</h1>
            <form action="submit_feedback.php" method="post">
                <p>Name:</p>
                <input type="text" name="name" placeholder="John Doe" required><br><br>

                <p>Email:</p>
                <input type="email" name="email" placeholder="john@gmail.com" required><br><br>

                <p>Message:</p>
                <textarea name="message" col="5" rows="5" required></textarea><br>

                <button type="submit">Submit Feedback</button>
            </form>
        </div>

    </body>
</html>