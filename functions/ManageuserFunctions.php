<?php
function CreateUser($anArray)
{
    if (CheckExist('email:employees:username', 'EMPLOYEES', 'EMAIL', $anArray)) {
        return "exist"; //email used
    } else {
        if (!empty($anArray)) {
            if (!empty($anArray['email:employees:username'])) {
                $values = ValueCheck($anArray, 'add');
                if (!is_array($values)) {
                    return $values;
                }
                $email = $values['email'];
                $type = $values['type'];
                $EmpType = $values['EmpType'];
                $rateType = $values['rateType'];
                $store = $values['store'];
                $queryEmployees = "INSERT INTO EMPLOYEES (EMAIL, TYPE, STORE) VALUES(:email, :EmpType, :store)";
                $bindEmployees = array(
                    array(
                        ':email',
                        $email
                    ),
                    array(
                        ':EmpType',
                        $type
                    ),
                    array(
                        ':store',
                        $store
                    )
                );
                 if (InsertData($queryEmployees, $bindEmployees) != 'success') {
                    return " Error: employees insert, line 19";
                }
                $empID = GrabData('EMPLOYEES', 'EMPID', 'EMAIL', $email);
                $empID = $empID['EMPID'];
                $queryEmployment = "INSERT ALL INTO EMPLOYMENT (EMPID, POSITION, RATE, TYPE, EMPSTART, EMPTYPE) VALUES(:empID, :position, :rate, :Ratetype, :startDate, :EmpType)
                INTO ACCUMULATION (EMPID) VALUES (:empID)
                select * FROM dual";
                $bindEmployment = array(
                    array(
                        ':empID',
                        $empID
                    ),
                    array(
                        ':rate',
                        $anArray['float:employment:rate']
                    ),
                    array(
                        ':position',
                        $anArray['string:employment:position']
                    ),
                    array(
                        ':Ratetype',
                        $rateType
                    ),
                    array(
                        ':Emptype',
                        $EmpType
                    ),
                    array(
                        ':startDate',
                        $anArray['date:employment:start']
                    )
                );
                
                if (!empty($anArray['bool:stores:manager']) && $type === 'MANAGER') {
                    if (!CheckExistExt($store, 'STORES', 'MANAGER', 'ID')) {
                        $queryStores = "UPDATE STORES SET MANAGER = :empID WHERE ID = :storeID";
                        $bindStores = array(
                            array(
                                ':empID',
                                $empID
                            ),
                            array(
                                'storeID',
                                $store)
                        );  
                        if (InsertData($queryStores, $bindStores) != 'success') {
                            return "Error: employment insert, line 64";
                        }
                    } else {
                        echo 'storeManager'; //store has already a manager
                        return false;
                    }
                }
                if (InsertData($queryEmployment, $bindEmployment) != 'success') {
                    return "Error: employment insert, line 64";
                }
                $storeInfo = GrabData('STORES', 'SUBURB', 'ID', $store)['SUBURB'];
                sendWelcomeEmail($email, $anArray['string:employment:position'], 'the '.$storeInfo.' store/branch', $empID, $anArray['date:employment:start']);
                return array(
                    "success",
                    $empID
                );
            }
        }
    }
}

function UpdateUser($anArray)
{
    if (CheckExist('number:employees:empid', 'EMPLOYEES', 'EMPID', $anArray)) {
        if (isset($anArray)) {
            if (!empty($anArray) && !empty($anArray['email:employees:username'])) {
                $values = ValueCheck($anArray, 'edit');
                if (!is_array($values)) {
                    return $values;
                }
                $empID = $anArray['number:employees:empid'];
                $email = $values['email'];
                $type = $values['type'];
                $EmpType = $values['EmpType'];
                $rateType = $values['rateType'];
                $store = $values['store'];
                $queryEmployees = "UPDATE EMPLOYEES SET EMAIL=:email, TYPE=:EmpType, STORE=:store WHERE EMPID = :EMPID";
                $bindEmployees = array(
                    array(
                        ':email',
                        $email
                    ),
                    array(
                        ':EmpType',
                        $type
                    ),
                    array(
                        ':EMPID',
                        $empID
                    ),
                    array(
                        ':store',
                        $store
                    )
                );
                
                if (empty($anArray['bool:submit:started']))
                {
                    $queryEmployment = "UPDATE EMPLOYMENT SET 
                    POSITION = :position,
                    RATE = :rate,
                    TYPE = :Ratetype,
                    EMPSTART = :startDate,
                    EMPTYPE = :EmpType
                    WHERE EMPID = :empID";
                    $bindEmployment = array(
                        array(
                            ':empID',
                            $empID
                        ),
                        array(
                            ':rate',
                            $anArray['float:employment:rate']
                        ),
                        array(
                            ':position',
                            $anArray['string:employment:position']
                        ),
                        array(
                            ':Ratetype',
                            $rateType
                        ),
                        array(
                            ':Emptype',
                            $EmpType
                        ),
                        array(
                            ':startDate',
                            $anArray['date:employment:start']
                        )
                    );
                } elseif ($anArray['bool:submit:started'] == 'true'){
                    $queryEmployment = "UPDATE EMPLOYMENT SET 
                    POSITION = :position,
                    RATE = :rate,
                    TYPE = :Ratetype,
                    EMPTYPE = :EmpType
                    WHERE EMPID = :empID";
                    $bindEmployment = array(
                        array(
                            ':empID',
                            $empID
                        ),
                        array(
                            ':rate',
                            $anArray['float:employment:rate']
                        ),
                        array(
                            ':position',
                            $anArray['string:employment:position']
                        ),
                        array(
                            ':Ratetype',
                            $rateType
                        ),
                        array(
                            ':Emptype',
                            $EmpType
                        )

                    );
                }
                
                if (!empty($anArray['bool:stores:manager']) && $type === 'MANAGER') {
                    if (GrabData('STORES', 'MANAGER', 'ID', $store)['MANAGER'] != $empID) {
                        $query = "UPDATE STORES SET MANAGER = NULL WHERE MANAGER = :empID";
                        $bind = array(
                            array(
                                ':empID',
                                $empID)
                        );
                        InsertData($query, $bind);
                        $queryStores = "UPDATE STORES SET MANAGER = :empID WHERE ID = :storeID";
                        $bindStores = array(
                            array(
                                ':empID',
                                $empID
                            ),
                            array(
                                'storeID',
                                $store)
                        );
                        if (InsertData($queryStores, $bindStores) != 'success') {
                            return "Error: stores update, UpdateUser()";
                    }
                    }
 //                   elseif (GrabData('STORES', 'MANAGER', 'ID', $store)['MANAGER'] == $empID) {
//                        echo 'storeManager'; //store has already a manager
//                        return false;
//                    }
                    
                } elseif (empty($anArray['bool:stores:manager']) && $type === 'MANAGER' && GrabData('STORES', 'ID', 'MANAGER', $empID)['ID'] == $store) {
                    $query = "UPDATE STORES SET MANAGER = NULL WHERE MANAGER = :empID";
                        $bind = array(
                            array(
                                ':empID',
                                $empID)
                        );
                        InsertData($query, $bind);
                } elseif (empty($anArray['bool:stores:manager']) && $type === 'MANAGER' && CheckExistExt($empID, 'STORES', 'ID', "MANAGER")) {
                    $query = "UPDATE STORES SET MANAGER = NULL WHERE MANAGER = :empID";
                        $bind = array(
                            array(
                                ':empID',
                                $empID)
                        );
                        InsertData($query, $bind);
                } elseif (empty($anArray['bool:stores:manager']) && $type != 'MANAGER' && CheckExistExt($empID, 'STORES', 'ID', "MANAGER")) {
                    $query = "UPDATE STORES SET MANAGER = NULL WHERE MANAGER = :empID";
                        $bind = array(
                            array(
                                ':empID',
                                $empID)
                        );
                        InsertData($query, $bind);
                }
                if (InsertData($queryEmployees, $bindEmployees) != 'success') {
                    return " Error: employees insert, UpdateUser()";
                }
                if (InsertData($queryEmployment, $bindEmployment) != 'success') {
                    return "Error: employment insert, UpdateUser()";
                }
                
                return "success";
            }
        }
    }
}

function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {
    $str = '';
    $max = strlen($keyspace) -1;
    if ($max < 1) {
        throw new Exception('$keyspace must be at least two characters long');
    }
    for ($i = 0; $i < $length; ++$i) {
        $str .= $keyspace[random_int(0, $max)];
    }
    return $str;
}

function ResetPassword($empID)
{
    if (!empty($empID)) {
        if (CheckExistExt($empID, 'EMPLOYEES', 'EMPID', 'EMPID')) {
            $password = random_str(6);
            $HashedPass = password_hash($password, PASSWORD_DEFAULT);
            $query = "UPDATE EMPLOYEES SET PASSWORD = :Resetpassword WHERE EMPID = :EMPID";
            $bind = array(
                array(
                    ':empID',
                    $empID
                ),
                array(
                    ':Resetpassword',
                    $HashedPass
                )
            );
            if (InsertData($query, $bind) != 'success') {
                return " Error: employees insert, ResetPassword()";
            }
            return array("success", $password);
        }
    }
}

function terminateUser($empID)
{
    if (!empty($empID)) {
        if (CheckExistExt($empID, 'EMPLOYEES', 'EMPID', 'EMPID')) {
            $EMPEND = time();
            $query = "UPDATE EMPLOYMENT SET EMPEND = :EMPEND WHERE EMPID = :EMPID";
            $bind = array(
                array(
                    ':empID',
                    $empID
                ),
                array(
                    ':EMPEND',
                    $EMPEND
                )
            );
            if (InsertData($query, $bind) != 'success') {
                return " Error: employment insert, terminateUser()";
            }
            $query = "UPDATE EMPLOYEES SET INITIALSETUP = 3 WHERE EMPID = :EMPID";
            $bind = array(
                array(
                    ':empID',
                    $empID
                )
            );
            if (InsertData($query, $bind) != 'success') {
                return " Error: employees insert, terminateUser()";
            }
            if (CheckExistExt($empID, 'STORES', 'MANAGER', 'MANAGER')) {
                $query = "UPDATE STORES SET MANAGER = NULL WHERE MANAGER = :EMPID";
                $bind = array(
                array(
                    ':empID',
                    $empID
                )
                );
                if (InsertData($query, $bind) != 'success') {
                    return " Error: stores insert, terminateUser()";
                }
            }
            return "success";
        }
    }
}

function CreateStore($anArray)
{
        if (!empty($anArray)) {
            if (!empty($anArray['string:stores:street'])) {
                if (CheckExist('string:stores:street', 'STORES', 'STREET', $anArray)) {
                    return "exist"; //street used
                }
                $values = ValueCheck($anArray, 'storeAdd');
                if (!is_array($values)) {
                    return $values;
                }
                $manager = $values['manager'];
                $phone = $values['phone'];
                $queryStores = "INSERT INTO STORES (STREET, SUBURB, POSTCODE, STATE, PHONE, MANAGER) VALUES(:street, :suburb, :postcode, :state, :phone, :manager)";
                $bindStores = array(
                    array(
                        ':street',
                         $anArray['string:stores:street']
                    ),
                    array(
                        ':suburb',
                         $anArray['string:stores:suburb']
                    ),
                    array(
                        ':postcode',
                         $anArray['number:stores:postcode']
                    ),
                    array(
                        ':state',
                         $anArray['string:stores:state']
                    ),
                    array(
                        ':phone',
                         $phone
                    ),
                    array(
                        ':manager',
                         $manager
                    )
                );
                 if (InsertData($queryStores, $bindStores) != 'success') {
                    return " Error: stores insert, CreateStore";
                }
                $storeID = GrabData('STORES', 'ID', 'STREET', $anArray['string:stores:street']);
                $storeID = $storeID['ID'];
                return array(
                    "success",
                    $storeID
                );
            }
        }
}

function UpdateStore($anArray)
{
        if (!empty($anArray)) {
            if (!empty($anArray['number:stores:id'])) {
                if (CheckExist('number:stores:id', 'STORES', 'ID', $anArray)) {
                    $values = ValueCheck($anArray, 'storeAdd');
                    if (!is_array($values)) {
                        return $values;
                    }
                    $manager = $values['manager'];
                    $phone = $values['phone'];
                    $ID = $anArray['number:stores:id'];
                    $queryStores = "UPDATE STORES SET STREET = :street, SUBURB = :suburb, POSTCODE = :postcode, STATE = :state, PHONE = :phone, MANAGER = :manager WHERE ID = :storeID";
                    $bindStores = array(
                        array(
                            ':street',
                             $anArray['string:stores:street']
                        ),
                        array(
                            ':suburb',
                             $anArray['string:stores:suburb']
                        ),
                        array(
                            ':postcode',
                             $anArray['number:stores:postcode']
                        ),
                        array(
                            ':state',
                             $anArray['string:stores:state']
                        ),
                        array(
                            ':phone',
                             $phone
                        ),
                        array(
                            ':manager',
                             $manager
                        ),
                        array(
                            ':storeID',
                             $ID
                        )
                    );
                     if (InsertData($queryStores, $bindStores) != 'success') {
                        return " Error: stores insert, UpdateStore";
                    }
                    return "success";
                }
            }
        }
}

function terminateStore($empID)
{
    if (!empty($empID)) {
        if (CheckExistExt($empID, 'STORES', 'ID', 'ID')) {
            $EMPEND = time();
            $query = "DELETE FROM STORES WHERE ID = :EMPID";
            $bind = array(
                array(
                    ':empID',
                    $empID
                )
            );
            if (InsertData($query, $bind) != 'success') {
                return " Error: employment insert, terminateStore()";
            }
            return "success";
        }
    }
}

function ValueCheck($anArray, $CheckType)
{
    $res = array();
    if ($CheckType == 'storeAdd') {
        if (!empty($anArray['number:stores:phone'])) {
        $anArray['number:stores:phone'] = intval($anArray['number:stores:phone']);
        $res['phone'] = filter_var($anArray['number:stores:phone'], FILTER_VALIDATE_INT);
        if (!filter_var($res['phone'], FILTER_VALIDATE_INT)) {
            return 'phone';
            }
        } else {
            return 'phone';
        }if (!empty($anArray['number:stores:postcode'])) {
            $anArray['number:stores:postcode'] = intval($anArray['number:stores:postcode']);
        $res['postcode'] = filter_var($anArray['number:stores:postcode'], FILTER_VALIDATE_INT);
        if (!filter_var($res['postcode'], FILTER_VALIDATE_INT)) {
            return 'postcode';
            }
        } else {
            return 'postcode';
        }
        if (!empty($anArray['number:stores:manager'])) {
            $anArray['number:stores:manager'] = intval($anArray['number:stores:manager']);
        $res['manager'] = filter_var($anArray['number:stores:manager'], FILTER_VALIDATE_INT);
        if (!filter_var($res['manager'], FILTER_VALIDATE_INT)) {
            return 'manager';
            }
        } else {
            return 'manager';
        }
         return $res;
    }
    $res['email'] = filter_var($anArray['email:employees:username'], FILTER_SANITIZE_EMAIL);
    if (!filter_var($res['email'], FILTER_VALIDATE_EMAIL)) {
        return "username";
    }
    if ($CheckType == 'Add') {
        if ($anArray['email:employees:username2'] !== $anArray['email:employees:username']) {
            return "username2";
        }
    }

    if (!empty($anArray['string:employees:type'])) {
        if ($anArray['string:employees:type'] !== 'EMPLOYEE' &&
            $anArray['string:employees:type'] !== 'PAYROLL' &&
            $anArray['string:employees:type'] !== 'MANAGER') {
            return "account";
        } else {
            $res['type'] = $anArray['string:employees:type'];
        }
    } else {
        return "account";
    }
    if (!empty($anArray['string:employment:type'])) {
        if ($anArray['string:employment:type'] !== 'CASUAL' &&
            $anArray['string:employment:type'] !== 'FULL-TIME' &&
            $anArray['string:employment:type'] !== 'PART-TIME') {
            return "type";
        } else {
            $res['EmpType'] = $anArray['string:employment:type'];
        }
    } else {
        return "type";
    }
    if (!empty($anArray['string:employment:salary'])) {
        $res['rateType'] = $anArray['string:employment:salary'];
    } else {
        $res['rateType'] = 'HOURLY';
    }
    if (!empty($anArray['number:employees:store'])) {
        $anArray['number:employees:store'] = intval($anArray['number:employees:store']);
        $res['store'] = filter_var($anArray['number:employees:store'], FILTER_VALIDATE_INT);
        if (!filter_var($res['store'], FILTER_VALIDATE_INT)) {
            return 'store';
        }
    }
    return $res;
}

function sendWelcomeEmail($email, $position, $store, $ID, $startDate)
{
    $ID = "E".str_pad($ID, 7, '0', STR_PAD_LEFT);
    $expireDate = $startDate + 259200;
    $para = rtrim(strtr(base64_encode($ID.".".$expireDate), '+/', '-_'), '='); 
    $html = "<!DOCTYPE html>
    <html>
        <head>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
            <meta charset='utf-8'>
            <meta http-equiv='x-ua-compatible' content='ie=edge'>
            <meta name='viewport' content='width=device-width, initial-scale=1'>


        </head>
        <body style='background-color: #677979; color: black; font-family: sans-serif; display: table; table-layout: auto; width: 100%; margin: 0; padding: 0; padding-bottom: 25px' bgcolor='#677979';>
            <div style='display: table-cell; width: 100%;'>
                <img src='https://www.ptrmanagement.online/images/LOGO1.png' alt='PTR logo' height='80' width='200' style='padding-top: 25px; padding-bottom: 25px; display: block; margin: 0 auto'>
                <div style='display: block; max-width: 700px; background-color: white; height: 100%; border-bottom-style: solid; border-bottom-width: 5px; border-bottom-color: #d4d4d4; font-size: 14px; margin: 0 auto;'>
                <div style='box-sizing: border-box; padding-left: 50px; padding-right: 50px; overflow: hidden;'>
                <h2>Welcome to ClothingCo.</h2>
                    <div>You must be excited to start your new position as $position at $store.
                    
                    This is your Employee ID, you will need it to sign up on the system: $ID.
                    You will also need to complete the following steps to ensure we have all your details:
                    <ol>
                        <li style='padding-bottom: 10px;'>Access a device with an internet connection</li>
                        <li style='padding-bottom: 10px;'>Click on the 'Access system' button below (this link will expire three days commencing employment)</li>
                        <li style='padding-bottom: 10px;'>Click on the 'Sign Up' link if you find yourself on the sign in page.</li>
                        <li style='padding-bottom: 10px;'>Enter the email you provided to your manager on the day of your interview or any further communication</li>
                        <li style='padding-bottom: 10px;'>Enter a strong password (at least 8 characters, an upper case letter and a number)</li>
                        <li style='padding-bottom: 10px;'>Enter the your Employee ID if not already entered</li>
                        <li style='padding-bottom: 10px;'>Click on the 'SIGN UP' button to proceed to the next page.</li>
                        </ol></div></div>
                    <a href='https://www.ptrmanagement.online/webpages/start.php?sid=$para' style='background-color: #33a7a7; width: 100%; display: block; text-align: center; padding-top: 15px; padding-bottom: 17px; color: white; text-decoration: none; font-weight: bold;'>Access system</a>
                    <div style='display: block;margin: 0 auto;padding-top: 10px;text-align: center;font-size: 11px;color: #bdbdbd;font-weight: bold;padding-bottom: 10px;'>Please do not respond to this email</div>
                </div>

            </div>
        </body>
    </html>
    ";
    Emailer(array($email), null, "Welcome to ClothingCo.", null,null,null,null,null,null,$html);
}
