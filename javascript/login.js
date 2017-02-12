$("form").submit(function(event){
    event.preventDefault();
    $form = $(this);
    $("input").blur();
    var $inputs = $form.find("input, select, button, textarea");    
    $(".show").removeClass("show");
    $.post("sign.php",$('form').serialize(),function(data){
        console.log(data);
        if(data == "username" )
            {
                $("#username").addClass("invalid");
                $(".error."+data).html("Invalid email");
                $(".error."+data).addClass("show");
                $(".password").addClass("invalid");
                $(".error.password").html("Invalid password");
                $(".error.password").addClass("show");
                
            }
         if(data == "exist")
            {
                $("#username").addClass("invalid");
                $(".error.email").html("Email address already in use");
                $(".error.email").addClass("show");
            }
        if (data == "password")
            {
                $(".input.password").addClass("invalid");
                $(".error.password").html("Invalid password");
                $(".error.password").addClass("show");
            }
        if(data == "ID")
            {
                $("#empID").addClass("invalid");
                $(".error."+data).html("Invalid ID");
                $(".error."+data).addClass("show");
            }
        if(data == "email")
            {
                $("#username").addClass("invalid");
                $(".error."+data).html("Invalid email address");
                $(".error."+data).addClass("show");
            }
       if (data == "success"){
            window.location.replace("account.php");
        }
       $(".input").focus(function(){
            $input = $(this);
            $input.siblings(".errorContainer").children(".error").removeClass("show");
            $input.removeClass("invalid");
                 }) 
    });
});

