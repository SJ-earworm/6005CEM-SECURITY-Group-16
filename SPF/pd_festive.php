<?php
    require("session_handling.php");

    //userID value assigned in `session_handling.php`
    if ($userID != null) {
        include("pd_festive_userview.php");
    }
    else {
        include("pd_festive_guestview.php");
    }


    //this file is to determine which interface to show the users depending on whether they are logged in 
    //or accessing the website as a guest
?>