$(document).ready(function(){
    $("form").removeClass("preload");
    $(".text").on("keyup change",function()
                {
                if (this.value == '')
                    {
                $(".label[for='"+$(this).attr("id")+"']").removeClass("notEmpty");
                    }
                else
                $(".label[for='"+$(this).attr("id")+"']").addClass("notEmpty");
                
                })
//    $(".Inputclear").on("click",function()
//    {
//        console.log("stuff");
//        $.post("webpages/clearDetail_logged.php",{column: this.getAttribute("clearfor")},function(data){
//            console.log(data);
//                    if (data != "successsuccess" && data != "success"){
//        }
//        else{
//            console.log("success");
//        }
//    })
//})
});

$("#email").ready(function(){
    $.post("webpages/loadDetails_logged.php",function(data)
           {
        userInfo = jQuery.parseJSON(data)
        console.log(userInfo);
        $("#empID").val(userInfo["EMPID"]);
        var keys = Object.keys(userInfo);
        for (var key in userInfo) {
            if (userInfo[key])
                {
                    $("#"+key.toLowerCase()).val(userInfo[key]);
                    $(".label[for="+key.toLowerCase()+"]").addClass("notEmpty");
                }
            
        }
    });
})
$(document).ready(function(){
$("form").submit(function(event){
        event.preventDefault();
        $(".emptyForm").removeClass("invalid");
        $(".emptyForm").addClass("wait");
        $(".emptyForm").html("Please wait...");
        $(".emptyForm").addClass("show");
        $values = 0;
        $form = $(this);
        $("input").blur();
        var $inputs = $(".input");
        for (var i = 0; i < $inputs.length; i++)
        {
            if ($inputs[i].value != '' && !$inputs[i].disabled )
            {
                $values++;
            }
        };
        if ($values != 0)
            {
                $(".show").removeClass("show");
                $.post("webpages/updateDetails.php",$('form').serialize(),function(data){
                    if (data != "successsuccess" && data != "success"){
                        console.log(data);
                        var errors= jQuery.parseJSON(data);
                        if (errors)
                            {
                            for (i = 0; i < errors.length;i++)
                                {
                                    if(errors[i] == "phone" )
                                    {
                                        $("#"+errors[i]).addClass("invalid");
                                        $(".error."+errors[i]).html("Phone number invalid, state code required, only numbers allowed");
                                        $(".error."+errors[i]).addClass("show");                
                                    }
                                    if(errors[i] == "postcode")
                                    {
                                        $("#"+errors[i]).addClass("invalid");
                                        $(".error."+errors[i]).html("Invalid");
                                        $(".error."+errors[i]).addClass("show");                
                                    }
                                    if (errors[i] == "tfn")
                                    {
                                        $("#"+errors[i]).addClass("invalid");
                                        $(".error."+errors[i]).html("Numbers only");
                                        $(".error."+errors[i]).addClass("show");      
                                    }
                                    if(errors[i] == "bsb")
                                    {
                                        $("#"+errors[i]).addClass("invalid");
                                        $(".error."+errors[i]).html("Invalid");
                                        $(".error."+errors[i]).addClass("show");      
                                    }
                                    if(errors[i] == "accnumber")
                                    {
                                        $("#"+errors[i]).addClass("invalid");
                                        $(".error."+errors[i]).html("Numbers only");
                                        $(".error."+errors[i]).addClass("show");      
                                    }
                                    if(errors[i] == "dob")
                                    {
                                        $("#"+errors[i]).addClass("invalid");
                                        $(".error."+errors[i]).html("Invalid date");
                                        $(".error."+errors[i]).addClass("show");      
                                    }
                                }
                            }
                    }
                   else{
                        $(".emptyForm").removeClass("invalid");
                        $(".emptyForm").removeClass("wait");
                        $(".emptyForm").addClass("saved");
                        $(".emptyForm").html("Information updated");
                        $(".emptyForm").addClass("show");
                    }

           $(".input").focus(function(){
                $input = $(this);
                $input.siblings(".errorContainer").children(".error").removeClass("show");
                $input.removeClass("invalid");
                     });
        });
            }
    else
    {
        $(".emptyForm").removeClass("wait");
        $(".emptyForm").removeClass("saved");
        $(".emptyForm").addClass("invalid");
        $(".emptyForm").html("You cannot submit an empty form");
        $(".emptyForm").addClass("show");
    }
    });
});

