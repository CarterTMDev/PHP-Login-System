<?php
    /*
        Send the user to this page when they need to be logged out.
    */
    session_save_path("sessions");
    session_start();
    session_unset();
    session_destroy();
    header("Location: index.php");
    die;
?>