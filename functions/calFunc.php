<?php include "../functions/functions.php";
date_default_timezone_set('Australia/Brisbane');

$empArray = GrabAllData('SELECT EMPID FROM EMPLOYMENT');
print_r($empArray);

$penalty = GrabAllData('SELECT MOD FROM PENALTY');

for ($i = 0; $i < count($empArray['EMPID']); $i++){
$empID = $empArray['EMPID'][$i];
$payquery = GrabData("EMPLOYMENT", "RATE", "EMPID", $empID);
$payrate = $payquery['RATE'];

$GLOBALS['sunShiftID'] = '';
$GLOBALS['pubShitfID'] = '';
    
$grossPay = gross_pay($empID, $payrate, $penalty, $GLOBALS['sunShiftID']);
$netPay = net_pay($grossPay);
    print_r($grossPay);
tax_calc($grossPay, $netPay);
annual_leave($empID);
accumulate_sick($empID, $sunShiftID);

}    
    function gross_pay($empID, $payrate, $penalty, $sunShiftID)
{   
    $sal = 'salary';
    $salaryquery = "SELECT RATE, TYPE FROM EMPLOYMENT WHERE EMPID = :empID AND TYPE = :salary";
    $bindsal = array(array(':empID', $empID),array(':salary', $sal));
    $ratearray = GrabMoreData($salaryquery, $bindsal);
    $type = $ratearray['TYPE'];
    $rate = $ratearray['RATE'];
    
    if($type == $sal){
        $grossPay = $rate;//db salary wage
    }
    else { 
        $sunday = penalty_sunday($empID, $payrate, $penalty);
        $holiday = penalty_holiday($empID, $payrate);
        $overtime = penalty_overtime($empID, $payrate, $penalty, $sunShiftID);
        $regpay = reg_pay($empID, $payrate, $sunShiftID);
        $grossPay = $sunday + $holiday + $overtime + $regpay;// Add total of all calc functions
    }
    return $grossPay;
}

function net_pay($grossPay){
    $yearly= $grossPay * 52;
    $bracketquery = 'SELECT BRACKETMIN, STATIC, RATE FROM TAX WHERE :gross >= BRACKETMIN AND :gross <= BRACKETMAX';
    $bracketbind = array(array(':gross', $grossPay));
    $bracket = GrabMoreData($bracketquery, $bracketbind);
    $netPay = $grossPay + (($grossPay - $bracket['BRACKETMIN']) * $bracket['RATE']) / 52;
    return $netPay;
}

function tax_calc($grossPay, $netPay){
    $tax = $grossPay - $netPay;
    return $tax;
}

function reg_pay($empID, $payrate){
    $hours = hourCalc($empID, $GLOBALS['sunShiftID']);
    $regPay = $payrate * $hours;
    return $regPay;
}

function penalty_overtime($empID, $payrate, $penalty)
{
    $hours = hourCalc($empID, $GLOBALS['sunShiftID']);
    $overtime = $penalty["MOD"][0];
    $doubleovertime = $penalty["MOD"][1];
    $overpay = 0;
    if($hours <= 38){
        $overpay = 0;
    }
        else {
            if(o_hours <= 3) {
        $overpay += $payrate * $hours * $overtime;
                return $overpay;
            
    }
    if(o_hours > 3) { 
        $overpaypay += $payrate * (hours-3) * $doubleovertime;
        return $overpay;
    }
        }
}

function penalty_sunday($empID, $payrate, $penalty)
{
    $sunhours = find_sunday($empID);
    $mod = $penalty["MOD"][2];
    $wkndpay = $payrate * $sunhours * $mod;
    return $wkndpay;
}

function penalty_holiday($empID, $payrate)
{
    $pubHours = public_calc($empID, $GLOBALS['sunShiftID']);
    $pubpay;
    if ($GLOBALS['pubShitfID'] != ('Christmas Day' or 'Good Friday')){ //[psudo], should use timestamp to determine date
        $pubpay = $payrate * $pubHours * 1.5;
    }
            else { 
                $pubpay = $payrate * $hours * 2;
            }
}
function annual_leave($empID)
{
    $ltaken = GrabData("ACCUMULATION", "LEAVETAKEN", "EMPID", $empID); //leave input from db (hr format)
    $leavetaken = $ltaken['LEAVETAKEN'];
    $tleave = GrabData("ACCUMULATION", "LEAVE", "EMPID", $empID);//leave input from db (hr format)
    $templeave = $tleave['LEAVE'];
    $anualleave = $templeave + $leavetaken;
    $annual = GrabData("PENALTY", "TYPE", "TYPE", 'annual');
    $week = GrabData("ACCUMULATION", "WEEK", "EMPID", $empID);
    $weeks = $week['WEEK'];
    if ($weeks = 0){
        $new_leave = 0;
        return $new_leave;
    }
        else {
            $calc = $weeks * $annual; //both these numbers should be stored / pulled from db
        if ($anualleave != $calc){
            $new_leave = $templeave + ($calc - $anualleave);
            return $new_leave;
        }
    }
}
        
function accumulate_sick($empID,$sunShiftID)
{
    $hours = hourCalc($empID, $sunShiftID);
    $staken = GrabData("ACCUMULATION", "SICKLEAVETAKEN", "EMPID", $empID); //sick input from db (hr format)
    $sicktaken = $staken['SICKLEAVETAKEN'];
    $tsick = GrabData("ACCUMULATION", "SICKLEAVE", "EMPID", $empID);//sick input from db (hr format)
    $tempsick = $tsick['SICKLEAVE'];
    $anualsick = $tempsick + $sicktaken;
    $sick = GrabData("PENALTY", "TYPE", "TYPE", "sick");
    $calc = $hours * $sick; //both these numbers should be stored / pulled from db
    if ($hours = 0){
        $new_sick = tempsick;
        return $new_sick;
    }
    if($anualsick != $calc){
        $new_sick = $tempsick + ($calc - $anualsick);
        return $new_sick;
    }
}

    function find_sunday($empID){
        $sunstart = strtotime("Sunday 12:01 am");
        $sunend = strtotime("Sunday 11:59 pm");
        $querysun = 'SELECT SHIFTID, SHIFTSTART, SHIFTEND FROM "CLOCK-IN" WHERE EMPLOYEEID = :empID AND SHIFTSTART >= :sunstart AND SHIFTEND <= :sunend';
        $bindsun = array(array(':empID', $empID),array(':sunstart',$sunstart),array(':sunend', $sunend));
        $sunstamps = GrabMoreData($querysun, $bindsun);
        if(empty($sunstamps)){
        $sunconvert = round(($sunstamps['SHIFTSTART'] - $sunstamps['SHIFTEND']) % (1000 * 60 * 60 * 24) / (1000 * 60 * 60));
        $GLOBALS['sunShiftID'] = $sunstamps['SHIFTID'];
        return $sunconvert;
        }
    }
    /*
    function public_calc($empID){
        $weekstart = strtotime("Monday 12:01 am");
        $weekend = strtotime("Sunday 11:59 pm");
        $state = GrabData("EMPLOYEES", "STATE", "EMPID", $empID);
        
        if(empty($GLOBALS['sunShiftID'])){
        $pubquery = 'SELECT SHIFTID, SHIFTSTART, SHIFTEND FROM "CLOCK-IN" WHERE EMPLOYEEID = :empID AND SHIFTID != :sunShiftID AND SHIFTSTART >= :weekstart AND SHIFTEND <= :weekend AND SHIFTSTART <= HOLIDAYSTART AND SHIFTEND >= HOLIDAYEND'; // SHIFTSTART <= HOLIDAY AND SHIFTEND >= HOLIDAY
        $pubbind = array(array(':empID', $empID),array(':weekstart',$weekstart),array(':weekend', $weekend), array(':sunShiftID', $GLOBALS['sunShiftID']));
        $pubstamps = GrabAllData($pubquery, $pubbind);
        $GLOBALS['pubShitfID'] = $pubstamps['SHIFTID'];
        $pubHours = 0;
            
            for ($i = 0; $i < count($pubstamps['SHIFTID']); $i++){
                $pubHours += round((($pubstamps['SHIFTSTART'][$i] - $pubstamps['SHIFTEND'][$i]) % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                array_push($pubShitfID,$pubstamps['SHIFTID'][$i]);
            }
            return $pubHours;
        }
    }
    */
    
    function hourCalc($empID, $sunShiftID){
        $weekstart = strtotime("Monday 12:01 am"); //need to separate pub holiday - requires csv pub holiday input
        $weekend = strtotime("Sunday 11:59 pm");
        
        if(empty($sunShiftID)){
        $queryweek = 'SELECT SHIFTID, SHIFTSTART, SHIFTEND FROM "CLOCK-IN" WHERE EMPLOYEEID = :empID AND SHIFTID != :sunShiftID AND SHIFTSTART >= :weekstart AND SHIFTEND <= :weekend';
        $bindweek = array(array(':empID', $empID),array(':weekstart',$weekstart),array('weekend', $weekend), array('sunShiftID', $sunShiftID));
        $weekstamps = GrabAllData($queryweek, $bindweek);
        $hours = 0;
        for($i=0; $i < count($weekstamps['SHIFTID']); $i++){
            $hours += round((($weekstamps['SHIFTSTART'][$i] - $weekstamps['SHIFTEND'][$i]) % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        }
        return $hours;
    }
    }

 
    function public_calc($empID, $sunShiftID){
        $weekstart = strtotime("Monday 12:01 am");
        $weekend = strtotime("Sunday 11:59 pm");
        $state = GrabData("EMPLOYEES", "STATE", "EMPID", $empID);
        $pubquery = 'SELECT SHIFTID, SHIFTSTART, SHIFTEND FROM "CLOCK-IN" WHERE EMPLOYEEID = :empID AND SHIFTID != :sunShiftID AND SHIFTSTART >= :weekstart AND SHIFTEND <= :weekend';
        $pubbind = array(array(':empID', $empID),array(':weekstart',$weekstart),array('weekend', $weekend), array('sunShiftID', $sunShiftID));
        $pubstamps = GrabAllData($pubquery, $pubbind);
        $holquery = 'SELECT HOLIDAYSTART, HOLIDAYEND, HOLIDAYNAME FROM PUBLICHOLIDAY WHERE STATE = :state';
        $holbind = array(array(':state', $state));
        $pubday = GrabAllData($holquery, $holbind);
        print_r($pubstamps);
        $pubhours = 0;
        $a = 0;
        $i = 0;
        
        if((count($pubstamps['SHIFTSTART'])) != 0){
            do {
                if($pubstamps['SHIFTSTART'][$a] <= $pubday['HOLIDAYSTART'][$i] && $pubstamps['SHIFTEND'][$a] >= $pubday['HOLIDAYEND']){
                $pubhours += round((($pubstamps['SHIFTSTART'][$a] - $pubstamps['SHIFTEND'][$a]) % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                //array_push($pubShitfID,$pubstamps['SHIFTID'][$i]);
                    $i = 0;
                    $a++;
                }
                if($i = count($pubday['HOLIDAYSTART'])){
                     $i=0;
                     $a++;
                 }   
            }while ($a < count($pubstamps['SHIFTSTART']));
        }
        return $pubhours;
    }

    function weekly_update($empID, $grossPay, $netPAY, $tax, $new_sick, $new_leave)
    {    //use the batch file to run, update all info generated from cal func into db at 11:59pm sun. Should include +1 to week column
        $time = time();
        $updateBind = 'INSERT INTO PAY (EMPID, GROSS, NET, TAX, DATE) VALUES (:empID :gorss, :net, :tax, :date)';
        $updateQuery= array(array(':empID', $empID),array(':gross',$grossPay),array(':net', $netPAY), array(':tax', $tax), array(':date', $time));
        $update = InsertData($updateQuery, updateBind);
        
        $weekq = GrabData("ACCUMULATION", "WEEK", "EMPID", $empID);
        $week = $weekq['WEEK'] + 1;
        
        $query = 'UPDATE "ACCUMULATION" SET WEEK= :week WHERE EMPID = :empID';
                        $bind = array(array(":week",$week),array(":ID",$empID));
                        $insert = InsertData($query, $bind);
        
        $query = 'UPDATE "ACCUMULATION" SET LEAVE = :leave WHERE EMPID = :empID';
                        $bind = array(array(":leave",$new_leave),array(":ID",$empID));
                        $insert = InsertData($query, $bind);
        
        $query = 'UPDATE "ACCUMULATION" SET SICKLEAVE= :sick WHERE EMPID = :empID';
                        $bind = array(array(":sick",$new_sick),array(":ID",$empID));
                        $insert = InsertData($query, $bind);
        
        
        
    }
 


?>