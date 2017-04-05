<?php 
include '../functions/tools.php';
if (session_id() == '')
    {
        session_start();
    }
if(empty($_SESSION['EMPID']) || $_SESSION['TYPE'] != "CEO")
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
<link rel="stylesheet" href="../javascript/tablesaw/tablesaw.css" media="none" onload="if(media!='all')media='all'">
<link rel="stylesheet" href="employees.css" media="none" onload="if(media!='all')media='all'">
<script type="application/javascript" src="../javascript/jquery-3.2.0.min.js"></script>
<script type="application/javascript" src="../javascript/tablesaw/tablesaw.jquery.js"></script>
<script type="application/javascript" src="../javascript/tablesaw/tablesaw-init.js"></script>
</head>
<body>
<div class="employeesContent preload">
        <div class="employees boxContainer">
            <a href="#" class="createNew input">Create new</a>
            <input type="text" class="searchBox input" placeholder="Search employees">
            <table class="mainTable tablesaw" data-tablesaw-mode="columntoggle" data-tablesaw-sortable >
                <thead class="tableHead">
                    <tr>
                        <th scope="col" class="tablesaw-sortable-head tableHeaders" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="persist">
                            Employee ID
                        </th>
                        <th scope="col" class="tableHeaders" data-tablesaw-sortable-col data-tablesaw-priority="1">Name
                        </th>
                        <th scope="col" class="tableHeaders" data-tablesaw-sortable-col data-tablesaw-priority="1">Position
                        </th>
                        <th scope="col" class="tableHeaders" data-tablesaw-sortable-col data-tablesaw-priority="1">Store
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php $query = 'SELECT EMPID, F_NAME, L_NAME FROM EMPLOYEES WHERE TYPE != :TYPE';
                    $bind = array(array(":TYPE",$_SESSION['TYPE']));
                     $employees = GrabAllData($query, $bind);
                    if (!empty($employees))
                    {
                       for ($i = 0; $i < count($employees["EMPID"]);$i++)
                        {
                            echo '<tr class="tableRow">
                            <td><a href="#" class="TableLink">'."E".str_pad($employees['EMPID'][$i], 7, '0', STR_PAD_LEFT).'
                            </a></td>
                            <td>'.$employees['F_NAME'][$i].' '.$employees['L_NAME'][$i].'
                            </td>
                            <td>d
                            </td>
                            <td>f
                            </td>
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
