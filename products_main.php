<?php
    require("session_handling.php");

    //userID value assigned in `session_handling.php`
    if ($userID != null) {
        include("products_main_userview.php");
    }
    else {
        include("products_main_guestview.php");
    }


    //this file is to determine which interface to show the users depending on whether they are logged in 
    //or accessing the website as a guest
?>