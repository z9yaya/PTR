<?php
//load.php by Yannick Mansuy

 include "../functions.php";
//this script is used to fetch contacts and encodes in json to send to the browser
if (session_id() == '')
            {
                session_start();
            }
if(isset($_SESSION['EMAIL']))
{
    if (!empty($_SESSION['EMAIL'])) 
    {
        $query = "SELECT EMAIL, F_NAME, L_NAME FROM EMPLOYEES WHERE EMAIL != :email";
        $bind = array(array(':email', $_SESSION['EMAIL']));
        $results = GrabAllData($query, $bind);
        echo json_encode($results);
   }
}

?>