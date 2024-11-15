<?php
    require("session_handling.php");

    //userID value assigned in `session_handling.php`
    if ($userID != null) {
        include("cart_userview.php");
    }
    else {
        header("Location: login.php");
    }


    //this file is to determine which interface to show the users depending on whether they are logged in 
    //or accessing the website as a guest
?>