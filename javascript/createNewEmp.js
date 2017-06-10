function LoadJqueryUI()
{
    $(".input[type=date]").attr("type","text");
    $(".label.start").removeClass("notEmpty");
              $( function() {  
				$("#start").datepicker({
                    changeYear: true,
                    minDate: '+1D',
                    dateFormat: 'dd/mm/yy',
                    beforeShow: function(input, inst) 
                    {
                        $(this).addClass("Focused");
                        $(this).attr("disabled", true);
                    },
                    onClose: function(dateText, inst) 
                    { 
                        $(this).attr("disabled", false);
                        $(this).removeClass("Focused");
                    },
                    onSelect: function(dateText, inst) {
                        $(".label[for='"+$(this).attr('id')+"']").addClass("notEmpty");
                    }
                   });
			  } );
}

function storeSelect()
{
    store = $("#store");
    typeValue = $(".hiddenRadio.accountType:checked").val();
    if (typeValue === 'MANAGER')
        {
            if ($("option:selected", store).attr("manager") || store.val() == '')
            {
                $("#StoreSelectCont").addClass("full");
                $("#StoreCheckCont").addClass("width0zero");
                $("#storeManager").attr("disabled", true);
                storeManager.checked = false;
            } else {
                $("#StoreSelectCont").removeClass("full");
                $("#StoreCheckCont").removeClass("width0zero");
                $("#storeManager").attr("disabled", false);
                storeManager.checked = false;
            }
        } else {
            $("#StoreSelectCont").addClass("full");
            $("#StoreCheckCont").addClass("width0zero");
            $("#storeManager").attr("disabled", true);
            storeManager.checked = false;
        }
}

function post(path, parameters) {
    var form = $('<form></form>');

    form.attr("method", "post");
    form.attr("action", path);

    $.each(parameters, function(key, value) {
        var field = $('<input></input>');
        field.attr("type", "hidden");
        field.attr("name", key);
        field.attr("value", value);
        form.append(field);
    });

    // The form needs to be a part of the document in
    // order for us to be able to submit it.
    $(document.body).append(form);
    form.submit();
}

$(document).ready(function(){
        $("input").on("click",function()
                      {
            window.onbeforeunload = function() {return true;};
        });
        $(".text:not('select')").on("keyup change",function()
                {
                if (this.value == '' && this.getAttribute("type") != "date")
                    {
                $(".label[for='"+$(this).attr("id")+"']").removeClass("notEmpty");
                    }
                else
                $(".label[for='"+$(this).attr("id")+"']").addClass("notEmpty");
                
                });
    $('.hiddenRadio.accountType').on('change',function()
    {
        storeSelect();
        if(this.checked) 
        {
            $(".account .checked").removeClass("checked");
            $(this).parent().addClass("checked");
        }});
    $('.hiddenRadio.employmentType').on('change',function()
    {
        if(this.checked) 
        {
            $(".Type .checked").removeClass("checked");
            $(this).parent().addClass("checked");
            if (this.getAttribute("value") == "FULL-TIME")
                {
                    $(".containers.float").addClass("Show");
                    $('.hiddenRadio.salary').attr("disabled", false);
                    if ($('.hiddenRadio.salary:checked').attr("value") == 'SALARY')
                    {
                        $("label.rate").html("Weekly rate");
                    }
                }
            else if($(".containers.float").hasClass("Show"))
            {
                $(".containers.float").removeClass("Show"); 
                $('.hiddenRadio.salary').attr("disabled", true);
                $("label.rate").html("Hourly rate");
            }
        }});
    $('.hiddenRadio.salary').on('change',function()
    {
        if(this.checked) 
        {
            $(".Salary.checked").removeClass("checked");
            $(this).parent().addClass("checked");
            if (this.getAttribute("value") == 'SALARY')
                {
                    $("label.rate").html("Weekly rate");
                }
            else
                {
                    $("label.rate").html("Hourly rate");
                }
            
        }});
    $("#store").on('change',function()
    {
        storeSelect()
    })
   $(".preload").removeClass("preload"); 
});

$(document).ready(function(){
$(".newEmpForm").submit(function(event){
        event.preventDefault();
        $(".newEmpForm input").css("pointer-events", "none");
        $(".submit.button").addClass("displayNone");
        $(".submit.wait").removeClass("displayNone");
        $form = $(this);
        $(".show").removeClass("show");
        $(".invalid").removeClass("invalid");
        $("input").blur();
        var $inputs = $form.find("input, select, button, textarea");    
        $values = 0;
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
                $.post("createEmpFunc.php",$('.newEmpForm').serialize(),function(data){
                    data = jQuery.parseJSON(data);
                    if (data[0] != "success"){
                        var errors = data;
                        if (Array.isArray(errors))
                            {
                            for (i = 0; i < errors.length;i++)
                                {
                                    if(errors[i] == "start" )
                                    {
                                        $("#"+errors[i]).addClass("invalid");
                                        $(".error."+errors[i]).html("Invalid start date");
                                        $(".error."+errors[i]).addClass("show");                
                                    }
                                    if(errors[i] == "position")
                                    {
                                        $("#"+errors[i]).addClass("invalid");
                                        $(".error."+errors[i]).html("Invalid title");
                                        $(".error."+errors[i]).addClass("show");                
                                    }
                                    if (errors[i] == "rate")
                                    {
                                        $("#"+errors[i]).addClass("invalid");
                                        $(".error."+errors[i]).html("Numbers only");
                                        $(".error."+errors[i]).addClass("show");      
                                    }
                                    if(errors[i] == "username")
                                    {
                                        $("#"+errors[i]).addClass("invalid");
                                        $(".error."+errors[i]).html("Invalid");
                                        $(".error."+errors[i]).addClass("show");      
                                    }
                                    if(errors[i] == "username2")
                                    {
                                        $("#"+errors[i]).addClass("invalid");
                                        $(".error."+errors[i]).html("Emails does not match");
                                        $(".error."+errors[i]).addClass("show");      
                                    }
                                }
                            }
                        else if(errors == 'exist')
                            {
                                $("#username").addClass("invalid");
                                $(".error.username").html("This user already exists");
                                $(".error.username").addClass("show");      
                            }
                        else
                        {
                            if(errors == "start" )
                            {
                                $("#"+errors).addClass("invalid");
                                $(".error."+errors).html("Invalid start date");
                                $(".error."+errors).addClass("show");                
                            }
                            if(errors == "position")
                            {
                                $("#"+errors).addClass("invalid");
                                $(".error."+errors).html("Invalid title");
                                $(".error."+errors).addClass("show");                
                            }
                            if (errors == "rate")
                            {
                                $("#"+errors).addClass("invalid");
                                $(".error."+errors).html("Numbers only");
                                $(".error."+errors).addClass("show");      
                            }
                            if(errors == "username")
                            {
                                $("#"+errors).addClass("invalid");
                                $(".error."+errors).html("Invalid");
                                $(".error."+errors).addClass("show");      
                            }
                            if(errors == "username2")
                            {
                                $("#"+errors).addClass("invalid");
                                $(".error."+errors).html("Emails does not match");
                                $(".error."+errors).addClass("show");      
                            }
                        }
                        
                        $(".submit.button").removeClass("displayNone");
                        $(".submit.wait").addClass("displayNone");
                        $(".newEmpForm input").css("pointer-events", "all");
                    }
                   else{
                        $(".newEmpForm").unbind();
                        $(".submit.button").removeClass("displayNone");
                        $(".submit.button").addClass("blue");
                        $(".submit.wait").addClass("displayNone");
                        $(".newEmpForm").unbind();
                        $(".newEmpForm input").attr("disabled", true);
                        $(".newEmpForm .Radiocontainers, .combinedRadiocontainers").addClass("noClick");
                       if (window.opener) {
                        window.opener.location.reload(true); }
                        $(".submit.button").val("");
                        setTimeout(function(){
                            $(".submit.button").addClass("tick");
                        },100)
                        setTimeout(function(){
                            $(".submit.button").addClass("Animate");
                            setTimeout(function(){
                                post("editEmp.php", {feeid: data[1]})
                            },1050);
                        },3000
                        );
                       //$(".submit.button").val("Close window");
                       
//                       $(".newEmpForm").submit(function(){
//                           window.close();
//                       });
                       //$(".submit.button").addClass("success");
                       window.onbeforeunload = null;
                    }

           $(".input").focus(function(){
                $input = $(this);
                $input.siblings(".errorContainer").children(".error").removeClass("show");
                $input.removeClass("invalid");
                $(".saved").removeClass("show");
                     });
        });
            }
    });
});
