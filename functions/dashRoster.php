<?php include 'functions.php';
function fetchSingleRoster () {
    $storeId = $_POST['STORE'];
    date_default_timezone_set('Australia/Brisbane');
    $weekEnd = strtotime("Sunday this week 11:59 pm");
    $weekStart = strtotime("Monday this week 12:01 am");
    if (date("H") > "18") {
        $dayOfWeek = date("N")+1;
    } else {
        $dayOfWeek = date("N");
    }
    $EMPID = $_SESSION['EMPID'];
    $storeDet = GrabMoreData('SELECT ID SUBURB, STATE FROM STORES JOIN EMPLOYEES ON STORES.ID = EMPLOYEES.STORE WHERE EMPID = :EMPID', array(array(":EMPID", $EMPID)));
    $storeId = $storeDet['ID'];
    $storeName = $storeDet['SUBURB'];
    date_default_timezone_set(getTimeZone($storeDet));
    $weekEnd = strtotime("Sunday this week 11:59 pm");
    $weekStart = strtotime("Monday this week 12:01 am");
    $SHIFTS = GrabAllData("SELECT END, BEGIN FROM CLOCKINSHIFTS RIGHT JOIN EMPLOYEES ON CLOCKINSHIFTS.EMPLOYEEID = EMPLOYEES.EMPID WHERE STOREID = :ID AND EMPLOYEEID = :EMPID AND BEGIN > :WEEKSTART AND END < :WEEKEND OR STORE = :ID AND EMPLOYEEID = :EMPID AND BEGIN IS NULL AND END IS NULL ORDER BY BEGIN ASC", array(array(':ID', $storeId),array(':WEEKSTART', $weekStart),array(':WEEKEND', $weekEnd),array(':EMPID', $EMPID))); 
    $empty = 0;
//    foreach ($SHIFTS['BEGIN'] as $k => $v)
//    {
//        if (empty($v)) {
//            $empty++;
//        }
//    }
//    if (count($SHIFTS['BEGIN']) == $empty) {
//        echo 'noEmp';
//        return false;
//    }
    //$sendTo = array_unique($SHIFTS['EMAIL']);
    $res = createRoster($SHIFTS);
    $shiftArr = $res[0];
    $days = $res[1];
    echo showRoster($SHIFTS, $shiftArr, $days, $dayOfWeek, 'emp')
}