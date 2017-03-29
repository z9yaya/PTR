<?php 
include '../functions/functions.php';
if (session_id() == '')
    {
        session_start();
    }
if(empty($_SESSION['EMPID']))
    header("Location: start.php");

?>
<!-- test to see if form values are stored in array
if(!empty($_POST))
{ print_r($_POST);} -->
<head>

<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="holidayrequest.css" rel="stylesheet">

			  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
			  <link rel="stylesheet" href="/resources/demos/style.css">
			  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
			  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
			  <script>
			  $( function() {
				$( "#datepicker1" ).datepicker();
			  } );
			  </script>
			  
			  <script>
			  $( function() {
				$( "#datepicker2" ).datepicker();
			  } );
			  </script>		

</head>
<body>
	<div class="holidayContent">     
	
							<form class="formpreload" method='POST'>
								<br>
								
							    <input type="text" value="<?php echo date('m/d/y');?>" class="textbox" readonly="readonly"/> <br><br>
								<input type="text" readonly="readonly" class="textbox" name="empID" value="<?php //$pop = GrabData("EMPLOYEES", "EMPID", "EMPID", "30");
															$mpID = $_SESSION['EMPID'];
															echo $mpID;
															?>">
								<input type="hidden" value=""  class="textbox" name="fname">
								<input type="hidden" value="" name="lname">
								<pre style= "margin-left: 43.5%;">Request Holiday: </pre>
								<pre class="textbox1">From: <input type="text" id="datepicker1" name="holidaystart" required></pre> 
								<pre class="textbox1">To: <input type="text" id="datepicker2" name="holidayend"  required></pre><br>
								<textarea class="reasonbox" name="comment" rows="5" cols="40" placeholder="Reason for holiday request." required></textarea><br><br>
								<button type="submit" class="submit_button">Submit</button>  
								<br>
								<?php
								if(!empty($_POST))
									{ print_r('Your request has been submitted.');}		
								?>
							</form>        
	</div>  
</body>