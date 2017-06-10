<?php include 'tools.php';
if (session_id() == '')
    {
        session_start();
    }
 if(empty($_SESSION['EMPID'])) {
     return false;
 }
$EMPID = intval($_SESSION['EMPID']);
if (!empty($_POST['SHIFTID'])) {
    $ID = $_POST['SHIFTID'];
    if (CheckExistExt($ID, '"CLOCK-IN"', 'SHIFTID', 'SHIFTID'))  { 
        $state = $_POST['STATE'];
        $TZ = getTimeZone($state, true);
        date_default_timezone_set($TZ);
        $stm = 'UPDATE "CLOCK-IN" SET SHIFTSTART = NULL, SHIFTEND = NULL WHERE SHIFTID = :SHIFTID AND EMPLOYEEID = :EMPID';
        $bind = array(array(':SHIFTID', $ID), array(':EMPID', $EMPID));
        if (!empty($_POST['SHIFTBEGIN']) && !empty($_POST['SHIFTEND'])) {
            $oriDate = date('m/d/Y', GrabData('SHIFTS', 'BEGIN', 'ID', $ID)['BEGIN']);
            $start = strtotime($_POST['SHIFTBEGIN']." ".$oriDate);
            $end = strtotime($_POST['SHIFTEND']." ".$oriDate);
            
            $stm = 'UPDATE "CLOCK-IN" SET SHIFTSTART = :SSTART, SHIFTEND = :SEND WHERE SHIFTID = :SHIFTID AND EMPLOYEEID = :EMPID';
            $bind = array(array(':SSTART', $start), array(':SEND', $end), array(':SHIFTID', $ID), array(':EMPID', $EMPID));
        }
        echo InsertData($stm, $bind);
        return true;
        
    }
}