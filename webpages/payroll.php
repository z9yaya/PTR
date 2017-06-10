<?php 
include '../functions/functions.php';
include '../functions/emailer.php';
if (session_id() == '')
    {
        session_start();
    }
if(empty($_SESSION['EMPID']))
    header("Location: start.php");

$mpID = $_SESSION['EMPID'];

if (!empty($_POST['submitType'])) {
    if ($_POST['submitType'] == 'update') {
        if (checkInputs ($_POST)) {
        $stm = 'UPDATE PAY SET GROSS = :UPGROSS, NET = :UPNET, TAX = :UPTAX, TOTALHOURS = :UPTH, REGHOURS = :UPRH, PUBLICHOURS = :UPPH, SUNDAYHOURS = :UPSH, OVERTIME = :UPO WHERE ID = :PID';
        $bind = array(array(':UPGROSS', $_POST['number:pay:totalhours']),
                      array(':UPNET', $_POST['number:pay:net']),
                      array(':UPTAX', $_POST['number:pay:tax']),
                      array(':UPTH', $_POST['number:pay:regularhours']),
                      array(':UPRH', $_POST['number:pay:regularhours']),
                      array(':UPPH', $_POST['number:pay:publichours']),
                      array(':UPSH', $_POST['number:pay:sundayhours']),
                      array(':UPO', $_POST['number:pay:overtime']),
                      array(':PID', $_POST['payID']));
        if (InsertData($stm, $bind) == 'success') {
            $postEmp = $_POST['empID'];
        }
        }
    }
}
$lastGen = strtotime('sunday 11pm last week');
$empdetails = GrabAllData("SELECT * FROM EMPLOYEES RIGHT JOIN BANKING ON EMPLOYEES.EMPID = BANKING.EMPID LEFT JOIN EMPLOYMENT ON BANKING.EMPID = EMPLOYMENT.EMPID LEFT JOIN ACCUMULATION ON BANKING.EMPID = ACCUMULATION.EMPID WHERE INITIALSETUP IS NOT NULL AND INITIALSETUP != 3 ORDER BY EMPLOYEES.EMPID ASC", null);
$allPay = GrabAllData("SELECT * FROM PAY WHERE GENERATED > :GENDATE AND PAYDATE IS NULL ORDER BY GENERATED DESC", array(array(":GENDATE", $lastGen)));
$nPay = array();
if (!empty($allPay['EMPID'])) {
    for ($i = 0; $i < count($allPay['EMPID']); $i++) {
    $nPay[$allPay['EMPID'][$i]] = array(
    'ID' => $allPay['ID'][$i],
    'GROSS' => $allPay['GROSS'][$i],
    'NET' => $allPay['NET'][$i],
    'TAX' => $allPay['TAX'][$i],
    'GENERATED' => $allPay['GENERATED'][$i],
    'TOTALHOURS' => $allPay['TOTALHOURS'][$i],
    'REGHOURS' => $allPay['REGHOURS'][$i],
    'PUBLICHOURS' => $allPay['PUBLICHOURS'][$i],
    'SUNDAYHOURS' => $allPay['SUNDAYHOURS'][$i],
    'OVERTIME' => $allPay['OVERTIME'][$i],
    'STOREID' => $allPay['STOREID'][$i]);
    }
} else {
    $nPay = false;
}

if(isset($_POST['empselect'])){
$postEmp = $_POST['empselect'];
    $empPos = array_search($postEmp, $empdetails['EMPID']);
    $tfnselect = $empdetails['TFN'][$empPos];	
    $empfname = $empdetails['F_NAME'][$empPos];	
    $empmname = $empdetails['M_NAME'][$empPos];	
    $emplname = $empdetails['L_NAME'][$empPos];
    $empemail = $empdetails['EMAIL'][$empPos];
    $street1 = $empdetails['STREET1'][$empPos];
    $street2 = $empdetails['STREET2'][$empPos];
    $suburb = $empdetails['SUBURB'][$empPos];
    $postcode = $empdetails['POSTCODE'][$empPos];
    $state = $empdetails['STATE'][$empPos];
    $position = $empdetails['POSITION'][$empPos];
    $store = $empdetails['STORE'][$empPos];
    $empidnum = $empdetails['EMPID'][$empPos];
    $rate = $empdetails['RATE'][$empPos];
    $rateT = $empdetails['TYPE'][$empPos];
    if ($nPay !== false && !empty($nPay[$postEmp])) {
        $payID = $nPay[$postEmp]['ID'];
        $grossselect = sprintf('%0.2f', $nPay[$postEmp]['GROSS']);	
        $taxselect = sprintf('%0.2f', $nPay[$postEmp]['TAX']);	
        $netselect = sprintf('%0.2f', $nPay[$postEmp]['NET']);	
        $totalhours = $nPay[$postEmp]['TOTALHOURS']; 
        $reghours = $nPay[$postEmp]['REGHOURS']; 
        $overtime = $nPay[$postEmp]['OVERTIME']; 
        $publichours = $nPay[$postEmp]['PUBLICHOURS']; 
        $sundayhours = $nPay[$postEmp]['SUNDAYHOURS']; 
        $payid = $nPay[$postEmp]['ID'];
        $paygen = $nPay[$postEmp]['GENERATED'];
    }
}
//foreach($allPay['EMPID'] as $k => $v) {
//  foreach ($v as $kA => $vA) {
//$nPay[$v][$k] = $vA;
//  }
//} 
$fname = $fullname["F_NAME"];
$mname = $fullname["M_NAME"];
$lname = $fullname["L_NAME"];

$empid = $empdetails["EMPID"];
//$empdetails = GrabAllData("SELECT * FROM BANKING WHERE EMPID > 0", null); 
$emptfn = $fullname["TFN"];
if (!empty($_POST['submitType'])) {
    if ($_POST['submitType'] == 'approved') {
        $pdfEmpid = $_POST['empID'];
        $pdfPayid = $_POST['payID'];
        $fiscalstart = strtotime('12:01 am 06/01 last year');
        $idtfn = 'SELECT * FROM PAY WHERE EMPID = :empselect AND PAYDATE >= :fiscalstart ORDER BY PAYDATE DESC';
        $bindidtfn = array(array(':empselect', $pdfEmpid), array(":fiscalstart", $fiscalstart));
        $ytdRes = GrabAllData($idtfn, $bindidtfn);
        $ytdgrossarray = $ytdRes['GROSS'];
        $ytdtaxarray = $ytdRes['TAX'];
        $ytdnetarray = $ytdRes['NET'];  
        $ytdgross = array_sum($ytdgrossarray);
        $ytdtax = array_sum($ytdtaxarray);
        $ytdnet = array_sum($ytdnetarray);
        //$payall = GrabAllData("SELECT * FROM PAY");
        $paygenerated = $nPay[$pdfEmpid]["GENERATED"];
        $payidnum = $nPay[$pdfEmpid]['ID'];

        $empemail =  $empemail;

        $emppayid =  $pdfEmpid;

        $empnet =  "$".sprintf('%0.2f',$nPay[$pdfEmpid]['NET']);    

        $empgross =  "$".sprintf('%0.2f',$nPay[$pdfEmpid]['GROSS']);    


        $emptax =  "$".sprintf('%0.2f',$nPay[$pdfEmpid]['TAX']);    


        $firstname =  $empfname;    


        $middlename =  $empmname;    


        $lastname =  $emplname;    


        $empstreet1 =   $street1;    


        $empstreet2 =   $street2;    


        $empsuburb =  $suburb;    


        $emppostcode =  $postcode;    


        $empstate =  $state;    


        $empholileft =  $nPay[$pdfEmpid]['LEAVE'] -  $nPay[$pdfEmpid]['LEAVETAKEN'];

        $empytdnet =  "$".sprintf('%0.2f',$ytdnet);

        $empytdgross =  "$".sprintf('%0.2f',$ytdgross);

        $empytdtax =  "$".sprintf('%0.2f',$ytdtax);

        $payid = $pdfPayid;

        $empposition =  $position;

        $empstoreid =  $store;

        $empidnum =  $pdfEmpid;

        $paygen =  time();
        $paygenF = date("d M", $paygen);

        $emptotalhours =  $totalhours;



        $datelastsundayfinal = date('d/m/Y', strtotime('sunday last week'));

        $datelastmondayfinal = date('d/m/Y', strtotime('monday last week'));    

        $datetodaynohis = date("d/m/Y");

        $datetoday = date("m/d/Y H:i:s", $paygen);
        $datetodayformat = $paygen;

        $empdetailsPDF = "$firstname $middlename $lastname \n$empstreet1 $empstreet2 $empsuburb $emppostcode $empstate \nPosition: $empposition \nStore ID: $empstoreid\nRate type: $rateT"; 


        $title = "Regular hours
Public holiday hours
Sunday hours
Overtime";

        $number = "$reghours 
$publichours
$sundayhours
$overtime";
$pdftotal = ($rate * $reghours)."
".($rate * 1.5) * $publichours."
".($rate * 1.5) * $sundayhours."
".($rate * 1.5) * $overtime."
".$empgross;
        $pdfRate = "$rate
".($rate * 1.5)."
".($rate * 1.5)."
".($rate * 1.5)."
        ";

    //    $emppayup = InsertData("UPDATE PAY SET TOTALHOURS = , REHOURS = , PUBLICHOURS = , SUNDAYHOURS = , GROSS = , TAX = , NET = WHERE EMPID = :EMPID", array(array(":EMPID, $emppayid")));
     
$dataArray = array (
'employee_details' => 
$empdetailsPDF,
'week_ending' => 
$datelastsundayfinal,
'transfer_date' => 
$datetodaynohis,
'title' => 
$title,
'shift_date' => 
$paygenF,
'shift_hours' => 
$number,
'shift_rate' => 
$pdfRate,
'shift_total' => 
$pdftotal,
'super' => 
'0',
'transfer_date_banking' => 
$datetodaynohis,
'YTD_gross' => 
$empytdgross,
'YTD_tax' => 
$empytdtax,
'YTD_net' => 
$empytdnet,
'PAY_gross' => 
$empgross,
'PAY_tax' => 
$emptax,
'PAY_net' => 
$empnet,
'OTHER_date' => 
'',
'OTHER_leave' => 
$empholileft,
'OTHER_rdo' => 
'');
$cow = FillPDF($dataArray ,'pay_advice', $pdfPayid, $pdfEmpid, false);
    $paydategen = InsertData("UPDATE PAY SET PAYDATE = $datetodayformat WHERE ID=:PAYID", array(array(":PAYID", $pdfPayid)));
    Emailer(array('email' => $empemail), "Attached is your Pay Advice for work carried out between $datelastmondayfinal and $datelastsundayfinal", "Pay Advice Period Ending  ".date('d/m/Y', strtotime($dateforlastsunday.'sunday last week')), null, null , null , $cow[1], $cow[0].".pdf", "Hello, $firstname" ); 
    header("Location: payroll.php");
}   
}

if(isset($_POST['empselect'])){
    $postEmp = $_POST['empselect'];

    $empPos = array_search($postEmp, $empdetails['EMPID']);
        $empleaveleft = 'SELECT * FROM ACCUMULATiON WHERE EMPID = :empselect';
        $bindname = array(
        array(
        ':empselect',
        $i));
        $toy = GrabAllData($empleaveleft, $bindname);
        $empholileft = $toy['HOLILEFT'];

        $empposition1 = 'SELECT EMPLOYMENT.POSITION FROM EMPLOYMENT INNER JOIN EMPLOYEES ON EMPLOYMENT.EMPID = EMPLOYEES.EMPID WHERE EMPLOYEES.EMPID = :empselect';
        $bindname = array(array(':empselect',$i));
        $toy = GrabAllData($empposition1, $bindname);
        $empposition2 = $toy['POSITION'];


        $empbanking = 'SELECT * FROM BANKING WHERE EMPID = :empselect';
        $bindname = array(
        array(
        ':empselect',
        $i));
        $toy = GrabAllData($empbanking, $bindname);
        $emptfn = $toy['TFN'];
        $empbsb = $toy['BSB'];
        $empaccnum = $toy['ACCNUMBER'];
        $empbankname = $toy['BANKNAME'];
    }

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1" name="viewport">
	<link rel="stylesheet" href="payroll.css">
    <script type="application/javascript" src="../javascript/jquery-3.2.0.min.js"></script>
    <script type="application/javascript" src="../javascript/payroll.js"></script>
    
	    
<script>
    function clickMe(){document.getElementById('statusInput').disabled = false;}
</script>
    
</head>
<body class="annualBody">
     <div class="accountInfo boxContainer preload">
        <svg class="title_icon" xmlns="http://www.w3.org/2000/svg" id="Capa_1" viewBox="0 0 48 48" x="0px" y="0px" width="110" height="110" version="1.1" xmlns:xml="http://www.w3.org/XML/1998/namespace" xml:space="preserve"><defs id="defs39"></defs><g id="g4" style="stroke: #d4d4d4; stroke-dasharray: none; stroke-miterlimit: 4; stroke-opacity: 1; stroke-width: 22.4466;" transform="matrix(0.0490052 0 0 0.0490052 13.4889 13.489)"><path id="path2" style="fill: none; stroke: #d4d4d4; stroke-dasharray: none; stroke-miterlimit: 4; stroke-opacity: 1; stroke-width: 22.4466;" d="m 414.101 373.866 l -106.246 -56.188 l -4.907 -15.332 c -1.469 -5.137 -3.794 -10.273 -8.576 -11.623 c -1.519 -0.428 -3.441 -3.201 -3.689 -5.137 l -2.836 -29.813 c -0.156 -2.553 0.868 -4.844 2.216 -6.453 c 8.14 -9.754 12.577 -21.051 14.454 -33.967 c 0.944 -6.494 4.323 -12.483 6.059 -18.879 l 6.812 -35.649 c 0.711 -4.681 0.573 -8.289 -4.659 -10.103 c -1.443 -0.503 -2.699 -2.894 -2.699 -6.479 l 0.069 -67.264 C 308.988 60.699 300.368 48.11 288.142 39.348 C 264.788 22.609 222.967 30.371 236.616 3.067 C 237.422 1.46 237.165 -1.332 231.554 0.732 C 210.618 8.435 154.853 28.789 140.844 39.348 C 128.306 48.797 120 60.699 118.887 76.979 l 0.069 67.264 c 0 2.96 -1.255 5.976 -2.7 6.479 c -5.233 1.813 -5.37 5.422 -4.659 10.103 l 6.814 35.649 c 1.732 6.396 5.113 12.386 6.058 18.879 c 1.875 12.916 6.315 24.213 14.453 33.967 c 1.347 1.609 2.372 3.9 2.216 6.453 l -2.836 29.813 c -0.249 1.936 -2.174 4.709 -3.69 5.137 c -4.783 1.35 -7.109 6.486 -8.577 11.623 l -4.909 15.332 l -106.25 56.188 c -2.742 1.449 -4.457 4.297 -4.457 7.397 v 39.343 c 0 4.621 3.748 8.368 8.37 8.368 h 391.4 c 4.622 0 8.37 -3.747 8.37 -8.368 v -39.343 c -0.002 -3.1 -1.717 -5.948 -4.458 -7.397 Z" /></g><g id="g6" transform="translate(0 -388.759)" /><g id="g8" transform="translate(0 -388.759)" /><g id="g10" transform="translate(0 -388.759)" /><g id="g12" transform="translate(0 -388.759)" /><g id="g14" transform="translate(0 -388.759)" /><g id="g16" transform="translate(0 -388.759)" /><g id="g18" transform="translate(0 -388.759)" /><g id="g20" transform="translate(0 -388.759)" /><g id="g22" transform="translate(0 -388.759)" /><g id="g24" transform="translate(0 -388.759)" /><g id="g26" transform="translate(0 -388.759)" /><g id="g28" transform="translate(0 -388.759)" /><g id="g30" transform="translate(0 -388.759)" /><g id="g32" transform="translate(0 -388.759)" /><g id="g34" transform="translate(0 -388.759)" /></svg>
            <div class="containers">
                    <input id="email" type="text" name="ptr:employees:email" class="text input" value="<?php echo $_SESSION['EMAIL'];?>" disabled>
                    <label for="email"  class="label notEmpty">Email Address</label>
            </div>
            <div class="containers">
                    <input id="empID" type="text" name="ptr:employees:empID" class="text input" value="<?php echo "E".str_pad($_SESSION['EMPID'], 7, '0', STR_PAD_LEFT);?>" disabled>
                    <label for="empID"  class="label notEmpty">Employee ID</label>
            </div>
    </div>
	
	<div class="payrollDivContainer">
	
		<div class="heading1">PAYROLL</div>
		
		<div class="heading2">Pay Period: <?php $date=date("Y-m-d"); echo date('d/m/Y', strtotime($date.'monday last week'));?> - <?php $date=date("Y-m-d"); echo date('d/m/Y', strtotime($date.'sunday last week'));?></div>
		<form action="" method="post" id='form'> 
		<div class="employeebox">			
			<div class="employeetext">EMPLOYEE:</div>
			<div class="dropdown">
			  <select class="selectemployees" name="empselect" onchange='this.form.submit()'>
			  <div class="dropdown-content">
				<option value="">Select an employee</option>
				<?php			
				FOREACH($empid AS $k => $v){
                    $empid = "E".str_pad($v, 7, '0', STR_PAD_LEFT);
                    if ($v == $postEmp) {
                        echo "<option value = '$v' selected>$empid | {$empdetails['F_NAME'][$k]} {$empdetails['L_NAME'][$k]} </option>";
                    } else {
					echo "<option value = '$v'>$empid | {$empdetails['F_NAME'][$k]} {$empdetails['L_NAME'][$k]} </option>";
                    }
				}	
				?>	
			  </div>
			  </select>
			</div>
			<div class="employeetfn">TFN:</div>
			<div class="tfn">
			<?php
				if(isset($_POST['empselect'])){
					echo $tfnselect;	
				}	
			?>				
			</div>			
		</div>	
		
<div id="payrolltable" class="payrolltable"><?php
                if (!empty($payID)) {
                echo "<div class='editOverlay'></div><div id='payrolltablein' class='alignLeft formCont'><div class='containers inline'>
                        <input type='text' id='totH' name='number:pay:totalhours' class='text input' required value='$totalhours' disabled>
                        <label for='totH' class='label totH'>Total hours</label>
                        <div class='errorContainer'><span class='error totH'></span></div>
                    </div>
                    <div class='containers inline'>
                        <input type='text' id='regH' name='number:pay:regularhours' class='text input' required disabled value='$reghours'>
                        <label for='regH' class='label regH'>Regular hours</label>
                        <div class='errorContainer'><span class='error regH'></span></div>
                    </div>
                     <div class='containers inline'>
                        <input type='text' id='oH' name=' number:pay:overtime' class='text input' required disabled value='$overtime'>
                        <label for='ogH' class='label oH'>Overtime hours</label>
                        <div class='errorContainer'><span class='error oH'></span></div>
                    </div>
                   
                    <div class='containers inline'>
                        <input type='text' id='pubH' name='number:pay:publichours' class='text input' required disabled value='$publichours'>
                        <label for='pubH' class='label pubH'>Public holiday hours</label>
                        <div class='errorContainer'><span class='error pubH'></span></div>
                    </div>
                    <div class='containers inline'>
                        <input type='text' id='sunH' name='number:pay:sundayhours' class='text input' required disabled value='$sundayhours'>
                        <label for='sunH' class='label sunH'>Sunday hours</label>
                        <div class='errorContainer'><span class='error totH'></span></div>
                    </div>
                    <div class='containers inline Money'>
                        <input type='text' id='gross' name='number:pay:gross' class='text input' required disabled value='$grossselect'>
                        <label for='gross' class='label totH'>Gross pay</label>
                        <div class='errorContainer'><span class='error gross'></span></div>
                    </div>
                    <div class='containers inline Money'>
                        <input type='text' id='tax' name='number:pay:tax' class='text input' required disabled value='$taxselect'>
                        <label for='tax' class='label tax'>Tax</label>
                        <div class='errorContainer'><span class='error totH'></span></div>
                    </div>
                    <div class='containers inline Money'>
                        <input type='text' id='net' name='number:pay:net' class='text input' required disabled value='$netselect'>
                        <label for='net' class='label net'>Net pay</label>
                        <div class='errorContainer'><span class='error net'></span></div>
                    </div>
                    <input type='hidden' class='type' name='submitType'>
                    <input type='hidden' name='payID' value='$payID'>
                    <input type='hidden' name='empID' value='$postEmp'>
                    </div>"; } elseif (!empty($postEmp)) {
                    echo 'No pay calculated for this employee';
                } ?></div> 
            <input type='submit' id='SubmitForm' value='Save' class='submit button displayNone'>
            <div class='submit wait displayNone'><div class='innerButton animationCircle'></div></div>
    </form>   
        <input type='submit' id='confirm' value='Approve' class=''><input type='submit' id='edit' value='Edit' class=''>
   
        
  	            
    
    </div>
</body>
</html>