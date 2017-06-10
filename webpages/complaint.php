
<head>
  <title>Report - PTR</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="complaint.css">
</head>
<div class="containers">
	<form class="employeedetail" method="POST" action="complaintEmail.php">
		<h3 class="reportTitle">Report Issue</h3>
	<label class="desc" id="title1" for="field2">Subject *</label>
		<div>
			<input id="Field2" name="Field2" type="text" class="field text medium" 
			value="" maxlength="255" placeholder="Subject.." required autofocus>
		</div>
		<label class="desc" id="title1" for="field2">Message *
	</label>
			<textarea rows="10" name="message" cols="77" class="input_text textarea" placeholder="Describe your issue(s) in details here." required autofocus></textarea>
        <input type="submit" class="submit button">
	</form>
 </div>
