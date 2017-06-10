<?php include 'tools.php';
if (!empty($_POST && !empty($_POST['STOREID']))) {
    $STOREID = $_POST['STOREID'];
    if (!empty($_POST['DELETE'])) {
        $del = json_decode($_POST['DELETE'], true);
        $stm = 'BEGIN ';
        $bind = array();
        foreach ($del['shift'] as $key => $value) {
        if (!empty($value)) {
                 $tu = "DELETE FROM \"CLOCK-IN\" WHERE SHIFTID = :SHIFTID{$key}; DELETE FROM SHIFTS WHERE ID = :SHIFTID{$key};";
                 array_push($bind, array(":SHIFTID{$key}", $value));
                 $stm .= $tu;
            }
        }
        foreach ($del['emp'] as $key => $value) {
        if (!empty($value)) {
                 $tu = "DELETE FROM \"CLOCK-IN\" WHERE SHIFTID = :SHIFTIDEMP{$key}; DELETE FROM SHIFTS WHERE ID = :SHIFTIDEMP{$key};";
                 array_push($bind, array(":SHIFTIDEMP{$key}", $value));
                 $stm .= $tu;
            }
        }
        $stm .= " END;";
        if (!empty($bind)) {
            echo InsertData($stm,$bind);
        }
    } else {
    $Cweek = 'next';
        
    if ($_POST['WEEK'] == 'true') {
        $Cweek = 'this';
    }
    $stores = GrabData('STORES','STATE','ID', $STOREID);
    $TZ = getTimeZone($stores);
    date_default_timezone_set($TZ);
    $week = array(array(), array(), array(), array(), array(), array(), array());
    $update = array();
    foreach ($_POST as $key => $value) {
        if ($key !== 'STOREID') {
            if (strpos($key, '_') !== false) {
                $explodedShift = explode("_", $key);
                if (!empty($explodedShift[3])) { 
                        $update[$explodedShift[3]]['EMPID'] = $explodedShift[2];
                    switch ($explodedShift[1]) { 
                    case 1:
                        $update[$explodedShift[3]][$explodedShift[0]] = strtotime("monday ".$value." $Cweek week");
                        break;
                    case 2:
                        $update[$explodedShift[3]][$explodedShift[0]] = strtotime("tuesday ".$value." $Cweek week");
                        break;
                                
                    case 3:
                        $update[$explodedShift[3]][$explodedShift[0]] = strtotime("wednesday ".$value." $Cweek week");
                        break;
                    case 4:
                        $update[$explodedShift[3]][$explodedShift[0]] = strtotime("thursday ".$value." $Cweek week");
                        break;
                    case 5:
                        $update[$explodedShift[3]][$explodedShift[0]] = strtotime("friday ".$value." $Cweek week");
                        break;
                    case 6:
                        $update[$explodedShift[3]][$explodedShift[0]] = strtotime("saturday ".$value." $Cweek week");
                        break;
                    case 7:
                        $update[$explodedShift[3]][$explodedShift[0]] = strtotime("sunday ".$value." $Cweek week");
                        break;
                    }
            } else {
                    switch ($explodedShift[1]) {
                    case 1:
                        $week[0] = addToDay($week[0], $explodedShift, strtotime("monday ".$value." $Cweek week"));
                        break;
                    case 2:
                        $week[1] = addToDay($week[1], $explodedShift, strtotime("tuesday ".$value." $Cweek week"));
                        break;
                    case 3:
                        $week[2] = addToDay($week[2], $explodedShift, strtotime("wednesday ".$value." $Cweek week"));
                        break;
                    case 4:
                        $week[3] = addToDay($week[3], $explodedShift, strtotime("thursday ".$value." $Cweek week"));
                        break;
                    case 5:
                        $week[4] = addToDay($week[4], $explodedShift, strtotime("friday ".$value." $Cweek week"));
                        break;
                    case 6:
                        $week[5] = addToDay($week[5], $explodedShift, strtotime("saturday ".$value." $Cweek week"));
                        break;
                    case 7:
                        $week[6] = addToDay($week[6], $explodedShift, strtotime("sunday ".$value." $Cweek week"));
                        break;
                    }
                }
            } 
        }

    }
    $stm = 'DECLARE RID INTEGER; BEGIN ';
    $stmU = 'BEGIN ';
    $bind = array();
    $bindU = array();
    foreach ($week as $key => $value) {
        if (!empty($value)) {
             foreach ($value as $emp => $shift) {
                 $t = "INSERT INTO SHIFTS (STOREID, BEGIN, END) values (:STOREID{$key}{$emp}, :SHIFTBEGIN{$key}{$emp}, :SHIFTEND{$key}{$emp}) returning ID into RID; INSERT INTO \"CLOCK-IN\" (SHIFTID, EMPLOYEEID) VALUES (RID, :EMPID{$key}{$emp});";
                 array_push($bind, array(":STOREID{$key}{$emp}", $STOREID));
                 array_push($bind, array(":SHIFTBEGIN{$key}{$emp}", $shift['start']));
                 array_push($bind, array(":SHIFTEND{$key}{$emp}", $shift['end']));
                 array_push($bind, array(":EMPID{$key}{$emp}", $emp));
                 $stm .= $t;
        }
    }
}
foreach ($update as $key => $value) {
    if (!empty($value)) {
             $tu = "UPDATE SHIFTS SET BEGIN = :SHIFTBEGIN{$key}start, END = :SHIFTEND{$key}end WHERE ID = :SHIFTID{$key}; UPDATE \"CLOCK-IN\" SET EMPLOYEEID = :EMPID{$key} WHERE SHIFTID = :SHIFTID{$key};";
             array_push($bindU, array(":SHIFTBEGIN{$key}start", $value['start']));
             array_push($bindU, array(":SHIFTEND{$key}end", $value['end']));
             array_push($bindU, array(":SHIFTID{$key}", $key));
             array_push($bindU, array(":EMPID{$key}", $value['EMPID']));
             $stmU .= $tu;
    }
}
    $stm .= "END;";
    $stmU .= " END;";
    if (!empty($bind)) {
        echo InsertData($stm,$bind);
    }
    if (!empty($bindU)) {
        echo InsertData($stmU,$bindU);
    }
    }
    

}
