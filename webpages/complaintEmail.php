
<?php include "../functions/tools.php";
include '../functions/emailer.php';
if (session_id() == '')
    {
        session_start();
    }
 if(!empty($_SESSION['EMPID']))
 {
     $EMPID = $_SESSION['EMPID'];
   $email = GrabMoreData('SELECT EMAIL FROM EMPLOYEES WHERE EMPID = (SELECT STORES.MANAGER FROM STORES INNER JOIN EMPLOYEES ON STORES.ID = EMPLOYEES.STORE WHERE EMPLOYEES.EMPID = :EMPID)', array(array(':EMPID', $EMPID)));
     $email['EMAIL'] = 'eliasrihan@yahoo.com';
     if (!empty($email['EMAIL'])) {
    Emailer(array('email' => $email['EMAIL']), $_POST['message'],$_POST['Field2'],null,null,null,null,null);
         echo '<script>window.close();</script>';
         
     } else echo 'You do not have a manager, sorry';
 }
?>