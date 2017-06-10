<?php include '../functions/tools.php';
if (session_id() == '') {
        session_start();
    }
if(empty($_SESSION['EMPID']) || $_SESSION['TYPE'] != "CEO" && $_SESSION['TYPE'] != "MANAGER") {
    header("HTTP/1.0 404 Not Found");
    header("Location: ../404.html");
}
$stores = GrabAllData('SELECT ID, SUBURB, STATE, MANAGER FROM STORES');
$dropdown = '<option value="">Please select a store</option>';
for ($i = 0; $i < count($stores['ID']); $i++)
{
    if (empty($stores['MANAGER'][$i])) {
        $dropdown .= "<option value = '{$stores['ID'][$i]}'>{$stores['ID'][$i]} | {$stores['SUBURB'][$i]} | {$stores['STATE'][$i]}</option>";
    } else {
        $dropdown .= "<option manager='{$stores['MANAGER'][$i]}' value = '{$stores['ID'][$i]}'>{$stores['ID'][$i]} | {$stores['SUBURB'][$i]} | {$stores['STATE'][$i]}</option>";
    }
}
if (!empty($_POST['eeid'])) {
    $ID = "E".str_pad($_POST['eeid'], 7, '0', STR_PAD_LEFT);
}
if (!empty($_POST['feeid'])) {
    $ID = $_POST['feeid'];
    
}
$empid = "E".str_pad($_SESSION['EMPID'], 7, '0', STR_PAD_LEFT);
if ($ID === $empid) {
    header("HTTP/1.0 404 Not Found");
    header("Location: ../404.html");
}

?>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PTR - Employee Details</title>
        <link rel="SHORTCUT ICON" href="../images/favico.ico">
        <link rel="icon" href="../images/favicon.png" type="image/ico">
        <link rel="stylesheet" href="../stylesheets/normalize.css" media="none" onload="if(media!='all')media='all'">
        <link rel="stylesheet" href="createNewEmp.css" media="none" onload="if(media!='all')media='all'">
        <script type="application/javascript" src="../javascript/header.js"></script>
        <script type="application/javascript" src="../javascript/jquery-3.2.0.min.js"></script>
        <?php if (!empty($_POST)) { echo '<script>TransfempID = "'.$ID.'";</script>';}?>
        <script type='application/javascript' src="../javascript/editEmp.js"></script>
        <script>
        head.load("../javascript/modernizr-inputs.js",function(){
                if (!Modernizr.inputtypes.date) {
                    head.load("../javascript/jquery-3.2.0.min.js");
                    head.load("../javascript/jquery-ui-1.12.1/jquery-ui.min.css");
                    head.load("../javascript/jquery-ui-1.12.1/jquery-ui.min.js",function(){
                        LoadJqueryUI();});
                };});</script>
    </head>
    <body class="newEmpContent preload">
        <div id="loadingPageOverlay" class="PageLoadingNormal PageLoadingAdd"><div class="loading"><div class="animationCircle"></div></div></div>
        <div id="timerStopPageOverlay" class="timerStopNormal">
                <div class="popUpContainer">
                    <div class="questionLabel"></div>
                    <div class="answerContainer">
                        <div id="timerStopConfirm" class="bigRoundButtons yesConfirm" tabindex="0" title="Yes"></div>
                        <div id="timerStopCancel" class="bigRoundButtons noDeny" tabindex="0" title="No"></div>
                    </div>
                    <div id="loadingStopTimer" class="loadingStopTimer"></div>
                    <a href="#" class="otherLinks displayNone">Close</a>
                </div>
            </div>
        <div class="fullPageOverlay"></div>
        <div class="accountInfo boxContainer preload">
            <div class="leftContainer">
                <div class="containers">
                        <input id="Date" type="text" name="ptr:employees:number" class="text input" value="<?php echo date('d/m/Y');?>" disabled>
                        <label for="date" class="label notEmpty">Date</label>
                </div>
                <div class="containers">
                        <input id="empend" type="text" name="ptr:employees:end" class="text input" disabled>
                        <label for="empend" class="label notEmpty">Status</label>
                </div>
            </div>
            <div class="rightContainer">
                <div class="containers">
                        <input id="emailCard" type="text" name="ptr:employees:email" class="text input" disabled>
                        <label for="emailCard" class="label notEmpty">Email Address</label>
                </div>
            <div class="containers">
                    <input id="empID" type="text" name="ptr:employees:empID" class="text input" disabled>
                    <label for="empID" class="label notEmpty">Employee ID</label>
            </div>
            </div>
    </div>
        <form class="newEmpForm BoxContainer view" method="post" action="#">
            <div class="smallMenu" tabindex="-1"><a class="Menu3dots" href="#" tabindex="0"></a>
                <ul class="smallOptionsCont displayNone">
                    <a class="smallOptionsLinks" clickValue="edit" href="#"><li class="smallOptions">Edit Employee</li></a>
                    <a class="smallOptionsLinks" clickValue="reset" href="#"><li class="smallOptions">Reset Password</li></a>
                    <a class="smallOptionsLinks" clickValue="terminate" href="#"><li class="smallOptions"  tabindex="0">Terminate Employee</li></a>
                </ul></div>
        <div class="baseIndex">
        <div class="containers Radio account">
<?php if ($_SESSION['TYPE'] === 'CEO') {
    echo '<div class="Radiocontainers noClick">
            <input id="radio1" type="radio" name="string:employees:type" value="MANAGER" class=" hiddenRadio accountType" required disabled>
            <label for="radio1"  class="accountType radio">Manager</label></div>';
} ?>
             <div class="Radiocontainers noClick">
            <input id="radio2" type="radio" name="string:employees:type" value="PAYROLL"  class="hiddenRadio accountType" required disabled>
                 <label for="radio2"  class="accountType radio">Payroll</label></div>
             <div class="Radiocontainers noClick">
            <input id="radio3" type="radio" name="string:employees:type" value="EMPLOYEE"  class="hiddenRadio accountType" required disabled>
                 <label for="radio3"  class="accountType radio">Employee</label></div>
        </div>
        <div class="containers">
            <input type="date" id="empstart" name="date:employment:start" class="text input" autocorrect="off" autocapitalize="off" required autocomplete="off" disabled>
            <label for="empstart" class="label empstart notEmpty">Start date</label>
            <div class="errorContainer"><span class="error start"></span></div>
        </div>
        <div class="containers empend widthZero">
            <input type="date" id="empendInput" name="date:employment:end" class="text input" autocorrect="off" autocapitalize="off" required autocomplete="off" disabled>
            <label for="empendInput" class="label empendInput notEmpty">Terminated date</label>
            <div class="errorContainer"><span class="error end"></span></div>
        </div>
        <div class="containers">
            <input type="text" id="position" name="string:employment:position" class="text input" required disabled>
            <label for="position" class="label position">Job title</label>
            <div class="errorContainer"><span class="error position"></span></div>
        </div>
         <div class="containers storeCheckbox">
            <div id="StoreSelectCont" class="containers inline left full">
                <select id="store" name="number:employees:store" class="text input nomarg" required disabled>
                <?php echo $dropdown; ?>
                </select>
                <label for="store" class="label store notEmpty">Store</label>
                <div class="errorContainer"><span class="error store"></span></div>
            </div>
            <div id="StoreCheckCont" class="containers inline width0zero">
                <div class="Checkbox">
                <label for="storeManager" class="label storeManager notEmpty">Store Manager</label>
                <input type="checkbox" id="storeManager" name="bool:stores:manager" value='1' class="text input nomarg checkbox" disabled></div>
                <div class="errorContainer"><span class="error storeManager"></span></div>
            </div>
        </div>
            <div class="containers Radio Type">
        <div class="Radiocontainers noClick">
            <input id="radioType1" type="radio" name="string:employment:type" value="FULL-TIME" class=" hiddenRadio employmentType" required disabled>
            <label for="radioType1"  class="employeeType radio">Full-time</label></div>
             <div class="Radiocontainers noClick">
            <input id="radioType2" type="radio" name="string:employment:type" value="PART-TIME" class="hiddenRadio employmentType" required disabled>
                 <label for="radioType2"  class="employeeType radio">Part-time</label></div>
             <div class="Radiocontainers noClick">
            <input id="radioType3" type="radio" name="string:employment:type" value="CASUAL" class="hiddenRadio employmentType" required disabled>
                 <label for="radioType3"  class="employeeType radio">Casual</label></div>
        </div>
            <div class="containers Float">
                <div class="combinedRadiocontainers Salary Left noClick">
                <input id="radioSalary" type="radio" name="string:employment:salary" value="SALARY" class=" hiddenRadio salary" required disabled>
                <label for="radioSalary"  class="salary radio">Salary</label></div>
                <div class="combinedRadiocontainers Salary Right noClick">
                <input id="radioHourly" type="radio" name="string:employment:salary" value="HOURLY" class=" hiddenRadio salary" required disabled>
                <label for="radioHourly"  class="salary radio">Hourly rate</label></div>
            </div>

            <div class="containers Money">
            <input type="number" id="rate" name="float:employment:rate" class="text input Money" autocorrect="off" autocapitalize="off" required autocomplete="off" disabled>
            <label for="rate" class="label rate Money">Hourly rate</label>
            <div class="errorContainer"><span class="error rate"></span></div>
        </div>
        <div class="containers">
            <input type="text" id="email" name="email:employees:username" class="text input" autocorrect="off" autocapitalize="off" required autocomplete="off" disabled>
            <label for="email" class="label username">Employee email</label>
            <div class="errorContainer"><span class="error email"></span></div>
        </div>            
        <input type="hidden" value="edit" name="form:submit:type">
        <input type="hidden" id="hasStarted" name="bool:submit:started">
        <input type="hidden" id="empIDinput" name="number:employees:empid" value="">
        <input type="submit" id="SubmitForm" value="Save" class="submit button displayNone" disabled>
            <div class="submit wait displayNone"><div class="innerButton animationCircle"></div></div>
            </div></form>
        </body>

    </html>