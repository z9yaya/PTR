<?php include '../tools.php';
if (!empty($_POST['creator'])) {
    $creator = $_POST['creator'];
    $receiver = $_POST['receiver'];
    $saveLocation = "/functions/chat/";
    $file = $creator."-".$receiver.".xml";
    $url = $file;  
    $exists = 'true';
    if( file_exists ( $url ) === false ) {
        $exists = 'false';
        $file2 = $receiver."-".$creator.".xml";
        $url2 = $file2; 
        if( file_exists ( $url2 ) === true ) { 
            $exists = 'true';
            $file = $file2;
        } 
    }
    echo json_encode(array($file,$exists));
    return true;
}
?>