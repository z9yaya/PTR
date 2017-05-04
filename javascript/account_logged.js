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
                
                });
    $(".Inputclear").on("click",function()
    {
        var inputID = this.getAttribute("inputID");
        if ($("#"+inputID).val() != '' && $("#"+inputID).val() != null)
            {
            $(".error."+inputID).removeClass("invalid saved"); 
            $(".error."+inputID).addClass("wait");
            $(".error."+inputID).html("Updating, please wait.");
            $(".error."+inputID).addClass("show");  
            $.post("webpages/clearDetail_logged.php",{column: this.getAttribute("clearfor")},function(data)
            {
                console.log(data);
                if (data === "0")
                    {
                        $(".error."+inputID).removeClass("invalid wait show");
                        $("#"+inputID).val('');
                        $(".label[for="+inputID+"]").removeClass('notEmpty');
                    }
                else if (data != "successsuccess" && data != "success")
                {
                    $(".error."+inputID).removeClass("wait saved"); 
                    $(".error."+inputID).addClass("invalid");
                    $(".error."+inputID).html("Error, please try again.");
                    $(".error."+inputID).addClass("show");    
                }
                else
                {
                    $("#"+inputID).val('');
                    $(".label[for="+inputID+"]").removeClass('notEmpty');
                    $(".error."+inputID).removeClass("invalid wait");
                    $(".error."+inputID).addClass("saved");
                    $(".error."+inputID).html("Information Updated");
                    $(".error."+inputID).addClass("show");
                    
                }
    })
            }

})
});

$("#email").ready(function(){
    $.post("webpages/loadDetails_logged.php",function(data)
           {
        userInfo = jQuery.parseJSON(data);
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
});
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
        }
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
                $(".saved").removeClass("show");
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

