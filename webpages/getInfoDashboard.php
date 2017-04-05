<?php
include "../functions/functions.php";
if (session_id() == '')
    {
        session_start();
    }
$data   = checkShift();
$holidaysleft = GrabMoreData("SELECT LEAVE FROM ACCUMULATION WHERE EMPID = :EMPID", array(array(":EMPID", $_SESSION['EMPID'])));
$submit = array();
if (!empty($data["SHIFTSTART"])) {
    $submit['startTime'] = $data["SHIFTSTART"];
} else {
    $submit['startTime'] = 0;
}
if (!empty($data["SHIFTEND"])) {
    $submit['stopTime'] = $data["SHIFTEND"];
} else {
    $submit['stopTime'] = 0;
}
if (!empty($data["BEGIN"])) {
    $submit['ShiftStart'] = $data["BEGIN"];
} else {
    $submit['ShiftStart'] = 0;
}
if (!empty($data["END"])) {
    $submit['ShiftEnd'] = $data["END"];
} else {
    $submit['ShiftEnd'] = 0;
}
if(!empty($holidaysleft['LEAVE']))
{
    $submit['holidaysLeft'] = $holidaysleft['LEAVE'];
} else {
    $submit['holidaysLeft'] = 0;
}
echo json_encode($submit);
?>


