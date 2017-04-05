<head>
<link rel="stylesheet" href="payment.css">
<link rel="stylesheet" href="../javascript/tablesaw.css">
<script type="application/javascript" src="../javascript/header.js"></script>
    <script type="application/javascript">head.load("../javascript/jquery-3.2.0.min.js",function()
    {head.load("../javascript/tablesaw/tablesaw.jquery.js");
     head.load("../javascript/tablesaw/tablesaw-init.js");
     head.load("payment.js");
    });
    </script>
</head>
<div class="container preload">
	<div class="boxContainerDiv">
		<div class="accountInfo boxContainer left">   
			<div class="containers">
					<label for="currentDate"  class="label notEmpty">Date:</label>
                    <input id="currentDate" type="text" name="ptr:employees:currentDate" class="text input" disabled value="19/03/2017">
            </div>
            <div class="containers">
                    <input id="lastPayment" type="text" name="ptr:employees:lastPayment" class="text input" disabled value="$1500.00">
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
        <input type="text" id="adviceSearch" class="searchInput" onkeyup="myFunction()" name="string:pay:ID" placeholder="Search for payment history..">
  </div>
    <div class="boxContainerDiv">
		
	<?php 
$payHeaders = array("PayID", "Advice Date", "Gross", "Tax", "Net"); 

//foreach ($pay as $value) {
echo'
<table class="tablesaw" data-tablesaw-sortable>
<thead class="thead">
<tr>';
  for($i=0;$i<5;$i++){
    echo  '<th scope="col"  data-tablesaw-sortable-col data-tablesaw-priority="4">'.$payHeaders[$i].' <span class="tablesaw-sortable-arrow"></span></th>';
  }//}
        ?>