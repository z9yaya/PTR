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
<a class="noDecoration ContentWithin" href="#/roster"><div class="dashboard_containers square_container" >
    <div class="SquareTitleContainer">
        <div class="SquareTitle">Hours Today</div>
    </div>
    <div class="DashboardContentContainer">
        <canvas id="chart-area" class="dashboardCanvas" />
        <div class="NumberContainer">
            <div class="chartNumber SquareNumber"></div>
        </div>
    </div>
    </div></a>
<a class="noDecoration" href="#/leave"><div class="dashboard_containers square_container">
    <div class="SquareTitleContainer">
        <div class="SquareTitle">Holidays Left</div>
    </div>
    <div class="DashboardContentContainer"><div class="NumberContainer">
        <div class="holidayNumber SquareNumber"></div>
    </div></div>
    
</div></a>
<div class="dashboard_containers square_container"></div>
<div class="dashboard_containers square_container"></div>
</div>
 <script>
     
     
    </script>