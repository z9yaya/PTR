<?php
//save.php by Yannick Mansuy

include "../functions.php";
//this script is used to write a file or create a file, then updates the database, recording when the file was last modified and adding the last online for the user writing to the file 
if (!empty($_POST)) {
    if (session_id() == '')
    {
        session_start();
    }
    if(isset($_SESSION['email']))
    {
        $user = $_SESSION['email']; 
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
        try
         {
             $pdo = connect();
             $query= "INSERT INTO chat(file, lastmodified, user, online)
             VALUES(:file, :lastmod, :user, :timenow);";
             $prepare = $pdo->prepare($query);
             $prepare -> bindValue(':file', $post_document);
             $prepare -> bindValue(':lastmod', $lastmod);
             $prepare -> bindValue(':user', $user);
             $prepare -> bindValue(':timenow', $timenow);
             $prepare->execute();
         }
         catch (PDOException $e)
         {
             echo "There was an error, contact the system adminstrator and copy this error: " . $e -> getMessage();
         } 
        try
         {
             $pdo = connect();
             $query= "INSERT INTO chat(file, lastmodified, user)
             VALUES(:file, :lastmod, :user);";
             $prepare = $pdo->prepare($query);
             $prepare -> bindValue(':file', $post_document);
             $prepare -> bindValue(':lastmod', $lastmod);
             $prepare -> bindValue(':user', $receiver);
             $prepare->execute();
         }
         catch (PDOException $e)
         {
             echo "There was an error, contact the system adminstrator and copy this error: " . $e -> getMessage();
         } 
    }
    else
    {
        $lastmod = filemtime($filename);
        $timenow = time(); 
        try
        {
            $pdo = connect();
            $query= "UPDATE chat SET online=:time, lastmodified=:lastmod WHERE file=:file AND user=:user";
            $prepare = $pdo->prepare($query);
            $prepare -> bindValue(':file', $post_document);
            $prepare -> bindValue(':user', $user);
            $prepare -> bindValue(':time', $timenow);
            $prepare -> bindValue(':lastmod', $lastmod);
            $prepare->execute();
        }
        catch (PDOException $e)
        {
            echo "data:{$e -> getMessage()}\n\n";
        }
        try
        {
            $pdo = connect();
            $query= "UPDATE chat SET lastmodified=:lastmod WHERE file=:file";
            $prepare = $pdo->prepare($query);
            $prepare -> bindValue(':file', $post_document);
            $prepare -> bindValue(':lastmod', $lastmod);
            $prepare->execute();
        }
        catch (PDOException $e)
        {
            echo "data:{$e -> getMessage()}\n\n";
        }
    }
     
}
?>