<?php
include "tools.php";

// /Used to register new users on the database,
// /grabs all the data from the form, then formats it, binds it to variables for inserting into database,
// /then pushes email,position and password to $_SESSION
function registerUser()
	{
    if (!empty($_POST) && !empty($_POST['ptr:signup:empID']) && !ctype_space($_POST['ptr:signup:empID']))
				{
				$email = filter_var($_POST['ptr:signup:username'], FILTER_SANITIZE_EMAIL);
				if (!filter_var($email, FILTER_VALIDATE_EMAIL))
					{
					echo "email";
					return false;
					}
                    if (ctype_space($_POST['ptr:signup:empID']) || empty($_POST['ptr:signup:empID'])) {
                        echo 'ID';
                        return false;
                    } else {
                        if ($_POST['ptr:signup:empID'][0] !== 'E')
                        {
                            echo 'ID';
                            return false;
                        }
                        $character_mask = " E\t\n\r\0\x0B";
                        $ID = filter_var($_POST['ptr:signup:empID'], FILTER_SANITIZE_NUMBER_INT);
                    }
                $query = 'SELECT COUNT(EMPID) FROM EMPLOYEES WHERE EMAIL = :email AND INITIALSETUP IS NULL AND EMPID = :EMPID';
                $bind = array(array(':email', $email),array(':EMPID', $ID));
                $count = GrabMoreData($query, $bind)['COUNT(EMPID)'];
	               if ($count == 1)
		          {
                    if ($_POST['ptr:signup:secret2'] === $_POST['ptr:signup:secret1'])
                        {
                        $password = $_POST['ptr:signup:secret2'];
                        }
                      else
                        {
                        echo "password";
                        return false;
                        }

                    $query = 'SELECT COUNT(EMPID) FROM EMPLOYEES WHERE EMAIL = :email AND EMPID = :EMPID AND INITIALSETUP is NULL';
                    $bind = array(array(':email', $email),array(':empID', $ID));
                    $count = GrabMoreData($query, $bind)['COUNT(EMPID)'];
                    if ($count == 1)
                      {   
                        $password = password_hash($password, PASSWORD_DEFAULT);
                        $pdo = connect();
                        $query = "UPDATE EMPLOYEES SET password = :password, INITIALSETUP = 0 WHERE EMPID = :EMPID AND EMAIL = :email";
                        $prepare = oci_parse($pdo, $query);
                        oci_bind_by_name($prepare, ':password', $password);
                        oci_bind_by_name($prepare, ':EMPID', $ID);
                        oci_bind_by_name($prepare, ':email', $email);
                        if (oci_execute($prepare))
                            {
                            if (session_id() == '')
                                {
                                session_start();
                                }
                              else
                            if (isset($_SESSION['email']))
                                {
                                session_unset();
                                }

                            $empIDres = GrabData('EMPLOYEES', '*', 'EMPID', $ID);
                            $_SESSION['EMPID'] = $empIDres["EMPID"];
                            $_SESSION['EMAIL'] = $email;
                            $_SESSION['TYPE'] = $empIDres["TYPE"];
                            //$passwordRes = GrabData('EMPLOYEES', 'PASSWORD', 'EMAIL', $email);
                            //$_SESSION['PASSWORD'] = $passwordRes["PASSWORD"];
                            echo "success";
                            return true;
                            }
                          else
                            {
                            $e = oci_error($prepare);
                            echo "There was an error, contact the system adminstrator and copy this error: " . $e['message'];
                            }
                    } else {
                        echo 'ID';
                        return false;
                    }
		}
        else {
            echo 'email';
            return false;
        }
	} else {
        echo 'ID';
        return false;
    }
}

// /Used to authenticate an existing user on the system, grabs data from form then checks against the database and pushes to $_SESSION

function authenticateUser()
	{
	if (CheckExist('ptr:login:email', 'EMPLOYEES', 'EMAIL', $_POST))
		{
		$pdo = connect();
		$email = filter_var($_POST['ptr:login:email'], FILTER_SANITIZE_EMAIL);
		$password = $_POST['ptr:login:secret'];
		$prepare = oci_parse($pdo, "SELECT EMPID,EMAIL,PASSWORD,INITIALSETUP,TYPE FROM EMPLOYEES WHERE INITIALSETUP != 3 AND EMAIL = :email");
		oci_bind_by_name($prepare, ':email', $email);
		if (oci_execute($prepare))
			{
			$res = oci_fetch_assoc($prepare);
			if ($res != null)
				{
				if (password_verify($password, $res['PASSWORD']))
					{
					if (session_id() == '')
						{
						session_start();
						}
					  else
					if (isset($_SESSION['EMAIL']))
						{
						session_unset();
						}

					$_SESSION['EMPID'] = $res['EMPID'];
					$_SESSION['EMAIL'] = $res['EMAIL'];
					$_SESSION['PASSWORD'] = $res['PASSWORD'];
					$_SESSION['INITIALSETUP'] = $res['INITIALSETUP'];
					$_SESSION['TYPE'] = $res['TYPE'];
					echo "success";
					return true;
					}
				  else
					{
					echo "password";
					return false;
					}
				}
			  else
				{
                echo "username";
				return false;
				}
			}
		  else
			{
			$e = oci_error($prepare);
			echo $e['message'];
			}
		}
	  else
		{
		echo "username";
		return false;
		}
	}

?>