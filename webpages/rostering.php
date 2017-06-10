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
$weekEnd = strtotime("Sunday next week 11:59 pm");
$weekStart = strtotime("Monday next week 12:01 am");
$EnglishEnd = date('jS \of M Y',$weekEnd);
$EnglishStart = date('jS',$weekStart);

$weekEndC = strtotime("Sunday this week 11:59 pm");
$weekStartC = strtotime("Monday this week 12:01 am");
$EnglishEndC = date('jS \of M Y',$weekEndC);
$EnglishStartC = date('jS',$weekStartC);

$EMPID = $_SESSION['EMPID'];
$WORKINGSTORES = GrabAllData("SELECT ID, SUBURB, STATE FROM STORES WHERE MANAGER = :EMPID",array(array(":EMPID",$EMPID)));
$empty = true;
if (!empty($WORKINGSTORES['ID'])) {
    $WORKINGSTOREID = array_unique($WORKINGSTORES['ID']);
    $WORKINGSTORESSUB = array_unique($WORKINGSTORES['SUBURB']);
    $storeCount = count($WORKINGSTOREID);
    $empty = false;
}
$ALLEMPSbind = array();
$ALLEMPS = GrabAllData("SELECT EMPID ,F_NAME, L_NAME, STORE FROM EMPLOYEES WHERE 
INITIALSETUP != 3 AND TYPE != 'CEO' AND TYPE != 'MANAGER' OR
INITIALSETUP IS NULL AND TYPE != 'CEO' AND TYPE != 'MANAGER' ORDER BY EMPID ASC");
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
<link rel="stylesheet" media="none" href="rostering.css" onload="if(media!='all')media='all'">
<script type="application/javascript" src="../javascript/jquery-3.2.0.min.js"></script>
<script type="application/javascript" src="../javascript/rostering.js"></script>
<script type="application/javascript" src="../javascript/tablesaw/tablesaw.jquery.js"></script>
<script type="application/javascript" src="../javascript/tablesaw/tablesaw-init.js"></script>
<script type="application/javascript"><?php echo "Stores = ".json_encode($WORKINGSTORES).";"?></script>
</head>
<body>
<div class="rosterContent preload">
    <div class="roster boxContainer">
        <span class="rosterTitle noClick inlineBlock Active" data-week=false>Next<span class="englishTitle">: <?php echo "$EnglishStart to $EnglishEnd";?></span></span>
        <span class="rosterTitle noClick inlineBlock" data-week=true>Current<span class="englishTitle">: <?php echo "$EnglishStartC to $EnglishEndC";?></span></span>
<?php   if ($empty) {
    echo '<div class="na">You do not manage any stores</div>';
} else {
    foreach ($WORKINGSTOREID as $key => $value) {
        $inStore = array('EMPID' => array(), 'F_NAME' => array(), 'L_NAME' => array());
        $notStore = array('EMPID' => array(), 'F_NAME' => array(), 'L_NAME' => array());
        for ($e = 0; $e < count($ALLEMPS['EMPID']); $e++) {
            if ($ALLEMPS['STORE'][$e] == $value) {
                array_push($inStore['EMPID'],$ALLEMPS['EMPID'][$e]);
                array_push($inStore['F_NAME'],$ALLEMPS['F_NAME'][$e]);
                array_push($inStore['L_NAME'],$ALLEMPS['L_NAME'][$e]);
            } else {

                array_push($notStore['EMPID'],$ALLEMPS['EMPID'][$e]);
                array_push($notStore['F_NAME'],$ALLEMPS['F_NAME'][$e]);
                array_push($notStore['L_NAME'],$ALLEMPS['L_NAME'][$e]);
            }
        }
        echo "<div class='storesCont view'><div class='rosterOverlay displayNone'></div><div class='smallMenu' tabindex='-1'><a class='Menu3dots' href='#' tabindex='0'></a>
                <ul class='smallOptionsCont'>
                    <a class='smallOptionsLinks' clickValue='edit' href='#'><li class='smallOptions'>Edit Roster</li></a><a class='smallOptionsLinks' clickValue='send' href='#'><li class='smallOptions'>Send Roster</li></a><a class='smallOptionsLinks' clickValue='delete' href='#'><li class='smallOptions'>Delete Shifts</li></a></ul></div>";
        echo '<a href="#" class="saveShifts displayNone" value="'.$WORKINGSTOREID[$key].'">Save store</a><a href="#" class="TableLinkStores rosterStore"><span class="storeNameSpan">'.$WORKINGSTORESSUB[$key].'</span><form action="editStore.php" method="post"><input type="hidden" name="esid" value="'.$WORKINGSTOREID[$key].'"></form></a>';
        showNewRoster($inStore, $notStore);
        echo "</div><div class='storesDivider'></div>";
    };
//    $ALLEMPS = GrabAllData("SELECT EMPID ,F_NAME, L_NAME FROM EMPLOYEES WHERE STORE != :ID AND INITIALSETUP != 3 OR STORE != :ID AND INITIALSETUP IS NULL ORDER BY EMPID ASC", array(array(':ID', $value)));
}
         ?>
    </div>
</div>    