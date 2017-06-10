<?php include 'tools.php';
//print_r($_POST);
if (!empty($_POST['empID'])) {
    $employeeID = $_POST['empID'];
$REPORTS = GrabAllData('SELECT EMPLOYEES.EMPID, F_NAME, M_NAME, L_NAME, DOB, EMAIL, PHONE, STREET1, STREET2, SUBURB, POSTCODE, STATE, LEAVE, LEAVETAKEN, SICKLEAVE, SICKLEAVETAKEN, WEEK, POSITION, RATE, EMPSTART, EMPEND, EMPTYPE, GROSS, NET, TAX, STORE, TFN, BSB, ACCNUMBER, BANKNAME, GENERATED, TOTALHOURS, 
REGHOURS, PUBLICHOURS, SUNDAYHOURS, PAYDATE, OVERTIME, EMPLOYMENT.TYPE
FROM EMPLOYEES 
LEFT JOIN ACCUMULATION ON EMPLOYEES.EMPID = ACCUMULATION.EMPID 
LEFT JOIN BANKING ON EMPLOYEES.EMPID = BANKING.EMPID
LEFT JOIN EMPLOYMENT ON EMPLOYEES.EMPID = EMPLOYMENT.EMPID
LEFT JOIN PAY ON EMPLOYEES.EMPID = PAY.EMPID
WHERE EMPLOYEES.EMPID = :EMPID
ORDER BY EMPLOYEES.EMPID', array(array(':EMPID', $employeeID)));
//print_r($REPORTS);

$check = $REPORTS['GENERATED'][(count($REPORTS['GENERATED'])-1)];
$TESTQUERY = 'SELECT SHIFTSTART, SHIFTEND FROM "CLOCK-IN" WHERE EMPLOYEEID = :EMPID AND SHIFTSTART < :SHIFTCHECK';
$TESTBIND = array(array(':EMPID', $employeeID),array(':SHIFTCHECK', $check));
$TEST = GrabAllData($TESTQUERY, $TESTBIND);
//print_r($TEST);
    
$empstart = date("d/m/Y",$REPORTS['EMPSTART'][0]);
$todaydate = date("d/m/Y");

$dob = date("d/m/Y",$REPORTS['DOB'][0]);
$empstart = date("d/m/Y",$REPORTS['EMPSTART'][0]);
if (!empty($REPORTS['EMPEND'][0])){
$empend = date("d/m/Y",$REPORTS['EMPEND'][0]);
} else{
    $empend = "Not Applicable";
}

 if($REPORTS['TYPE'][0] == "HOURLY"){
    $rate = '$'.sprintf("%.2f",$REPORTS['RATE'][0]).' per hour';    
 } elseif($REPORTS['TYPE'][0] == "SALARY"){
    $rate = '$'.sprintf("%.2f",$REPORTS['RATE'][0]).' per week';    
 }

$grossTotal = 0;
$netTotal = 0;
$taxTotal = 0;
$hoursTotal = 0;
for($i=0;$i<count($REPORTS['EMPID']);$i++){
    $grossTotal += $REPORTS['GROSS'][$i];
    $netTotal += $REPORTS['NET'][$i];
    $taxTotal += $REPORTS['TAX'][$i];
    $hoursTotal += $REPORTS['TOTALHOURS'][$i];
    };
$grossTotal = '$'.sprintf("%.2f",$grossTotal);
$netTotal = '$'.sprintf("%.2f",$netTotal);
$taxTotal = '$'.sprintf("%.2f",$taxTotal);

$averageWorked = 0;
if (!empty($TEST['SHIFTSTART'])){
$averageWorked = $hoursTotal / count($TEST['SHIFTSTART']);
}
$employeeID = "E".str_pad($_POST['empID'], 7, "0", STR_PAD_LEFT);
$dataArray = array (
'Report' =>
'Employee Information',
'empID' =>
$employeeID,
'dateRange' =>
"$empstart - $todaydate",
//'03/09/2016 - 06/06/2017',
'f_name' => 
$REPORTS['F_NAME'][0],
'm_name' => 
$REPORTS['M_NAME'][0],
'l_name' => 
$REPORTS['L_NAME'][0],
'dob' => 
$dob,
'email' => 
$REPORTS['EMAIL'][0],
'phone' => 
str_pad($REPORTS['PHONE'][0], 10, '0', STR_PAD_LEFT),
'address' => 
$REPORTS['STREET1'][0]." ".$REPORTS['STREET2'][0],
'suburb' => 
$REPORTS['SUBURB'][0],
'postcode' => 
$REPORTS['POSTCODE'][0],
'state' => 
$REPORTS['STATE'][0],
'tfn' => 
$REPORTS['TFN'][0],
'bank' => 
$REPORTS['BANKNAME'][0],
'bsb' => 
$REPORTS['BSB'][0],
'acc' => 
$REPORTS['ACCNUMBER'][0],
'position' => 
$REPORTS['POSITION'][0],
'type' => 
$REPORTS['EMPTYPE'][0],
'rate' => 
$rate,
'empstart' => 
$empstart,
'empend' => 
$empend,
'store' => 
$REPORTS['STORE'][0],
'week_worked' => 
$REPORTS['WEEK'][0],
'gross' => 
$grossTotal,
'net' => 
$netTotal,
'tax' => 
$taxTotal,
'total_shifts' => 
count($TEST['SHIFTSTART']),
'avg_shift' => 
$averageWorked,
'shift_skipped' => 
'0',
'total_hours' => 
$hoursTotal,
'shift_start_late' => 
'0',
'shift_start_early' => 
'0',
'shift__finish_late' => 
'0',
'shift_finish_early' => 
'0',
'leave_total' => 
$REPORTS['LEAVE'][0],
'leave_taken' => 
$REPORTS['LEAVETAKEN'][0],
'sick_taken' => 
$REPORTS['SICKLEAVETAKEN'][0],
'sick_left' => 
$REPORTS['SICKLEAVE'][0],
'overtime' => 
$REPORTS['OVERTIME'][0]);
    if(!empty($_POST['type'])) {
        $type = $_POST['type'];
        if ($_POST['type'] == 'employee_pay') {
            $dataArray['Report']= 'Employment Information';
        }
    }
FillPDF($dataArray, $type, null, $employeeID, true);
} 

?>