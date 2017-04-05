<?php include 'tools.php'; 
include 'emailer.php';
//functions.php by Yannick Mansuy
///function used to connect to create a new connection object to connect to the database
date_default_timezone_set('Australia/Brisbane');
$GLOBALS['timelate'] = strtotime("11:59 pm");
$GLOBALS['timeearly'] = strtotime("12:01 am");


//used to check if shift has been started to set the time on timer accordingly
//Checks the SESSION for any start epoch,
//if none then queries the database, table "CLOCK-IN" for a started shift, then resumes timer.
//if none, gets the start time of the shift set by supervisor and does not start timer.
function checkShift()
{
    if (session_id() == '')
        {
            session_start();
        }
    if (!empty($_SESSION['EMPID']))
        {//user logged in
            
            unset($_SESSION["SHIFTSTART"]);
            unset($_SESSION["SHIFTID"]);
            unset($_SESSION["SHIFTEND"]);
            unset($_SESSION["SHIFTBEGIN"]);
            
            $EMPID = $_SESSION["EMPID"];
            $table = '"CLOCK-IN"';
            $column = "SHIFTSTART";
            $attribute = $EMPID;
            $wherecolumn = "EMPLOYEEID";
            $columnNew = "SHIFTID";
        
            $columnStart = "SHIFTSTART";
            $tableStart = '"CLOCK-IN"';
            $wherecolumnStart = "EMPLOYEEID";
            $wherecolumnStart0 = "SHIFTEND";
            $queryStart = 'SELECT COUNT('.$columnStart.') AS '.$columnStart.' FROM '.$tableStart.' WHERE '.$wherecolumnStart.' = :EMPID AND '.$wherecolumnStart0.' IS NULL';
            $queryFunction = GrabMoreData($queryStart,
                array(array(":EMPID",$EMPID)));
        
                if ($queryFunction["$columnStart"] == 1)
                {//1 Shift is still going
                    $query = 'SELECT SHIFTID,SHIFTSTART,BEGIN,END FROM CLOCKINSHIFTS WHERE EMPLOYEEID = :EMPLOYEEID AND SHIFTEND IS NULL';
                    $bind = array(array(":EMPLOYEEID", $attribute));
                    $data = GrabMoreData($query, $bind);
                    if (!empty($data))
                        {
                            $data["SHIFTSTART"] = $data["SHIFTSTART"]*1000;
                            $data["END"] = $data["END"]*1000;
                            $data["BEGIN"] = $data["BEGIN"]*1000;
                            $_SESSION["SHIFTSTART"] = $data["SHIFTSTART"];
                            $_SESSION["SHIFTID"] = $data["SHIFTID"];
                            $_SESSION["SHIFTEND"] = $data["END"];
                            $_SESSION["SHIFTBEGIN"] = $data["BEGIN"];
                            //return json_encode($data);
                            return $data;
                        }
                }
                    else
                    {
                        
                        if (CheckExistExt($attribute, $table, $columnNew, $wherecolumn))
                        {//Shift assigned but not started
                            $now = time();
                            $query = 'SELECT SHIFTID, BEGIN, END FROM "CLOCK-IN" LEFT JOIN SHIFTS ON "CLOCK-IN".SHIFTID=SHIFTS.ID WHERE EMPLOYEEID = :EMPLOYEEID AND SHIFTEND IS NULL AND SHIFTSTART IS NULL AND BEGIN < :TIMELATE AND BEGIN > :TIMEEARLY';
                            $bind = array(array(":EMPLOYEEID", $attribute), array(":TIMELATE",$GLOBALS["timelate"]), array(":TIMEEARLY",$GLOBALS["timeearly"]));
                            $data = GrabMoreData($query, $bind);
                            if (!empty($data))
                            {   
                                if (($data["BEGIN"] - 18000) < $now)
                                {//3 hours before shift start
                                    unset($_SESSION['SHIFTSTART']);
                                    $_SESSION["SHIFTID"] = $data["SHIFTID"];
                                    $_SESSION["SHIFTEND"] = $data["END"]*1000;
                                    $_SESSION["SHIFTBEGIN"] = $data["BEGIN"]*1000; 
                                }
                                else
                                {//no shift starting in less than 3 hours
                                    echo "out";
                                    return $data;
                                    return false;
                                }
                                $data["BEGIN"] = $data["BEGIN"]*1000;
                                $data["END"] = $data["END"]*1000;
                                return $data;
                            }
                            else
                            {//no shift not started today
                                $query = 'SELECT SHIFTSTART,SHIFTEND,SHIFTID FROM CLOCKINSHIFTS WHERE EMPLOYEEID = :EMPLOYEEID AND SHIFTEND < :TIMELATE AND SHIFTEND > :TIMEEARLY';
                                $bind = array(array(":EMPLOYEEID", $attribute), array(":TIMELATE",$GLOBALS["timelate"]), array(":TIMEEARLY",$GLOBALS["timeearly"]));
                                $data = GrabMoreData($query, $bind);
                                if (!empty($data["SHIFTID"])) 
                                {
                                    $data["SHIFTSTART"] = $data["SHIFTSTART"]*1000;
                                    $data["SHIFTEND"] = $data["SHIFTEND"]*1000;
                                    return $data;
                                }
                                else
                                {
                                    return false;
                                }                            
                            }
                        }   
                }
                
            }
    else
    {//user not logged in
        echo "login";
        return false;
    }
}
//used to start a new shift
//checks the session for a shift ID set in the checkShift function,
//insert the current time in "CLOCK-IN" table in the shiftstart column which is then put into session
function startShift()
{
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
                    if (!CheckExistExt($id, '"CLOCK-IN"', "SHIFTSTART", "SHIFTID"))
                    {
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
                        echo "exist";
                        return false;
                    }
                }
                else
                {
                    print_r($_SESSION);
                    echo "started";
                    return false;
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
}

function stopShift()
{
    if (session_id() == '')
    {
        session_start();
    }
    if(isset($_SESSION['EMPID']))
    {
        if (!empty($_SESSION['EMPID']))
            {
                if (isset($_SESSION["SHIFTID"]) && !empty($_SESSION['SHIFTID']) && isset($_SESSION["SHIFTSTART"]) && !empty($_SESSION['SHIFTSTART']))
                {
                    $id = $_SESSION["SHIFTID"];
                    $timenow = time();
                    $query = 'UPDATE "CLOCK-IN" SET SHIFTEND=:TIME WHERE SHIFTID = :ID AND SHIFTEND IS NULL';
                    $bind = array(array(":TIME",$timenow),array(":ID",$id));
                    $insert = InsertData($query, $bind);
                    if ($insert == "success")
                    {
                        $_SESSION["SHIFTEND"] = $timenow*1000;
                        unset($_SESSION["SHIFTSTART"]);
                        echo "success";
                        return true;
                    }
                    else
                    {
                        print_r($insert);
                    }
                }
                else
                {
                    echo "notstarted";
                    return false;
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
}

function AddTimerCheck()
{
    if (session_id() == '')
    {
        session_start();
    }
    if(isset($_SESSION['SHIFTID']) && !empty($_SESSION['SHIFTID']))
            {
                $now = time()*1000;
                if(!empty($_SESSION['SHIFTSTART']) || $_SESSION["SHIFTBEGIN"] - 10800000 < $now //up to 3 hours before
                   && $_SESSION["SHIFTEND"] > $now ){echo 'headerTimer';}else{echo 'headerNoTimer';}}else{echo 'headerNoTimer';}
}
?>