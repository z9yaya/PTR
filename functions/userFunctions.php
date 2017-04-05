<?php
include "tools.php";

// /Used to register new users on the database,
// /grabs all the data from the form, then formats it, binds it to variables for inserting into database,
// /then pushes email,position and password to $_SESSION

function registerUser()
	{
	if (CheckExist('ptr:signup:username', 'EMPLOYEES', 'EMAIL', $_POST))
		{
		echo "exist";
		return false;
		}
	  else
		{
		if (isset($_POST))
			{
			if (!empty($_POST) && !empty($_POST['ptr:signup:username']))
				{
				$email = filter_var($_POST['ptr:signup:username'], FILTER_SANITIZE_EMAIL);
				if (!filter_var($email, FILTER_VALIDATE_EMAIL))
					{
					echo "email";
					return false;
					}

				if ($_POST['ptr:signup:secret2'] === $_POST['ptr:signup:secret1'])
					{
					$password = $_POST['ptr:signup:secret2'];
					}
				  else
					{
					echo "password";
					return false;
					}

				$ID = filter_var($_POST['ptr:signup:empID'], FILTER_SANITIZE_NUMBER_INT);
				$password = password_hash($password, PASSWORD_DEFAULT);
				$pdo = connect();
				$query = "INSERT INTO EMPLOYEES (email, password)
                         VALUES(:email, :password)";
				$prepare = oci_parse($pdo, $query);
				oci_bind_by_name($prepare, ':email', $email);
				oci_bind_by_name($prepare, ':password', $password);
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

					$empIDres = GrabData('EMPLOYEES', 'EMPID', 'EMAIL', $email);
					$_SESSION['EMPID'] = $empIDres["EMPID"];
					$_SESSION['EMAIL'] = $email;
					$_SESSION['TYPE'] = "EMPLOYEE";
					$passwordRes = GrabData('EMPLOYEES', 'PASSWORD', 'EMAIL', $email);
					$_SESSION['PASSWORD'] = $passwordRes["PASSWORD"];
					echo "success";
					return true;
					}
				  else
					{
					$e = oci_error($prepare);
					echo "There was an error, contact the system adminstrator and copy this error: " . $e['message'];
					}
				}
			}
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
		$prepare = oci_parse($pdo, "SELECT EMPID,EMAIL,PASSWORD,INITIALSETUP,TYPE FROM EMPLOYEES WHERE EMAIL = :email");
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
					$_SESSION['INITIAL'] = $res['INITIALSETUP'];
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