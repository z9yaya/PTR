<?php include "../functions/tools.php";
if (session_id() == '')
    {
        session_start();
    }
 if(!empty($_SESSION['EMPID']))
 {
$EMPID = $_SESSION['EMPID'];
if(!empty($_GET['payid'])) {
    $payid = $_GET['payid'];
    $filename = 'PAY_'.$payid;
    $location = "C:\\Users\\Administrator\\Documents\\pay_advice\\$EMPID\\$payid.pdf";
    ShowPDF($filename, $location);
}
}

