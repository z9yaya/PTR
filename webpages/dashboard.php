<?php include "../functions/functions.php";
if (session_id() == '')
    {
        session_start();
    }
if(empty($_SESSION['EMPID']))
    header("Location: start.php");
header("Content-Security-Policy: frame-ancestors 'self'");
if (empty(getallheaders()['Referer'])) {
    header("Location: ../index.php");
    //GrabData()
}
$pay = GrabAllData('SELECT NET FROM PAY WHERE EMPID = :EMPID AND PAYDATE IS NOT NULL ORDER BY PAYDATE DESC', array(array(":EMPID", $_SESSION['EMPID'])));
$totpay = 0;
foreach ($pay as $k => $v) {
    $totpay += $v['PAY'];
}
?>
<head>
    <script src="javascript/dashboard.js"></script>
    <script>
        if(window.location.href.indexOf('webpages') != -1)
        {
            window.location.replace("../index.php#/dashboard");
        }
    </script>
    <link rel="stylesheet" href="../stylesheets/normalize.css" media="none" onload="if(media!='all')media='all'">
    <link rel="stylesheet" href="webpages/dashboard.css" media="none" onload="if(media!='all')media='all'">
</head>
<div class="dashboardContent">
    <?php if ($_SESSION['TYPE']== 'CEO' OR $_SESSION['TYPE'] == 'MANAGER') {
    echo '<div class="dashboard_containers rectangle_container"><div class="SquareTitleContainer"><div class="SquareTitle">Weekly Expenditure</div></div>
    <div class="DashboardContentContainer rectangle"><div class="NumberContainer">
        <div class="expenditure SquareNumber">'.expend().'</div>
    </div></div></div>';
} else {
echo '<a class="noDecoration ContentWithin" href="#/roster">
<div class="dashboard_containers rectangle_container">
    <div class="SquareTitleContainer">
        <div class="SquareTitle">Current Roster</div>
    </div>';
fetchSingleRoster();
echo '</div></a>';} ?>
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
<div class="dashboard_containers square_container">
    <div class="SquareTitleContainer">
        <div class="SquareTitle">Total Pay</div>
    </div>
    <div class="DashboardContentContainer"><div class="NumberContainer">
        <div class="expenditure SquareNumber"><?php echo "$".sprintf('%0.2f',$totpay[0]['PAY']); ?></div>
    </div></div>
    
</div>
<div class="dashboard_containers square_container">    
    <div class="SquareTitleContainer">
        <div class="SquareTitle">Last pay</div>
    </div>
    <div class="DashboardContentContainer"><div class="NumberContainer">
        <div class="pay SquareNumber"><?php echo "$".sprintf('%0.2f',$pay[0]['PAY']); ?></div>
    </div></div></div>
</div>