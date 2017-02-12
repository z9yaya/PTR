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
if(isset($_SESSION['email']))
{
    $user = $_SESSION['email']; 
}
$file = $_GET['file'];
$timeuser= time();
try
     {
        $pdo = connect();
        $query= "UPDATE chat SET online=:time WHERE file=:file AND user=:user";
        $prepare = $pdo->prepare($query);
        $prepare -> bindValue(':file', $file);
        $prepare -> bindValue(':user', $user);
        $prepare -> bindValue(':time', $timeuser);
        $prepare->execute();
    }
catch (PDOException $e)
     {
         echo "data:{$e -> getMessage()}\n\n";
     }
$fileTime = filemtime($file);
$time = time() - 10;
if ($fileTime >= $time)
{
    echo "data: {$fileTime}\n\n";
    flush();
}
?>