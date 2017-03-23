<?php
include '../functions/functions.php';
if (!empty($_POST)) {
    if ($_POST['method'] == 'login') {
        authenticateUser();
    } else if ($_POST['method'] == 'signup') {
        registerUser();
    }
}
?>
