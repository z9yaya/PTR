<?php include '../functions/tools.php';
include '../functions/ManageuserFunctions.php';
if (!empty($_POST['EMPID'])) {
        $res = terminateUser($_POST['EMPID']);
    }
    if ($res[0] == 'success' || $res == 'success') {
        //email($res[1]);
        echo json_encode($res);
        return true;
    } 