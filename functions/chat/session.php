<?php
//session.php by Yannick Mansuy

//this script is used to return the user currently logged in
if (session_id() == '')
            {
                session_start();
            }
if(isset($_SESSION['EMAIL']))
{
    if (!empty($_SESSION['EMAIL'])) {
        $data = $_SESSION['EMAIL'];

    }
}
else
{
    $data = false;
}
echo $data;
?>