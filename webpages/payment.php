<?php 
include '../functions/functions.php';
if (session_id() == '')
    {
        session_start();
    }
 if(!isset($_SESSION['EMPID']) || empty($_SESSION['EMPID']))
     header("Location: webpages/start.php");

$EMPID = $_SESSION['EMPID'];
$PAY = GrabAllData("SELECT * FROM PAY WHERE EMPID = :ID AND PAYDATE IS NOT NULL ORDER BY ID DESC", array(array(':ID', $EMPID)));
?>
<head>
<link rel="stylesheet" href="payment.css">
<link rel="stylesheet" href="../javascript/tablesaw/tablesaw.css">
<script type="application/javascript" src="../javascript/jquery-3.2.0.min.js"></script>
<script type="application/javascript" src="../javascript/tablesaw/tablesaw.jquery.js"></script>
<script type="application/javascript" src="../javascript/tablesaw/tablesaw-init.js"></script>
<script type="application/javascript" src="payment.js"></script>
</head>
<div class="container preload">
	<div class="boxContainerDiv">
		<div class="accountInfo boxContainer left">   
			<div class="containers">
					<label for="currentDate"  class="label notEmpty">Date:</label>
                    <input id="currentDate" type="text" name="ptr:employees:currentDate" class="text input" disabled value="">
            </div>
            <div class="containers">
                    <input id="lastPayment" type="text" name="ptr:employees:lastPayment" class="text input" disabled value="<?php if (!empty($PAY['NET'])) {echo '$'.sprintf("%.2f",$PAY['NET'][0]);} else echo '$0.00';?>">
                    <label for="lastPayment"  class="label notEmpty">Last Payment</label>
            </div>
    </div>	
	<div class="accountInfo boxContainer right">   
			<div class="containers">
                    <input id="email" type="text" name="ptr:employees:email" class="text input" disabled value="<?php echo $_SESSION['EMAIL'];?>">
                    <label for="email"  class="label notEmpty">Email address</label>
            </div>
            <div class="containers">
                    <input id="empID" type="text" name="ptr:employees:empID" class="text input" disabled value="<?php echo "E".str_pad($_SESSION['EMPID'], 7, '0', STR_PAD_LEFT);?>">
                    <label for="empID"  class="label notEmpty">Employee ID</label>
            </div>
    </div>
			<a href="webpages/complaint.php" onclick="event.preventDefault();window.open('complaint.php', 'newwindow', 'width=600, height=455'); 
return false;" class="report button">Report Issue</a>
        <input type="text" id="adviceSearch" class="searchInput" name="string:pay:ID" placeholder="Search for payment history..">  
  </div>
    <div class="boxContainerDiv">
		
	<?php 
        if (!empty($PAY['ID'])) {
$payHeaders = array("PayID", "Advice Date", "Gross", "Tax", "Net"); 

echo'
<table class="mainTable tablesaw" data-tablesaw-sortable>
<thead class="thead tableHead">
<tr>';
  for($i=0;$i<5;$i++){
    echo  '<th scope="col" class="tableHeaders" data-tablesaw-sortable-col>'.$payHeaders[$i].'</th>';
  }//}
echo "</tr>
</thead>           
<tbody>";

        
for($i=0;$i<count($PAY['ID']);$i++){
    echo '<tr class="tableRow">
    <td><a href="payPDF.php?payid='.$PAY['ID'][$i]. '" target="_blank" class="TableLink">'.$PAY['ID'][$i].'</a></td>
    <td>'.date('d/m/Y',$PAY['PAYDATE'][$i]).'</td>
    <td>'.'$'.sprintf("%.2f",$PAY['GROSS'][$i]).'</td>
    <td>'.'$'.sprintf("%.2f",$PAY['TAX'][$i]).'</td>
    <td>'.'$'.sprintf("%.2f",$PAY['NET'][$i]).'</td>
  </tr>';
    ;}
   
echo '</tbody>
	  </table>';
        } else {
            echo '<div class="na">No pay advices processed.</div>';
        }
    ?>
    </div>
       </div>