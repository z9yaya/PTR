<?php
//functions.php by Yannick Mansuy
///function used to connect to create a new connection object to connect to the database
date_default_timezone_set('Australia/Brisbane');
$GLOBALS['timelate'] = strtotime("11:59 pm");
$GLOBALS['timeearly'] = strtotime("12:01 am");
function connect()
    {
        $conn = oci_connect('ptr', 'ptr', 'localhost:1521/xe');

        if (!$conn) 
        {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
        else
        return $conn;
    }//end connect

///Used to register new users on the database,
///grabs all the data from the form, then formats it, binds it to variables for inserting into database,
///then pushes email,position and password to $_SESSION
function registerUser()
    {
        if (CheckExist('ptr:signup:username', 'EMPLOYEES', 'EMAIL', $_POST))
        {
            echo "exist";
            return false;
        }
        else
        {
            if (isset($_POST))
            {
                 if (!empty($_POST) && !empty($_POST['ptr:signup:username']))
                    {
                         $email = filter_var($_POST['ptr:signup:username'], FILTER_SANITIZE_EMAIL);
                         if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                             echo "email";
                             return false;
                         }
                         if ($_POST['ptr:signup:secret2'] === $_POST['ptr:signup:secret1'])
                         {
                             $password = $_POST['ptr:signup:secret2'];
                         }
                         else
                         {
                             echo "password";
                             return false;
                         }
                         $ID = filter_var($_POST['ptr:signup:empID'], FILTER_SANITIZE_NUMBER_INT);
                         $password = password_hash($password, PASSWORD_DEFAULT);
                         $pdo = connect();
                         $query= "INSERT INTO EMPLOYEES (email, password)
                         VALUES(:email, :password)";
                         $prepare = oci_parse($pdo,$query);
                         oci_bind_by_name($prepare, ':email', $email);
                         oci_bind_by_name($prepare, ':password', $password);
                         if(oci_execute($prepare))
                         {
                            if (session_id() == '')
                            {
                                session_start();
                            }
                            else if(isset($_SESSION['email']))
                            {
                               session_unset();
                            }
                            $_SESSION['EMPID'] = GrabData('EMPLOYEES', 'EMPID', 'EMAIL', $email)["EMPID"];
                            $_SESSION['EMAIL'] = $email;
                            $_SESSION['PASSWORD'] = GrabData('EMPLOYEES', 'PASSWORD', 'EMAIL', $email)["PASSWORD"];
                            echo "success";
                            return true;
                         }
                          else
                         {
                            $e = oci_error($prepare); 
                            echo "There was an error, contact the system adminstrator and copy this error: " . $e['message']; 
                         }
                 }
            }
        }
}

///Used to authenticate an existing user on the system, grabs data from form then checks against the database and pushes to $_SESSION
function authenticateUser()
{
    if (CheckExist('ptr:login:email', 'EMPLOYEES', 'EMAIL', $_POST))
    {
        $pdo = connect();
        $email = filter_var($_POST['ptr:login:email'], FILTER_SANITIZE_EMAIL);
        $password = $_POST['ptr:login:secret'];
        $prepare = oci_parse($pdo,"SELECT * FROM EMPLOYEES WHERE EMAIL = :email");
        oci_bind_by_name($prepare, ':email', $email);
        if(oci_execute($prepare))
        {
             $res = oci_fetch_assoc($prepare);
             if ($res != null)
             {
                 if(password_verify($password,$res['PASSWORD']))
                    {
                          if (session_id() == '')
                          {
                              session_start();
                          }
                          else if(isset($_SESSION['EMAIL']))
                          {
                              session_unset();
                          }
                          $_SESSION['EMPID'] = $res['EMPID'];
                          $_SESSION['EMAIL'] = $res['EMAIL'];
                          $_SESSION['PASSWORD'] = $res['PASSWORD'];
                          $_SESSION['INITIAL'] = $res['INITIALSETUP'];
                          
                          echo "success";
                          return true;
                    }
                    else
                    {
                        echo "password";
                        return false;
                    }
                  
             }
             else
             {
                  return false;
             }
        }
        else
             {
                $e = oci_error($prepare); 
                echo $e['message']; 
             }        
    }
    else
    {
       echo "username";
       return false;
    }
}

///Used to check if a row with the specified value exist in a table
///INPUT $attribute: index name in $_GET or $_POST
///INPUT $table: name of table in database
///INPUT $column: name of column to check against in database
///INPUT $getOrpost: specifies where the data is stored, options:$_GET or $_POST
function CheckExist($attribute, $table, $column, $getOrpost)
{
    if (isset($getOrpost))
            {
                 if (!empty($getOrpost) && !empty($getOrpost[$attribute]))
                    {
                         $input = htmlspecialchars($getOrpost[$attribute]);
                         $pdo = connect();
                         $sql= 'SELECT COUNT(' . $column . ') FROM ' . $table . ' where ' . $column . ' = :attribute';
                         $prepare = oci_parse($pdo,$sql);
                         oci_bind_by_name($prepare, ':attribute', $input);
                         if(oci_execute($prepare))
                         {                             
                            if (oci_fetch_array($prepare, OCI_ASSOC+OCI_RETURN_NULLS)['COUNT('. $column .')'] != 0)
                             {
                                 return true;
                             }
                             else
                             {
                                 return false;
                             }
                         }
                         else
                         {
                            $e = oci_error($prepare); 
                            echo $e['message']; 
                         }
                    }
                         
                         
                }  
}

function CheckExistExt($attribute, $table, $column, $wherecolumn)
{
    if (isset($attribute))
            {
                 if (!empty($attribute))
                    {
                         $input = htmlspecialchars($attribute);
                         $pdo = connect();
                         $sql= 'SELECT COUNT(' . $column . ') FROM ' . $table . ' where ' . $wherecolumn . ' = :attribute';
                         $prepare = oci_parse($pdo,$sql);
                         oci_bind_by_name($prepare, ':attribute', $input);
                         if(oci_execute($prepare))
                         {                             
                            if (oci_fetch_array($prepare, OCI_ASSOC+OCI_RETURN_NULLS)['COUNT('. $column .')'] != 0)
                             {
                                 return true;
                             }
                             else
                             {
                                 return false;
                             }
                         }
                         else
                         {
                            $e = oci_error($prepare); 
                            echo $e['message']; 
                            exit();
                         }
                    }
                         
                         
                }  
}

///Used to return single cell from database
///INPUT $table: table in the database where to look for the data
///INPUT $column: the name of the column you want to select
///INPUT $where_column: the name of the column that contains the data that needs to match the input
///INPUT $where: the data that will be looked for in the specified column.
function GrabData($table, $column, $where_column, $where)
{
                         $input = $where;
                         $pdo = connect();
                         $sql= 'SELECT ' . $column . ' FROM ' . $table . ' where ' . $where_column . ' = :attribute';
                         $prepare = oci_parse($pdo,$sql);
                         oci_bind_by_name($prepare, ':attribute', $input);
                         if(oci_execute($prepare))
                         {
                             $res = oci_fetch_array($prepare, OCI_ASSOC+OCI_RETURN_NULLS);
                             if ($res != null)
                             {
                                  return $res;
                             }
                             else
                             {
                                 return false;
                             }
                         }
                         else
                         {
                            $e = oci_error($prepare); 
                            echo $e['message']; 
                         }
                         
}

///Used to return the results of a specified mySQL query
///$query is the basic mySQL query eg: "SELECT * FROM users WHERE email = :email AND password = :password".
///$bind is a nested array, must be in pairs, eg: 'array(array(':email', 'generic@email.com'), array(':password', 'passwordtext'))'
function GrabMoreData($query, $bind =null)
{
                             $pdo = connect();
                             $sql= $query;
                             $prepare = oci_parse($pdo,$sql);
                             if (!empty($bind))
                             {
                                 foreach ($bind as $attribute)
                                 {
                                    oci_bind_by_name($prepare, $attribute[0], $attribute[1]);
    /*                                 echo $attribute[0]." ".$attribute[1];*/
                                 }
                             }
                             if(oci_execute($prepare))
                             {
                                $res =  oci_fetch_array($prepare,OCI_ASSOC+OCI_RETURN_NULLS);
                                 if ($res != null)
                                 {
                                      return $res;
                                 }
                                 else
                                 {
                                     return false;
                                 }
                             }
                             else
                             {
                                $e = oci_error($prepare); 
                                echo $e['message']; 
                             }                         
}

function GrabAllData($query, $bind = null)
{
                             $pdo = connect();
                             $sql= $query;
                             $prepare = oci_parse($pdo,$sql);
                             if (!empty($bind))
                             {
                                 foreach ($bind as $attribute)
                                 {
                                    oci_bind_by_name($prepare, $attribute[0], $attribute[1]);
                                 }
                             }
                             if(oci_execute($prepare))
                             { 
                                oci_fetch_all($prepare, $res);
                                 if ($res != null)
                                 {
                                      return $res;
                                 }
                                 else
                                 {
                                     return false;
                                 }
                             }
                             else
                             {
                                $e = oci_error($prepare); 
                                echo $e['message']; 
                             }                         
}

function InsertData($query, $bind)
{
                             $pdo = connect();
                             $sql= $query;
                             $prepare = oci_parse($pdo,$sql);
                             foreach ($bind as $attribute)
                             {
                                oci_bind_by_name($prepare, $attribute[0], $attribute[1]);
                             }
                             if(oci_execute($prepare))
                             {
                                return "success";
                    
                             }
                             else
                             {
                                $e = oci_error($prepare); 
                                echo $e['message']; 
                             }                         
}
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
                   && $_SESSION["SHIFTEND"] > $now )
                {
                    echo 'headerTimer';
                }
                else
                {
                    echo 'headerNoTimer';
                }
            }
            else
            {
                echo 'headerNoTimer';
            }
}
?>