<?php 
include '../../functions/functions.php';
include '../../functions/emailer.php';
if (session_id() == '')
    {
        session_start();
    }
if(empty($_SESSION['EMPID']))
    header("Location: ../start.php");

$empID = $_SESSION['EMPID'];

$holidaysleft = GrabMoreData("SELECT LEAVE FROM ACCUMULATION WHERE EMPID = :EMPID", array(array(":EMPID", $empID)))["LEAVE"];

$status = "Pending";
$datainsert = '';

$holileftup = GrabMoreData("SELECT HOLILEFT FROM ACCUMULATION WHERE EMPID = :EMPID", array(array(":EMPID", $empID)));
$leaveleft = $holileftup['HOLILEFT'];
//echo $leaveleft;
//echo "<br>";

//$datediff = (($holidaysend - $holidaystart) / 86400) + 1;
//echo $datediff;

if(!empty($_POST['comment']))
{
    $red = $_POST['comment'];
}	

$empname = GrabMoreData("SELECT F_NAME, M_NAME, L_NAME, STORE FROM EMPLOYEES WHERE EMPID = :EMPID", array(array(":EMPID", $empID)));
$empfirst = $empname['F_NAME'];
$empmiddle = $empname['M_NAME'];
$emplast = $empname['L_NAME'];
$empstore = $empname['STORE'];



$empmanager = GrabAllData("SELECT EMPLOYEES.EMAIL FROM EMPLOYEES INNER JOIN STORES ON STORES.MANAGER = EMPLOYEES.EMPID WHERE STORE = $empstore");
$empmanageremail = $empmanager['EMAIL'];
//print_r($empmanageremail);

?>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="holidayrequest.css" rel="stylesheet" media="none" onload="if(media!='all')media='all'">
<script type="application/javascript" src="../../javascript/header.js"></script>
<script type="application/javascript">head.load("../../javascript/modernizr-inputs.js",function(){
    if (!Modernizr.inputtypes.date) {
        console.log(Modernizr.touch);
        console.log(Modernizr.inputtypes.date);
        
        head.load("../../javascript/jquery-ui-1.12.1/jquery-ui.min.css");
        head.load("../../javascript/jquery-ui-1.12.1/jquery-ui.min.js",function(){
            head.load("holidayrequest.js");});
    };
      <?php ?>
    }
)</script>
<script type="application/javascript" src='../../javascript/jquery-3.2.0.min.js'></script>
<script type="application/javascript" src='../../javascript/messagePopUp.js'>
</script>
    <?php 
    if(!empty($_POST))
{
$startdate = (isset($_POST['holidaystart'])) ? $_POST['holidaystart'] : "not";
$holidaystart = DatetoUnix($startdate); 
//$holidaystart = strtotime($startdate); 
$startdatedmy = date("d/m/Y", DatetoUnix($startdate));       
$enddate = (isset($_POST['holidaysend'])) ? $_POST['holidaysend'] : "not";
$holidaysend = DatetoUnix($enddate); 
$enddatedmy = date("d/m/Y", DatetoUnix($enddate));      
$datediff = ((($holidaysend - $holidaystart) / 86400) + 1) * 8;
$boo = $leaveleft - $datediff;     
//echo $datediff;        
//echo $boo;        
    if ($boo >= 0 && $holidaystart <= $holidaysend && !empty($_POST['comment'])) {
        $reason = $_POST['comment'];
        
        $datainsert = InsertData("INSERT INTO HOLIDAYREQUEST(EMPID,STARTDATE,ENDDATE,STATUS) VALUES (:EMPID,:STARTDATE,:ENDDATE,:STATUS)", array(array(":EMPID", $empID),array(":STARTDATE", $holidaystart),array(":ENDDATE", $holidaysend),array(":STATUS", $status)));   
    
        $leavetakenup = InsertData("UPDATE ACCUMULATION SET LEAVETAKEN = LEAVETAKEN + :DATEDIFF WHERE EMPID = :EMPID", array(array(":EMPID", $empID),array(":DATEDIFF", $datediff)));
        
        $holileftup = InsertData("UPDATE ACCUMULATION SET HOLILEFT = :HOLUP WHERE EMPID = :EMPID", array(array(":HOLUP", $boo),array(":EMPID", $empID)));
        
        if($datainsert === 'success') echo '<script type="application/javascript">window.opener.location.reload(true);$(document).ready(function() {showPopUp(); hidePopUp(); setTimeout(function(){  window.close();  }, 3500)})</script>';
        
        Emailer(array('email' => $empmanageremail), "The following employee requests a leave of absence: <br><br> Employee Name: $empfirst $empmiddle $emplast <br> Employee ID: $empID <br><br> Leave for the period starting: $startdatedmy and ending: $enddatedmy <br><br> For reason stated: $reason <br><br> Login to accept or deny the request.", "Employee requests leave ", null, null , null , null, null, null, null);     
        
    } else {
            echo "<script type='application/javascript'>$(document).ready(function() {showPopUp(); AddMessagePopUp('The length of duration requested is too long or invalid.', 'Error', 'showNotice');})</script>";
    }   
}  
    ?>
</head>
<body>
    <div id="timerStopPageOverlay" class="timerStopNormal">
                <div class="popUpContainer">
                    <div class="questionLabel"></div>
                    <div class="answerContainer">
                        <div id="timerStopConfirm" class="bigRoundButtons yesConfirm" title="Yes"></div>
                        <div id="timerStopCancel" class="bigRoundButtons noDeny" title="No"></div>
                    </div>
                    <div id="loadingStopTimer" class="loadingStopTimer animationCircle"></div>
                    <a href="#" class="otherLinks displayNone">Close</a>
                </div>
            </div>
	<div class="holidayContent">
        <div class="accountInfo boxContainer preload">
            <div class="leftContainer">
                <div class="containers">
                        <input id="Date" type="text" name="ptr:employees:number" class="text input" value="<?php echo date('d/m/Y');?>" disabled>
                        <label for="date" class="label notEmpty">Date</label>
                </div>
                <div class="containers">
                        <input id="Holidays" type="text" name="ptr:employees:date" class="text input" value="<?php echo $leaveleft;?>" disabled>
                        <label for="Holidays" class="label notEmpty">Leave left (hrs)</label>
                </div>
            </div>
            <div class="rightContainer">
                <div class="containers">
                        <input id="email" type="text" name="ptr:employees:email" class="text input" value="<?php echo $_SESSION['EMAIL'];?>" disabled>
                        <label for="email" class="label notEmpty">Email Address</label>
                </div>
            <div class="containers">
                    <input id="empID" type="text" name="ptr:employees:empID" class="text input" value="<?php echo "E".str_pad($empID, 7, '0', STR_PAD_LEFT);?>" disabled>
                    <label for="empID" class="label notEmpty">Employee ID</label>
            </div>
            </div>
    </div>
        <div class="Title">Request Holiday</div>
							<form class="holidayRequestForm" method='POST'>				
								<input type="hidden" readonly="readonly" name="empID" value="<?php echo $_SESSION['EMPID'];?>">
                                <div class="containers Left">
                                    <input type="date" id="datepicker1" name="holidaystart" class="input" min="<?php echo date('Y-m-d');?>" required><label for="datepicker1" class="label notEmpty">Start date</label> 
                                </div>
                                <div class="toSeperator"></div>
                                <div class="containers Right">
                                    <input type="date" id="datepicker2" name="holidaysend" min="<?php echo date('Y-m-d');?>" class="input" required><label for="datepicker2" class="label notEmpty">End date</label> 
                                </div>
                                <center style="font-size: 18px;">Each day is the equivalent of 8 hours leave. <br> Starting & Ending on the same day is equal to one day.</center>
								<textarea class="reasonbox" name="comment" rows="5" cols="40" maxlength="250" placeholder="Reason for holiday request." required></textarea>
								<button type="submit" class="submit_button">Submit</button>  
								<br>
								
							</form> 
	</div>  
</body>