<?php 
include '../functions/functions.php';
if (session_id() == '')
    {
        session_start();
    }
 if(empty($_SESSION['EMPID'])) {
     header("Location: webpages/start.php");
 }

date_default_timezone_set('Australia/Brisbane');
$weekEnd = strtotime("Sunday this week 11:59 pm");
$weekStart = strtotime("Monday this week 12:01 am");
$EnglishEnd = date('jS \of M Y',$weekEnd);
$EnglishStart = date('jS',$weekStart);
if (date("H") > "18") {
    $dayOfWeek = date("N")+1;
} else {
    $dayOfWeek = date("N");
}
$EMPID = $_SESSION['EMPID'];
$WORKINGSTORES = GrabAllData("SELECT STOREID, SUBURB FROM CLOCKINSHIFTS LEFT JOIN STORES ON STOREID = STORES.ID WHERE EMPLOYEEID = :EMPID AND BEGIN > :WEEKSTART AND END < :WEEKEND", array(array(':EMPID', $EMPID),array(':WEEKSTART', $weekStart),array(':WEEKEND', $weekEnd)));
$empty = true;
if (!empty($WORKINGSTORES['STOREID'])) {
    $WORKINGSTOREID = array_unique($WORKINGSTORES['STOREID']);
    $WORKINGSTORES = array_unique($WORKINGSTORES['SUBURB']);
    $storeCount = count($WORKINGSTOREID);
    $empty = false;
}
//$WORKINGSTOREID = $WORKINGSTORES['STOREID'];
//$SHIFTS = GrabAllData("SELECT EMPID ,F_NAME, L_NAME, END, BEGIN FROM CLOCKINSHIFTS LEFT JOIN EMPLOYEES ON CLOCKINSHIFTS.EMPLOYEEID = EMPLOYEES.EMPID WHERE STOREID = :ID AND BEGIN > :WEEKSTART AND END < :WEEKEND ORDER BY BEGIN ASC", array(array(':ID', $STOREID),array(':WEEKSTART', $weekStart),array(':WEEKEND', $weekEnd)));
//print_r($SHIFTS);

?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="../javascript/tablesaw/tablesaw.css">
<link rel="stylesheet" media="none" href="roster.css" onload="if(media!='all')media='all'">
<script type="application/javascript" src="../javascript/jquery-3.2.0.min.js"></script>
<script type="application/javascript" src="../javascript/tablesaw/tablesaw.jquery.js"></script>
<script type="application/javascript" src="../javascript/tablesaw/tablesaw-init.js"></script>
</head>
<body>
<div class="rosterContent preload">
    <div class="roster boxContainer">
        <span class="rosterTitle">Current: <?php echo "$EnglishStart to $EnglishEnd";?></span>
<?php   if ($empty) {
    echo '<div class="na">No shifts this week</div>';
} else {
    foreach ($WORKINGSTOREID as $key => $value) {
        $SHIFTS = GrabAllData("SELECT EMPID ,F_NAME, L_NAME, END, BEGIN FROM CLOCKINSHIFTS LEFT JOIN EMPLOYEES ON CLOCKINSHIFTS.EMPLOYEEID = EMPLOYEES.EMPID WHERE STOREID = :ID AND BEGIN > :WEEKSTART AND END < :WEEKEND ORDER BY BEGIN ASC", array(array(':ID', $value),array(':WEEKSTART', $weekStart),array(':WEEKEND', $weekEnd)));
        $res = createRoster($SHIFTS);
        $shiftArr = $res[0];
        $days = $res[1];
        echo "<span class='rosterStore'>{$WORKINGSTORES[$key]} Store</span>";
        showRoster($SHIFTS, $shiftArr, $days, $dayOfWeek);
    };
}
         ?>
    </div>
</div>    