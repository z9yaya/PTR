<?php 
include '../../functions/functions.php';

if (session_id() == '')
    {
        session_start();
    }

if(empty($_SESSION['EMPID']))
    header("Location: start.php");


$mpID = $_SESSION['EMPID'];

$holreq = GrabAllData("SELECT * FROM HOLIDAYREQUEST WHERE EMPID = :EMPID AND STATUS = 'Pending' ORDER BY HOLREQID DESC", array(array(":EMPID", $mpID)));

$holreqid = $holreq['HOLREQID'];
$holreqempid = $holreq['EMPID'];
$holreqstartdate = $holreq['STARTDATE'];
$holreqenddate = $holreq['ENDDATE'];
$holreqstatus = $holreq['STATUS'];

$holreqapproved = GrabAllData("SELECT * FROM HOLIDAYREQUEST WHERE EMPID = :EMPID AND STATUS = 'Approved' ORDER BY HOLREQID DESC", array(array(":EMPID", $mpID)));

$holreqidapproved = $holreqapproved['HOLREQID'];
$holreqempidapproved = $holreqapproved['EMPID'];
$holreqstartdateapproved = $holreqapproved['STARTDATE'];
$holreqenddateapproved = $holreqapproved['ENDDATE'];
$holreqstatusapproved = $holreqapproved['STATUS'];

$holreqdenied = GrabAllData("SELECT * FROM HOLIDAYREQUEST WHERE EMPID = :EMPID AND STATUS = 'Denied' ORDER BY HOLREQID DESC", array(array(":EMPID", $mpID)));

$holreqiddenied = $holreqdenied['HOLREQID'];
$holreqempiddenied = $holreqdenied['EMPID'];
$holreqstartdatedenied = $holreqdenied['STARTDATE'];
$holreqenddatedenied = $holreqdenied['ENDDATE'];
$holreqstatusdenied = $holreqdenied['STATUS'];



$datetotalpending = 0;
FOR($i = 0; $i < count ($holreqid); $i++){   
    $datediff = ((($holreqenddate[$i] - $holreqstartdate[$i]) / 86400) + 1) * 8;
    $datetotalpending += $datediff; 
}
//echo $datetotalpending;

$datetotalapproved = 0;
FOR($i = 0; $i < count ($holreqidapproved); $i++){   
    $datediffapproved = ((($holreqenddateapproved[$i] - $holreqstartdateapproved[$i]) / 86400) + 1) * 8;
    $datetotalapproved += $datediffapproved; 
}
//echo $datetotalapproved;

$datetotal = $datetotalpending + $datetotalapproved;

//FOR($i = 0; $i < count ($holreqid); $i++){   
//    $holreqnumber = $holreqid[$i];
//}


$accumulation = GrabMoreData("SELECT * FROM ACCUMULATION WHERE EMPID = :EMPID", array(array(":EMPID", $mpID)));

$holileft = $accumulation['LEAVE'] - ($datetotal);


if(!empty($_POST['reqid']))
{
    $reqid = $_POST['reqid'];
    
    $rowdelete = InsertData("DELETE FROM HOLIDAYREQUEST WHERE HOLREQID= :REQID", array(array(":REQID", $reqid)));
    
    $leavetakenup = InsertData("UPDATE ACCUMULATION SET LEAVETAKEN = LEAVETAKEN - :DATEDIFF WHERE EMPID = :EMPID", array(array(":EMPID", $mpID),array(":DATEDIFF", $datediff)));
    
    $holileftup = InsertData("UPDATE ACCUMULATION SET HOLILEFT = :HOLUP WHERE EMPID = :EMPID", array(array(":HOLUP", $holileft), array(":EMPID", $mpID)));
}

///////////////////////////////////////////////////////////////////
    
$holreq = GrabAllData("SELECT * FROM HOLIDAYREQUEST WHERE EMPID = :EMPID AND STATUS = 'Pending' ORDER BY HOLREQID DESC", array(array(":EMPID", $mpID)));

$holreqid = $holreq['HOLREQID'];
$holreqempid = $holreq['EMPID'];
$holreqstartdate = $holreq['STARTDATE'];
$holreqenddate = $holreq['ENDDATE'];
$holreqstatus = $holreq['STATUS'];

$holreqapproved = GrabAllData("SELECT * FROM HOLIDAYREQUEST WHERE EMPID = :EMPID AND STATUS = 'Approved' ORDER BY HOLREQID DESC", array(array(":EMPID", $mpID)));

$holreqidapproved = $holreqapproved['HOLREQID'];
$holreqempidapproved = $holreqapproved['EMPID'];
$holreqstartdateapproved = $holreqapproved['STARTDATE'];
$holreqenddateapproved = $holreqapproved['ENDDATE'];
$holreqstatusapproved = $holreqapproved['STATUS'];

$holreqdenied = GrabAllData("SELECT * FROM HOLIDAYREQUEST WHERE EMPID = :EMPID AND STATUS = 'Denied' ORDER BY HOLREQID DESC", array(array(":EMPID", $mpID)));

$holreqiddenied = $holreqdenied['HOLREQID'];
$holreqempiddenied = $holreqdenied['EMPID'];
$holreqstartdatedenied = $holreqdenied['STARTDATE'];
$holreqenddatedenied = $holreqdenied['ENDDATE'];
$holreqstatusdenied = $holreqdenied['STATUS'];

$datetotalpending = 0;
FOR($i = 0; $i < count ($holreqid); $i++){   
    $datediff = ((($holreqenddate[$i] - $holreqstartdate[$i]) / 86400) + 1) * 8;
    $datetotalpending += $datediff; 
}
//echo $datetotalpending;

$datetotalapproved = 0;
FOR($i = 0; $i < count ($holreqidapproved); $i++){   
    $datediffapproved = ((($holreqenddateapproved[$i] - $holreqstartdateapproved[$i]) / 86400) + 1) * 8;
    $datetotalapproved += $datediffapproved; 
}
//echo $datetotalapproved;

$datetotal = $datetotalpending + $datetotalapproved;

//FOR($i = 0; $i < count ($holreqid); $i++){   
//    $holreqnumber = $holreqid[$i];
//}


$accumulation = GrabMoreData("SELECT * FROM ACCUMULATION WHERE EMPID = :EMPID", array(array(":EMPID", $mpID)));

$holileft = $accumulation['LEAVE'] - ($datetotal);


$holileftup = InsertData("UPDATE ACCUMULATION SET HOLILEFT = :HOLUP WHERE EMPID = :EMPID", array(array(":HOLUP", $holileft), array(":EMPID", $mpID)));
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1" name="viewport">
    <link rel="stylesheet" href="../../javascript/tablesaw/tablesaw.css" media="none" onload="if(media!='all')media='all'">
	<link rel="stylesheet" href="annualbenefits.css">
    <script src="../../javascript/jquery-3.2.0.min.js"></script>
    <script type="application/javascript" src="../../javascript/tablesaw/tablesaw.jquery.js"></script>
    <script type="application/javascript" src="../../javascript/tablesaw/tablesaw-init.js"></script>
    
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
	<div class="annualbenefitsDivContainer">
	<div class="benefitsContainersOutsideDiv">
	
		<div class="infoCont">
			<div class="holidaystitle annualTitles">Leave taken (hrs)</div><div class="holidaynumber annualNumber"><?php echo  $accumulation['LEAVETAKEN'];?></div>
		</div>
	<!-- $accumulation['LEAVETAKEN'] + -->
		<div class="infoCont">
			<div class="holidaystitle annualTitles">Leave left (hrs)</div>
				<div class="holidaynumber annualNumber"><?php echo $accumulation['LEAVE'] - ($datetotal);?></div>
		</div>
		<div class="infoCont ">
			<div class="sickdaystitle annualTitles">Sick days left</div>
				<div class="sickdaynumber annualNumber"><?php echo $accumulation['SICKLEAVE'];?></div>
			</div>
			
		<div class="infoCont ">
			<div class="sickdaystitle annualTitles">Sick Days taken</div>
				<div class="sickdaynumber annualNumber"><?php echo $accumulation['SICKLEAVETAKEN'];?></div>
		</div>	
		</div>
		<div class="requestleavebuttonContainer">
            <?php
            if ($holileft == '0'){
                echo '<br> You can not request anymore leave. <br> Please cancel an existing request or wait until you accumulate more leave hours.';}
            else {
                echo '<a href="webpages/holidayrequest.php" onclick="myFunction(event)" style="text-decoration: none;" class="requestleavebutton">
                Request Leave
            </a>';}
            ?>
	    </div>
        
    <p style= "color:white; display:block; margin-top:40px; font-size:20pt; font-weight:bold;">Pending Requests</p>    
	
	<div id="holidayhistorytable">
      <table class="tablesaw" data-tablesaw-mode="columntoggle" data-tablesaw-sortable>
      <thead>
        <tr>
          <th scope="col" class="tableHeaders" data-tablesaw-sortable-col data-tablesaw-priority="persist">Holiday Request ID</th> 
     	  <th scope="col" class="tableHeaders" data-tablesaw-sortable-col data-tablesaw-priority="4">Employee ID</th>
		  <th scope="col" class="tableHeaders" data-tablesaw-sortable-col data-tablesaw-priority="2">Start Date</th>
		  <th scope="col" class="tableHeaders" data-tablesaw-sortable-col data-tablesaw-priority="3">End Date</th>    
		  <th scope="col" class="tableHeaders" data-tablesaw-sortable-col data-tablesaw-priority="persist">Status</th>
          <th class='btnth'></th>    
        </tr>
	   </thead>
	   <tbody>
				<?php				
				FOR($i = 0; $i < count ($holreqid); $i++){
                    echo 
                    "<tr class='base'> 
					   <td class='holereqtd'>" . $holreqid[$i] . "</td>
                       <td class='holreqempidid'>" . $holreqempid[$i] . "</td>
                       <td>" . date('d/m/Y', $holreqstartdate[$i]) . "</td>
                       <td>" . date('d/m/Y', $holreqenddate[$i]) . "</td>
                       <td class='datediff'>" . ($holreqenddate[$i] - $holreqstartdate[$i]) / 86400 . "</td>
                       <td>" . $holreqstatus[$i] . "</td>
                       <td class='deletebtn'><form class='Deleteform' id='deleteform$i' name='$i' action='#' method='post'><input type='hidden' value='$holreqid[$i]' name='reqid'><input type='submit' class='buttondelete' value='Cancel Request'></form></td>";
				};
            echo  "</tr>"; 
				 ?>       
	   </tbody>
        </table>
	</div>   
        
    <p style= "color: white; display:block; margin-top:40px; font-size:20pt; font-weight:bold;">Approved Requests</p>     
        
	<div id="holidayhistorytable">
      <table class="tablesaw" data-tablesaw-mode="columntoggle" data-tablesaw-sortable>
      <thead>
        <tr>
          <th scope="col" class="tableHeaders" data-tablesaw-sortable-col data-tablesaw-priority="persist">Holiday Request ID</th> 
     	  <th scope="col" class="tableHeaders" data-tablesaw-sortable-col data-tablesaw-priority="4">Employee ID</th>
		  <th scope="col" class="tableHeaders" data-tablesaw-sortable-col data-tablesaw-priority="2">Start Date</th>
		  <th scope="col" class="tableHeaders" data-tablesaw-sortable-col data-tablesaw-priority="3">End Date</th>    
		  <th scope="col" class="tableHeaders" data-tablesaw-sortable-col data-tablesaw-priority="persist">Status</th>
          <th class='btnth'></th>    
        </tr>
	   </thead>
	   <tbody>
				<?php				
				FOR($i = 0; $i < count ($holreqidapproved); $i++){
                    echo 
                    "<tr class='base'> 
					   <td class='holereqtd'>" . $holreqidapproved[$i] . "</td>
                       <td class='holreqempidid'>" . $holreqempidapproved[$i] . "</td>
                       <td>" . date('d/m/Y', $holreqstartdateapproved[$i]) . "</td>
                       <td>" . date('d/m/Y', $holreqenddateapproved[$i]) . "</td>
                       <td class='datediff'>" . ($holreqenddateapproved[$i] - $holreqstartdateapproved[$i]) / 86400 . "</td>
                       <td>" . $holreqstatusapproved[$i] . "</td>";
				};
            echo  "</tr>"; 
				 ?>       
	   </tbody>
        </table>
	</div>        
        
    <p style= "color: white; display:block; font-size:20pt; font-weight:bold;">Denied Requests</p>  
        
	<div id="holidayhistorytable">
      <table class="tablesaw" data-tablesaw-mode="columntoggle" data-tablesaw-sortable>
      <thead>
        <tr>
          <th scope="col" class="tableHeaders" data-tablesaw-sortable-col data-tablesaw-priority="persist">Holiday Request ID</th> 
     	  <th scope="col" class="tableHeaders" data-tablesaw-sortable-col data-tablesaw-priority="4">Employee ID</th>
		  <th scope="col" class="tableHeaders" data-tablesaw-sortable-col data-tablesaw-priority="2">Start Date</th>
		  <th scope="col" class="tableHeaders" data-tablesaw-sortable-col data-tablesaw-priority="3">End Date</th>    
		  <th scope="col" class="tableHeaders" data-tablesaw-sortable-col data-tablesaw-priority="persist">Status</th>
          <th class='btnth'></th>    
        </tr>
	   </thead>
	   <tbody>
				<?php				
				FOR($i = 0; $i < count ($holreqiddenied); $i++){
                    echo 
                    "<tr class='base'> 
					   <td class='holereqtd'>" . $holreqiddenied[$i] . "</td>
                       <td class='holreqempidid'>" . $holreqempiddenied[$i] . "</td>
                       <td>" . date('d/m/Y', $holreqstartdatedenied[$i]) . "</td>
                       <td>" . date('d/m/Y', $holreqenddatedenied[$i]) . "</td>
                       <td>" . $holreqstatusdenied[$i] . "</td>";
				};
            echo  "</tr>"; 
				 ?>       
	   </tbody>
        </table>
	</div>          



    </div>		


<script>
    function myFunction(event) {
        event.preventDefault();
        var myWindow = window.open("holidayrequest.php", "newwindow", "width=380,height=525");
    }
    $(document).ready(function(){
        $(".buttondelete").on("click", function(event){
            event.preventDefault();
            form = $(this).parent();
            submitForm(form);
        });
    })
    
    function submitForm(id) {
        parent.showPopUp("Are you sure you want to delete this request?","Deleting request, please wait...", function(){
            id.submit(parent.hidePopUp(doneText = "Done!", type = ''));
            console.log(id.submit());
        });
    }
</script>	
</body>
</html>