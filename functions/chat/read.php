<?php
//read.php by Yannick Mansuy

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
    $post_document = $_POST['document'];
    $data = file_get_contents($post_document);
    if (!$data) {
        echo '<div class="noMessage">No messages.</div>';
    }
    echo $data;
     
}
?>