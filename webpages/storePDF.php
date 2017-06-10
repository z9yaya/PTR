<?php include "../functions/tools.php";
      include "../functions/storesPDF.php";
//print_r($_POST);
if(!empty($_POST['empID'])) {
    $storeID = $_POST['empID'];
    if(!empty($_POST['START']))
    {
        $startDate =  DatetoUnix($_POST['START']);
/*        echo $startDate;
        echo "</br>";*/
    }
    if(!empty($_POST['END'])){
        $endDate = DatetoUnix($_POST['END']);
/*        echo $endDate;*/
         $res = storeStats($storeID, $startDate, $endDate );
    }
    if (!$res) {
        echo ("<div style='margin: 0 auto;text-align: center;margin-top: 45vh;font-family: sans-serif;font-weight: bold;'>There is no matching data. <br /> 
        Please try a different range.</div>");
        return false;
    }

}



