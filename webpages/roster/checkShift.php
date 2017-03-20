<?php include "../../functions/functions.php";
if (session_id() == '')
    {
        session_start();
    }
if(isset($_SESSION['EMPID']))
    {
        if (!empty($_SESSION['EMPID']))
            {
                if (isset($_SESSION["SHIFTSTART"]) && !empty($_SESSION['SHIFTSTART']))
                {
                    echo json_encode(array("SHIFTSTART" => $_SESSION['SHIFTSTART']));
                    return true;
                }
            
                $table = '"CLOCK-IN"';
                $column = "SHIFTSTART";
                $attribute = $_SESSION["EMPID"];
                $wherecolumn = "EMPLOYEEID";
                $columnNew = "SHIFTID";
                if (CheckExistExt($attribute, $table, $column, $wherecolumn))
                {
                    $query = 'SELECT SHIFTSTART, END FROM "CLOCK-IN" LEFT JOIN SHIFTS ON "CLOCK-IN".SHIFTID=SHIFTS.ID WHERE EMPLOYEEID = :EMPLOYEEID AND SHIFTEND IS NULL';
                    $bind = array(array(":EMPLOYEEID", $attribute));
                    $data = GrabMoreData($query, $bind);
                    $data["SHIFTSTART"] = $data["SHIFTSTART"]*1000;
                    $data["END"] = $data["END"]*1000;
                    $_SESSION["SHIFTSTART"] = $data["SHIFTSTART"];
                    $_SESSION["END"] = $data["END"];
                    echo json_encode($data);
                    return true;
                }
                else if (CheckExistExt($attribute, $table, $columnNew, $wherecolumn))
                {
                    $query = 'SELECT SHIFTID, BEGIN, END FROM "CLOCK-IN" LEFT JOIN SHIFTS ON "CLOCK-IN".SHIFTID=SHIFTS.ID WHERE EMPLOYEEID = :EMPLOYEEID AND SHIFTEND IS NULL AND SHIFTSTART IS NULL';
                    $bind = array(array(":EMPLOYEEID", $attribute));
                    $data = GrabMoreData($query, $bind);
                    //3 hours before shift start
                    if ($data["BEGIN"]- 10800 < time() && $data["END"] > time())
                    {
                        $_SESSION["SHIFTID"] = $data["SHIFTID"];
                        $_SESSION["SHIFTEND"] = $data["END"];
                        $_SESSION["SHIFTBEGIN"] = $data["BEGIN"];
                    }
                    else
                    {
                        echo "out";
                        return false;
                    }
                    echo json_encode($data);
                    return true;
                }
                
            }
        else
            {
                echo "login";
                return false;
            }
    }
else
{
    echo "login";
    return false;
}
?>