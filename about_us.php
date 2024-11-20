<?php
    // error handling setup
	error_reporting(E_ALL | E_STRICT);
    ini_set('display_startup_errors', 'Off');   // syntax errors considered startup errors cos they run before the execution of the page render
    ini_set('display_errors', 'Off');
    ini_set('log_errors', 'On');
    ini_set('error_log', 'C:/Applications/XAMPP/apache/logs/SPF/SPF-error.log');
    
    require("session_handling.php");

    //userID value assigned in `session_handling.php`
    if ($userID != null) {
        include("aboutus_userview.php");
    }
    else {
        include("aboutus_guestview.php");
    }


    //this file is to determine which interface to show the users depending on whether they are logged in 
    //or accessing the website as a guest
?>