<?php
function storeStats($storeID, $startInput, $endInput){
if (!empty($storeID)) {
    
	$query1 = 'SELECT STREET, SUBURB FROM STORES WHERE ID = :storeID';
    $bind1 = array(
			array(
				':storeID',
				$storeID
			) 
		);
		$result1 = GrabAllData($query1, $bind1);

date_default_timezone_set('Australia/Brisbane');
$rangeStart = date("Y\WW", $startInput); 
$rangeStart = strtotime("Saturday ".$rangeStart." 12:01 am");
$rangeEnd = date("Y\WW", $endInput); 
$rangeEnd = strtotime("Saturday ".$rangeEnd." 12:01 am");

$sunArray = array();
$normArray = array();
$totArray = array();
$sunday;
$date = date("d/m/Y");
//$weeks = fmod(($rangeEnd - $rangeStart),(60 * 60 * 24)) / (((60 * 60)/24)/7);
$weeks = ceil(($rangeEnd - $rangeStart) / 604800);
    
$query2 = 'SELECT GROSS, NET, TAX, TOTALHOURS, REGHOURS, SUNDAYHOURS, STOREID, GENERATED, PUBLICHOURS, OVERTIME FROM PAY WHERE STOREID = :storeID AND GENERATED > :rangeStart AND GENERATED < :rangeEnd ORDER BY GENERATED ASC';
$bind2 = array(array(':storeID', $storeID
        ),array(
			':rangeStart',
			$rangeStart
		),
        array(
			':rangeEnd',
			$rangeEnd
		));
$result2 = GrabAllData($query2, $bind2);
if(!empty($result2['GENERATED'])){
$gen= $result2['GENERATED'][0];
    }
    else{
        return false;
        $gen = 0;
    }

//$startGen = $gen => $rangeStart and $gen <= $rangeEnd;
$firstSun = $gen - 604800;
$sunday = $firstSun;
    
$empArrayQuery = 'SELECT EMPID FROM EMPLOYEES WHERE INITIALSETUP IS NOT NULL AND INITIALSETUP != 3 AND STORE = :store';
$empArrayBind = array(array(':store', $storeID));
$empArray = GrabAllData($empArrayQuery, $empArrayBind);
  
    
//for ($j=0;$j<count($result2['GENERATED'])/(count($empArray['EMPID'])*$weeks); $j++){
 
    for ($i = 0; $i < $weeks; $i++){
    $sunstart = $sunday - 96280;
	$sunend = $sunday;
	$querysun = 'SELECT SHIFTSTART, SHIFTEND FROM "CLOCK-IN" WHERE SHIFTSTART >= :sunstart AND SHIFTEND <= :sunend';
	$bindsun = array(
		array(
			':sunstart',
			$sunstart
		) ,
		array(
			':sunend',
			$sunend
		)
	);
	$sunstamps = GrabMoreData($querysun, $bindsun);
    array_push($sunArray, $sunstamps);

    $start = $sunday + 236;
	$end = $sunday + 432000;
	$query = 'SELECT SHIFTSTART, SHIFTEND FROM "CLOCK-IN" WHERE SHIFTSTART >= :startVar AND SHIFTEND <= :endVar';
	$normbind = array(
		array(
			':startVar',
			$start
		) ,
		array(
			':endVar',
			$end
		)
	);
	$norm = GrabAllData($query, $normbind);
    array_push($normArray, $norm);

    $start1 = $sunday + 236;
	$end1 = $sunday + 604800;
	$query3 = 'SELECT SHIFTSTART, SHIFTEND FROM "CLOCK-IN" WHERE SHIFTSTART >= :start1 AND SHIFTEND <= :end1';
	$totbind = array(
		array(
			':start1',
			$start1
		) ,
		array(
			':end1',
			$end1
		)
	);
	$tot = GrabMoreData($query3, $totbind);
        array_push($totArray, $tot);

    $sunday = $sunday + 604800;
}

$grossTotal = 0;
$netTotal = 0;
$taxTotal = 0;
$publicTotal = 0;
$hoursTotal = 0;
$overtimeTotal = 0;
$sundayTotal = 0;
$normalTotal = 0;
for($i=0;$i<count($result2['STOREID']);$i++){
    $grossTotal += $result2['GROSS'][$i];
    $netTotal += $result2['NET'][$i];
    $taxTotal += $result2['TAX'][$i];
    $hoursTotal += $result2['TOTALHOURS'][$i];
    $sundayTotal += $result2['SUNDAYHOURS'][$i];
    $normalTotal += $result2['REGHOURS'][$i];
    $publicTotal += $result2['PUBLICHOURS'][$i];
    $overtimeTotal += $result2['OVERTIME'][$i];
    };

$averageTotalWorked = 0;
if (!empty($totArray[0])){
$averageTotalWorked = $hoursTotal / count($totArray);}
    
$averageSundayWorked = 0;
if (!empty($sunArray[0])){
$averageSundayWorked = $sundayTotal / count($sunArray);}
    
$averageNormalWorked = 0;
if (!empty($normArray[0])){
$averageNormalWorked = $normalTotal / count($normArray);}
    
$averageTax = 0;
if (!empty($result2['STOREID'])){
$averageTax = $taxTotal / count($result2['STOREID']);}
$averageTax = '$'.sprintf("%.2f",$averageTax);
    
$averageGrossPay = 0;
if (!empty($result2['STOREID'])){
$averageGrossPay = $grossTotal / count($result2['STOREID']);}
$averageGrossPay = '$'.sprintf("%.2f",$averageGrossPay);
    
$averageNetPay = 0;
if (!empty($result2['STOREID'])){
$averageNetPay = $netTotal / count($result2['STOREID']);}
$averageNetPay = '$'.sprintf("%.2f",$averageNetPay);

$grossTotal = '$'.sprintf("%.2f",$grossTotal);
$netTotal = '$'.sprintf("%.2f",$netTotal);
$taxTotal = '$'.sprintf("%.2f",$taxTotal);

$dataArray = array (
'Report' => 
'Store Statistics',
'ID' => 
'Store '.$storeID,
'dateRange' => 
date("d/m/Y",$startInput)." - ".date("d/m/Y",$endInput),
'suburb' => 
$result1['SUBURB'][0],
'storeid' => 
$storeID,
'street' => 
$result1['STREET'][0],
'date' => 
$date,
'gross' => 
$grossTotal,
'net' => 
$netTotal,
'tax' => 
$taxTotal,
'avg_gross' => 
$averageGrossPay,
'avg_net' => 
$averageNetPay,
'avg_tax' => 
$averageTax,
'hours' => 
$normalTotal,
'holiday_hours' => 
$publicTotal,
'overtime_hours' => 
$overtimeTotal,
'sunday_hours' => 
$sundayTotal,
'tot_hours' => 
$hoursTotal,
'avg_total' => 
$averageTotalWorked,
'avg_shift' => 
$averageNormalWorked,
'avg_sunday_shift' => 
$averageSundayWorked);

//FillPDF('store_report', null, null, $storeID, $dataArray);
FillPDF($dataArray,'store', null, $storeID);
}
}

?>