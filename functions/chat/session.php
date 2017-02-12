<?php
//session.php by Yannick Mansuy

//this script is used to return the user currently logged in
if (session_id() == '')
            {
                session_start();
            }
if(isset($_SESSION['email']))
{
    if (!empty($_SESSION['email'])) {
        $data = $_SESSION['email'];

    }
}
else
{
    $data = false;
}
echo $data;
?>