<?php include "../functions/tools.php"; 
if (session_id() == '')
  {
      session_start();
        
  }
if(isset($_SESSION['EMAIL']))
{
    if (!empty($_SESSION['EMAIL'])) {

        if (CheckExist("EMPID", "EMPLOYMENT", "EMPID", $_POST))
        {
            $query = "SELECT EMPLOYEES.EMPID, EMAIL, F_NAME, L_NAME, STORE, POSITION, RATE, EMPLOYMENT.TYPE, EMPSTART, EMPEND, EMPTYPE, EMPLOYEES.TYPE AS ACCTYPE FROM EMPLOYEES LEFT JOIN EMPLOYMENT ON EMPLOYEES.EMPID = EMPLOYMENT.EMPID WHERE EMPLOYEES.EMPID = :EMPID";
            $bind = array(array(':EMPID', $_POST['EMPID']));
            $data = GrabMoreData($query, $bind);
            if (!empty($data["EMPSTART"]))
            {
                $data["EMPSTART"] = date("Y-m-d", $data["EMPSTART"]);
            }
            if (!empty($data["EMPEND"]))
            {
                $data["EMPEND"] = date("Y-m-d", $data["EMPEND"]);
            }
            $data["RATE"] = sprintf("%.2f", $data['RATE']);
            echo json_encode($data);
            return true;
        }        
    }
}
else
{
    return false;
}