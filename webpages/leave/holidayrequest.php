<?php 
include '../../functions/functions.php';
if (session_id() == '')
    {
        session_start();
    }
if(empty($_SESSION['EMPID']))
    header("Location: ../start.php");
$empID = $_SESSION['EMPID'];
$holidaysleft = GrabMoreData("SELECT LEAVE FROM ACCUMULATION WHERE EMPID = :EMPID", array(array(":EMPID", $empID)))["LEAVE"];
?>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="holidayrequest.css" rel="stylesheet" media="none" onload="if(media!='all')media='all'">
<script type="application/javascript" src="../../javascript/header.js"></script>
<script type="application/javascript">head.load("../../javascript/modernizr-inputs.js",function(){
    if (Modernizr.pointerevents || !Modernizr.inputtypes.date) {
        head.load("../../javascript/jquery-3.2.0.min.js");
        head.load("../../javascript/jquery-ui-1.12.1/jquery-ui.min.css");
        head.load("../../javascript/jquery-ui-1.12.1/jquery-ui.min.js",function(){
            head.load("holidayrequest.js");});
    };});</script>
</head>
<body>
	<div class="holidayContent">
        <div class="accountInfo boxContainer preload">
            <div class="leftContainer">
                <div class="containers">
                        <input id="Date" type="text" name="ptr:employees:number" class="text input" value="<?php echo date('d/m/Y');?>" disabled>
                        <label for="date" class="label notEmpty">Date</label>
                </div>
                <div class="containers">
                        <input id="Holidays" type="text" name="ptr:employees:date" class="text input" value="<?php echo $holidaysleft;?>" disabled>
                        <label for="Holidays" class="label notEmpty">Days off left</label>
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
                                    <input type="date" id="datepicker1" name="holidaystart" class="input" min="<?php echo date('Y-m-d');?>" required><label for="datepicker1" class="label">Start date</label> 
                                </div>
                                <div class="toSeperator"></div>
                                <div class="containers Right">
                                    <input type="date" id="datepicker2" name="holidaysend" min="<?php echo date('Y-m-d');?>" class="input" required><label for="datepicker2" class="label">End date</label> 
                                </div>
								<textarea class="reasonbox" name="comment" rows="5" cols="40" placeholder="Reason for holiday request." required></textarea>
								<button type="submit" class="submit_button">Submit</button>  
								<br>
								<?php
								if(!empty($_POST))
									{ print_r('Your request has been submitted.');}		
								?>
							</form>        
	</div>  
</body>