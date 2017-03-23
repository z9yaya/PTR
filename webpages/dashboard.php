<?php include "../functions/functions.php";
if (session_id() == '')
    {
        session_start();
    }
if(empty($_SESSION['EMPID']))
    header("Location: start.php");
?>
<head>
    <script src="javascript/dashboard.js"></script>
    <script>
        if(window.location.href.indexOf('webpages') != -1)
        {
            window.location.replace("../index.php#/dashboard");
        }
    </script>
    <link rel="stylesheet" href="webpages/dashboard.css" media="none" onload="if(media!='all')media='all'">
</head>
<div class="dashboardContent">
<div class="dashboard_containers rectangle_container"></div>
<a class="noDecoration" href="index.php#/roster"><div class="dashboard_containers square_container" href="index.php#/roster">
    <div class="chartTitleContainer">
        <div class="chartTitle">Hours Today</div>
    </div>
    <div class="canvasContainer">
        <canvas id="chart-area" class="dashboardCanvas" />
        <div class="NumberContainer">
            <div class="chartNumber"></div>
        </div>
    </div>
    </div></a>
<div class="dashboard_containers square_container"></div>
<div class="dashboard_containers square_container"></div>
<div class="dashboard_containers square_container"></div>
</div>
 <script>
     
     
    </script>