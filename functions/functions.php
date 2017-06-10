<?php include 'tools.php';
//functions.php by Yannick Mansuy
///function used to connect to create a new connection object to connect to the database
date_default_timezone_set('Australia/Brisbane');
$GLOBALS['timelate'] = strtotime("11:59 pm");
$GLOBALS['timeearly'] = strtotime("12:01 am");


//used to check if shift has been started to set the time on timer accordingly
//Checks the SESSION for any start epoch,
//if none then queries the database, table "CLOCK-IN" for a started shift, then resumes timer.
//if none, gets the start time of the shift set by supervisor and does not start timer.
function checkShift()
{
    if (session_id() == '')
        {
            session_start();
        }
    if (!empty($_SESSION['EMPID']))
        {//user logged in
            
            unset($_SESSION["SHIFTSTART"]);
            unset($_SESSION["SHIFTID"]);
            unset($_SESSION["SHIFTEND"]);
            unset($_SESSION["SHIFTBEGIN"]);
            
            $EMPID = $_SESSION["EMPID"];
            $table = '"CLOCK-IN"';
            $column = "SHIFTSTART";
            $attribute = $EMPID;
            $wherecolumn = "EMPLOYEEID";
            $columnNew = "SHIFTID";
        
            $columnStart = "SHIFTSTART";
            $tableStart = '"CLOCK-IN"';
            $wherecolumnStart = "EMPLOYEEID";
            $wherecolumnStart0 = "SHIFTEND";
            $queryStart = 'SELECT COUNT('.$columnStart.') AS '.$columnStart.' FROM '.$tableStart.' WHERE '.$wherecolumnStart.' = :EMPID AND '.$wherecolumnStart0.' IS NULL';
            $queryFunction = GrabMoreData($queryStart,
                array(array(":EMPID",$EMPID)));
        
                if ($queryFunction["$columnStart"] == 1)
                {//1 Shift is still going
                    $query = 'SELECT SHIFTID,SHIFTSTART,BEGIN,END FROM CLOCKINSHIFTS WHERE EMPLOYEEID = :EMPLOYEEID AND SHIFTEND IS NULL AND SHIFTSTART IS NOT NULL';
                    $bind = array(array(":EMPLOYEEID", $attribute));
                    $data = GrabMoreData($query, $bind);
                    if (!empty($data))
                        {
                            $data["SHIFTSTART"] = $data["SHIFTSTART"]*1000;
                            $data["END"] = $data["END"]*1000;
                            $data["BEGIN"] = $data["BEGIN"]*1000;
                            $_SESSION["SHIFTSTART"] = $data["SHIFTSTART"];
                            $_SESSION["SHIFTID"] = $data["SHIFTID"];
                            $_SESSION["SHIFTEND"] = $data["END"];
                            $_SESSION["SHIFTBEGIN"] = $data["BEGIN"];
                            //return json_encode($data);
                            return $data;
                            return $data;
                        }
                }
                    else
                    {
                        
                        if (CheckExistExt($attribute, $table, $columnNew, $wherecolumn))
                        {//Shift assigned but not started
                            $now = time();
                            $query = 'SELECT SHIFTID, BEGIN, END FROM "CLOCK-IN" LEFT JOIN SHIFTS ON "CLOCK-IN".SHIFTID=SHIFTS.ID WHERE EMPLOYEEID = :EMPLOYEEID AND SHIFTEND IS NULL AND SHIFTSTART IS NULL AND BEGIN < :TIMELATE AND BEGIN > :TIMEEARLY';
                            $bind = array(array(":EMPLOYEEID", $attribute), array(":TIMELATE",$GLOBALS["timelate"]), array(":TIMEEARLY",$GLOBALS["timeearly"]));
                            $data = GrabMoreData($query, $bind);
                            if (!empty($data))
                            {   
                                if (($data["BEGIN"] - 18000) < $now)
                                {//3 hours before shift start
                                    unset($_SESSION['SHIFTSTART']);
                                    $_SESSION["SHIFTID"] = $data["SHIFTID"];
                                    $_SESSION["SHIFTEND"] = $data["END"]*1000;
                                    $_SESSION["SHIFTBEGIN"] = $data["BEGIN"]*1000; 
                                }
                                else
                                {//no shift starting in less than 3 hours
                                    echo "out";
                                    return $data;
                                    return false;
                                }
                                $data["BEGIN"] = $data["BEGIN"]*1000;
                                $data["END"] = $data["END"]*1000;
                                return $data;
                            }
                            else
                            {//no shift not started today
                                $query = 'SELECT SHIFTSTART,SHIFTEND,SHIFTID FROM CLOCKINSHIFTS WHERE EMPLOYEEID = :EMPLOYEEID AND SHIFTEND < :TIMELATE AND SHIFTEND > :TIMEEARLY';
                                $bind = array(array(":EMPLOYEEID", $attribute), array(":TIMELATE",$GLOBALS["timelate"]), array(":TIMEEARLY",$GLOBALS["timeearly"]));
                                $data = GrabMoreData($query, $bind);
                                if (!empty($data["SHIFTID"])) 
                                {
                                    $data["SHIFTSTART"] = $data["SHIFTSTART"]*1000;
                                    $data["SHIFTEND"] = $data["SHIFTEND"]*1000;
                                    return $data;
                                }
                                else
                                {
                                    return false;
                                }                            
                            }
                        }   
                }
                
            }
    else
    {//user not logged in
        echo "login";
        return false;
    }
}
//used to start a new shift
//checks the session for a shift ID set in the checkShift function,
//insert the current time in "CLOCK-IN" table in the shiftstart column which is then put into session
function startShift()
{
    if (session_id() == '')
    {
        session_start();
    }
    if(isset($_SESSION['EMPID']))
    {
        if (!empty($_SESSION['EMPID']))
            {
                if (isset($_SESSION["SHIFTID"]) && !empty($_SESSION['SHIFTID']) && !isset($_SESSION["SHIFTSTART"]))
                {
                    $id = $_SESSION["SHIFTID"];
                    if (!CheckExistExt($id, '"CLOCK-IN"', "SHIFTSTART", "SHIFTID"))
                    {
                        $timenow = time();
                        $query = 'UPDATE "CLOCK-IN" SET SHIFTSTART=:TIME WHERE SHIFTID = :ID';
                        $bind = array(array(":TIME",$timenow),array(":ID",$id));
                        $insert = InsertData($query, $bind);
                        if ($insert == "success")
                        {
                            $_SESSION["SHIFTSTART"] = $timenow*1000;
                            echo "success";
                            return true;
                        }
                    }
                    else
                    {
                        echo "exist";
                        return false;
                    }
                }
                else
                {
                    //print_r($_SESSION);
                    echo "started";
                    return false;
                }

            }
            else
            {
                echo "login";
                return false;
            }
    }
    else
    {
        echo "login";
        return false;
    }
}

function stopShift()
{
    if (session_id() == '')
    {
        session_start();
    }
    if(isset($_SESSION['EMPID']))
    {
        if (!empty($_SESSION['EMPID']))
            {
                if (isset($_SESSION["SHIFTID"]) && !empty($_SESSION['SHIFTID']) && isset($_SESSION["SHIFTSTART"]) && !empty($_SESSION['SHIFTSTART']))
                {
                    $id = $_SESSION["SHIFTID"];
                    $timenow = time();
                    $query = 'UPDATE "CLOCK-IN" SET SHIFTEND=:TIME WHERE SHIFTID = :ID AND SHIFTEND IS NULL';
                    $bind = array(array(":TIME",$timenow),array(":ID",$id));
                    $insert = InsertData($query, $bind);
                    if ($insert == "success")
                    {
                        $_SESSION["SHIFTEND"] = $timenow*1000;
                        unset($_SESSION["SHIFTSTART"]);
                        echo "success";
                        return true;
                    }
                    else
                    {
                        print_r($insert);
                    }
                }
                else
                {
                    echo "notstarted";
                    return false;
                }

            }
            else
            {
                echo "login";
                return false;
            }
    }
    else
    {
        echo "login";
        return false;
    }
}

function AddTimerCheck()
{
    if (session_id() == '')
    {
        session_start();
    }
    if(isset($_SESSION['SHIFTID']) && !empty($_SESSION['SHIFTID']))
            {
                $now = time()*1000;
                if(!empty($_SESSION['SHIFTSTART']) || $_SESSION["SHIFTBEGIN"] - 10800000 < $now //up to 3 hours before
                   
                   && $_SESSION["SHIFTEND"] > $now ){
                    echo 'headerTimer';
                } else {
                    echo 'headerNoTimer';
                }} else {
        echo 'headerNoTimer';
    }
}

function DecodeThis($string)
{
    $res =  base64_decode(str_pad(strtr($string, '-_', '+/'), strlen($string) % 4, '=', STR_PAD_RIGHT)); 
    $res = explode ("." , $res);
    return $res;
}

function createRoster(array $SHIFTS) {
    $shiftArr = array_unique($SHIFTS["EMPID"]);
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
        if (!empty($value)) {
        switch (date('N',$value)) {
            case 1:
                $days['monday'][$SHIFTS['EMPID'][$key]] = array('BEGIN' => $value, 'END' => $SHIFTS['END'][$key]);
                break;
            case 2:
                $days['tuesday'][$SHIFTS['EMPID'][$key]] = array('BEGIN' => $value, 'END' => $SHIFTS['END'][$key]);
                break;
            case 3:
                $days['wednesday'][$SHIFTS['EMPID'][$key]] = array('BEGIN' => $value, 'END' => $SHIFTS['END'][$key]);
                break;
            case 4:
                $days['thursday'][$SHIFTS['EMPID'][$key]] = array('BEGIN' => $value, 'END' => $SHIFTS['END'][$key]);
                break;
            case 5:
                $days['friday'][$SHIFTS['EMPID'][$key]] = array('BEGIN' => $value, 'END' => $SHIFTS['END'][$key]);
                break;
            case 6:
                $days['saturday'][$SHIFTS['EMPID'][$key]] = array('BEGIN' => $value, 'END' => $SHIFTS['END'][$key]);
                break;
            case 7:
                $days['sunday'][$SHIFTS['EMPID'][$key]] = array('BEGIN' => $value, 'END' => $SHIFTS['END'][$key]);
                break;
        }
    }
    }
    return array($shiftArr,$days);
}

function showRoster($SHIFTS, $shiftArr, $days, $dayOfWeek, $type = 'store') {
    $res = '';
    $format = 'H:i';
    if ($type == 'store') {
         $res .= '<table class="mainTable tablesaw tablesaw-stack" data-tablesaw-mode="stack" data-tablesaw-hide-empty>
        <thead class="tablehead">
            <tr class="tableHeaderRow">
                <th scope="col" class="tableHeaders" data-tablesaw-priority="persist">Name</th>';
        $shifts = array("Staff", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"); 
        for($i=1;$i<=7;$i++){
            switch ($i) {
                case $dayOfWeek:
                     $res .= '<th scope="col" class="tableHeaders" data-tablesaw-priority="persist">'.$shifts[$i].'</th>';
                    break;
                case $dayOfWeek+1:
                    $res .= '<th scope="col" class="tableHeaders" data-tablesaw-priority="1">'.$shifts[$i].'</th>';
                    break;
                case $dayOfWeek+2:
                    $res .= '<th scope="col" class="tableHeaders" data-tablesaw-priority="1">'.$shifts[$i].'</th>';
                    break;
                default:
                    $res .= '<th scope="col" class="tableHeaders" data-tablesaw-priority="1">'.$shifts[$i].'</th>';
            }
        };
        $res .= '</tr></thead><tbody>';
        foreach($shiftArr as $n => $id ) 
        {
            $shiftEmp = $id;
            $username = "N/A";
            if (!empty($SHIFTS["F_NAME"][$n])) {
                $username = $SHIFTS["F_NAME"][$n].' '.$SHIFTS["L_NAME"][$n];
            }
            $res .= '<tr class="tableRow"><td class="tableCell">'."E".str_pad($SHIFTS['EMPID'][$n], 7, '0', STR_PAD_LEFT).'<span class="empName">'.$username.'</span>'.'</td>';
            foreach($days as $key => $value ) {

                if (!empty($value[$shiftEmp])) {
                $res .= "<td class='tableCell'>Start: ".date($format, $value[$shiftEmp]['BEGIN'])."<span class='shiftEnd'>End: ".date($format, $value[$shiftEmp]['END'])."</td>";
                } else {
                    $res .= "<td class='tableCell'></td>";
                }

            }
             $res .= "</tr>";
        }
        $res .= '</tbody></table>';
    } elseif ($type == 'emp') {
         $res .= '<table class="mainTable tablesaw tablesaw-stack" data-tablesaw-mode="stack" data-tablesaw-hide-empty>
        <thead class="tablehead">
            <tr class="tableHeaderRow">';
        $shifts = array("Staff", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"); 
        for($i=1;$i<=7;$i++){
            switch ($i) {
                case $dayOfWeek:
                     $res .= '<th scope="col" class="tableHeaders" data-tablesaw-priority="persist">'.$shifts[$i].'</th>';
                    break;
                case $dayOfWeek+1:
                    $res .= '<th scope="col" class="tableHeaders" data-tablesaw-priority="1">'.$shifts[$i].'</th>';
                    break;
                case $dayOfWeek+2:
                    $res .= '<th scope="col" class="tableHeaders" data-tablesaw-priority="1">'.$shifts[$i].'</th>';
                    break;
                default:
                    $res .= '<th scope="col" class="tableHeaders" data-tablesaw-priority="1">'.$shifts[$i].'</th>';
            }
        };
        $res .= '</tr></thead><tbody>';
        foreach($shiftArr as $n => $id ) 
        {
            $shiftEmp = $id;
            $res .= '<tr class="tableRow">';
            foreach($days as $key => $value ) {

                if (!empty($value[$shiftEmp])) {
                $res .= "<td class='tableCell'>Start: ".date($format, $value[$shiftEmp]['BEGIN'])."<span class='shiftEnd'>End: ".date($format, $value[$shiftEmp]['END'])."</td>";
                } else {
                    $res .= "<td class='tableCell'></td>";
                }

            }
             $res .= "</tr>";
        }
        $res .= '</tbody></table>';
    }
    return $res;
}

function showPDFRoster($SHIFTS, $shiftArr, $days, $englishDate, $storeName) {
    $format = 'H:i';
    $res = '<!DOCTYPE html><html><head><title>Roster</title><style>.cont{width:1230px;margin: 0 auto;}.mainTable{width:1230px;max-width:100%;empty-cells:show;border-collapse:collapse;padding:0;font-family:Lato,sans-serif;font-size:14.4px;margin-bottom:25px;margin-top:10px;border:1px solid #e8e8e8;table-layout:fixed}.mainTable *{box-sizing:border-box}.tableCell,.tableHeaders{padding:.5em .7em;text-align:left;vertical-align:middle;border-left:1px solid #ececec;border-right:1px solid #ececec}.tableHeaders{padding:.75rem;text-align:left}.tableHeaderRow{border-bottom:1px solid #e8e8e8;border-top:1px solid #e8e8e8}.tableRow:nth-child(even){background:#f5f8ff}.tableRow .tableCell:first-of-type{font-weight:700;color:#6495ed}.shiftEnd{display:block}.rosterTitle{display:block;font-size:20px;color:#808080;font-family:"Lato", sans-serif;margin-top:25px;}.rosterStore{display:block;font-family:"Lato", sans-serif;margin-top:10px;margin-bottom:10px;font-size:17px;float:right;color:cornflowerblue;}.empName {color: black;display: block;font-weight: normal;}
    </style></head><body><div class="cont"><span class="rosterTitle">Roster for week: '.$englishDate.'</span><span class="rosterStore">'.$storeName.' Store</span>';
         $res .= '<table class="mainTable">
        <thead class="tablehead">
            <tr class="tableHeaderRow">
                <th class="tableHeaders">Name</th>';
        $shifts = array("Staff", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"); 
        for($i=1;$i<=7;$i++){
            $res .= '<th class="tableHeaders">'.$shifts[$i].'</th>';
        };
        $res .= '</tr></thead><tbody>';
        foreach($shiftArr as $n => $id ) 
        {
            $shiftEmp = $id;
            $username = "N/A";
            if (!empty($SHIFTS["F_NAME"][$n])) {
                $username = $SHIFTS["F_NAME"][$n].' '.$SHIFTS["L_NAME"][$n];
            }
            $res .= '<tr class="tableRow"><td class="tableCell">'."E".str_pad($SHIFTS['EMPID'][$n], 7, '0', STR_PAD_LEFT).'<span class="empName">'.$username.'</span>'.'</td>';
            foreach($days as $key => $value ) {

                if (!empty($value[$shiftEmp])) {
                $res .= "<td class='tableCell'>Start: ".date($format, $value[$shiftEmp]['BEGIN'])."<span class='shiftEnd'>End: ".date($format, $value[$shiftEmp]['END'])."</span></td>";
                } else {
                    $res .= "<td class='tableCell'></td>";
                }

            }
             $res .= "</tr>";
        }
        $res .= '</tbody></table></div></body></html>';
    return $res;
}

function showNewRoster($emps, $notStore) {
    $options = '<option value="" selected></option><option>04:00</option><option>04:30</option><option>05:00</option><option>05:30</option><option>06:00</option><option>06:30</option><option>07:00</option><option>07:30</option><option>08:00</option><option>08:30</option><option>09:00</option><option>09:30</option><option>10:00</option> <option>10:30</option><option>11:00</option><option>11:30</option><option>12:00</option><option>12:30</option><option>13:00</option> <option>13:30</option><option>14:00</option><option>14:30</option><option>15:00</option><option>15:30</option><option>16:00</option> <option>16:30</option><option>17:00</option><option>17:30</option><option>18:00</option><option>18:30</option><option>19:00</option> <option>19:30</option><option>20:00</option><option>20:30</option>';
    $format = 'H:i';
    echo '<table class="mainTable tablesaw tablesaw-stack noClick"  data-tablesaw-mode="stack"><thead class="tablehead"><tr class="tableHeaderRow"><th scope="col" class="tableHeaders" data-tablesaw-priority="persist">Staff</th>';
    $days = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"); 
    for($i=0;$i<7;$i++){
        echo '<th scope="col" class="tableHeaders" data-tablesaw-priority="1">'.$days[$i].'</th>';
    };
    echo '</tr></thead>
            <tbody>';
    for($j=0;$j<count($emps['EMPID']);$j++) {
        $empsID = "E".str_pad($emps['EMPID'][$j], 7, '0', STR_PAD_LEFT);
        $empsCell = '<a href="#" class="TableLink">'.$empsID.'<form class="empLinkForm" action="editEmp.php" method="post"><input type="hidden" name="eeid" value="'.$emps['EMPID'][$j].'"></form></a>';
        echo '<tr class="tableRow"><td class="tableCell"><b>'.$empsCell.'</b>'.$emps['F_NAME'][$j].' '.$emps['L_NAME'][$j].'</td>';
        for ($k=1;$k<8;$k++) {
            $startID = "start.$k.{$emps['EMPID'][$j]}";
            $endID = "end.$k.{$emps['EMPID'][$j]}";
            echo '<td class="tableCell"><div class="addShift" title="Add shift"></div><div class="containers displayNone storeCheckbox"><select id="'.$startID.'" name="'.$startID.'" class="input dropTime notConfirmed text start startSelect">'.$options.'</select><label for="'.$startID.'" class="label start notEmpty">Start</label><div class="errorContainer"><span class="error '.$startID.'"></span></div></div>';
            echo '<div class="containers displayNone storeCheckbox nomarg"><select id="'.$endID.'" name="'.$endID.'" class="input dropTime notConfirmed text end endSelect"></select><label for="'.$endID.'" class="label end notEmpty">End</label><div class="errorContainer"><span class="error '.$endID.'"></span></div></div></td>';
        }
        echo '</tr>';
    }
    $notOptions = "<option action='remove' class='bold'>Remove employee</option><option value='' class='Default' selected>Select an employee</option>";
    for($n=0;$n<count($notStore['EMPID']);$n++) {
        $notID = "E".str_pad($notStore['EMPID'][$n], 7, '0', STR_PAD_LEFT);
        $notOptions .= "<option value='{$notStore['EMPID'][$n]}'empName= '{$notStore['F_NAME'][$n]} {$notStore['L_NAME'][$n]}'>$notID | {$notStore['F_NAME'][$n]} {$notStore['L_NAME'][$n]}</option>";
    }
    echo "<tr class='tableRow addOne displayNone'><td class='tableCell cellSelect'><div class='removeEmpBt displayNone' title='Delete employee'></div><select class='text input nomarg dropdownBg notStoreSelect'>$notOptions</select><div class='nameOverlay displayNone'></div></td>";
    for ($k=1;$k<8;$k++) {
            $startID = "start.$k.";
            $endID = "end.$k.";
            echo '<td class="tableCell"><div class="addShift" title="Add shift"></div><div class="containers displayNone storeCheckbox"><select id="'.$startID.'" name="'.$startID.'" class="input dropTime notConfirmed text start startSelect">'.$options.'</select><label for="'.$startID.'" class="label start notEmpty">Start</label><div class="errorContainer"><span class="error '.$startID.'"></span></div></div>';
            echo '<div class="containers displayNone storeCheckbox nomarg"><select id="'.$endID.'" name="'.$endID.'" class="input dropTime notConfirmed text end endSelect"></select><label for="'.$endID.'" class="label end notEmpty">End</label><div class="errorContainer"><span class="error '.$endID.'"></span></div></div></td>';
        }
    echo '</tr>';
    echo '</tbody></table><div class="addEmp"><div class="logoContainer"><span class="addEmpText">Add employee</span></div></div>';
}

function fetchSingleRoster () {
      if (session_id() == '')
        {
            session_start();
        }
    if (!empty($_SESSION['EMPID']))
        {//user logged in
    date_default_timezone_set('Australia/Brisbane');
    $weekEnd = strtotime("Sunday this week 11:59 pm");
    $weekStart = strtotime("Monday this week 12:01 am");
    if (date("H") > "18") {
        $dayOfWeek = date("N")+1;
    } else {
        $dayOfWeek = date("N");
    }
    $EMPID = $_SESSION['EMPID'];
    $storeDet = GrabMoreData('SELECT ID, STORES.SUBURB AS SUBURB, STORES.STATE AS STATE FROM STORES INNER JOIN EMPLOYEES ON STORES.ID = EMPLOYEES.STORE WHERE EMPID = :EMPID', array(array(":EMPID", $EMPID)));
    $storeId = $storeDet['ID'];
    $storeName = $storeDet['SUBURB'];
    if (empty($storeDet['ID'])) {
        return false;
}
    date_default_timezone_set(getTimeZone($storeDet));
    $weekEnd = strtotime("Sunday this week 11:59 pm");
    $weekStart = strtotime("Monday this week 12:01 am");
    $SHIFTS = GrabAllData("SELECT EMPID ,END, BEGIN FROM CLOCKINSHIFTS RIGHT JOIN EMPLOYEES ON CLOCKINSHIFTS.EMPLOYEEID = EMPLOYEES.EMPID WHERE STOREID = :ID AND EMPLOYEEID = :EMPID AND BEGIN > :WEEKSTART AND END < :WEEKEND OR STORE = :ID AND EMPLOYEEID = :EMPID AND BEGIN IS NULL AND END IS NULL ORDER BY BEGIN ASC", array(array(':ID', $storeId),array(':WEEKSTART', $weekStart),array(':WEEKEND', $weekEnd),array(':EMPID', $EMPID))); 
    $empty = 0;
//    foreach ($SHIFTS['BEGIN'] as $k => $v)
//    {
//        if (empty($v)) {
//            $empty++;
//        }
//    }
//    if (count($SHIFTS['BEGIN']) == $empty) {
//        echo 'noEmp';
//        return false;
//    }
    //$sendTo = array_unique($SHIFTS['EMAIL']);
    $res = createRoster($SHIFTS);
    $shiftArr = $res[0];
    $days = $res[1];
    echo showRoster($SHIFTS, $shiftArr, $days, $dayOfWeek, 'emp');
    }
}

function expend(){
$rangeStart = strtotime("Sunday last week 12:01 am");
$rangeEnd = strtotime("Sunday last week 11:30 pm");
    
$query = 'SELECT GROSS FROM PAY WHERE GENERATED >= :rangeStart AND GENERATED <= :rangeEnd';
$bind = array(
		array(
			':rangeStart',
			$rangeStart
		) ,
		array(
			':rangeEnd',
			$rangeEnd
		)
	);
$expGrab = GrabAllData($query, $bind);

    
$grossTotal = 0;
for ($i = 0; $i < count($expGrab['GROSS']); $i++){
     $grossTotal += $expGrab['GROSS'][$i]; 
}

$grossTotal = '$'.sprintf("%.2f",$grossTotal);
return $grossTotal;
}

function checkInputs ($arr) {
    $errors = array();
    foreach ($arr as $key => $value) {
        if ($value) {
            if ((explode(":", $key)[0]) == "date") {
                if (DatetoUnix($value) != false) {
                    $value = DatetoUnix($value);
                    $arr[$key] = $value;
                } else {
                    array_push($errors, explode(":", $key)[2]);
                    $value = "";
                    $arr[$key] = $value;
                }
            }
            if ((explode(":", $key)[0]) == "number") {
                if (!preg_match('/^[0-9]+$/', $value)) {
                    array_push($errors, explode(":", $key)[2]);
                }
            }
            if ((explode(":", $key)[0]) == "float") {
                if (!is_numeric($value) || $value < 0) {
                    array_push($errors, explode(":", $key)[2]);
                } elseif (is_numeric($value)) {
                    $value = (float)$value;
                    if (!is_float($value)) {
                        array_push($errors, explode(":", $key)[2]);
                    } else {
                        if (strlen(strrchr($value, "."))-1 > 2)
                        {
                           $value = round($value, 2);
                        }
                        $value = sprintf("%.2f", $value);
                        $arr[$key] = $value;
                    }
                }

            }
            if ((explode(":", $key)[0]) == "string") {

                if (ctype_space($value) || empty($value)) {
                    array_push($errors, explode(":", $key)[2]);
                } else {
                    $character_mask = " \t\n\r\0\x0B";
                    $value = filter_var($value, FILTER_SANITIZE_STRING);
                    $arr[$key] = trim ( $value, $character_mask );
                }

            }
            if ((explode(":", $key)[0]) == "bool") {
                if ($value != "true" && $value != "false" && $value != 1 && $value != 0) {
                    array_push($errors, explode(":", $key)[2]);
                }            
            }
        }
    }

    if (!empty($errors)) {
        return $errors;
    } 
    return true;
}

?>