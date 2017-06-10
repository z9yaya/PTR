<?php include 'functions.php';
if (!empty($_POST['ID'])) { 
    $q = $_POST['ID'];
    $weekend = strtotime("Sunday next week 11:59 pm");
    $weekbegin = strtotime("Monday next week 12:01 am");
    $stm = 'SELECT * FROM CLOCKINSHIFTS WHERE BEGIN > :WEEKSTART AND END < :WEEKEND ORDER BY STOREID';
    $b = array(array(":WEEKSTART", $weekbegin),array(":WEEKEND", $weekend));
    $SHIFTS = GrabAllData($stm, $b);
    function sortStores($a, $b, $q) {
        $r = array();
        $i = 0;
        if (is_array($a)) {
            $k = array_keys($a);
            foreach ($a[$b] as $key => $store) {
                if (in_array($store, $q)) {
                    $p = $key - 1;
                    if ($p == -1) {
                        $p = 0;
                    }
                    if ($store !== $a[$b][$p]) {
                        $i = 0;
                    }
                    foreach ($k as $num => $keyName) {
                        $r[$store][$keyName][$i] = $a[$keyName][$key];
                    }
                    $i++;
                }
            }
        }
        return $r;
    }

    function fetchRoster(array $SHIFTS, array $STORES, $ID) {
        $format = 'H:i';
        $shiftArr = array_unique($SHIFTS["EMPLOYEEID"]);
        $days = array(
            'monday' => array(),
            'tuesday' => array(),
            'wednesday' => array(),
            'thursday' => array(),
            'friday' => array(),
            'saturday' => array(),
            'sunday' => array(),
        );
        foreach($SHIFTS['BEGIN'] as $key => $value ) {
            $index = array_search($SHIFTS['STOREID'][$key], $ID);
            $TZ = getTimeZone($STORES['STATE'][$index], true);
            date_default_timezone_set($TZ);
            $emp = $SHIFTS['EMPLOYEEID'][$key];
            $sid = $SHIFTS['SHIFTID'][$key];
            switch (date('N',$value)) {
                case 1:
                    $days['monday'][$emp] = array(array("start.1.$emp" => date($format, $value), "end.1.$emp" => date($format, $SHIFTS['END'][$key])), array('SHIFTID' => $sid));
                    break;
                case 2:
                    $days['tuesday'][$emp] = array(array("start.2.$emp" => date($format, $value), "end.2.$emp" => date($format, $SHIFTS['END'][$key])), array('SHIFTID' => $sid));
                    break;
                case 3:
                    $days['wednesday'][$emp] = array(array("start.3.$emp" => date($format, $value), "end.3.$emp" => date($format, $SHIFTS['END'][$key])), array('SHIFTID' => $sid));
                    break;
                case 4:
                    $days['thursday'][$emp] = array(array("start.4.$emp" => date($format, $value), "end.4.$emp" => date($format, $SHIFTS['END'][$key])), array('SHIFTID' => $sid));
                    break;
                case 5:
                    $days['friday'][$emp] = array(array("start.5.$emp" => date($format, $value), "end.5.$emp" => date($format, $SHIFTS['END'][$key])), array('SHIFTID' => $sid));
                    break;
                case 6:
                    $days['saturday'][$emp] = array(array("start.6.$emp" => date($format, $value), "end.6.$emp" => date($format, $SHIFTS['END'][$key])), array('SHIFTID' => $sid));
                    break;
                case 7:
                    $days['sunday'][$emp] = array(array("start.7.$emp" => date($format, $value), "end.7.$emp" => date($format, $SHIFTS['END'][$key])), array('SHIFTID' => $sid));
                    break;
            }
        }
        return array($shiftArr,$days);
    }
    $sorted = sortStores($SHIFTS, 'STOREID', $q);
    $f = array();
    if (!empty($sorted)) {
    foreach ($sorted as $k => $v) {
        $f[$k] = fetchRoster($v, $_POST, $q);
    }
    }
    echo json_encode($f);
    return true;
}