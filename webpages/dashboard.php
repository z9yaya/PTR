<?php 
if (session_id() == '')
    {
        session_start();
    }
if(!isset($_SESSION['EMPID']) || empty($_SESSION['EMPID']))
    header("Location: start.php");
?>
<head>
    <script>
        if(window.location.href.indexOf('webpages') != -1)
        {
            window.location.replace("../index.php#/dashboard");
        }
        $("document").ready(function(){
            var Width = (($(window).width() - 62) / 2);
            var  WidthBig = (($(window).width() - 75) / 3);
            if ($(window).width() <= 415)
                {
                    $('.square_container').css('height', Width);
                    $('.square_container').css('width', Width);
                    $('.rectangle_container').css('height', Width);
                    $('.rectangle_container').css('width', (Width*2)+5);
                }
            else if($(window).width() > 590 & $(window).width() <= 1057)
            {
                $('.square_container').css('height', WidthBig);
                $('.square_container').css('width', WidthBig);
                $('.rectangle_container').css('height', WidthBig);
                $('.rectangle_container').css('width', (WidthBig*2)+5);
            }
            else if($(window).width() > 415 & $(window).width() <= 590)
                {
                    $('.square_container').css('height', 175);
                    $('.square_container').css('width',175);
                    $('.rectangle_container').css('height',175);
                    $('.rectangle_container').css('width', 355);
                }
            else
            {
                $('.square_container').css('height', 325);
                $('.square_container').css('width', 325);
                $('.rectangle_container').css('height', 325);
                $('.rectangle_container').css('width', 655);
            }
        });
    </script>
    <link rel="stylesheet" href="webpages/dashboard.css" media="none" onload="if(media!='all')media='all'">
</head>
<div class="dashboardContent">
<div class="dashboard_containers rectangle_container"></div>
<a class="noDecoration" href="index.php#/roster"><div class="dashboard_containers square_container" href="index.php#/roster">
    <div class="chartTitleContainer">
        <div class="chartTitle">Hours Done</div>
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
     function ResizeSquares()
     {
        var Width = (($(window).width() - 62) / 2);
        var  WidthBig = (($(window).width() - 75) / 3);
        if ($(window).width() <= 415)
        {
            $('.square_container').css('height', Width);
            $('.square_container').css('width', Width);
            $('.rectangle_container').css('height', Width);
            $('.rectangle_container').css('width', (Width*2)+5);
        }
        else if($(window).width() > 590 & $(window).width() <= 1057)
        {
            $('.square_container').css('height', WidthBig);
            $('.square_container').css('width', WidthBig);
            $('.rectangle_container').css('height', WidthBig);
            $('.rectangle_container').css('width', (WidthBig*2)+5);
        }
        else if($(window).width() > 415 & $(window).width() <= 590)
        {
            $('.square_container').css('height', 175);
            $('.square_container').css('width',175);
            $('.rectangle_container').css('height',175);
            $('.rectangle_container').css('width', 355);
        }
        else
        {
            $('.square_container').css('height', 325);
            $('.square_container').css('width', 325);
            $('.rectangle_container').css('height', 325);
            $('.rectangle_container').css('width', 655);
        }
     }
     function calcHours()
     {
         var now = new Date().getTime();
         if (startTime == 0)
         {
            hoursDone = 00;
            hoursLeft = ("0"+Math.floor((ShiftEnd - ShiftStart) % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)).slice(-2);
             return true;
         }
         else
         {
            hoursDone = ("0"+Math.floor(((now - startTime) % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).slice(-2);
         }
         if (ShiftEnd == 0)
         {
            hoursLeft = 0;
         }
         else
         {
            hoursLeft = ("0"+Math.floor(((ShiftEnd - now) % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).slice(-2);
         }
}
     
 $.getScript("javascript/chartBundle.js",function(){
     window.chartColors = {
	red: 'rgb(255, 99, 132)',
	orange: 'rgb(255, 159, 64)',
	yellow: 'rgb(255, 205, 86)',
	green: 'rgb(75, 192, 192)',
	blue: 'rgb(54, 162, 235)',
	purple: 'rgb(153, 102, 255)',
	grey: 'rgb(231,233,237)',
    darkblue: 'rgb(40, 129, 189)'
};
     

     startTime = <?php if(isset($_SESSION["SHIFTSTART"]) && !empty($_SESSION["SHIFTSTART"]))
                {
                    echo $_SESSION["SHIFTSTART"];
                }
                else
                {
                    echo 0;
                }?>;
     stopTime = <?php if(isset($_SESSION["SHIFTSTOP"]) && !empty($_SESSION["SHIFTSTOP"]))
                {
                    echo $_SESSION["SHIFTSTART"];
                }
                else
                {
                    echo 0;
                }?>;
     ShiftStart = <?php if(isset($_SESSION["SHIFTBEGIN"]) && !empty($_SESSION["SHIFTBEGIN"]))
                { 
                    echo $_SESSION["SHIFTBEGIN"];
                }
                else
                {
                    echo 0;
                }?>;
     ShiftEnd = <?php if(isset($_SESSION["SHIFTEND"]) && !empty($_SESSION["SHIFTEND"]))
                { 
                    echo $_SESSION["SHIFTEND"];
                }
                else
                {
                    echo 0;
                }?>;
     calcHours();
     dashboardInterval =  setInterval(function() {
        calcHours();
        $(".chartNumber").html(hoursDone);
     },3600000);
     
     var config = {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [
                    hoursDone,
                    hoursLeft
                ],
                backgroundColor: [
                    window.chartColors.blue,
                    window.chartColors.grey
                ],
                label: 'Hours Today'
            }],
            labels: [
                "Hours done",
                "Hours left"
            ]
        },
        options: {
            cutoutPercentage: 70,
            responsive: true,
            legend: {
                position: 'top',
                display: false
            },
            title: {
                display: false,
                text: 'Hours Today'
            },
            animation: {
                animateScale: false,
                animateRotate: false
            }
        }
    };

     $("document").ready(function(){
                         $(".chartNumber").html(hoursDone);
                         ResizeSquares();
                         $(".dashboard_containers.linkable").on("mouseup",function(event)
                            {
                             if (event.which == 2)
                                 {
                                     window.open($(this).attr("href"));
                                 }
                             else if(event.which == 1)
                            window.location.href = $(this).attr("href");
                            })
                         });
        var ctx = document.getElementById("chart-area").getContext("2d");
        window.myDoughnut = new Chart(ctx, config);
        var colorNames = Object.keys(window.chartColors);
     });
     $( window ).resize(ResizeSquares);
    </script>