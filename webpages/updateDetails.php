<?php include "../functions/functions.php";
function addUpDate()
{
    $date = array();
    foreach ($_POST as $key => $value)
    {        
        if ($value)
        {
            if ((explode(":",$key)[0]) == "dob")
            {
                if ((explode(":",$key)[2]) == "day")
                {
                    $date["day"] = $value;
                    unset($_POST[$key]);
                }
                if ((explode(":",$key)[2]) == "month")
                {
                    $date["month"] = $value;
                    unset($_POST[$key]);
                }
                if ((explode(":",$key)[2]) == "year")
                {
                    $date["year"] = $value;
                    unset($_POST[$key]);
                }
            }
    
        }
    }
    if (count($date) == 3)
    {
        $_POST["date:employees:dob"] = $date;
        return true;
    }
}
function validateDate($date, $format = 'Y-m-d H:i:s')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}
    if (session_id() == '')
    {
      session_start();
    }
    addUpDate();
    $keys = array();
    $banking = array(array(':empID', $_SESSION["empID"]));
    $employees = array(array(':empID', $_SESSION["empID"]));
    $oneOrTwo = array(0,0);
    $errors = array();
    foreach ($_POST as $key => $value)
    {        
        if ($value)
        {
            if ((explode(":",$key)[0]) == "date")
            {
//                if(validateDate($value, 'Y-m-d'))
//                {
//                    $a = date_parse_from_format('Y-m-d', $value);
                    $value = date("d-M-y",mktime(0, 0, 0, $value['month'], $value['day'], $value['year']));
//                } 
//                else 
//                {
//                    array_push($errors,explode(":",$key)[2]);
//                }
            }
            if ((explode(":",$key)[0]) == "number")
            {
                
                if (!preg_match('/^[0-9]+$/',$value)) 
                {
                    array_push($errors,explode(":",$key)[2]); 
                } 
            }
            if ((explode(":",$key)[0]) == "string")
            {
                $value = filter_var($value, FILTER_SANITIZE_STRING);
            }
            array_push(${explode(":",$key)[1]},array(":".explode(":",$key)[2],htmlspecialchars ($value)));
            array_push($keys, explode(":",$key)[2]);
        }        

    }
    
    if (!empty($errors))
    {
        echo json_encode($errors);
        return false;
    }
    $queryUpdate = "UPDATE EMPLOYEES SET ";
    if (CheckExist("empID", "BANKING", "EMPID", $_SESSION))
    {
        $query = "update";
        $queryBanking = "UPDATE BANKING SET ";
    }
    else
    {
        $queryBanking = "INSERT INTO BANKING(EMPID,";
        $valueInsert = "VALUES(:empID,";
    }
    
    for ($i= 0; $i < count($keys); $i++)
    {
        if ($keys[$i] != "bsb" & $keys[$i] != "bankname" & $keys[$i] != "accnumber" & $keys[$i] != "tfn")
        {
            $queryUpdate .= strtoupper($keys[$i])." = :".$keys[$i].",";
            $oneOrTwo[0]=1;
        }

        else if($query == "update")
        {
            $queryBanking .= strtoupper($keys[$i])." = :".$keys[$i].",";
            $oneOrTwo[1]=1;
        }
        else
        {
            $queryBanking .= strtoupper($keys[$i]).","; 
            $valueInsert .=  ":".$keys[$i].",";
            $oneOrTwo[1]=1;
        }

    }
    if($query == "update")
    {
        $queryBanking = substr($queryBanking, 0,-1);
        $queryBanking .= " WHERE EMPID = :empID";
    }
    else
    {
        $valueInsert = substr($valueInsert, 0,-1).")";
        $queryBanking = substr($queryBanking, 0,-1).") ".$valueInsert;
    }
    $queryUpdate = substr($queryUpdate, 0,-1);
    $queryUpdate .= " WHERE EMPID = :empID";

    if ($oneOrTwo[0]==1)
    echo (InsertData($queryUpdate,$employees));
    if($oneOrTwo[1]==1)
    echo (InsertData($queryBanking,$banking));  
           

?>