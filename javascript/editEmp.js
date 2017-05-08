if (typeof (Storage) !== "undefined") {
    if (typeof (TransfempID) === 'undefined') {
        TransfempID = localStorage.getItem("LocalEmpID");
    } else {
        console.log(TransfempID);
        localStorage.removeItem("LocalEmpID");
        localStorage.setItem("LocalEmpID", TransfempID);
    }
} else {
    if (typeof (TransfempID) === 'undefined') {
        // WriteOops, something is missing, please close this window and reopen it from the Employees page
    }
}
//Changing the document title//
document.title += ': ' + TransfempID;
formattedID = parseInt(TransfempID.slice(1));
//Function to add the JqueryUI datepicker to the DOM//
function LoadJqueryUI() {
        $(".input[type=date]").attr("type", "text");
        $(".label.start").removeClass("notEmpty");
        $(function () {
            $("#empstart").datepicker({
                changeYear: true,
                minDate: '+1D',
                dateFormat: 'dd/mm/yy',
                beforeShow: function (input, inst) {
                    $(this).addClass("Focused");
                    $(this).attr("disabled", true);
                },
                onClose: function (dateText, inst) {
                    $(this).attr("disabled", false);
                    $(this).removeClass("Focused");
                },
                onSelect: function (dateText, inst) {
                    $(".label[for='" + $(this).attr('id') + "']").addClass("notEmpty");
                }
            });
        });
    }
//Function to load the details of a specific employee//
function LoadDetails(ID) {
    $.post("LoadEmpDetails.php", {
        EMPID: ID
    }, function (postData) {
        console.log(postData);
        userInfo = jQuery.parseJSON(postData);
        var keys = Object.keys(userInfo);
        $("#emailCard").val(userInfo['EMAIL']);
        $("#empIDinput").val(ID);
        if (userInfo['EMPEND'] === null) {
            $("#empend").addClass("active");
            $("#empend").val('Employed');
        }
        for (var key in userInfo) {
            if (userInfo[key]) {
                if (key == 'EMPTYPE' || key == 'ACCTYPE' || key == 'TYPE') {
                    empTypeObject = $(".hiddenRadio[value='" + userInfo[key] + "']");
                    empTypeObject.attr("checked", true);
                    $(empTypeObject).parent().addClass("checked");
                }
                if (key == 'EMPEND')
                    {
                        $("#empend").addClass("inactive");
                        $("#empend").val('Terminated');
                        $("#empendInput").val(userInfo[key]);
                        changeToTerminated();
                        $(".empend").removeClass("widthZero");
                        continue;
                    }
                if (key == 'STORE') {
                    $("#store").val(userInfo[key]);
                   storeSelect();
                }
                else{
                     $("#" + key.toLowerCase()).val(userInfo[key]);
                }
                $(".label[for=" + key.toLowerCase() + "]").addClass("notEmpty");
            }
        }
        if (userInfo['EMPTYPE'] == "FULL-TIME") {
            $(".containers.float").addClass("Show");
            if (userInfo['TYPE'] == "SALARY") {
                $(".label.rate").html("Weekly rate");
            }
        }
        var startMsec = Date.parse(userInfo['EMPSTART']);
        startDate = new Date(startMsec);
        setTimeout(function(){
            $('#loadingPageOverlay').removeClass('PageLoadingAdd');
        },500);
        
    });
}
//Function to restore inputs to initial state
function ResetPage()
{   
    storeValue = $("#store").val();
    $.post("loadStores.php", function(data){
        $("#store").html(data);
        $("#store").val(storeValue);
        storeSelect('dynamic');
    })
    $(".newEmpForm input, select").attr("disabled",true);
    ChangeMenu('normal');
    $(".newEmpForm .Radiocontainers, .combinedRadiocontainers").addClass("noClick");
    $(".newEmpForm").addClass("view");
    $(".newEmpForm").removeClass("edit");
    $(".submit.button").attr('class', "submit button displayNone");
    $(".submit.button").attr('value', "Save");
    $(".fullPageOverlay").removeClass("show");
}

function ChangeMenu(Type = 'cancel')
{
    if (Type == 'normal')
        {
            $(".menu3dots").removeClass("Cancel");
            $(".menu3dots").attr("title", '');
            $(".Menu3dots").off('click').on("click", function(event){
                setMenu3dotsEvent(event)});
        }
    else if(Type == 'cancel')
        {
            $(".menu3dots").addClass("Cancel");
            $(".menu3dots").attr("title", 'Cancel');
            $(".Menu3dots").on("click", function (event) {
                event.preventDefault();
                location.reload();
            });
        }
}

function setMenu3dotsEvent(event)
{
    event.preventDefault();
            $(".smallOptionsCont").removeClass("displayNone");
            setTimeout(function () {
                $(".smallOptionsCont").toggleClass("expand");
                setTimeout(function () {
                    $(".Menu3dots").addClass("displayNone");
                    $(".smallOptions").toggleClass("expand");
                }, 100);
            }, 1);
}
function ResetPassword(ID)
{
    window.onbeforeunload = function () {
        return true;
    };
    $.post("resetPassword.php", {EMPID: ID}, function (data) {
        Password = jQuery.parseJSON(data);
        setTimeout(function(){
            AddMessagePopUp(Password[1], "New password:");
        },1000);    
    });
}

function TerminateUser(ID)
{
    window.onbeforeunload = function () {
        return true;
    };
    hidePopUp('','hide');
    setTimeout(function(){showPopUp(question = "THIS WILL CUT USER ACCESS, IT CANNOT BE UNDONE. Continue?", loadingText = "Terminating user, please wait...",callback = function(){
        $.post("terminateUser.php", {EMPID: ID}, function (data) {
        terminate = jQuery.parseJSON(data);
            if (terminate === 'success'){
                hidePopUp('User terminated');
                window.opener.location.reload(true);
                window.onbeforeunload = null;
                setTimeout(function(){
                    location.reload();
                },3000);
            }
    });
    })},1000);
    
}

function changeToTerminated()
{
    $("body").off("focus click");
    $(".smallMenu").addClass("displayNone");
}

function showPopUp(question = "Are you sure you want to end your shift?", loadingText = "Saving shift, please wait...",callback = null)
{
            $("#timerStopPageOverlay").html(oriQuestion);
            $(".questionLabel").html(question);
            $("#timerStopPageOverlay").addClass("timerStopQuestion");
            setTimeout(function() {
                $(".popUpContainer").addClass("showPopUp");
                $("#timerStopConfirm").on("click", function() {
                    $(".answerContainer").addClass('noClickTouch');
                    setTimeout(function() {
                        $(".answerContainer").addClass("displayNone");
                    }, 1000);
                    $(".answerContainer").addClass("remove");
                    $(".questionLabel").html(loadingText);
                    $('.loadingStopTimer').addClass('animationCircle');
                    if (callback !== null)
                    callback();
                });
                $("#timerStopCancel").on("click", function() {
                    $("#timerStopPageOverlay > .popUpContainer").removeClass("showPopUp");
                    setTimeout(function() {
                        $("#timerStopPageOverlay").removeClass("timerStopQuestion");
                    }, 500);
                });
            }, 1);        
}
/* Function used to add a message in the middle of the window
message: string, text to show in place of the possible answers
messageTitle: string, used to show text in place of question
Class: string, default: 'showMessage', the css class to use for the message*/
function AddMessagePopUp(message, messageTitle, Class = 'showMessage')
{
    if (!$(".popUpContainer").hasClass("showPopUp"))
        {
            $("#loadingStopTimer").removeClass("showMessage");
            $("#timerStopPageOverlay").addClass("timerStopQuestion");
             setTimeout(function() {
                $(".popUpContainer").addClass("showPopUp");
                $('.answerContainer').addClass("displayNone");
                   $('.loadingStopTimer').addClass('animationCircle');
            }, 1);
        }
        $("#loadingStopTimer").addClass(Class);
        $("#loadingStopTimer").html(message);
            setTimeout(function(){
        $(".questionLabel").html(messageTitle);
        $(".popUpContainer .otherLinks").removeClass("displayNone");
        },200);
        $(".popUpContainer .otherLinks").on("click",function(event){
            event.preventDefault(event);
            window.onbeforeunload = null;
            hidePopUp(doneText = '', type='hide');
        })
    
}

/* Function used to hide the popup question window
doneText: string, text to show in place of the question when hiding the window
type: string, default '', 'hide' to use this function to hide the popup box and not display animations*/
function hidePopUp(doneText = "Done!", type = '')
{
    if (type == 'hide')
        {
            $("#timerStopPageOverlay > .popUpContainer").removeClass("showPopUp");
            setTimeout(function() {
                $("#timerStopPageOverlay").removeClass("timerStopQuestion");
            }, 1000);
        }
    else{
        $(".questionLabel").html(doneText);
        $('.loadingStopTimer').addClass('SavingDone');
        $('.loadingStopTimer').removeClass('animationCirle');
        setTimeout(function() {
            $("#timerStopPageOverlay > .popUpContainer").removeClass("showPopUp");
            setTimeout(function() {
                $("#timerStopPageOverlay").removeClass("timerStopQuestion");
            }, 1000);
        }, 3000);
    }

}

function storeSelect(trigger = 'load')
{
    store = $("#store");
    if (trigger === 'load') {
        typeValue = userInfo['ACCTYPE'];
    } else if (trigger === 'dynamic') {
        typeValue = $(".hiddenRadio.accountType:checked").val();
    }
    if (typeValue === 'MANAGER')
        {
            if ($("option:selected", store).attr("manager") || store.val() == '')
            {
                if ($("option:selected", store).attr("manager") == formattedID)
                    {
                        storeManager.checked = true;
                        $("#StoreSelectCont").removeClass("full");
                        $("#StoreCheckCont").removeClass("width0zero");
                        if ($(".newEmpForm").hasClass('edit')) {
                            $("#storeManager").attr("disabled", false);
                        }
                        return true;
                    }
                $("#StoreSelectCont").addClass("full");
                $("#StoreCheckCont").addClass("width0zero");
                $("#storeManager").attr("disabled", true);
                storeManager.checked = false;
            } else {
                $("#StoreSelectCont").removeClass("full");
                $("#StoreCheckCont").removeClass("width0zero");
                if ($(".newEmpForm").hasClass('edit')) {
                    $("#storeManager").attr("disabled", false);
                }
            }
        } else {
            $("#StoreSelectCont").addClass("full");
            $("#StoreCheckCont").addClass("width0zero");
            $("#storeManager").attr("disabled", true);
            storeManager.checked = false;
        }
}

//Task to execute when the document is ready//
$(document).ready(function () {
    //Removing CSS animation trigger hack//
    $(".preload").removeClass("preload");
    //Configuring event handlers for everything that is not the "Menu"//
    $("body").on("focus click", function (event) {
        if (!$(event.target).hasClass("smallOptions") && !$(event.target).hasClass("Menu3dots") && !$(event.target).hasClass("smallOptionsCont")) {
            $(".smallOptions").removeClass("expand");
            $(".smallOptionsCont").removeClass("expand");
            setTimeout(function () {
                $(".Menu3dots").removeClass("displayNone");
                $(".smallOptionsCont").addClass("displayNone");
            }, 150);
        }

    });
    //Configuring event handlers for the "Menu"//
    $(".Menu3dots").on("click", function (event) {
        event.preventDefault(event);
        $(".smallOptionsCont").removeClass("displayNone");
        setTimeout(function () {
            $(".smallOptionsCont").toggleClass("expand");
            setTimeout(function () {
                $(".Menu3dots").addClass("displayNone");
                $(".smallOptions").toggleClass("expand");
            }, 100);
        }, 1);
    });
    //Configuring actions for "Menu" option click//
    $(".smallOptionsLinks").on("click", function (event) {
        event.preventDefault(event);
        if ($(this).attr('clickValue') == 'edit') {
            today = new Date();
            $(".newEmpForm input[id != 'empendInput'], select").attr("disabled", false);
            if (startDate <  today)
                {
                    $("#hasStarted").val(true);
                    $("#empstart").attr("disabled", true);
                    $("#empstart").add("novalidate", true);
                    $("#empstart").parent().on("dblclick", function(){
                        AddMessagePopUp("This employee has already started", '', 'showNotice');
                    });
                    
                }
            $(".newEmpForm").removeClass("view");
            $(".newEmpForm").addClass("edit");
            $(".newEmpForm .Radiocontainers.noClick, .combinedRadiocontainers.noClick").removeClass("noClick");
            $("#SubmitForm").removeClass("displayNone");
            ChangeMenu();
            window.onbeforeunload = function () {
                return true;
            };
        } else if ($(this).attr('clickValue') == 'reset') {
            showPopUp(question = "Are you sure you want to reset this user's password?", loadingText = "Resetting password, please wait...", callback = function(){ResetPassword(formattedID);});
        } else if ($(this).attr('clickValue') == 'terminate') {
            showPopUp(question = "Are you sure you want to TERMINATE this user?", loadingText = "", callback = function(){TerminateUser(formattedID);});
        }
        $(".smallOptions").removeClass("expand");
        $(".smallOptionsCont").removeClass("expand");
        setTimeout(function () {
            $(".Menu3dots").removeClass("displayNone");
        }, 150);
    });
    //Loading existing information//
    LoadDetails(formattedID);
        //Removing menu if the user has been terminated
    $("#empID").attr('value', TransfempID);
    //Changing the label for the date field//
    $(".text").on("keyup change", function () {
        if (this.value == '' && this.getAttribute("type") != "date") {
            $(".label[for='" + $(this).attr("id") + "']").removeClass("notEmpty");
        } else
            $(".label[for='" + $(this).attr("id") + "']").addClass("notEmpty");

    });
    //Form edit handling (from CreateNewEmp.js)//
    $('.hiddenRadio.accountType').on('change', function () {
        storeSelect('dynamic');
        if (this.checked) {
            $(".account .checked").removeClass("checked");
            $(this).parent().addClass("checked");
        }
    });
    $('.hiddenRadio.employmentType').on('change', function () {
        if (this.checked) {
            $(".Type .checked").removeClass("checked");
            $(this).parent().addClass("checked");
            if (this.getAttribute("value") == "FULL-TIME") {
                $(".containers.float").addClass("Show");
                $('.hiddenRadio.salary').attr("disabled", false);
                if ($('.hiddenRadio.salary:checked').attr("value") == 'SALARY') {
                    $("label.rate").html("Weekly rate");
                }
            } else if ($(".containers.float").hasClass("Show")) {
                $(".containers.float").removeClass("Show");
                $('.hiddenRadio.salary').attr("disabled", true);
                $("label.rate").html("Hourly rate");
            }
        }
    });
    $('.hiddenRadio.salary').on('change', function () {
        if (this.checked) {
            $(".Salary.checked").removeClass("checked");
            $(this).parent().addClass("checked");
            if (this.getAttribute("value") == 'SALARY') {
                $("label.rate").html("Weekly rate");
            } else {
                $("label.rate").html("Hourly rate");
            }

        }
    });
    $("#store").on('change',function()
    {
        storeSelect('dynamic');
    })
    oriQuestion = $("#timerStopPageOverlay").html(); //PART OF POPUP QUESTION, used to store original form
    
});

$(document).ready(function () {
    $(".newEmpForm").submit(function (event) {
        console.log("submit");
        event.preventDefault();
        $(".submit.button").addClass("displayNone");
        $(".submit.wait").removeClass("displayNone");
        $form = $(this);
        $(".show").removeClass("show");
        $(".invalid").removeClass("invalid");
        $("input").blur();
        var $inputs = $form.find("input, select, button, textarea");
        $values = 0;
        var $inputs = $(".input");
        for (var i = 0; i < $inputs.length; i++) {
            if ($inputs[i].value != '' && !$inputs[i].disabled) {
                $values++;
            }
        }
        if ($values != 0) {
            $.post("createEmpFunc.php", $('.newEmpForm').serialize(), function (data) {
                if (data != "success") {
                    var errors = jQuery.parseJSON(data);
                    if (Array.isArray(errors)) {
                        for (i = 0; i < errors.length; i++) {
                            if (errors[i] == "start") {
                                $("#empstart").addClass("invalid");
                                $(".error.empstart").html("Invalid start date");
                                $(".error.empstart").addClass("show");
                            }
                            if (errors[i] == "position") {
                                $("#" + errors[i]).addClass("invalid");
                                $(".error." + errors[i]).html("Invalid title");
                                $(".error." + errors[i]).addClass("show");
                            }
                            if (errors[i] == "rate") {
                                $("#" + errors[i]).addClass("invalid");
                                $(".error." + errors[i]).html("Numbers only");
                                $(".error." + errors[i]).addClass("show");
                            }
                            if (errors[i] == "username") {
                                $("#email").addClass("invalid");
                                $(".error.email").html("Invalid");
                                $(".error.email").addClass("show");
                            }
                            if (errors[i] == "username2") {
                                $("#" + errors[i]).addClass("invalid");
                                $(".error." + errors[i]).html("Emails does not match");
                                $(".error." + errors[i]).addClass("show");
                            }
                        }
                    } else if (errors == 'exist') {
                        $("#username").addClass("invalid");
                        $(".error.username").html("This user already exists");
                        $(".error.username").addClass("show");
                    } else {
                        if (errors == "start") {
                            $("#empstart").addClass("invalid");
                            $(".error.empstart").html("Invalid start date");
                            $(".error.empstart").addClass("show");
                        }
                        if (errors == "position") {
                            $("#" + errors).addClass("invalid");
                            $(".error." + errors).html("Invalid title");
                            $(".error." + errors).addClass("show");
                        }
                        if (errors == "rate") {
                            $("#" + errors).addClass("invalid");
                            $(".error." + errors).html("Numbers only");
                            $(".error." + errors).addClass("show");
                        }
                        if (errors == "username") {
                            $("#email").addClass("invalid");
                            $(".error.email").html("Invalid");
                            $(".error.email").addClass("show");
                        }
                    }
                } else {
                    $(".submit.button").val("");
                    $(".submit.button").addClass("tick");
                    setTimeout(function(){
                        $(".submit.button").addClass("Animate");
                        setTimeout(function(){
                            ResetPage();
                        },1050);
                    },3000
                    );
                    window.opener.location.reload(true);
                    window.onbeforeunload = null;
                }

                $(".input").focus(function () {
                    $input = $(this);
                    $input.siblings(".errorContainer").children(".error").removeClass("show");
                    $input.removeClass("invalid");
                    $(".saved").removeClass("show");
                });
            });
            $(".fullPageOverlay").addClass("show");//Block user input
            $(".submit.button").removeClass("displayNone");
            $(".submit.wait").addClass("displayNone");
        }
    });
});
//parseInt($(this).html().slice(1));