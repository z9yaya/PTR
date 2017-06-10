<?php include "../functions/tools.php";
//print_r($_POST);
if(!empty($_POST['empID'])) {
    $storeId = $_POST['empID'];
    if($_POST['ROSTER']=="CURRENT");
    {
        $weekEnd = strtotime("Sunday this week 11:59 pm");
        $weekStart = strtotime("Monday this week 12:01 am");
    }
    if($_POST["ROSTER"]=="NEXT"){
        $weekEnd = strtotime("Sunday next week 11:59 pm");
        $weekStart = strtotime("Monday next week 12:01 am");
    }
    $EnglishEnd = date('jS \of M Y',$weekEnd);
    $EnglishStart = date('jS \of M Y',$weekStart);
    $rosterDate =  "$EnglishStart - $EnglishEnd";
    $filename = $storeId."_".$rosterDate.".pdf";
    $location = "C:\Users\Administrator\Documents\\rosters\\".$storeId."\\".$rosterDate.".pdf";
    if(file_exists($location)){
    ShowPDF($filename, $location);
}
    else{
        echo ("<div style='margin: 0 auto;text-align: center;margin-top: 50vh;font-family: sans-serif;font-weight: bold;'>The requested roster cannot be found.</div>");
        return false;
    }
}



