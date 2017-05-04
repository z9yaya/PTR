startTime = '';
ShiftEnd = '';
stopTime = '';
ShiftStart = '';
hoursDone = '';
hoursLeft = '';
console.log("stuff");
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
//////
function calcHours() {
    var now = new Date().getTime();
    if (startTime === 0) {
        hoursDone = "00";
        if (ShiftEnd !== 0) {
            possible = "0" + Math.floor(((ShiftEnd - ShiftStart) % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            hoursLeft = possible.slice(-2);
            delete possible;
            return true;
        }
    } else if (stopTime === 0) {
        possible = "0" + Math.floor(((now - startTime) % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        hoursDone = possible.slice(-2);
        delete possible;
    } else if (stopTime !== 0) {
        possible = "0" + Math.floor(((stopTime - startTime) % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        hoursDone = possible.slice(-2);
        delete possible;
    }
    if (ShiftEnd === 0) {
        hoursLeft = "00";
    } else {
        possible = "0" + Math.floor(((ShiftEnd - now) % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        hoursLeft = possible.slice(-2);
        delete possible;
    }
}

function ResizeSquares() {
    var Width = (($(window).width() - 62) / 2);
    var WidthBig = (($(window).width() - 75) / 3);
    if ($(window).width() <= 415) {
        $(".square_container").css('height', Width);
        $(".square_container").css('width', Width);
        $(".rectangle_container").css('height', Width);
        $(".rectangle_container").css('width', (Width * 2) + 5);
    } else if ($(window).width() > 590 && $(window).width() <= 1057) {
        $(".square_container").css('height', WidthBig);
        $(".square_container").css('width', WidthBig);
        $(".rectangle_container").css('height', WidthBig);
        $(".rectangle_container").css('width', (WidthBig * 2) + 5);
    } else if ($(window).width() > 415 && $(window).width() <= 590) {
        $(".square_container").css('height', 175);
        $(".square_container").css('width', 175);
        $(".rectangle_container").css('height', 175);
        $(".rectangle_container").css('width', 355);
    } else {
        $(".square_container").css("height", 325);
        $(".square_container").css("width", 325);
        $(".rectangle_container").css("height", 325);
        $(".rectangle_container").css("width", 655);
    }
}

function initialiseDash() {
    $(".chartNumber").html(hoursDone);
    $(".holidayNumber").html(holidaysLeft);
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
    head.load("javascript/chartBundle.js", function() {
        window.randomScalingFactor = function() {
            return (Math.random() > 0.5 ? 1.0 : -1.0) * Math.round(Math.random() * 100);

        };
        var ctx = document.getElementById("chart-area").getContext("2d");
        window.myDoughnut = new Chart(ctx, config);
        var colorNames = Object.keys(window.chartColors);
    });

    $(".dashboard_containers.linkable").on("mouseup", function(event) {
        if (event.which == 2) {
            window.open($(this).attr("href"));
        } else if (event.which == 1)
            window.location.href = $(this).attr("href");
    });
}
///////NON FUNCTIONS//////
$("document").ready(function() {
    ResizeSquares();
    $.post("webpages/getInfoDashboard.php", function(data) {
        console.log(data);
        shifts = jQuery.parseJSON(data);
        startTime = shifts['startTime'];
        ShiftEnd = shifts['ShiftEnd'];
        stopTime = shifts['stopTime'];
        ShiftStart = shifts['ShiftStart'];
        holidaysLeft = shifts['holidaysLeft'];
        calcHours();
        initialiseDash();
    });

});

$(window).resize(ResizeSquares);
dashboardInterval = setInterval(function() {
    calcHours();
    $(".chartNumber").html(hoursDone);
}, 1800000); //every hour