var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0!
var yyyy = today.getFullYear();
if(dd<10){dd='0'+dd} 
if(mm<10){mm='0'+mm} 
function CheckOngoing()
{
    $.post("webpages/roster/checkShift.php",function(data)
           {
        if (data === "login")
            {
                window.location.replace("webpages/start.php");
            }
        else if (data != '')
            {   
                console.log(data);
                shiftInfo = jQuery.parseJSON(data);
//                if (shiftInfo["SHIFTID"])
//                    {
//                        
//                    }
                    if(shiftInfo["SHIFTSTART"])
                    {
                        res = shiftInfo["SHIFTSTART"];
                        startTimer(res);
                        $("#timerButtonClick").addClass("running");
                    }
                console.log(shiftInfo);            
            }
        else
        {
            return false;    
        }
       
        });
}
// Set the date we're counting down to
$(document).ready(function() {
    CheckOngoing();
    $(".dateContainer").html(dd+'/'+mm+'/'+yyyy);
    $("#timerButtonClick").on("click",function()
        {
            if ($( this ).hasClass("running"))
            {
                clearInterval(x);
                $("#timerButtonClick").blur();
                $("#timerButtonClick").removeClass("running");
                $("#timerButtonClick").addClass("ready");
            }
            else
            {
                if ($("#timeContainer").html() != "00:00:00")
                    {
                        $("#timeContainer").addClass("clearAnimation");
                        setTimeout(function(){
                            $("#timeContainer").removeClass("clearAnimation");
                        },1000);
                    }
                $.post("webpages/roster/StartShift.php",function(data)
                {
                    console.log(data);
                    if (data == "success")
                    {
                        startTimer();
                    }
                    else
                    {
                        res = shiftInfo["SHIFTSTART"];
                        startTimer(res);
                    }
                                        
                });
                $("#timerButtonClick").blur();
                $("#timerButtonClick").addClass("running");
                $("#timerButtonClick").removeClass("ready"); 
            }

        })
});

function startTimer(countDownDate = new Date().getTime())
{
    $("#timeContainer").ready(function(){
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
            $("#timeContainer").html(("0"+hours).slice(-2) + ":"
            + ('0'  + minutes).slice(-2) + ":" + ('0' + seconds).slice(-2));
        }, 1000);
    });
}

