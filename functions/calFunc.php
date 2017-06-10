<?php
include "../functions/tools.php";

date_default_timezone_set('Australia/Brisbane');
$empArray = GrabAllData('SELECT EMPID, STORE FROM EMPLOYEES');
$penalty = GrabAllData('SELECT MOD FROM PENALTY');

for ($i = 0; $i < count($empArray['EMPID']); $i++)
	{
	$empID = $empArray['EMPID'][$i];
    $storeID = $empArray['STORE'][$i];
    
	$payquery = GrabData("EMPLOYMENT", "RATE", "EMPID", $empID);
	$payrate = $payquery['RATE'];
	$GLOBALS['sunShiftID'] = '';
	$GLOBALS['pubShitfID'] = '';
	$GLOBALS['sunday'] = find_sunday($empID);
	$GLOBALS['pubhours'] = public_calc($empID, $sunShiftID);
    $GLOBALS['hours'] = hourCalc($empID, $sunShiftID) - $GLOBALS['pubhours'];
    $GLOBALS['overtime'] = 0;
	$GLOBALS['total'] = $GLOBALS['sunday'] + $GLOBALS['pubhours'] + $GLOBALS['hours'];
	$grossPay = gross_pay($empID, $payrate, $penalty, $GLOBALS['sunShiftID']);
	$netPay = net_pay($grossPay);
	$tax = tax_calc($grossPay, $netPay);
	$leave = annual_leave($empID, $penalty);
	$sick = accumulate_sick($empID, $sunShiftID, $penalty);
	weekly_update($empID, $grossPay, $netPay, $tax, $sick, $leave, $storeID);
	}

function gross_pay($empID, $payrate, $penalty, $sunShiftID)
	{
	$sal = 'salary';
	$salaryquery = "SELECT RATE, TYPE FROM EMPLOYMENT WHERE EMPID = :empID AND TYPE = :salary";
	$bindsal = array(
		array(
			':empID',
			$empID
		) ,
		array(
			':salary',
			$sal
		)
	);
	$ratearray = GrabAllData($salaryquery, $bindsal);
	$type = $ratearray['TYPE'];
	$rate = $ratearray['RATE'];
	if ($type == $sal)
		{
		$grossPay = $rate; //db salary wage
		}
	  else
		{
		$sunday = penalty_sunday($empID, $payrate, $penalty);
		$holiday = penalty_holiday($empID, $payrate, $penalty);
		$overtime = penalty_overtime($empID, $payrate, $penalty, $sunShiftID);
		$regpay = reg_pay($empID, $payrate, $sunShiftID);
		$grossPay = $sunday + $holiday + $overtime + $regpay; // Add total of all calc functions
		}
	return $grossPay;
	}

function net_pay($grossPay)
	{
	$yearly = $grossPay * 52;
	$bracketquery = 'SELECT BRACKETMIN, STATIC, RATE FROM TAX WHERE :gross >= BRACKETMIN AND :gross <= BRACKETMAX';
	$bracketbind = array(
		array(
			':gross',
			$yearly
		)
	);
	$bracket = GrabMoreData($bracketquery, $bracketbind);

	$netPayDec = $grossPay + (($grossPay - $bracket['BRACKETMIN']) * $bracket['RATE']) / 52;
	$netPay = round($netPayDec, 2);
	return $netPay;
	}

function tax_calc($grossPay, $netPay)
	{
	$tax = $grossPay - $netPay;
	return $tax;
	}

function reg_pay($empID, $payrate)
	{
		if ($GLOBALS['hours'] > 38)
		{
			$hours = 38;
			$regPay = $payrate * $hours;
		}
		else {
			$regPay = $payrate * $GLOBALS['hours'];
		}
	return $regPay;
	}

function penalty_overtime($empID, $payrate, $penalty)
	{
	$overtime = $penalty["MOD"][0];
	$doubleovertime = $penalty["MOD"][1];
    $o_hours;
	$overpay = 0;
	if ($GLOBALS['hours'] > 0 && $GLOBALS['hours'] <= 38)
		{
		$overpay = 0;
		}
	 if ($GLOBALS['hours'] <= 38){
		{
         $o_hours = $GLOBALS['hours'] - 38;
         $GLOBALS['overtime'] = $o_hours;
        if ($GLOBALS['overtime'] <= 0){
         $GLOBALS['overtime'] = 0;
         
		if ($o_hours > 0 && $o_hours <= 3)
			{
			$overpay += $payrate * $o_hours * $overtime;
			return $overpay;
			}

		if ($o_hours > 0 && $o_hours > 3)
			{
			$overpaypay+= $payrate * (hours - 3) * $doubleovertime;
			return $overpay;
			}
        }
        }
		}
	}

function penalty_sunday($empID, $payrate, $penalty)
	{
	$mod = $penalty["MOD"][4];
	$wkndpay = $payrate * $GLOBALS['sunday'] * $mod;
	return $wkndpay;
	}

function penalty_holiday($empID, $payrate, $penalty)
	{
	$pubpay;
	if ($GLOBALS['pubShitfID'] != ('Christmas Day' or 'Good Friday'))
		{ 
		$pubpay = $payrate * $GLOBALS['pubhours'] * $penalty["MOD"][1];
		}
	  else
		{
		$pubpay = $payrate * $GLOBALS['pubhours'] * $penalty["MOD"][3];
		}
		return $pubpay;
	}

function annual_leave($empID, $penalty)
	{
	$ltaken = GrabData("ACCUMULATION", "LEAVETAKEN", "EMPID", $empID); //leave input from db (hr format)
	$leavetaken = $ltaken['LEAVETAKEN'];
	$tleave = GrabData("ACCUMULATION", "LEAVE", "EMPID", $empID); //leave input from db (hr format)
	$templeave = $tleave['LEAVE'];
	$anualleave = $templeave + $leavetaken;
	$annual = $penalty["MOD"][5];
	$week = GrabData("ACCUMULATION", "WEEK", "EMPID", $empID);
	$weeks = $week['WEEK'];
	if ($weeks == 0)
		{
		$new_leave = 0;
		return $new_leave;
		}
	  else
		{
		$calc = $weeks * $annual; //both these numbers should be stored / pulled from db
		if ($anualleave != $calc)
			{
			$new_leave = $templeave + ($calc - $anualleave);
			return $new_leave;
			}
		}
	}

function accumulate_sick($empID, $sunShiftID, $penalty)
	{
	$hours = $GLOBALS['hours'] + $GLOBALS['sunday'] + $GLOBALS['pubhours'];
	//$staken = GrabData("ACCUMULATION", "SICKLEAVETAKEN", "EMPID", $empID); //sick input from db (hr format)
	//$sicktaken = $staken['SICKLEAVETAKEN'];
	$tsick = GrabData("ACCUMULATION", "SICKLEAVE", "EMPID", $empID); //sick input from db (hr format)
	$tempsick = $tsick['SICKLEAVE'];
	//$anualsick = $tempsick + $sicktaken;;
	$sick = $penalty["MOD"][6];
	$calc = $hours * $sick; //both these numbers should be stored / pulled from db
	if ($hours == 0)
		{
		$new_sick = $tempsick;
		}
		if ($tempsick != $calc)
			{
			$new_sick = $tempsick + $calc;
		}
	if ($tempsick == $calc){
			$new_sick = $tempsick + $calc;
		}
		return $new_sick;
	}

function find_sunday($empID)

	{
	$sunstart = strtotime("Sunday this week 12:01 am");
	$sunend = strtotime("Sunday this week 11:59 pm");
	$querysun = 'SELECT SHIFTID, SHIFTSTART, SHIFTEND FROM "CLOCK-IN" WHERE EMPLOYEEID = :empID AND SHIFTSTART >= :sunstart AND SHIFTEND <= :sunend';
	$bindsun = array(
		array(
			':empID',
			$empID
		) ,
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
	if (!empty($sunstamps))
		{
    $sunconvert = round((($sunstamps['SHIFTEND'] - $sunstamps['SHIFTSTART']) % (60 * 60 * 24)) / (60 * 60));
		$GLOBALS['sunShiftID'] = $sunstamps['SHIFTID'];
		return $sunconvert;
		}
	}

function hourCalc($empID, $sunShiftID)
	{
	$weekstart = strtotime("Monday this week 12:01 am"); //need to separate pub holiday - requires csv pub holiday input
	$weekend = strtotime("Sunday this week 11:59 pm");
	if (!empty($sunShiftID))
		{
		$queryweek = 'SELECT SHIFTID, SHIFTSTART, SHIFTEND FROM "CLOCK-IN" WHERE EMPLOYEEID = :empID AND SHIFTID != :sunShiftID AND SHIFTSTART >= :weekstart AND SHIFTEND <= :weekend';
		$bindweek = array(
			array(
				':empID',
				$empID
			) ,
			array(
				':weekstart',
				$weekstart
			) ,
			array(
				'weekend',
				$weekend
			) ,
			array(
				'sunShiftID',
				$sunShiftID
			)
		);
		$weekstamps = GrabAllData($queryweek, $bindweek);
		}
	  else
		{
		$queryweek = 'SELECT SHIFTID, SHIFTSTART, SHIFTEND FROM "CLOCK-IN" WHERE EMPLOYEEID = :empID AND SHIFTSTART >= :weekstart AND SHIFTEND <= :weekend';
		$bindweek = array(
			array(
				':empID',
				$empID
			) ,
			array(
				':weekstart',
				$weekstart
			) ,
			array(
				'weekend',
				$weekend
			)
		);
		$weekstamps = GrabAllData($queryweek, $bindweek);
		}

	$hours = 0;
	for ($i = 0; $i < count($weekstamps['SHIFTID']); $i++)
		{

		// echo $weekstamps['SHIFTSTART'][$i] - $weekstamps['SHIFTEND'][$i];
		// $hours += round((($weekstamps['SHIFTEND'][$i] - $weekstamps['SHIFTSTART'][$i]) % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));

		$hours+= round((($weekstamps['SHIFTEND'][$i] - $weekstamps['SHIFTSTART'][$i]) % (60 * 60 * 24)) / (60 * 60));
		}
	return $hours;
	}

function public_calc($empID, $sunShiftID)
	{
	$weekstart = strtotime("Monday this week 12:01 am");
	$weekend = strtotime("Sunday this week 11:59 pm");
	$state = GrabData("EMPLOYEES", "STATE", "EMPID", $empID) ['STATE'];
	if (!empty($sunShiftID))
		{
		$pubquery = 'SELECT SHIFTID, SHIFTSTART, SHIFTEND FROM "CLOCK-IN" WHERE EMPLOYEEID = :empID AND SHIFTID != :sunShiftID AND SHIFTSTART >= :weekstart AND SHIFTEND <= :weekend';
		$pubbind = array(
			array(
				':empID',
				$empID
			) ,
			array(
				':weekstart',
				$weekstart
			) ,
			array(
				'weekend',
				$weekend
			) ,
			array(
				'sunShiftID',
				$sunShiftID
			)
		);
		$pubstamps = GrabAllData($pubquery, $pubbind);
		}
	  else if (empty($sunShiftID))
		{
		$pubquery = 'SELECT SHIFTID, SHIFTSTART, SHIFTEND FROM "CLOCK-IN" WHERE EMPLOYEEID = :empID AND SHIFTSTART >= :weekstart AND SHIFTEND <= :weekend';
		$pubbind = array(
			array(
				':empID',
				$empID
			) ,
			array(
				':weekstart',
				$weekstart
			) ,
			array(
				':weekend',
				$weekend
			)
		);
		$pubstamps = GrabAllData($pubquery, $pubbind);
		}
        
	$holquery = 'SELECT HOLIDAYSTART, HOLIDAYEND, HOLIDAYNAME FROM PUBLICHOLIDAY WHERE STATE = :nat OR STATE = :state';
	$holbind = array(
		array(
			':state',
			$state
		),
		array(
			':nat',
			"NAT")
	);
	$pubday = GrabAllData($holquery, $holbind);
	$pubhours = 0;
	$holiday ="null";
	$a = 0;
	$i = 0;
	if (!empty($pubstamps['SHIFTID']))
		{
		do
			{
			if ($pubstamps['SHIFTSTART'][$a] >= $pubday['HOLIDAYSTART'][$i] && $pubstamps['SHIFTEND'][$a] <= $pubday['HOLIDAYEND'][$i])
				{
        $pubhours += round((($pubstamps['SHIFTEND'][$a] - $pubstamps['SHIFTSTART'][$a]) % (60 * 60 * 24)) / (60 * 60));
				$holiday .= $pubday['HOLIDAYNAME'][$i];

				//array_push($pubShitfID,$pubstamps['SHIFTID'][$i]);

				$i = 0;
				$a++;
				}

			if ($i == count($pubday['HOLIDAYSTART'])-1)
				{
				$i = 0;
				$a++;
				}
				else {
					$i++;
				}
			}

		while ($a < count($pubstamps['SHIFTSTART']));
		}
		$GLOBALS['pubShitfID'] = $holiday;
	return $pubhours;
	}

function weekly_update($empID, $grossPay, $netPay, $tax, $sick, $leave, $storeID)
	{ //use the batch file to run, update all info generated from cal func into db at 11:59pm sun. Should include +1 to week column
	$time = time();
	$updateQuery = 'INSERT INTO PAY (EMPID, GROSS, NET, TAX, GENERATED, TOTALHOURS, REGHOURS, PUBLICHOURS, SUNDAYHOURS, OVERTIME, STOREID) VALUES (:empID, :gross, :net, :tax, :generated, :totalhours, :reghours, :publichours, :sundayhours, :overtime, :storeid)';
	$updateBind = array(
		array(
			':empID',
			$empID
		) ,
		array(
			':gross',
			$grossPay
		) ,
		array(
			':net',
			$netPay
		) ,
		array(
			':tax',
			$tax
		) ,
		array(
			':generated',
			$time
		),
		array(
			':totalhours',
			$GLOBALS['total']
		),
		array(
			':reghours',
			$GLOBALS['hours']
		),
		array(
			':publichours',
			$GLOBALS['pubhours']
		),
		array(
			':sundayhours',
			$GLOBALS['sunday']
		),
        array(
			':overtime',
			$GLOBALS['overtime']
		),
         array(
			':storeid',
			$storeID
		)
	);
	$update = InsertData($updateQuery, $updateBind);
	$weekq = GrabData("ACCUMULATION", "WEEK", "EMPID", $empID);
	$week = $weekq['WEEK'] + 1;
	$query = 'UPDATE "ACCUMULATION" SET WEEK = :week WHERE EMPID = :empID';
	$bind = array(
		array(
			":week",
			$week
		) ,
		array(
			":empID",
			$empID
		)
	);
	$insert1 = InsertData($query, $bind);

	$query = 'UPDATE "ACCUMULATION" SET LEAVE = :leave WHERE EMPID = :empID';
	$bind = array(
		array(
			":leave",
			$leave
		) ,
		array(
			":empID",
			$empID
		)
	);
	$insert = InsertData($query, $bind);
	$query = 'UPDATE "ACCUMULATION" SET SICKLEAVE= :sick WHERE EMPID = :empID';
	$bind = array(
		array(
			":sick",
			$sick
		) ,
		array(
			":EMPID",
			$empID
		)
	);
	$insert2 = InsertData($query, $bind);
	}

?>
