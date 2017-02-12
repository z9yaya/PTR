<?php include "../functions/functions.php"; 
if (session_id() == '')
  {
      session_start();
  }
if(isset($_SESSION['email']))
{
    if (!empty($_SESSION['email'])) {
        $data = array("empID" => "E".str_pad($_SESSION['empID'], 7, '0', STR_PAD_LEFT),"email" => $_SESSION['email']);
        echo json_encode($data);
    }
}
else
{
    return false;
}
   


?>