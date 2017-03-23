<?php include "../functions/functions.php";
if (session_id() == '')
    {
      session_start();
    }
if(isset($_POST['column']))
{
    if (!empty($_POST['column'])) {
         if (CheckExistExt($_SESSION["EMPID"], "EMPLOYEES", $_POST['column'], "EMPID"))
         {
             if ($_POST['column'] == "M_NAME")
             {
                 $query = "UPDATE EMPLOYEES SET M_NAME=NULL WHERE EMPID = :EMPID";
                 $bind = array(array(":EMPID",$_SESSION["EMPID"]));
                 echo InsertData($query,$bind);
                 return true;
             }
             else if ($_POST['column'] == "STREET2")
             {
                 $query = "UPDATE EMPLOYEES SET STREET2=NULL WHERE EMPID = :EMPID";
                 $bind = array(array(":EMPID",$_SESSION["EMPID"]));
                 echo InsertData($query,$bind);
                 return true;
             }
             
         }
        else
        {
            echo "0";
            return false;
        }
    }
}

?>