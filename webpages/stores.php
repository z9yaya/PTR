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
        if(top==self){
            window.location.href = "../404.html";
        }
    </script>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="../stylesheets/normalize.css" media="none" onload="if(media!='all')media='all'">
<link rel="stylesheet" href="../javascript/tablesaw/tablesaw.css" media="none" onload="if(media!='all')media='all'">
<link rel="stylesheet" href="stores.css" media="none" onload="if(media!='all')media='all'">
<script type="application/javascript" src="../javascript/jquery-3.2.0.min.js"></script>
<script type="application/javascript" src="../javascript/tablesaw/tablesaw.jquery.js"></script>
<script type="application/javascript" src="../javascript/tablesaw/tablesaw-init.js"></script>

<script type="application/javascript" src="../javascript/stores.js"></script>
</head>
<body>
<div class="storesContent preload">
        <div class="stores boxContainer">
            <a href="#" class="createNew input">Create new</a>
            <input type="search" id="SearchEmp" class="searchBox input" placeholder="Search stores">
            <table class="mainTable tablesaw" data-tablesaw-mode="columntoggle" data-tablesaw-sortable >
                <thead class="tableHead">
                    <tr>
                        <th scope="col" class="tablesaw-sortable-head tableHeaders" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="persist">
                            Store ID
                        </th>
                        <th scope="col" class="tableHeaders" data-tablesaw-sortable-col data-tablesaw-priority="2">Street
                        </th>
                        <th scope="col" class="tableHeaders" data-tablesaw-sortable-col data-tablesaw-priority="1">Suburb
                        </th>
                        <th scope="col" class="tableHeaders" data-tablesaw-sortable-col data-tablesaw-priority="3">Postcode
                        </th>
                        <th scope="col" class="tableHeaders" data-tablesaw-sortable-col data-tablesaw-priority="2">State
                        </th>
                        <th scope="col" class="tableHeaders" data-tablesaw-sortable-col data-tablesaw-sortable-numeric data-tablesaw-priority="4">Phone
                        </th>
                        <th scope="col" class="tableHeaders" data-tablesaw-sortable-col data-tablesaw-priority="persist">Manager
                        </th>
                    </tr>
                </thead>
                <tbody id="TableSearchTbody">
                    <?php 
                    if ($_SESSION['TYPE'] === 'MANAGER') {
                         
                        $query = 'SELECT * FROM STORES WHERE MANAGER = :EMPID ORDER BY ID';
                        $bind = array(array(":EMPID", $_SESSION['EMPID']));
                    }
                    elseif ($_SESSION['TYPE'] === 'CEO') {
                        $query = 'SELECT * FROM STORES ORDER BY ID';
                        $bind = null;
                    }
                     $stores = GrabAllData($query, $bind);
                    if (!empty($stores))
                    {
                       for ($i = 0; $i < count($stores["ID"]);$i++)
                        {
                           $managerCell = 'N/A';
                           if(!empty($stores['MANAGER'][$i])) {
                               $managerID = "E".str_pad($stores['MANAGER'][$i], 7, '0', STR_PAD_LEFT);
                               $managerCell = '<a href="#" class="TableLink">'.$managerID.' <form action="editEmp.php" method="post"><input type="hidden" name="eeid" value="'.$stores['MANAGER'][$i].'"></form></a>';
                           }
                           
                            echo '<tr class="tableRow">
                            <td><a href="#" class="TableLinkStores">'.$stores['ID'][$i].'
                            <form action="editStore.php" method="post"><input type="hidden" name="esid" value="'.$stores['ID'][$i].'"></form></a></td>
                            <td>'.$stores['STREET'][$i].'
                            </td>
                            <td>'.$stores['SUBURB'][$i].'
                            </td>
                            <td>'.$stores['POSTCODE'][$i].'
                            </td>
                            <td>'.$stores['STATE'][$i].'
                            </td>
                            <td>'.str_pad($stores['PHONE'][$i], 10, '0', STR_PAD_LEFT).'
                            </td>
                            <td>'.$managerCell.'</td>
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
