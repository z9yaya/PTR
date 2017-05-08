<?php include 'vendor\autoload.php';
use mikehaertl\pdftk\Pdf;
function connect()
{
    $conn = oci_connect('ptr', 'ptr', 'localhost:1521/xe');
    if (!$conn) {
        $e = oci_error();
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    } else {
        return $conn;
    }
}//end connect
/* Used to check if a row with the specified value exist in a table
 INPUT $attribute: index name in $_GET or $_POST
 INPUT $table: name of table in database
 INPUT $column: name of column to check against in database
 INPUT $getOrpost: specifies where the data is stored, options:$_GET or $_POST*/
function CheckExist($attribute, $table, $column, $getOrpost)
{
    if (isset($getOrpost)) {
        if (!empty($getOrpost) && !empty($getOrpost[$attribute])) {
            $input = htmlspecialchars($getOrpost[$attribute]);
            $pdo = connect();
            $sql = 'SELECT COUNT('.$column.') FROM '.$table.' where '.$column.' = :attribute';
            $prepare = oci_parse($pdo, $sql);
            oci_bind_by_name($prepare, ':attribute', $input);
            if (oci_execute($prepare)) {
                $res = oci_fetch_array($prepare, OCI_ASSOC + OCI_RETURN_NULLS);
                if ($res['COUNT('.$column.')'] != 0) {
                    return true;
                } else {
                    return false;
                }
            } else {
                $e = oci_error($prepare);
                echo $e['message'];
            }
        }
    }
}

function CheckExistExt($attribute, $table, $column, $wherecolumn)
{
    if (!empty($attribute)) {
        $input = htmlspecialchars($attribute);
        $pdo = connect();
        $sql = 'SELECT COUNT('.$column.') FROM '.$table.' where '.$wherecolumn.' = :attribute';
        $prepare = oci_parse($pdo, $sql);
        oci_bind_by_name($prepare, ':attribute', $input);
        if (oci_execute($prepare)) {
            $res = oci_fetch_array($prepare, OCI_ASSOC + OCI_RETURN_NULLS);
            if ($res['COUNT('.$column.')'] != 0) {
                return true;
            } else {
                return false;
            }
        } else {
                $e = oci_error($prepare);
                echo $e['message'];
                exit();
        }
    }
}

/*Used to return single cell from database
INPUT $table: table in the database where to look for the data
INPUT $column: the name of the column you want to select
INPUT $where_column: the name of the column that contains the data that needs to match the input
INPUT $where: the data that will be looked for in the specified column.*/
function GrabData($table, $column, $where_column, $where)
{
    $input = htmlspecialchars($where);
    $pdo = connect();
    $sql = 'SELECT ' . $column . ' FROM ' . $table . ' where ' . $where_column . ' = :attribute';
    $prepare = oci_parse($pdo, $sql);
    oci_bind_by_name($prepare, ':attribute', $input);
    if (oci_execute($prepare)) {
        $res = oci_fetch_array($prepare, OCI_ASSOC + OCI_RETURN_NULLS);
        if ($res != null) {
            return $res;
        } else {
            return false;
        }
    } else {
        $e = oci_error($prepare);
        echo $e['message'];
    }
}

/*Used to return the results of a specified mySQL query
$query is the basic mySQL query eg: "SELECT * FROM users WHERE email = :email AND password = :password".
$bind is a nested array, must be in pairs, 
eg: 'array(array(':email', 'generic@email.com'), array(':password', 'passwordtext'))'*/
function GrabMoreData($query, $bind = null)
{
    $pdo = connect();
    $sql = $query;
    $prepare = oci_parse($pdo, $sql);
    if (!empty($bind)) {
        foreach ($bind as $attribute) {
            oci_bind_by_name($prepare, $attribute[0], $attribute[1]);
        }
    }

    if (oci_execute($prepare)) {
        $res = oci_fetch_array($prepare, OCI_ASSOC + OCI_RETURN_NULLS);
        if ($res != null) {
            return $res;
        } else {
            return false;
        }
    } else {
        $e = oci_error($prepare);
        echo $e['message'];
    }
}

function GrabAllData($query, $bind = null)
{
    $pdo = connect();
    $sql = $query;
    $prepare = oci_parse($pdo, $sql);
    if (!empty($bind)) {
        foreach ($bind as $attribute) {
            oci_bind_by_name($prepare, $attribute[0], $attribute[1]);
        }
    }

    if (oci_execute($prepare)) {
        oci_fetch_all($prepare, $res);
        if ($res != null) {
            return $res;
        } else {
            return false;
        }
    } else {
        $e = oci_error($prepare);
        echo $e['message'];
    }
}

function InsertData($query, $bind = null)
{
    $pdo = connect();
    $sql = $query;
    $prepare = oci_parse($pdo, $sql);
    if (!empty($bind)) {
        foreach ($bind as $attribute) {
            oci_bind_by_name($prepare, $attribute[0], $attribute[1]);
        }
    }
    if (oci_execute($prepare)) {
        return 'success';
    } else {
        $e = oci_error($prepare);
        echo $e['message'];
    }
}

function FillPDF($report = 'pay_advice', $payID = 0, $paygName = 'MANYAN_16', $employeeID = 0)
{
    if ($report === 'pay_advice') {
        $TemplateLocation = "C:\\Users\\Administrator\\Documents\\forms\\pay_advice.pdf";
        $resLocation = "C:\\Users\\Administrator\\Documents\\pay_advice\\$payID.pdf";
        $PDFname = 'PAY_'.$payID;
        $dataArray = array (
'employee_details' => 
'',
'week_ending' => 
'',
'transfer_date' => 
'',
'title' => 
'',
'shift_date' => 
'',
'shift_hours' => 
'',
'shift_rate' => 
'',
'shift_total' => 
'',
'super' => 
'',
'transfer_date_banking' => 
'',
'YTD_gross' => 
'',
'YTD_tax' => 
'',
'YTD_net' => 
'',
'PAY_gross' => 
'',
'PAY_tax' => 
'',
'PAY_net' => 
'',
'OTHER_date' => 
'',
'OTHER_leave' => 
'',
'OTHER_rdo' => 
'');
    } elseif ($report === 'payg') {
        $TemplateLocation = "C:\\Users\\Administrator\\Documents\\forms\\payg.pdf";
        $resLocation = "C:\\Users\\Administrator\\Documents\\payg\\$paygName.pdf";
        $PDFname = $paygName;
        $dataArray = array (
'employee_details' => 
'',
'from' => 
'',
'to' => 
'',
'tfn' => 
'',
'tax' => 
'',
'gross' => 
'',
'cdep' => 
'',
'other' => 
'',
'fringe' => 
'',
'lumpA' => 
'',
'lumpB' => 
'',
'lumpC' => 
'',
'lumpD' => 
'',
'year' => 
'',
'comment' => 
'',
'signature' => 
'',
'signDate' => 
'',
'payerName' =>
'Clothing.Co Stores Pty Ltd',
'branchN' =>
'001',
'payer_ABN' =>
'95 152 075 203'); 
    } else if ($report === 'employee') {
        $TemplateLocation = "C:\\Users\\Administrator\\Documents\\forms\\employee.pdf";
        $resLocation = "C:\\Users\\Administrator\\Documents\\payg\\$employeeID.pdf";
        $PDFname = $employeeID;
        $dataArray = array (
'Report' =>
'Employee Information',
'empID' =>
$employeeID,
'dateRange' =>
'03/09/2016 - 06/06/2017',
'f_name' => 
'Yannick',
'm_name' => 
'Malcolm Denis Louis',
'l_name' => 
'Mansuy',
'dob' => 
'03/09/1994',
'email' => 
'y.mansuy@outlook.com',
'phone' => 
'0415142510',
'address' => 
'16-18 Mingah Crescent',
'suburb' => 
'Shailer Park',
'postcode' => 
'4128',
'state' => 
'QLD',
'tfn' => 
'',
'bank' => 
'',
'bsb' => 
'',
'acc' => 
'',
'position' => 
'',
'type' => 
'',
'rate' => 
'',
'empstart' => 
'',
'empend' => 
'',
'store' => 
'',
'weeks_worked' => 
'',
'gross' => 
'',
'net' => 
'',
'tax' => 
'',
'overtime' => 
'',
'total_shifts' => 
'',
'avg_shift' => 
'',
'shift_skipped' => 
'',
'total_hours' => 
'',
'shift_start_late' => 
'',
'shift_start_early' => 
'',
'shift_finish_late' => 
'',
'shift_finish_late' => 
'',
'rosterd' => 
'',
'leave_total' => 
'',
'leave_taken' => 
'',
'shift_skipped' => 
'',
'sick_taken' => 
'ergrgr',
'sick_left' => 
''); 
    }    
    $temp = tempnam(sys_get_temp_dir(), gethostname());
    $fdf = '%FDF-1.2
    1 0 obj<</FDF<< /Fields[';
    foreach ($dataArray as $key => $value) {
        $fdf .= '<</T(' . $key . ')/V(' . $value . ')>>';
    }
    $fdf .= "] >> >>
    endobj
    trailer
    <</Root 1 0 R>>
    %%EOF";
    $fdf_file = $temp;
    file_put_contents($fdf_file, $fdf);
    $programLocation = '"C:\\Program Files (x86)\\PDFtk Server\\bin\\pdftk.exe"';
    $command = "$programLocation $TemplateLocation fill_form $fdf_file output $resLocation flatten 2>&1";
    echo $command;
    system($command, $execoutput);
    //unlink($temp);
    if ($execoutput !== 0) {
        return $execoutput[0];
    }
    ShowPDF($PDFname,$resLocation);
}

function ShowPDF($filename, $location)
{
    header('Content-type: application/pdf');
    header('Content-Disposition: inline; filename="' . $filename . '.pdf"');
    header('Content-Transfer-Encoding: binary');
    header('Accept-Ranges: bytes');
    @readfile($location);
}
//FillPDF('employee');