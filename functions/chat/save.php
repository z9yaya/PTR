<?php
//save.php by Yannick Mansuy

include "../functions.php";
//this script is used to write a file or create a file, then updates the database, recording when the file was last modified and adding the last online for the user writing to the file 
if (!empty($_POST)) {
    if (session_id() == '')
    {
        session_start();
    }
    if(isset($_SESSION['EMAIL']))
    {
        $user = $_SESSION['EMAIL']; 
    }
    $post_data = $_POST['data'];
    $post_document = $_POST['document'];
    $filename = $post_document;
    $data = "\n" . $post_data;
    $handle = fopen($filename, "a");
    fwrite($handle, $data);
    fclose($handle);
    if (isset($_POST['user']))
    {
        $lastmod = filemtime($filename);
        $timenow = time();
        $receiver = $_POST['user'];
        
        $query = "INSERT INTO CHAT(CHATFILE, LASTMOD, EMAIL, LASTLOG)
             VALUES(:chatfile, :lastmod, :email, :timenow)";
        $bind = array(array(':chatfile', $post_document),array(':lastmod', $lastmod),array(':email', $user),array(':timenow', $timenow));
        InsertData($query, $bind);
        
        $queryReceiver = "INSERT INTO CHAT(CHATFILE, LASTMOD, EMAIL)
             VALUES(:chatfile, :lastmod, :email)";
        $bindReceiver = array(array(':chatfile', $post_document),array(':lastmod', $lastmod),array(':email', $receiver));
        InsertData($queryReceiver, $bindReceiver);
    }
    else
    {
        $lastmod = filemtime($filename);
        $timenow = time(); 
        $query = "UPDATE CHAT SET LASTLOG=:time, LASTMOD=:lastmod WHERE CHATFILE=:chatfile AND EMAIL=:email";
        $bind = array(array(':chatfile', $post_document),array(':email', $user),array(':time', $timenow),array(':lastmod', $lastmod));
        InsertData($query, $bind);
        
        $queryLast = "UPDATE CHAT SET LASTMOD=:lastmod WHERE CHATFILE=:chatfile";
        $bindLast = array(array(':chatfile', $post_document),array(':lastmod', $lastmod));
        InsertData($queryLast, $bindLast);
    }
     
}
?>