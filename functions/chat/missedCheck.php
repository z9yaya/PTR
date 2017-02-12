<?php
//missedCheck.php by Yannick Mansuy

 include "../functions.php";
//this script sends the last online column for a specific user to the browser, this script is ran every 10 seconds
if (session_id() == '')
    {
        session_start();
    }
    if(isset($_SESSION['email']))
    {
        $user = $_SESSION['email']; 
        $lastOnline= GrabMoreData("SELECT file FROM chat WHERE user=:user AND online < lastmodified", array(array(':user', $user)));
        echo json_encode($lastOnline);
    }

?>