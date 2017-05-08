<?php 
include '../functions/tools.php';
if (session_id() == '')
    {
        session_start();
    }
if(empty($_SESSION['EMPID']) || $_SESSION['TYPE'] != "CEO" && $_SESSION['TYPE'] != "MANAGER")
    header("Location: start.php");
?>
<!doctype html>
<html lang="en" class="employeesHTML">
<head>
    <script>
//        if(window.location.href.indexOf('webpages') != -1)
//        {
//            //window.location.replace("../index.php#/employees");
//        }
    </script>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="../stylesheets/normalize.css" media="none" onload="if(media!='all')media='all'">
<link rel="stylesheet" href="../javascript/tablesaw/tablesaw.css" media="none" onload="if(media!='all')media='all'">
<link rel="stylesheet" href="employees.css" media="none" onload="if(media!='all')media='all'">
<script type="application/javascript" src="../javascript/jquery-3.2.0.min.js"></script>
<script type="application/javascript" src="../javascript/tablesaw/tablesaw.jquery.js"></script>
<script type="application/javascript" src="../javascript/tablesaw/tablesaw-init.js"></script>
<script type="application/javascript" src="../javascript/employees.js"></script>
</head>
<body>
<div class="employeesContent preload">
        <div class="employees boxContainer">
            <a href="#" class="createNew input">Create new</a>
            <a href="#" class="showTerm input">Show terminated</a>
            <input type="text" id="SearchEmp" class="searchBox input" placeholder="Search employees">
            <table class="mainTable tablesaw" data-tablesaw-mode="columntoggle" data-tablesaw-sortable >
                <thead class="tableHead">
                    <tr>
                        <th scope="col" class="tablesaw-sortable-head tableHeaders" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="persist">
                            Employee ID
                        </th>
                        <th scope="col" class="tableHeaders" data-tablesaw-sortable-col data-tablesaw-priority="persist">Name
                        </th>
                        <th scope="col" class="tableHeaders" data-tablesaw-sortable-col data-tablesaw-priority="1">Email
                        </th>
                        <th scope="col" class="tableHeaders" data-tablesaw-sortable-col data-tablesaw-priority="2">Position
                        </th>
                        <th scope="col" class="tableHeaders" data-tablesaw-sortable-col data-tablesaw-priority="3">Employment Type
                        </th>
                        <th scope="col" class="tableHeaders" data-tablesaw-sortable-col data-tablesaw-sortable-numeric data-tablesaw-priority="4">Rate $
                        </th>
                        <th scope="col" class="tableHeaders" data-tablesaw-sortable-col data-tablesaw-priority="0">Start Date
                        </th>
                        <th scope="col" class="tableHeaders" data-tablesaw-sortable-col data-tablesaw-priority="0">End Date
                        </th>
                    </tr>
                </thead>
                <tbody id="TableSearchTbody">
                    <?php 
                    if ($_SESSION['TYPE'] === 'MANAGER') {
                         $query = 'SELECT EMPLOYEES.EMPID, EMAIL, F_NAME, L_NAME, POSITION, RATE, EMPLOYMENT.TYPE, EMPSTART, EMPEND, EMPTYPE FROM EMPLOYEES LEFT JOIN EMPLOYMENT ON EMPLOYEES.EMPID = EMPLOYMENT.EMPID WHERE EMPLOYEES.TYPE = :TYPE OR EMPLOYEES.TYPE = :TYPE2 ORDER BY EMPLOYEES.EMPID';
                        $bind = array(array(":TYPE", 'EMPLOYEE'),array(":TYPE2", 'PAYROLL'));
                    }
                    elseif ($_SESSION['TYPE'] === 'CEO') {
                        $query = 'SELECT EMPLOYEES.EMPID, EMAIL, F_NAME, L_NAME, POSITION, RATE, EMPLOYMENT.TYPE, EMPSTART, EMPEND, EMPTYPE FROM EMPLOYEES LEFT JOIN EMPLOYMENT ON EMPLOYEES.EMPID = EMPLOYMENT.EMPID WHERE EMPLOYEES.TYPE != :TYPE ORDER BY EMPLOYEES.EMPID';
                        $bind = array(array(":TYPE", $_SESSION['TYPE']));
                    }
                     $employees = GrabAllData($query, $bind);
                    if (!empty($employees))
                    {
                       for ($i = 0; $i < count($employees["EMPID"]);$i++)
                        {
                           if ($employees['TYPE'][$i] == 'SALARY')
                           {
                               $typePay = " p/w";
                           }
                           else
                           {
                               $typePay = " p/h";
                           }
                           $type = ucwords(strtolower($employees['EMPTYPE'][$i]));
                            echo '<tr class="tableRow">
                            <td><a href="#" class="TableLink">'."E".str_pad($employees['EMPID'][$i], 7, '0', STR_PAD_LEFT).'
                            </a></td>
                            <td>'.$employees['F_NAME'][$i].' '.$employees['L_NAME'][$i].'
                            </td>
                            <td>'.$employees['EMAIL'][$i].'
                            </td>
                            <td>'.$employees['POSITION'][$i].'
                            </td>
                            <td>'.$type.'
                            </td>
                            <td>'.sprintf("%.2f", $employees['RATE'][$i]).$typePay.'
                            </td>
                            <td>';
                            if (!empty($employees['EMPSTART'][$i]))
                                {
                                    echo date('d/m/Y',$employees['EMPSTART'][$i]);
                                }
                           echo '</td>
                            <td>';
                            if (!empty($employees['EMPEND'][$i]))
                                {
                                    echo date('d/m/Y',$employees['EMPEND'][$i]);
                                }
                           echo '</td>
                        </tr>';
                        } 
                    }
                    
    ?>
                </tbody>
            </table>
        </div>
</div>
    <script type="application/javascript">$(document).ready(function(){
        $(".preload").removeClass("preload");})</script>
</body>
</html>
