<?php include "../functions/functions.php";
if (session_id() == '')
    {
      session_start();
    }
if(isset($_POST['column']))
{
echo "stuff";
    if (!empty($_POST['column'])) {
         if (CheckExistExt($_SESSION["EMPID"], "EMPLOYEES", $_POST['column'], "EMPID"))
         {
             $query = "UPDATE EMPLOYEES SET M_NAME=NULL WHERE EMPID = :EMPID";
             $bind = array(array(":EMPID",$_SESSION["EMPID"]));
             echo InsertData($query,$bind);
             echo $query;
//             print_r($bind);$query = "UPDATE EMPLOYEES SET :COLUMN=NULL WHERE EMPID = :EMPID";
//             $bind = array(array(":COLUMN", $_POST['column']),array(":EMPID",$_SESSION["EMPID"]));
//             echo InsertData($query,$bind);
//             echo $query;
//             print_r($bind);
         }
    }
}

?>