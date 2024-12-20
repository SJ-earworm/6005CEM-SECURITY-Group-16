<?php
// error handling setup
error_reporting(E_ALL | E_STRICT);
ini_set('display_startup_errors', 'Off');   // syntax errors considered startup errors cos they run before the execution of the page render
ini_set('display_errors', 'Off');
ini_set('log_errors', 'On');
ini_set('error_log', 'C:/Applications/XAMPP/apache/logs/SPF/SPF-error.log');

require("session_handling.php");

?>

<!DOCTYPE html>
<html lang="utf=8">



<head>
<title>Admin Dashboard</title>
<link rel="stylesheet" href="astyle.css">
</head>
<body>


<nav>
		<img src ="images/logo.png" width="190" height="90">
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
