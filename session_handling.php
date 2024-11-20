<?php
    // error handling setup
    error_reporting(E_ALL | E_STRICT);
    ini_set('display_startup_errors', 'Off');   // syntax errors considered startup errors cos they run before the execution of the page render
    ini_set('display_errors', 'Off');
    ini_set('log_errors', 'On');
    ini_set('error_log', 'C:/Applications/XAMPP/apache/logs/SPF/SPF-error.log');

    //start the session with the logged in user
    session_start();

    //isset() to check if 'userID' exists in the session
    if (isset($_SESSION['userID'])) {
        //retrieving the user ID from the session
        $userID = $_SESSION['userID'];
    }
    else {
        //userID set to null if user is viewing as a guest
        $userID = null;
    }



    //note: when the user logs into their account, the session is actively being remembered by the web browser
    //and the session identifier will automatically be passed throughout the web pages the user visits. That's why
    //there's no need for code to link `login.php` to this php file

?>