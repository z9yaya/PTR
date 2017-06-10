<?php 
include '../functions/tools.php';
include "../functions/emailer.php";
include '../functions/ManageuserFunctions.php';
$errors = array();
if (!empty($_POST['number:stores:manager'])) {
            $_POST['number:stores:manager'] = intval(ltrim($_POST['number:stores:manager'], 'E'));
foreach ($_POST as $key => $value) {
    if ($value) {
        if ((explode(":", $key)[0]) == "date") {
            if (checkdate($value['month'], $value['day'], $value['year'])) {
                $value = mktime(0, 0, 0, $value['month'], $value['day'], $value['year']);
                $_POST[$key] = $value;
            } else {
                array_push($errors, explode(":", $key)[2]);
                $value = "";
                $_POST[$key] = $value;
            }
        }
        if ((explode(":", $key)[0]) == "number") {
            if (!preg_match('/^[0-9]+$/', $value)) {
                array_push($errors, explode(":", $key)[2]);
            } else {
                $_POST[$key] = intval($value);
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
                    $_POST[$key] = $value;
                }
            }

        }
        if ((explode(":", $key)[0]) == "string") {
            
            if (ctype_space($value) || empty($value)) {
                array_push($errors, explode(":", $key)[2]);
            } else {
                $character_mask = " \t\n\r\0\x0B";
                $value = filter_var($value, FILTER_SANITIZE_STRING);
                $_POST[$key] = trim ( $value, $character_mask );
            }
            
        }
        if ((explode(":", $key)[0]) == "bool") {
            if ($value != "true" && $value != "false" && $value != 1 && $value != 0) {
                array_push($errors, explode(":", $key)[2]);
            }            
        }
        if ($key == 'email:employees:username') {
            $value = filter_var($value, FILTER_SANITIZE_EMAIL);
            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                array_push($errors, explode(":", $key)[2]);
            } else {
                $_POST[$key] = $value;
            }

        }
        if ($key == 'email:employees:username2') {
            if ($value !== $_POST['email:employees:username']) {
                array_push($errors, explode(":", $key)[2]);
            }
        }

    }
}
if (!empty($errors)) {
    echo json_encode($errors);
    return false;
} else {
    if (empty($_POST['form:submit:type'])) {
        $res = CreateStore($_POST);
        if ($res[0] == 'success')
        {
            echo json_encode($res);
            return true;
        }
    } elseif ($_POST['form:submit:type'] == 'edit') {
        $res = UpdateStore($_POST);
    }

    if ($res[0] == 'success' || $res == 'success') {
        //email(CreateUser($_POST)[1]);
        echo 'success';
        return true;
    } else {
        echo json_encode($res);
        return false;
    }

    }
}
