<?php

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
    $datediff = (($holreqenddate[$i] - $holreqstartdate[$i]) / 86400) + 1;
    $datetotalpending += $datediff; 
}
//echo $datetotalpending;

$datetotalapproved = 0;
FOR($i = 0; $i < count ($holreqidapproved); $i++){   
    $datediffapproved = (($holreqenddateapproved[$i] - $holreqstartdateapproved[$i]) / 86400) + 1;
    $datetotalapproved += $datediffapproved; 
}
//echo $datetotalapproved;

$datetotal = $datetotalpending + $datetotalapproved;

FOR($i = 0; $i < count ($holreqid); $i++){   
    $holreqnumber = $holreqid[$i];
}
//print_r ($holreqnumber);


$accumulation = GrabMoreData("SELECT * FROM ACCUMULATION WHERE EMPID = :EMPID", array(array(":EMPID", $mpID)));

$holileft = $accumulation['LEAVE'] - $datetotal;

if(!empty($_POST['reqid']))
{
    $reqid = $_POST['reqid'];
    $rowdelete = InsertData("DELETE FROM HOLIDAYREQUEST WHERE HOLREQID= :REQID", array(array(":REQID", $reqid)));
    $holileftup = InsertData("UPDATE ACCUMULATION SET HOLILEFT = :HOLUP WHERE EMPID = :EMPID", array(array(":HOLUP", $holileft), array(":EMPID", $mpID)));
}	

?>