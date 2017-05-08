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
});

$("#email").ready(function(){
    $.post("loadDetails.php",function(data)
           {
        console.log(data);
        var userInfo = jQuery.parseJSON(data);
        console.log(userInfo);
        $("#email").val(userInfo["EMAIL"]);
        $("#empID").val(userInfo["EMPID"]);
    });
});
$(document).ready(function(){
$("form").submit(function(event){
    event.preventDefault();
    $form = $(this);
    $("input").blur();
    var $inputs = $(".input");    
    $(".show").removeClass("show");
    $(".invalid").removeClass("invalid");
    $.post("updateDetails.php",$('form').serialize(),function(data){
        console.log(data);
        if (data != "successsuccess"){
            var errors= jQuery.parseJSON(data);
            console.log(errors);
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
                            $(".error."+errors[i]).html("Only numbers allowed");
                            $(".error."+errors[i]).addClass("show");      
                        }
                    }
                }
        }
       else{
           $.post("updateSession.php",function(){
                window.location.replace("../index.php#/dashboard");
            })
       }
       $(".input").focus(function(){
            $input = $(this);
            $input.siblings(".errorContainer").children(".error").removeClass("show");
            $input.removeClass("invalid");
                 }) 
    });
});});

