<?php include '../functions/tools.php';
if (session_id() == '') {
        session_start();
    }
if(empty($_SESSION['EMPID']) || $_SESSION['TYPE'] != "CEO" && $_SESSION['TYPE'] != "MANAGER") {
    header("HTTP/1.0 404 Not Found");
    header("Location: ../404.html");
}
if (empty($_GET['q'])) {
    
}
if (!empty($_POST['esid'])) {
    $ID = $_POST['esid'];
}
?>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php $title = '<title>PTR - Create new store</title>';
        if (empty($_POST['q'])) {
            $title = '<title>PTR - Store Details</title>';
        };
        echo $title;?>
        <link rel="SHORTCUT ICON" href="../images/favico.ico">
        <link rel="icon" href="../images/favicon.png" type="image/ico">
        <link rel="stylesheet" href="../stylesheets/normalize.css" media="none" onload="if(media!='all')media='all'">
        <link rel="stylesheet" href="editStore.css" media="none" onload="if(media!='all')media='all'">
        <script type="application/javascript" src="../javascript/header.js"></script>
        <script type="application/javascript" src="../javascript/jquery-3.2.0.min.js"></script>
        <?php if (!empty($_POST['esid'])) { echo '<script>TransfstoreID = "'.$ID.'";</script>';}?>
        <script type='application/javascript' src="../javascript/editStore.js"></script>
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
        <?php if(empty($_GET['q'])) {echo '
        <div class="accountInfo boxContainer preload">
            <div class="leftContainer">
                <div class="containers">
                        <input id="Date" type="text" name="ptr:employees:number" class="text input" value="'.date('d/m/Y').'" disabled>
                        <label for="date" class="label notEmpty">Date</label>
                </div>
            </div>
            <div class="rightContainer">
                <div class="containers">
                    <a href="#" class="TableLink text input"><span id="managerIDcard"></span><form class="displayNone" action="editEmp.php" method="post"><input id="popupid" name="feeid" value></form></a>
                        <label for="managerIDCard" class="label notEmpty">Manager</label>
                </div>
            <div class="containers">
                    <input id="empID" type="text" name="ptr:employees:empID" class="text input" disabled>
                    <label for="empID" class="label notEmpty">Store ID</label>
            </div>
            </div>
    </div>';}?>
        <?php $form = '<form class="newEmpForm BoxContainer view" method="post" action="#"><div class="smallMenu" tabindex="-1"><a class="Menu3dots" href="#" tabindex="0"></a>
                <ul class="smallOptionsCont displayNone">
                    <a class="smallOptionsLinks" clickValue="edit" href="#"><li class="smallOptions">Edit Store</li></a>';
        if ($_SESSION['TYPE'] === 'CEO') {
            $form .= '<a class="smallOptionsLinks" clickValue="terminate" href="#"><li class="smallOptions"  tabindex="0">Close Store</li></a>';
        }
                $form .= '</ul></div>';
    if (!empty($_GET['q'])) {
    $form = '<form class="newEmpForm BoxContainer" method="post" action="#">';
}; echo $form ?>
        
            
        <div class="baseIndex">
        <div class="containers">
            <input type="text" id="street" name="string:stores:street" class="text input" required  disabled>
            <label for="street" class="label street">Street</label>
            <div class="errorContainer"><span class="error street"></span></div>
        </div>
        <div class="containers">
            <input type="text" id="suburb" name="string:stores:suburb" class="text input" required disabled>
            <label for="suburb" class="label suburb">City/Suburb</label>
            <div class="errorContainer"><span class="error suburb"></span></div>
        </div>
        <div class="containers">
            <input type="text" id="postcode" name="number:stores:postcode" maxlength="4" minlength='4' class="text input" required disabled>
            <label for="postcode" class="label postcode">Postcode</label>
            <div class="errorContainer"><span class="error postcode"></span></div>
        </div>
         <div class="containers storeCheckbox">
            <div id="StoreSelectCont" class="containers inline left full">
                <select id="state" name="string:stores:state" class="text input nomarg" required disabled>
                        <option value="" >Please select an option</option>
                        <option value="ACT">Australian Capital Territory</option>
                        <option value="NSW">New South Wales</option>
                        <option value="NT">Northern Territory</option>
                        <option value="QLD">Queensland</option>
                        <option value="SA">South Australia</option>
                        <option value="TAS">Tasmania</option>
                        <option value="VIC">Victoria</option>
                        <option value="WA">Western Australia</option>
                </select>
                <label for="state" class="label state notEmpty">State/Territory</label>
                <div class="errorContainer"><span class="error state"></span></div>
            </div>
        </div>
            <div class="containers">
            <input type="text" id="phone" name="number:stores:phone" class="text input" maxlength="10" minlength="10" required disabled>
            <label for="phone" class="label phone">Phone</label>
            <div class="errorContainer"><span class="error phone"></span></div>
        </div>
            <?php if ($_SESSION['TYPE'] === 'CEO') {
        echo '<div class="containers">
            <select id="manager" name="number:stores:manager" class="text input" autocorrect="off" autocapitalize="off" required autocomplete="off" disabled></select>
            <label for="manger" class="label manager notEmpty">Manager</label>
            <div class="errorContainer"><span class="error manager"></span></div>
        </div>';
    } else {
        echo '<input type="hidden" id="manager" name="number:stores:manager">';
    } ?>
                    
        <input type="hidden" value="edit" name="form:submit:type">
        <input type="hidden" value="" name="number:stores:id">
        <input type="submit" id="SubmitForm" value="Save" class="submit button displayNone" disabled>
            <div class="submit wait displayNone"><div class="innerButton animationCircle"></div></div>
            </div></form>
    <?php if(empty($_GET['q'])) {
        echo '<div href="#" id="tableHideShow" class="buttonHeading storeEmps">Employees</div><div id="employeeList" class="employeeListCont boxContainer storeEmps">
        <table class="mainTable">
            <tbody>
            </tbody>
        </table>
        </div>';
    }?>
        </body>

    </html>