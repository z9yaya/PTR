<?php include "../functions/tools.php";
if (session_id() == '') {
        session_start();
    }
if(empty($_SESSION['EMPID']) || $_SESSION['TYPE'] != "CEO" && $_SESSION['TYPE'] != "MANAGER") {
    header("HTTP/1.0 404 Not Found");
    header("Location: ../404.html");
}
$stores = GrabAllData('SELECT ID, SUBURB, STATE, MANAGER FROM STORES');
$dropdown = '';
for ($i = 0; $i < count($stores['ID']); $i++)
{
    if (empty($stores['MANAGER'][$i])) {
        $dropdown .= "<option value = '{$stores['ID'][$i]}'>{$stores['ID'][$i]} | {$stores['SUBURB'][$i]} | {$stores['STATE'][$i]}</option>";
    } else {
        $dropdown .= "<option manager='{$stores['MANAGER'][$i]}' value = '{$stores['ID'][$i]}'>{$stores['ID'][$i]} | {$stores['SUBURB'][$i]} | {$stores['STATE'][$i]}</option>";
    }
}
?>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PTR - Create new employee</title>
        <link rel="SHORTCUT ICON" href="../images/favico.ico">
        <link rel="icon" href="../images/favicon.png" type="image/ico">
        <link rel="stylesheet" href="../stylesheets/normalize.css" media="none" onload="if(media!='all')media='all'">
        <link rel="stylesheet" href="createNewEmp.css" media="none" onload="if(media!='all')media='all'">
        <script type="application/javascript" src="../javascript/header.js"></script>
        <script type="application/javascript" src="../javascript/jquery-3.2.0.min.js"></script>
        <script>$.getScript("../javascript/createNewEmp.js");
            head.load("../javascript/modernizr-inputs.js",function(){
                if (!Modernizr.inputtypes.date) {
                    head.load("../javascript/jquery-3.2.0.min.js");
                    head.load("../javascript/jquery-ui-1.12.1/jquery-ui.min.css");
                    head.load("../javascript/jquery-ui-1.12.1/jquery-ui.min.js",function(){
                        LoadJqueryUI();});
                };});</script>
    </head>
    <body class="newEmpContent preload">
        <form class="newEmpForm BoxContainer" method="post" action="#">
        <div class="containers Radio account">
<?php if ($_SESSION['TYPE'] === 'CEO') {
    echo '<div class="Radiocontainers">
            <input id="radio1" type="radio" name="string:employees:type" value="MANAGER" class=" hiddenRadio accountType" required>
            <label for="radio1"  class="accountType radio">Manager</label></div>';
} ?>
             <div class="Radiocontainers">
            <input id="radio2" type="radio" name="string:employees:type" value="PAYROLL"  class="hiddenRadio accountType" required>
                 <label for="radio2"  class="accountType radio">Payroll</label></div>
             <div class="Radiocontainers">
            <input id="radio3" type="radio" name="string:employees:type" value="EMPLOYEE"  class="hiddenRadio accountType" required>
                 <label for="radio3"  class="accountType radio">Employee</label></div>
        </div>
        <div class="containers">
            <input type="date" id="start" name="date:employment:start" class="text input" autocorrect="off" autocapitalize="off" required autocomplete="off">
            <label for="start" class="label start notEmpty">Start date</label>
            <div class="errorContainer"><span class="error start"></span></div>
        </div>
        <div class="containers">
            <input type="text" id="position" name="string:employment:position" class="text input" required>
            <label for="position" class="label position">Job title</label>
            <div class="errorContainer"><span class="error position"></span></div>
        </div>
        <div class="containers storeCheckbox">
            <div id="StoreSelectCont" class="containers inline left full">
                <select id="store" name="number:employees:store" class="text input nomarg" required>
                <option value=''>Please select a store</option>
                    <?php echo $dropdown; ?>
                </select>
                <label for="store" class="label store notEmpty">Store</label>
                <div class="errorContainer"><span class="error store"></span></div>
            </div>
            <div id="StoreCheckCont" class="containers inline width0zero">
                <div class="Checkbox">
                <label for="storeManager" class="label storeManager notEmpty">Store Manager</label>
                <input type="checkbox" id="storeManager" name="bool:stores:manager" value='1' class="text input nomarg checkbox"></div>
                <div class="errorContainer"><span class="error storeManager"></span></div>
            </div>
        </div>
            <div class="containers Radio Type">
        <div class="Radiocontainers">
            <input id="radioType1" type="radio" name="string:employment:type" value="FULL-TIME" class=" hiddenRadio employmentType" required>
            <label for="radioType1"  class="employeeType radio">Full-time</label></div>
             <div class="Radiocontainers">
            <input id="radioType2" type="radio" name="string:employment:type" value="PART-TIME" class="hiddenRadio employmentType" required>
                 <label for="radioType2"  class="employeeType radio">Part-time</label></div>
             <div class="Radiocontainers">
            <input id="radioType3" type="radio" name="string:employment:type" value="CASUAL" class="hiddenRadio employmentType" required>
                 <label for="radioType3"  class="employeeType radio">Casual</label></div>
        </div>
            <div class="containers Float">
                <div class="combinedRadiocontainers Salary Left">
                <input id="radioSalary" type="radio" name="string:employment:salary" value="SALARY" class=" hiddenRadio salary" required>
                <label for="radioSalary"  class="salary radio">Salary</label></div>
                <div class="combinedRadiocontainers Salary Right checked">
                <input id="radioHourly" type="radio" name="string:employment:salary" value="HOURLY" class=" hiddenRadio salary" required checked>
                <label for="radioHourly"  class="salary radio">Hourly rate</label></div>
            </div>

            <div class="containers Money">
            <input type="text" id="rate" name="float:employment:rate" class="text input Money" autocorrect="off" autocapitalize="off" required autocomplete="off">
            <label for="rate" class="label rate Money">Hourly rate</label>
            <div class="errorContainer"><span class="error rate"></span></div>
        </div>
        <div class="containers">
            <input type="text" id="username" name="email:employees:username" class="text input" autocorrect="off" autocapitalize="off" required autocomplete="off">
            <label for="username" class="label username">Employee email</label>
            <div class="errorContainer"><span class="error username"></span></div>
        </div>            
        <div class="containers">
        <input type="text" id="username2" name="email:employees:username2" class="text input" autocorrect="off" autocapitalize="off" required autocomplete="off">
            <label for="username2" class="label username">Repeat employee email</label>
            <div class="errorContainer"><span class="error username2"></span></div>
        </div>
        
        <input type="submit" value="Create" class="submit button">
            <div class="submit wait displayNone"><div class="innerButton animationCircle"></div></div></form>
        </body>

    </html>