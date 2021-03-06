var today = new Date();
var dd = today.getDate();
var mm = today.getMonth() + 1; //January is 0!
var yyyy = today.getFullYear();
if (dd < 10) {
    dd = '0' + dd
}
if (mm < 10) {
    mm = '0' + mm
}

function CheckOngoing() {
    $.post("webpages/timer/checkShift.php", function(data) {
        if (data === "login") {
            window.location.replace("webpages/start.php");
        } else if (data != '') {
            //console.log(data);
            shiftInfo = jQuery.parseJSON(data);
            console.log(shiftInfo);
           Sdat =  ("0" + new Date(shiftInfo["BEGIN"]).getHours()).slice(-2)+":"+("0" + new Date(shiftInfo["BEGIN"]).getMinutes()).slice(-2);
            Edat = ("0" + new Date(shiftInfo["END"]).getHours()).slice(-2)+":"+("0" + new Date(shiftInfo["END"]).getMinutes()).slice(-2);
            $(".dateContainer").html(Sdat+" - "+Edat);
            if (shiftInfo["SHIFTSTART"]) {
                res = shiftInfo["SHIFTSTART"];
                startTimer(res);
                $("#timerButtonClick").addClass("running");
            }
            //console.log(shiftInfo);
        } else {
            return false;
        }

    });
}

function StartTimerOnButton() {
    $.post("webpages/timer/StartShift.php", function(data) {
        if (data == "success") {
            startTimer();
        } else if (data == 'login') {
            window.location.replace("webpages/start.php");
        } else if (data == "started") {
            res = shiftInfo["SHIFTSTART"];
            startTimer(res);
        } else {
            console.log("data");
        }

    });
}

function StopTimerOnButton() {
    $.post("webpages/timer/StopShift.php", function(data) {
        if (data == "success") {
            clearInterval(x);
            $(".questionLabel").html("Done!");
            $('.loadingStopTimer').addClass('SavingDone');
            $('.loadingStopTimer').removeClass('animationCirle');
            setTimeout(function() {
                $("#timerStopPageOverlay > .popUpContainer").removeClass("showPopUp");
                setTimeout(function() {
                    $("#timerStopPageOverlay").removeClass("timerStopQuestion");
                }, 1000);
            }, 3000);
            $("#timerButtonClick").blur();
            $("#timerButtonClick").removeClass("running");
            $("#rosterLink").removeClass("Timeractive");
            $("#timerButtonClick").addClass("ready");
            $("#headerTimer").addClass("timerStopped");
            $(".content").removeClass("timer");
            setTimeout(function() {
                $("#headerTimer").empty();
                $("#headerTimer").attr("id", "headerNoTimer");
            }, 600);
        } else if (data == 'login') {
            window.location.replace("webpages/start.php");
        } else {
            console.log(data);
        }
    });
}
// Set the date we're counting down to
$(document).ready(function() {
    CheckOngoing();
    $(".dateContainer").html(dd + '/' + mm + '/' + yyyy);
    $("#timerButtonClick").on("click", function() {
        if ($(this).hasClass("running")) {
            showPopUp(undefined,undefined,StopTimerOnButton);
        } else {
            if ($("#timeContainer").html() != "00:00:00") {
                $("#timeContainer").addClass("clearAnimation");
                setTimeout(function() {
                    $("#timeContainer").removeClass("clearAnimation");
                }, 1000);
            }
            StartTimerOnButton();
            $("#timerButtonClick").blur();
            $("#timerButtonClick").addClass("running");
            $("#timerButtonClick").removeClass("ready");
        }

    })
});

function startTimer(countDownDate = new Date().getTime()) {
    $("#timeContainer").ready(function() {
        $("#rosterLink").addClass("Timeractive");
        // Update the count down every 1 second
        x = setInterval(function() {

            // Get todays date and time
            var now = new Date().getTime();
            // Find the distance between now an the count down date
            var distance = now - countDownDate;
            // Time calculations for days, hours, minutes and seconds
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Display the result in the element with id="demo"
            $("#timeContainer").html(("0" + hours).slice(-2) + ":" +
                ('0' + minutes).slice(-2) + ":" + ('0' + seconds).slice(-2));
        }, 1000);
    });
}