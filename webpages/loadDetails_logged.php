<?php include "../functions/functions.php"; 
if (session_id() == '')
  {
      session_start();
  }
if(isset($_SESSION['EMAIL']))
{
    if (!empty($_SESSION['EMAIL'])) {
        if (CheckExist("EMPID", "BANKING", "EMPID", $_SESSION))
        {
            $query = "SELECT * FROM EMPLOYEES JOIN BANKING ON EMPLOYEES.EMPID = BANKING.EMPID WHERE EMPLOYEES.EMPID = :EMPID";
            $bind = array(array(':EMPID', $_SESSION['EMPID']));
            $data = GrabMoreData($query, $bind);
            if (!empty($data["DOB"]))
            {
                $data["day"] = date( "j",$data["DOB"]);
                $data["month"] = date("n",$data["DOB"]);
                $data["year"] = date("o", $data["DOB"]);
                unset($data["DOB"]);
            }
            unset($data['PASSWORD']);
            $data['EMPID'] = "E".str_pad($data['EMPID'], 7, '0', STR_PAD_LEFT);
            echo json_encode($data);
        }
        else
        {
            $data = GrabData("EMPLOYEES", "*", "EMPID", $_SESSION['EMPID']);
            unset($data['PASSWORD']);
            if (!empty($data["DOB"]))
            {
                $data["day"] = date( "j",$data["DOB"]);
                $data["month"] = date("n",$data["DOB"]);
                $date["year"] = date("o", $date["DOB"]);
                unset($data["DOB"]);
            }
            $data['EMPID'] = "E".str_pad($data['EMPID'], 7, '0', STR_PAD_LEFT);
            echo json_encode($data);
        }
        
    }
}
else
{
    return false;
}