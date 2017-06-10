<?php include 'functions.php'; include 'emailer.php';
if (!empty($_POST['STORE'])) {
    $storeId = $_POST['STORE'];
    $weekC = 'next';
    if ($_POST['WEEK'] == 'true') {
        $weekC = 'this';
    } 
//$storeId = 1;
    $storeDet = GrabMoreData('SELECT SUBURB, STATE FROM STORES WHERE ID = :storeId', array(array(":storeId", $storeId)));
    $storeName = $storeDet['SUBURB'];
    date_default_timezone_set(getTimeZone($storeDet));
    $weekEnd = strtotime("Sunday $weekC week 11:59 pm");
    $weekStart = strtotime("Monday $weekC week 12:01 am");
    $EnglishEnd = date('jS \of M Y',$weekEnd);
    $EnglishStart = date('jS \of M Y',$weekStart);
    $rosterDate =  "$EnglishStart - $EnglishEnd";
    $EMPS = GrabAllData("SELECT EMPID, EMAIL, F_NAME, L_NAME FROM EMPLOYEES WHERE STORE = :ID ORDER BY EMPID ASC", array(array(':ID', $storeId))); 
    $SHIFTS = GrabAllData("SELECT EMPID, EMAIL, F_NAME, L_NAME, END, BEGIN FROM CLOCKINSHIFTS RIGHT JOIN EMPLOYEES ON CLOCKINSHIFTS.EMPLOYEEID = EMPLOYEES.EMPID WHERE STOREID = :ID AND BEGIN > :WEEKSTART AND END < :WEEKEND OR STORE = :ID AND BEGIN IS NULL AND END IS NULL ORDER BY EMPID ASC", array(array(':ID', $storeId),array(':WEEKSTART', $weekStart),array(':WEEKEND', $weekEnd))); 
    $empty = 0;
    foreach ($SHIFTS['EMPID'] as $k => $v) {
        if (!in_array($v, $EMPS['EMPID'])) {
            array_push($EMPS['EMAIL'], $SHIFTS['EMAIL'][$k]);
            array_push($EMPS['F_NAME'], $SHIFTS['F_NAME'][$k]);
            array_push($EMPS['L_NAME'], $SHIFTS['L_NAME'][$k]);
            array_push($EMPS['EMPID'], $v);
        }
    }
    foreach ($EMPS['EMPID'] as $k => $v) {
        if (!in_array($v, $SHIFTS['EMPID'])) {
            array_push($SHIFTS['EMAIL'], $EMPS['EMAIL'][$k]);
            array_push($SHIFTS['F_NAME'], $EMPS['F_NAME'][$k]);
            array_push($SHIFTS['L_NAME'], $EMPS['L_NAME'][$k]);
            array_push($SHIFTS['EMPID'], $v);
            array_push($SHIFTS['END'], '');
            array_push($SHIFTS['BEGIN'], '');
        }
    }
    foreach ($SHIFTS['BEGIN'] as $k => $v)
    {
        if (empty($v)) {
            $empty++;
        }
    }
    if (count($SHIFTS['BEGIN']) == $empty) {
        echo 'noEmp';
        return false;
    }
    //$sendTo = array_unique($SHIFTS['EMAIL']);
    $sendTo = $EMPS['EMAIL'];
    if (empty($sendTo)) {
        echo 'noEmp';
        return false;
    }
    $res = createRoster($SHIFTS);
    $shiftArr = $res[0];
    $days = $res[1];
    $data = showPDFRoster($SHIFTS, $shiftArr, $days, $rosterDate, $storeName);
    $resLocation = writePdf($rosterDate, $storeId, $data);
    if ($resLocation != "C:\Users\Administrator\Documents\\rosters\\".$storeId."\\".$rosterDate.".pdf") {
        echo 'error';
        return false;
    }
    $message = "Your manager has finalised the roster for the $storeName store, you will find it attached to this email.";
    if ($_POST['WEEK']) {
        $message = "Your manager has made changes to this week's roster for the $storeName store, you will find it attached to this email.";
    }
    
    $email = Emailer($sendTo, $message, "Roster for $storeName: $rosterDate", $fromEmail = null, $fromName = null, $sendingName = null, $resLocation, "$rosterDate.pdf", $greeting = "Hello,", $html = null);
    if ($email == '202') {
        echo "success";
        return true;
    }
}