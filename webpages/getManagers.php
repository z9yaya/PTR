<?php 
include "../functions/tools.php";
$managers = GrabAllData("SELECT EMPID, EMAIL, F_NAME, L_NAME FROM EMPLOYEES WHERE TYPE = 'MANAGER' AND INITIALSETUP != 3OR TYPE = 'MANAGER'AND INITIALSETUP IS NULL OR TYPE = 'CEO' AND INITIALSETUP != 3 OR TYPE = 'CEO' AND INITIALSETUP IS NULL ORDER BY EMPID");
if (empty($managers['EMPID'])) {
    $managers = "No managers";
    echo json_encode($managers);
    return true;
}
    foreach ($managers['EMPID'] as $key => $value) {
        $managers['EMPID'][$key] = "E".str_pad($value, 7, '0', STR_PAD_LEFT);
    }
echo json_encode($managers);
    return true;