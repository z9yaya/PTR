<?php include "../functions/functions.php"; 
if (session_id() == '')
  {
      session_start();
  }
if(isset($_SESSION['EMAIL']))
{
    if (!empty($_SESSION['EMAIL'])) {
        $data = array("EMPID" => "E".str_pad($_SESSION['EMPID'], 7, '0', STR_PAD_LEFT),"EMAIL" => $_SESSION['EMAIL']);
        echo json_encode($data);
    }
}
else
{
    return false;
}
   


?>