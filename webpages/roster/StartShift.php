<?php include "../../functions/functions.php";
if (session_id() == '')
{
    session_start();
}
if(isset($_SESSION['EMPID']))
{
    if (!empty($_SESSION['EMPID']))
        {
            if (isset($_SESSION["SHIFTID"]) && !empty($_SESSION['SHIFTID']) && !isset($_SESSION["SHIFTSTART"]))
            {
                $id = $_SESSION["SHIFTID"];
                $timenow = time();
                $query = 'UPDATE "CLOCK-IN" SET SHIFTSTART=:TIME WHERE SHIFTID = :ID';
                $bind = array(array(":TIME",$timenow),array(":ID",$id));
                $insert = InsertData($query, $bind);
                if ($insert == "success")
                {
                    $_SESSION["SHIFTSTART"] = $timenow*1000;
                    echo "success";
                    return true;
                }
            }
            else
            {
                echo "started";
                print_r($_SESSION);
                echo $_SESSION["SHIFTSTART"];
                return false;
            }

        }
}