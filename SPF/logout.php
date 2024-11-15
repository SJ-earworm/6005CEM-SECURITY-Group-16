<?php
    session_start();

    //clearing the user ID from the session
    unset($_SESSION['userID']);
    session_destroy();
    header("Location: login.php");
?>