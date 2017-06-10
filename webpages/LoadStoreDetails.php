<?php include "../functions/tools.php"; 
if (session_id() == '')
  {
      session_start();
        
  }
if(isset($_SESSION['EMAIL']))
{
    if (!empty($_SESSION['EMAIL'])) {

        if (CheckExist("STOREID", "STORES", "ID", $_POST))
        {
            $query = "SELECT * FROM STORES WHERE ID = :STOREID";
            $bind = array(array(':STOREID', $_POST['STOREID']));
            $data = GrabMoreData($query, $bind);
            if (!empty($data["MANAGER"]))
            {
                $data["MANAGER"] = "E".str_pad($data['MANAGER'], 7, '0', STR_PAD_LEFT);
            }
            if (!empty($data["PHONE"])) {
                $data["PHONE"] = str_pad($data['PHONE'], 10, '0', STR_PAD_LEFT);
            }
            echo json_encode($data);
            return true;
        }        
    }
}
else
{
    return false;
}