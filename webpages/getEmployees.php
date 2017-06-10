<?php include "../functions/tools.php";
if (!empty($_POST['id'])) {
    $employees = GrabAllData("SELECT * FROM EMPLOYEES WHERE STORE = :storeID AND INITIALSETUP != 3 AND TYPE != 'MANAGER' AND TYPE != 'CEO' OR STORE = :storeID AND INITIALSETUP IS NULL AND TYPE != 'MANAGER' AND TYPE != 'CEO'ORDER BY EMPID", array(array(":storeID",$_POST['id'])));
    if (empty($employees['EMPID'])) {
        $employees = "No employees";
        echo json_encode($employees);
        return true;
    }
        foreach ($employees['EMPID'] as $key => $value) {
            $employees['EMPID'][$key] = "E".str_pad($value, 7, '0', STR_PAD_LEFT);
        }
    echo json_encode($employees);
        return true;
} return false;
