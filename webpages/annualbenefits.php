<?php 
include '../functions/functions.php';
if (session_id() == '')
    {
        session_start();
    }
if(!isset($_SESSION['EMPID']) || empty($_SESSION['EMPID']))
    header("Location: start.php");

$mpID = $_SESSION['EMPID'];

$holidaysleft = GrabMoreData("SELECT * FROM ACCUMULATION WHERE EMPID = :EMPID", array(array(":EMPID", $mpID)));
$sickdaysleft = GrabMoreData("SELECT * FROM ACCUMULATION WHERE EMPID = :EMPID", array(array(":EMPID", $mpID)));

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1" name="viewport">
	<link href="annualbenefits.css" rel="stylesheet">
</head>
<body class="annualBody">

    </div>
	<div class="annualbenefitsDivContainer">
	<div class="benefitsContainersOutsideDiv">
		<div class="holidays benefitsContainers left">
			<div class="holidaystitle annualTitles">
				Days of Holiday left
			</div>
			<div>
				<div class="holidaynumber annualNumber"><?php print_r ($holidaysleft['LEAVE']);?></div>
			</div>
		</div>
		<div class="sickdays benefitsContainers right">
			<div class="sickdaystitle annualTitles">
				Days of Sick leave left
			</div>
			<div>
				<div class="sickdaynumber annualNumber"><?php print_r ($sickdaysleft['SICKLEAVE']);?></div>
			</div>
			</div>
		</div>
		<div class="requestleavebuttonContainer"><a href="webpages/holidayrequest.php" onclick="myFunction()" style="text-decoration: none;"><button class="requestleavebutton">Request Leave</button></a>
	</div>

	<script>
function myFunction() {
	event.preventDefault();
    var myWindow = window.open("holidayrequest.php", "newwindow", "width=600;height=455");
	return flase;
}
</script>	
</body>
</html>