<?php
//missedCheck.php by Yannick Mansuy
 include "../functions.php";
//this script sends the last online column for a specific user to the browser, this script is ran every 10 seconds
if (session_id() == '')
    {
        session_start();
    }
    if(isset($_SESSION['EMAIL']))
    {
        $user = $_SESSION['EMAIL']; 
        $bind = array(array(':email', $user));
        $lastOnline= GrabAllData("SELECT CHATFILE FROM CHAT WHERE EMAIL= :email AND LASTLOG < LASTMOD",$bind );
        echo json_encode($lastOnline);
    }

?>