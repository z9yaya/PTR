<?php 
include '../functions/tools.php';
if (session_id() == '')
    {
        session_start();
    }
 if(!isset($_SESSION['EMPID']) || empty($_SESSION['EMPID'])){
     header("Location: webpages/start.php");
 }
$employees = GrabAllData("SELECT * FROM EMPLOYEES");
$select = '';
for ($i = 0; $i < count($employees['EMPID']); $i++) {
    $select .= "<option value='{$employees['EMPID'][$i]}'>{$employees['EMPID'][$i]} | {$employees['F_NAME'][$i]} {$employees['L_NAME'][$i]} | {$employees['EMAIL'][$i]}</option>";
}
$stores = GrabAllData("SELECT * FROM STORES");
$selectStore = '';
for ($i = 0; $i < count($stores['ID']); $i++) {
    $selectStore .= "<option value='{$stores['ID'][$i]}'>{$stores['ID'][$i]} | {$stores['STREET'][$i]} | {$stores['SUBURB'][$i]} | {$stores['POSTCODE'][$i]} | {$stores['STATE'][$i]}</option>";
}
?>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" media="none" href="reporting.css" onload="if(media!='all')media='all'">
<script type="text/javascript" src="../javascript/jquery-3.2.0.min.js"></script>
<script type="application/javascript" src="../javascript/header.js"></script>
<script type="text/javascript" src="reporting.js"></script>
<script>
head.load("../javascript/modernizr-inputs.js",function(){
if (!Modernizr.inputtypes.date) {
    head.load("../javascript/jquery-ui-1.12.1/jquery-ui.min.css");
    head.load("../javascript/jquery-ui-1.12.1/jquery-ui.min.js",function(){
        LoadJqueryUI();});
};});</script>
</head>
<div class="reportsContainer">
    <div class="reportContent">
    <div class="containers Radio EMPCont">
            <div class="Radiocontainers EMP">
            <input id="EMP_Profile" type="radio" name="radioInput" value="EMP_Profile" class=" hiddenRadio EMP" required>
                <label for="EMP_Profile"  class="accountType radio">Employee Reports</label>
            </div>
            <div class="Radiocontainers EMP">
            <input id="EMP_Stats" type="radio" name="radioInput" value="EMP_Stats" class=" hiddenRadio EMP" required>
                <label for="EMP_Stats"  class="accountType radio">Store Statistics</label>
            </div>
             <div class="Radiocontainers EMP">
            <input id="EMP_Roster" type="radio" name="radioInput" value="EMP_Roster" class=" hiddenRadio EMP" required>
                <label for="EMP_Roster"  class="accountType radio">Roster</label>
            </div>
    </div> 
    <div class="reportContainer EMP_Profile displayNone">
         <div class="containers inline reportTypeCont">
            <select id="reportTypeEmp" name="number:employment:empsearch" class="text input">
                <option value="">Select a Report Type</option>
                <option value="empPro">Basic Information</option>
                <option value="empStats">Employment Information</option>
            </select>
             <label for="reportTypeEmp" class="label reportTypeEmp notEmpty">Report Type</label>
             <div class="errorContainer"><span class="error reportTypeEmp"></span></div>
        </div>   
        <div class="containers inline reportEmpIdCont">  
            <select id="EmpId" name="number:employment:empsearch" class="text input">
                <option value="">Select an Employee</option>
                <?php echo $select;?>
            </select>
            <label for="EmpId" class="label EmpId notEmpty">Employee Info</label>
            <div class="errorContainer"><span class="error EmpId"></span></div>
        </div>       
    </div>
 <div class="reportContainer EMP_Stats displayNone">        
        <div class="containers inline reportTypeCont">            
            <input type="date" id="startdate"class="text input inline">
            <label for="startdate" class="label startdate notEmpty">From:</label>
            <div class="errorContainer"><span class="error startdate"></span></div>
        </div>
        <div class="containers inline reportTypeCont">   
            <input type="date" id="enddate"class="text input inline">
            <label for="enddate" class="label enddate notEmpty">To:</label>
            <div class="errorContainer"><span class="error enddate"></span></div>
        </div>   
        <div class="containers inline reportEmpIdCont">  
            <select id="StatsId" name="STORE" class="text input">
                <option value="">Select a store</option>
                <?php echo $selectStore;?>
            </select>
            <label for="EmpId" class="label EmpId notEmpty">Store ID</label>
            <div class="errorContainer"><span class="error EmpId"></span></div>
        </div>       
    </div>         
    <div class="reportContainer EMP_Roster displayNone">
         <div class="containers inline reportTypeCont">
            <select id="rosterType" name="ROSTER" class="text input">
                <option value="">Select a Roster Type</option>
                <option value="CURRENT">This Week Roster</option>
                <option value="NEXT">Next Week Roster</option>
            </select>
             <label for="reportTypeEmp" class="label reportTypeEmp notEmpty">Report Type</label>
             <div class="errorContainer"><span class="error reportTypeEmp"></span></div>
        </div>   
        <div class="containers inline reportEmpIdCont">  
            <select id="RosterId" name="STORE" class="text input">
                <option value="">Select a store</option>
                <?php echo $selectStore;?>
            </select>
            <label for="EmpId" class="label EmpId notEmpty">Store ID</label>
            <div class="errorContainer"><span class="error EmpId"></span></div>
        </div>       
    </div>     
        <form method="POST" id="form"  action="../functions/EliasPDF.php" target="_blank">
            <input type ="hidden" id ="hidden_empid" name="empID">
            <input type ="hidden" id ="hidden_type" name="type">
            <input type ="hidden" id ="hidden_roster" name="ROSTER">
            <input type ="hidden" id ="hidden_start" name="START">
            <input type ="hidden" id ="hidden_end" name="END">
            <input type="submit" value="Generate" class="submit button" id="submit">
            <div class="submit wait displayNone"><div class="innerButton animationCircle"></div></div>
        </form>
    </div>    
</div>