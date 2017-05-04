<?php
function CreateUser($anArray)
{
    if (CheckExist('email:employees:username', 'EMPLOYEES', 'EMAIL', $anArray)) {
        return "exist"; //email used
    } else {
        if (!empty($anArray)) {
            if (!empty($anArray['email:employees:username'])) {
                $values = ValueCheck($anArray, 'add');
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

function ValueCheck($anArray, $CheckType)
{
    $res = array();
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
        $res['store'] = filter_var($anArray['number:employees:store'], FILTER_VALIDATE_INT);
        if (!filter_var($res['store'], FILTER_VALIDATE_INT)) {
            return 'store';
        }
    }
    return $res;
}