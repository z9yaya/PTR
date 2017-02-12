<?php include "../functions/functions.php";
$keys = array();
$account = array();
$banking = array();
$array;
foreach ($_POST as $key => $value)
{
    if ($value)
    {
       print_r(explode(":",$key)[1]);
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        if (substr($key, 12) != "bsb" & substr($key, 12) != "bankname" & substr($key, 12) != "accnumber" & substr($key, 12) != "tfn")
        {
            array_push($account, array(":".explode(":",$key)[2],$value));
        }
        else
        {
            array_push($banking, array(":".substr($key, 12),$value));
        }
        
    array_push($keys, substr($key, 12));
    }
}
print_r($account);
print_r($keys);
$queryUpdate = "UPDATE EMPLOYEES SET ";
$queryInsert = "INSERT INTO BANKING(EMPID,";
$valueInsert = "VALUES(:empID,";
for ($i= 0; $i < count($keys); $i++)
{
    print_r($keys[$i]);
    if ($keys[$i] != "bsb" & $keys[$i] != "bankname" & $keys[$i] != "accnumber" & $keys[$i] != "tfn")
    {
        $queryUpdate .= strtoupper($keys[$i])." = :".$keys[$i].",";
    }
    
    else
    {
        $queryInsert .= strtoupper($keys[$i]).","; 
        $valueInsert .=  ":".$keys[$i].",";
    }
    
}

$valueInsert = substr($valueInsert, 0,-1).")";
$queryInsert = substr($queryInsert, 0,-1).") ".$valueInsert;
$queryUpdate = substr($queryUpdate, 0,-1);
$queryUpdate .= " WHERE EMPID = :empID;";

//GrabMoreData($queryUpdate,              
//$queryInsert .= "INSERT INTO BANKING(";
print_r($queryUpdate);
print_r($queryInsert);
//ptr:account:f_name
//ptr:account:m_name
//ptr:account:l_name
//ptr:account:dob
//ptr:account:phone
//ptr:account:street1
//ptr:account:street2
//ptr:account:suburb
//ptr:account:postcode
//ptr:account:state
//ptr:account:tfn
//ptr:account:bankname
//ptr:account:bsb
//ptr:account:accnumber
//GrabMoreData($query, array(
//    array(':f_name', $f_name), 
//    array(':l_name', $l_name),
//    array(':l_name', $l_name),
//    array(':l_name', $l_name),
//    array(':l_name', $l_name),
//    array(':l_name', $l_name),
//    array(':l_name', $l_name),
//    array(':l_name', $l_name),
//    array(':l_name', $l_name),
//    array(':l_name', $l_name),
//))

?>