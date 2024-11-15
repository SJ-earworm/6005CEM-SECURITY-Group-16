<?php
require("session_handling.php");
?>

<!DOCTYPE html>
<html lang="utf-8">

<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="astyle.css">
</head>
<body>

<nav>
    <img src="images/logo.png" width="190" height="90">
</nav>

<div class="dashboard">
    <h1>Admin Dashboard</h1>
    <div class="row">
        <div class="column">
            <a href="apselect.php"><img src="images/product.png" width="300" height="300"></a>
        </div>

        <?php if ($_SESSION['role'] == 'admin') { ?>
            <div class="column">
                <a href="auser.php"><img src="images/user.png" width="300" height="300"></a>
            </div>
        <?php } ?>

        <div class="column">
            <a href="areport.php"><img src="images/statistic.png" width="300" height="300"></a>
        </div>
    </div>
    <div class="logout">
        <a href="logout.php"><img src="images/logout.png" width="200" height="90"></a>
    </div>
</div>

</body>
</html>
