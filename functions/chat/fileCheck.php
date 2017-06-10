<?php
//fileCheck.php by Yannick Mansuy

 include "../functions.php";
//used to watch a file for any changes, when the last modified time of a file is within 10 seconds of the current time, this script sends a event to the browser using SSE, this also updates the database everytime it is ran to keep track of when a user had a conversation opened last.
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');  
if (session_id() == '')
{
    session_start();
}
if(isset($_SESSION['EMAIL']))
{
    $user = $_SESSION['EMAIL']; 
}
$file = $_GET['file'];
$timeuser= time();
$query = "UPDATE CHAT SET LASTLOG=:time WHERE CHATFILE = :chatfile AND EMAIL = :email";
$bind = array(array(':chatfile', $file),array(':email', $user),array(':time', $timeuser));
InsertData($query, $bind);

$fileTime = filemtime($file);
$time = time() - 10;
if ($fileTime >= $time)
{
    echo "data: {$fileTime}\n\n";
    flush();
}
?>