<?php 
include '../functions/functions.php';
if (session_id() == '')
    {
        session_start();
    }
 if(empty($_SESSION['EMPID'])) {
     header("Location: webpages/start.php");
 }
header("Content-Security-Policy: frame-ancestors 'self'");
if (empty(getallheaders()['Referer'])) {
    header("Location: ../index.php");
} 

date_default_timezone_set('Australia/Brisbane');
$weekEnd = strtotime("Sunday this week 11:59 pm");
$weekStart = strtotime("Monday this week 12:01 am");
$nextweekEnd = strtotime("Sunday next week 11:59 pm");
$nextweekStart = strtotime("Monday next week 12:01 am");
$EnglishEnd = date('jS \of M Y',$weekEnd);
$EnglishStart = date('jS',$weekStart);
$EnglishnEnd = date('jS \of M Y',$nextweekEnd);
$EnglishnStart = date('jS',$nextweekStart);
if (date("H") > "18") {
    $dayOfWeek = date("N")+1;
} else {
    $dayOfWeek = date("N");
}
$EMPID = $_SESSION['EMPID'];
$WORKINGSTORESORI = GrabAllData("SELECT STOREID, SUBURB, STATE, BEGIN, END, SHIFTSTART, SHIFTEND, SHIFTID  FROM CLOCKINSHIFTS LEFT JOIN STORES ON STOREID = STORES.ID WHERE EMPLOYEEID = :EMPID AND BEGIN > :WEEKSTART AND END < :WEEKEND", array(array(':EMPID', $EMPID),array(':WEEKSTART', $weekStart),array(':WEEKEND', $weekEnd)));
$WORKINGSTORESn = GrabAllData("SELECT STOREID, SUBURB, STATE FROM CLOCKINSHIFTS LEFT JOIN STORES ON STOREID = STORES.ID WHERE EMPLOYEEID = :EMPID AND BEGIN > :NWEEKSTART AND END < :NWEEKEND", array(array(':EMPID', $EMPID),array(':NWEEKSTART', $nextweekStart),array(':NWEEKEND', $nextweekEnd)));
$empty = true;
$emptyn = true;
if (!empty($WORKINGSTORESORI['STOREID'])) {
    $WORKINGSTOREID = array_unique($WORKINGSTORESORI['STOREID']);
    $WORKINGSTATES = array_unique($WORKINGSTORESORI['STATE']);
    $WORKINGSTORES = array_unique($WORKINGSTORESORI['SUBURB']);
    $storeCount = count($WORKINGSTOREID);
    $empty = false;
}if (!empty($WORKINGSTORESn['STOREID'])) {
    $WORKINGSTOREIDn = array_unique($WORKINGSTORESn['STOREID']);
    $WORKINGSTATESn = array_unique($WORKINGSTORESn['STATE']);
    $WORKINGSTORESn = array_unique($WORKINGSTORESn['SUBURB']);
    $storeCountn = count($WORKINGSTOREIDn);
    $emptyn = false;
}
$prev = '';
for($i = 0; $i < count($WORKINGSTORESORI['SHIFTID']); $i++) {
    if (!empty($WORKINGSTORESORI['END'][$i]) && $WORKINGSTORESORI['END'][$i] < time()) {
    //$prev .= "<div class='doneShift'><div><div class='doneDay'>".date("l",$WORKINGSTORESORI['BEGIN'][$i]).": <span class='weightNormal'>".date("H:i",$WORKINGSTORESORI['BEGIN'][$i])." - ".date("H:i",$WORKINGSTORESORI['END'][$i])."</span></div><div class='shiftTime'>Logged: ".date("H:i",$WORKINGSTORESORI['SHIFTSTART'][$i])." - ".date("H:i",$WORKINGSTORESORI['SHIFTEND'][$i])."</div></div></div>";
        $startH = '';
        $startM = '';
        $endH = '';
        $endM = '';
        if (!empty($WORKINGSTORESORI['SHIFTSTART'][$i])) {
            $startH = date("H",$WORKINGSTORESORI['SHIFTSTART'][$i]);
            $startM = date("i",$WORKINGSTORESORI['SHIFTSTART'][$i]);
        }
        if (!empty($WORKINGSTORESORI['SHIFTEND'][$i])) {
            $endH = date("H",$WORKINGSTORESORI['SHIFTEND'][$i]);
            $endM = date("i",$WORKINGSTORESORI['SHIFTEND'][$i]);
        }
    $prev .= "<div class='doneShift'>
    <div class='editCont'>
        <div class='doneDay'>".date("l",$WORKINGSTORESORI['BEGIN'][$i]).": <span class='weightNormal formCont'><form class='form'><input class='doneShiftID' type='hidden' value='".$WORKINGSTORESORI['SHIFTID'][$i]."'><input class='doneStoreState' type='hidden' value='".$WORKINGSTORESORI['STATE'][$i]."'><input class='input numb start H' name='startH' type='text' maxlength=2 min-length=2  value='".$startH."' disabled>:<input class='input numb start M' type='text' name='startM' maxlength=2 min-length=2  value='".$startM."' disabled> - <input class='input numb end H' name='endH' type='text' maxlength=2 min-length=2 value='".$endH."' disabled>:<input class='input numb end M' name='endM' type='text' maxlength=2 min-length=2  value='".$endM."' disabled>
                </form><div class='edit_bttn' tabindex='0' title='Edit'></div><a href='#' class='saveShifts displayNone'>Save shift</a><a href='#' class='cancel displayNone' title='Cancel changes'></a>
            </span>
        </div>
        <div class='shiftTime'>Rostered: ".date("H:i",$WORKINGSTORESORI['BEGIN'][$i])." - ".date("H:i",$WORKINGSTORESORI['END'][$i])."</div>
    </div>
</div>";
    }
}
//$WORKINGSTOREID = $WORKINGSTORES['STOREID'];
//$SHIFTS = GrabAllData("SELECT EMPID ,F_NAME, L_NAME, END, BEGIN FROM CLOCKINSHIFTS LEFT JOIN EMPLOYEES ON CLOCKINSHIFTS.EMPLOYEEID = EMPLOYEES.EMPID WHERE STOREID = :ID AND BEGIN > :WEEKSTART AND END < :WEEKEND ORDER BY BEGIN ASC", array(array(':ID', $STOREID),array(':WEEKSTART', $weekStart),array(':WEEKEND', $weekEnd)));
//print_r($SHIFTS);

?>
<!DOCTYPE html>
<html>
<head>
    <script>
        if(top==self){
            window.location.href = "../404.html";
        }
    </script>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="../javascript/tablesaw/tablesaw.css">
<link rel="stylesheet" media="none" href="roster.css" onload="if(media!='all')media='all'">
<script type="application/javascript" src="../javascript/jquery-3.2.0.min.js"></script>
<script type="application/javascript" src="../javascript/tablesaw/tablesaw.jquery.js"></script>
<script type="application/javascript" src="../javascript/tablesaw/tablesaw-init.js"></script>
<script type="application/javascript" src="../javascript/roster.js"></script>
</head>
<body>
<div class="rosterContent preload">
    <div class="boxContainer logged">
        <span class="rosterTitle">Logged time this week</span>
        <?php echo $prev; ?>
    </div>
    <div class="roster boxContainer">
        <span class="rosterTitle">Current: <?php echo "$EnglishStart to $EnglishEnd";?></span>
<?php   if ($empty) {
    echo '<div class="na">No shifts this week</div>';
} else {
    $noOne = false;
    foreach ($WORKINGSTOREID as $key => $value) {
        $SHIFTS = GrabAllData("SELECT EMPID ,F_NAME, L_NAME, END, BEGIN FROM CLOCKINSHIFTS RIGHT JOIN EMPLOYEES ON CLOCKINSHIFTS.EMPLOYEEID = EMPLOYEES.EMPID WHERE STOREID = :ID AND BEGIN > :WEEKSTART AND END < :WEEKEND OR STORE = :ID AND BEGIN IS NULL AND END IS NULL ORDER BY EMPID ASC", array(array(':ID', $value),array(':WEEKSTART', $weekStart),array(':WEEKEND', $weekEnd)));
        if (empty($SHIFTS['EMPID']) && !$noOne) {
             echo '<div class="na">No shifts this week</div>';
            $noOne = true;
            continue;
        } elseif ($noOne) {
            continue;
        }
        date_default_timezone_set(getTimeZone($WORKINGSTATES[$key], true));
        $res = createRoster($SHIFTS);
        $shiftArr = $res[0];
        $days = $res[1];
        echo "<span class='rosterStore'>{$WORKINGSTORES[$key]} Store</span>";
        echo showRoster($SHIFTS, $shiftArr, $days, $dayOfWeek);
    };
}
         ?>
    </div>
    <div class="roster boxContainer">
        <span class="rosterTitle">Next: <?php echo "$EnglishnStart to $EnglishnEnd";?></span>
<?php   if ($emptyn) {
    echo '<div class="na">No shifts next week</div>';
} else {
    $noTwo = false;
    foreach ($WORKINGSTOREIDn as $key => $value) {
        $SHIFTS = GrabAllData("SELECT EMPID ,F_NAME, L_NAME, END, BEGIN FROM CLOCKINSHIFTS RIGHT JOIN EMPLOYEES ON CLOCKINSHIFTS.EMPLOYEEID = EMPLOYEES.EMPID WHERE STOREID = :ID AND BEGIN > :WEEKSTART AND END < :WEEKEND OR 
        STORE = :ID AND BEGIN IS NULL AND END IS NULL ORDER BY EMPID ASC", array(array(':ID', $value),array(':WEEKSTART', $nextweekStart),array(':WEEKEND', $nextweekEnd)));
        if (empty($SHIFTS['EMPID']) && !$noTwo) {
             echo '<div class="na">No shifts next week</div>';
            $noTwo = true;
            continue;
        } elseif ($noTwo) {
            continue;
        }
        date_default_timezone_set(getTimeZone($WORKINGSTATESn[$key], true));
        $res = createRoster($SHIFTS);
        $shiftArr = $res[0];
        $days = $res[1];
        echo "<span class='rosterStore'>{$WORKINGSTORESn[$key]} Store</span>";
        echo showRoster($SHIFTS, $shiftArr, $days, $dayOfWeek);
    };
}
         ?>
    </div>
</div>  
    <script type="application/javascript">parent.PageLoader(false);</script>
</body>
</html>