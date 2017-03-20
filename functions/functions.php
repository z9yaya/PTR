<?php
//functions.php by Yannick Mansuy
///function used to connect to create a new connection object to connect to the database
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
function GrabMoreData($query, $bind)
{
                             $pdo = connect();
                             $sql= $query;
                             $prepare = oci_parse($pdo,$sql);
                             foreach ($bind as $attribute)
                             {
                                oci_bind_by_name($prepare, $attribute[0], $attribute[1]);
/*                                 echo $attribute[0]." ".$attribute[1];*/
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

function GrabAllData($query, $bind)
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
    if(isset($_SESSION['EMPID']))
        {
            if (!empty($_SESSION['EMPID']))
                {
/*                    if (isset($_SESSION["SHIFTSTART"]) && !empty($_SESSION['SHIFTSTART']))
                    {
                        echo json_encode(array("SHIFTSTART" => $_SESSION['SHIFTSTART']));
                        return true;
                    }*/

                    $table = '"CLOCK-IN"';
                    $column = "SHIFTSTART";
                    $attribute = $_SESSION["EMPID"];
                    $wherecolumn = "EMPLOYEEID";
                    $columnNew = "SHIFTID";
                    if (CheckExistExt($attribute, $table, $column, $wherecolumn))
                    {
                        $query = 'SELECT SHIFTID, SHIFTSTART, END, BEGIN FROM "CLOCK-IN" LEFT JOIN SHIFTS ON "CLOCK-IN".SHIFTID=SHIFTS.ID WHERE EMPLOYEEID = :EMPLOYEEID AND SHIFTEND IS NULL';
                        $bind = array(array(":EMPLOYEEID", $attribute));
                        $data = GrabMoreData($query, $bind);
                        $data["SHIFTSTART"] = $data["SHIFTSTART"]*1000;
                        $data["END"] = $data["END"]*1000;
                        $data["BEGIN"] = $data["BEGIN"]*1000;
                        $_SESSION["SHIFTSTART"] = $data["SHIFTSTART"];
                        $_SESSION["SHIFTID"] = $data["SHIFTID"];
                        $_SESSION["SHIFTEND"] = $data["END"];
                        $_SESSION["SHIFTBEGIN"] = $data["BEGIN"];
                        echo json_encode($data);
                        return true;
                    }
                    else if (CheckExistExt($attribute, $table, $columnNew, $wherecolumn))
                    {
                        $now = time();
                        $query = 'SELECT SHIFTID, BEGIN, END FROM "CLOCK-IN" LEFT JOIN SHIFTS ON "CLOCK-IN".SHIFTID=SHIFTS.ID WHERE EMPLOYEEID = :EMPLOYEEID AND SHIFTEND IS NULL AND SHIFTSTART IS NULL';
                        $bind = array(array(":EMPLOYEEID", $attribute));
                        $data = GrabMoreData($query, $bind);
                        if ($data["BEGIN"]- 86400 < $now && $data["END"] + 86400 > $now)//3 hours before shift start
                        {
                            $_SESSION["SHIFTID"] = $data["SHIFTID"];
                            $_SESSION["SHIFTEND"] = $data["END"]*1000;
                            $_SESSION["SHIFTBEGIN"] = $data["BEGIN"]*1000;
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
                    $query = 'UPDATE "CLOCK-IN" SET SHIFTEND=:TIME WHERE SHIFTID = :ID';
                    $bind = array(array(":TIME",$timenow),array(":ID",$id));
                    $insert = InsertData($query, $bind);
                    if ($insert == "success")
                    {
                        $_SESSION["SHIFTEND"] = $timenow*1000;
                        echo "success";
                        return true;
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
                if($_SESSION["SHIFTBEGIN"] - 10800000 < $now //up to 3 hours before
                   && $_SESSION["SHIFTEND"] > $now 
                   || isset($_SESSION['SHIFTSTART']) 
                   && !empty($_SESSION['SHIFTSTART']))
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

///used to count the number of objects in an array
///INPUT $resultsItems: array containing data/objects
function countResults($resultsItems)
        {
            $num_rows=0;
            for ($i=0;$i < count($resultsItems); $i++) 
            {
                $num_rows++;
            }
            return $num_rows;
        }

///used to send a complaint email using the website, currently uses gmail account to send emails,
///grabs the data from the submitted form, then picks the data to generate a basic email sent to the ///email address of your choice, in this case my personal email address
function Emailer()
{
    if (session_id() == '')
    {
        session_start();
    }
    if (isset($_POST) && isset($_SESSION))
            {
                 if (!empty($_POST) && !empty($_SESSION) && !empty($_POST['ID']) && !empty($_SESSION['email']))
                    {
                        $account="dropitdeliveries@gmail.com";//source email, DO NOT CHANGE
                        $password="drop.itsupport";//source password, DO NOT CHANGE
                        $to="ze_yaya@msn.com";//recipient
                        $email = $_SESSION['email'];
                        $from = $email; //reply to email
                        $name = GrabData("users","name","email",  $email);
                        $name = $name[0]['name'];
                        $from_name= $name; //From name
                        $msg= htmlspecialchars($_POST['contents']); // HTML message
                        $subject="Complaint Delivery: " . $_POST['ID'];//email subject

                        $mail = new PHPMailer();
                        $mail->IsSMTP();
                        $mail->CharSet = 'UTF-8';
                        $mail->Host = "smtp.gmail.com";
                        $mail->SMTPAuth= true;
                        $mail->Port = 465;
                        $mail->Username= $account;
                        $mail->Password= $password;
                        $mail->SMTPSecure = 'ssl';
                        $mail->addReplyTo($email, $name);
                        $mail->FromName = $name;
                        $mail->isHTML(true);
                        $mail->Subject = $subject;
                        $mail->Body = $msg;
                        $mail->addAddress($to);
                        if(!$mail->send())
                        {
                            echo "Mailer Error: " . $mail->ErrorInfo;
                        }
                        else
                        {
                            echo "E-Mail has been sent";
                            header("Location: deliveries.php");
                        }
                 }
            }
}
//used to add the instant messaging system to a page when the required criterias are met
function AddChat()
{
    if (session_id() == '')
    {
        session_start();
    }
    if (isset($_SESSION))
    {
                 if (!empty($_SESSION) && !empty($_SESSION['EMAIL']))
                 {
                        echo '<div id=ChatSystem><div id="messageBox"><div id="id" class="chat"></div><form id="messageBoxForm" onsubmit="event.preventDefault()"><textarea onkeydown="SubmitFormEnter(event)" cols="40" rows="1" name="chatMessage" id="chat_input" placeholder="Type a message..." autocomplete="off" autocapitalize=off resize=off></textarea><input type="submit" id="submitText"><label title="Send message" id="submitMessageLabel" for="submitText"><svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" viewBox="0 0 55 55" x="0px" y="0px" width="55" height="55" version="1.1" xmlns:xml="http://www.w3.org/XML/1998/namespace" xml:space="preserve"><defs id="defs43"><linearGradient id="linearGradient5980" xmlns:osb="http://www.openswatchbook.org/uri/2009/osb" osb:paint="solid"><stop id="stop5978" style="stop-color: rgb(174, 174, 174); stop-opacity: 1;" offset="0" /></linearGradient></defs><g id="g8" transform="matrix(0.0661397 0 0 0.0661397 1.06883 11.809)"><g id="g6"><path id="path2" style="fill: none; fill-opacity: 0.995536; stroke: #2b2b2b; stroke-dasharray: none; stroke-miterlimit: 4; stroke-opacity: 1; stroke-width: 24.4788;" d="m 244.8 489.6 c 135 0 244.8 -109.8 244.8 -244.8 C 489.6 109.8 379.8 0 244.8 0 C 109.8 0 0 109.8 0 244.8 c 0 135 109.8 244.8 244.8 244.8 Z m 0 -469.8 c 124.1 0 225 100.9 225 225 c 0 124.1 -100.9 225 -225 225 c -124.1 0 -225 -100.9 -225 -225 c 0 -124.1 100.9 -225 225 -225 Z" /><path id="path4" style="fill: #767e88; fill-opacity: 1; stroke: #0063b1; stroke-dasharray: none; stroke-miterlimit: 4; stroke-opacity: 1; stroke-width: 24.4788;" d="m 210 326.1 c 1.9 1.9 4.5 2.9 7 2.9 c 2.5 0 5.1 -1 7 -2.9 l 74.3 -74.3 c 1.9 -1.9 2.9 -4.4 2.9 -7 c 0 -2.6 -1 -5.1 -2.9 -7 L 224 163.5 c -3.9 -3.9 -10.1 -3.9 -14 0 c -3.9 3.9 -3.9 10.1 0 14 l 67.3 67.3 l -67.3 67.3 c -3.8 3.9 -3.8 10.2 0 14 Z" /></g></g><g id="g10" transform="translate(0 -434.6)" /><g id="g12" transform="translate(0 -434.6)" /><g id="g14" transform="translate(0 -434.6)" /><g id="g16" transform="translate(0 -434.6)" /><g id="g18" transform="translate(0 -434.6)" /><g id="g20" transform="translate(0 -434.6)" /><g id="g22" transform="translate(0 -434.6)" /><g id="g24" transform="translate(0 -434.6)" /><g id="g26" transform="translate(0 -434.6)" /><g id="g28" transform="translate(0 -434.6)" /><g id="g30" transform="translate(0 -434.6)" /><g id="g32" transform="translate(0 -434.6)" /><g id="g34" transform="translate(0 -434.6)" /><g id="g36" transform="translate(0 -434.6)" /><g id="g38" transform="translate(0 -434.6)" /></svg></label></form></div><div id="contactsPart"><input type="checkbox" id="hideContacts"><label id="contactsButton" for="hideContacts"><svg xmlns="http://www.w3.org/2000/svg" id="Capa_1" viewBox="0 0 48 48" x="0px" y="0px" width="48" height="48" version="1.1" xmlns:xml="http://www.w3.org/XML/1998/namespace" xml:space="preserve"><defs id="defs37"></defs><path id="path2" style="stroke: #000000; stroke-dasharray: none; stroke-miterlimit: 4; stroke-opacity: 1; stroke-width: 0.4;" d="M 32.3384 29.1299 L 29.1539 27.5378 C 28.8536 27.3875 28.6669 27.0855 28.6669 26.7495 v -1.12706 c 0.07634 -0.09334 0.156675 -0.199676 0.239679 -0.317015 c 0.41302 -0.583363 0.744037 -1.23273 0.984716 -1.9331 C 30.3617 23.1566 30.667 22.6916 30.667 22.1666 v -1.3334 c 0 -0.321016 -0.120006 -0.632032 -0.33335 -0.875044 v -1.77309 c 0.01867 -0.183343 0.09201 -1.27473 -0.697368 -2.17511 c -0.684701 -0.781039 -1.79576 -1.17706 -3.30283 -1.17706 c -1.50707 0 -2.61813 0.39602 -3.30283 1.17673 c -0.478357 0.545694 -0.639365 1.16039 -0.688034 1.60175 c -0.571362 -0.295348 -1.24473 -0.445022 -2.00943 -0.445022 c -3.46317 0 -3.66485 2.95181 -3.66685 3.00015 v 1.52641 c -0.216011 0.235345 -0.33335 0.507025 -0.33335 0.776705 v 1.15139 c 0 0.359685 0.161008 0.695035 0.437022 0.921713 c 0.275014 1.03672 0.951381 1.82009 1.21473 2.0951 v 0.914379 c 0 0.262346 -0.142674 0.503025 -0.390353 0.638365 l -2.21778 1.39107 C 14.5272 30.045 13.9995 30.934 13.9995 31.9014 v 1.26573 h 4.6669 h 0.6667 h 14.6674 v -1.34773 c 0 -1.14606 -0.637032 -2.17678 -1.66208 -2.68947 Z M 18.6664 31.7544 v 0.746037 h -4.0002 v -0.59903 c 0 -0.723369 0.394686 -1.38807 1.04705 -1.74442 l 2.21744 -1.39107 c 0.444355 -0.242346 0.720369 -0.707035 0.720369 -1.21373 v -1.19706 l -0.106005 -0.099 c -0.0087 -0.008 -0.894378 -0.844709 -1.15606 -1.9851 l -0.03034 -0.132012 l -0.114006 -0.07334 c -0.153341 -0.09934 -0.245012 -0.26568 -0.245012 -0.444689 v -1.15139 c 0 -0.120006 0.08167 -0.26268 0.223678 -0.391353 l 0.109672 -0.09901 l -0.000667 -1.79342 c 0.006 -0.09567 0.179676 -2.35278 3.00082 -2.35278 c 0.797707 0 1.46941 0.184343 2.0001 0.548027 v 1.57708 c -0.213344 0.243012 -0.33335 0.554028 -0.33335 0.875044 v 1.3334 c 0 0.101338 0.01167 0.20101 0.03367 0.297682 c 0.009 0.03867 0.027 0.074 0.03933 0.111338 c 0.01833 0.056 0.033 0.113673 0.05867 0.166675 c 0.000333 0.000667 0.000667 0.001 0.001 0.0017 c 0.08534 0.176009 0.209677 0.33335 0.366352 0.459023 c 0.0017 0.0063 0.0037 0.012 0.0053 0.018 c 0.02 0.07634 0.041 0.152341 0.06367 0.226678 l 0.027 0.087 c 0.0047 0.01533 0.01033 0.031 0.01533 0.04634 c 0.01167 0.036 0.023 0.07167 0.035 0.107005 c 0.02 0.05834 0.041 0.118673 0.06534 0.184343 c 0.01033 0.02734 0.02167 0.052 0.03233 0.079 c 0.02734 0.06967 0.05467 0.137007 0.08334 0.203677 c 0.007 0.016 0.013 0.03334 0.02 0.049 l 0.01867 0.042 c 0.0087 0.01933 0.01767 0.03667 0.02633 0.05567 c 0.03267 0.07134 0.06467 0.14034 0.09801 0.20701 c 0.0053 0.01067 0.01033 0.02234 0.01567 0.033 c 0.021 0.04167 0.042 0.081 0.063 0.121006 c 0.036 0.06867 0.07134 0.13334 0.106672 0.19601 c 0.01733 0.03067 0.03433 0.06067 0.05134 0.08967 c 0.048 0.082 0.09367 0.157341 0.138007 0.227344 c 0.0097 0.015 0.019 0.03067 0.02834 0.045 c 0.08067 0.125006 0.150674 0.226344 0.208677 0.305348 c 0.01533 0.021 0.02867 0.039 0.04167 0.05667 c 0.0073 0.0097 0.01733 0.02367 0.02367 0.03233 v 1.10306 c 0 0.322683 -0.176009 0.618697 -0.459023 0.773372 l -0.882044 0.481024 l -0.153675 -0.01367 l -0.06267 0.131673 l -1.87543 1.02305 c -0.966712 0.528016 -1.56708 1.5394 -1.56708 2.64079 Z m 14.6674 0.746037 H 19.3331 v -0.746037 c 0 -0.857043 0.467357 -1.64475 1.21973 -2.05477 l 2.97381 -1.62208 c 0.497692 -0.27168 0.806707 -0.792706 0.806707 -1.35907 v -1.3394 v -0.000333 l -0.06467 -0.07734 l -0.01267 -0.015 c -0.000667 -0.001 -0.02134 -0.026 -0.055 -0.07 c -0.002 -0.0027 -0.004 -0.0053 -0.0063 -0.008 c -0.01767 -0.023 -0.03834 -0.05067 -0.062 -0.08367 c -0.000333 -0.000667 -0.000666 -0.001 -0.001 -0.0017 c -0.04967 -0.069 -0.112005 -0.158674 -0.181342 -0.26668 c -0.0017 -0.0023 -0.003 -0.005 -0.0047 -0.0073 c -0.03267 -0.051 -0.06734 -0.106672 -0.102672 -0.165675 c -0.0027 -0.0043 -0.0053 -0.0087 -0.008 -0.01333 c -0.07537 -0.126347 -0.155375 -0.269354 -0.235046 -0.427696 c 0 0 -0.000333 -0.000333 -0.000333 -0.000666 c -0.04234 -0.08501 -0.08467 -0.174342 -0.126007 -0.267347 v 0 c -0.0057 -0.013 -0.01167 -0.02567 -0.01733 -0.03867 v 0 c -0.01833 -0.04167 -0.03667 -0.08534 -0.05534 -0.130339 c -0.0067 -0.01633 -0.01333 -0.03334 -0.02 -0.05 c -0.01733 -0.04367 -0.035 -0.08767 -0.05367 -0.138007 c -0.034 -0.09067 -0.066 -0.185342 -0.09667 -0.283014 l -0.01833 -0.05934 c -0.002 -0.0067 -0.0043 -0.01333 -0.0063 -0.02033 c -0.03134 -0.105338 -0.06134 -0.21301 -0.08667 -0.323682 L 23.089 22.7989 L 22.9753 22.7256 C 22.7819 22.6009 22.6666 22.3919 22.6666 22.1666 v -1.3334 c 0 -0.187009 0.07934 -0.361351 0.223344 -0.491691 l 0.110006 -0.09901 V 18.1664 V 18.0484 l -0.009 -0.007 c -0.01133 -0.240679 0.003 -0.978383 0.541027 -1.59208 c 0.552361 -0.630365 1.49507 -0.949714 2.80147 -0.949714 c 1.30173 0 2.24244 0.317016 2.79547 0.942714 c 0.649033 0.733703 0.541694 1.67242 0.541027 1.68042 l -0.003 2.11977 l 0.110006 0.09934 c 0.144007 0.130007 0.223344 0.304349 0.223344 0.491358 v 1.3334 c 0 0.291015 -0.190676 0.545694 -0.474024 0.633032 l -0.166008 0.051 l -0.05334 0.165008 c -0.223011 0.693702 -0.540694 1.3344 -0.944714 1.90443 c -0.09901 0.14034 -0.195343 0.26468 -0.279014 0.359685 l -0.083 0.09467 v 1.37507 c 0 0.590029 0.327683 1.12039 0.855376 1.3844 l 3.18449 1.59208 c 0.79804 0.39902 1.29373 1.20106 1.29373 2.09344 Z" /><g id="g4" transform="translate(0 -12)" /><g id="g6" transform="translate(0 -12)" /><g id="g8" transform="translate(0 -12)" /><g id="g10" transform="translate(0 -12)" /><g id="g12" transform="translate(0 -12)" /><g id="g14" transform="translate(0 -12)" /><g id="g16" transform="translate(0 -12)" /><g id="g18" transform="translate(0 -12)" /><g id="g20" transform="translate(0 -12)" /><g id="g22" transform="translate(0 -12)" /><g id="g24" transform="translate(0 -12)" /><g id="g26" transform="translate(0 -12)" /><g id="g28" transform="translate(0 -12)" /><g id="g30" transform="translate(0 -12)" /><g id="g32" transform="translate(0 -12)" /></svg></label><div id="contacts_bar"></div></div></div>';
                 }
    }
}
?>