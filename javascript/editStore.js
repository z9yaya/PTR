formPara = window.location.search.substr(1) == 'q=1';
if (!formPara) {
//Changing the document title//
document.title += ': ' + TransfstoreID;
formattedID = parseInt(TransfstoreID);
} else {
    document.title = 'PTR - Create Store';
}

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
    $.post("LoadStoreDetails.php", {
        STOREID: ID
    }, function (postData) {
        userInfo = jQuery.parseJSON(postData);
        $("#popupid").val(userInfo['MANAGER']);
        if (userInfo['MANAGER'] === null) {
            userInfo['MANAGER'] = 'N/A';
        }
        $("#managerIDcard").html(userInfo['MANAGER']);
        $("#empIDinput").val(ID);
        for (var key in userInfo) {
            if (userInfo[key]) {
                $("#" + key.toLowerCase()).val(userInfo[key]);
                $(".label[for=" + key.toLowerCase() + "]").addClass("notEmpty");
            }
        }
        setTimeout(function(){
            $('#loadingPageOverlay').removeClass('PageLoadingAdd');
        },500);
        
    });
}
//Function to restore inputs to initial state
function ResetPage()
{   
    $(".newEmpForm input, select").attr("disabled",true);
    ChangeMenu('normal');
    $("#managerIDcard").html($("#manager").val());
    $("#popupid").val($("#manager").val());
    $(".newEmpForm .Radiocontainers, .combinedRadiocontainers").addClass("noClick");
    $(".newEmpForm").addClass("view");
    $(".newEmpForm").removeClass("edit");
    $(".submit.button").attr('class', "submit button displayNone");
    $(".submit.button").attr('value', "Save");
    $(".storeEmps").removeClass("displayNone");
    $(".fullPageOverlay").removeClass("show");
    $(".newEmpForm").removeClass("noClick");
    $(".newEmpForm").submit(postChanges);
    window.onbeforeunload = null;
}

function ChangeMenu(Type = 'cancel')
{
    if (Type == 'normal')
        {
            $(".Cancel").off();
            $(".Cancel").addClass("Menu3dots");
            $(".Cancel").removeClass("Cancel");
            $(".menu3dots").attr("title", '');
            $(".smallOptionsCont").removeClass("expand");
            $(".Menu3dots").off();
            $(".Menu3dots").on("click", setMenu3dotsEvent);
        }
    else if(Type == 'cancel')
        {
            $(".Menu3dots").off();
            $(".Menu3dots").removeClass("displayNone");
            $(".Menu3dots").addClass("Cancel");
            $(".Menu3dots").attr("title", 'Cancel');
            $(".Menu3dots").removeClass("Menu3dots");
            $(".smallOptionsCont").removeClass("expand");
            $(".Cancel").on("click", refreshPage);
        }
}
function refreshPage(event) {
    $(".smallOptionsCont").addClass("displayNone");
    $(".smallOptionsCont").removeClass("expand");
    location.reload();
    $(".smallOptionsCont").removeClass("expand");
    return false;
}

function setMenu3dotsEvent(event)
{
            $(".smallOptionsCont").removeClass("displayNone");
            setTimeout(function () {
                $(".smallOptionsCont").addClass("expand");
                setTimeout(function () {
                    $(".Menu3dots").addClass("displayNone");
                    $(".smallOptions").addClass("expand");
                }, 100);
            }, 1);
    return false;
}

function TerminateUser(ID)
{
    window.onbeforeunload = function () {
        return true;
    };
    hidePopUp('','hide');
    setTimeout(function(){showPopUp(question = "YOU WILL NEED TO ASSIGN A NEW STORE TO THE EMPLOYEES LINKED TO THIS STORE, Continue?", loadingText = "Closing store, please wait...",callback = function(){
        $.post("terminateStore.php", {ID: ID}, function (data) {
        terminate = jQuery.parseJSON(data);
            if (terminate === 'success'){
                hidePopUp('Store closed, this window will close in 10 seconds');
                window.opener.location.reload(true);
                window.onbeforeunload = null;
                setTimeout(function(){
                    window.close();
                },10000);
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
            //event.preventDefault(event);
            window.onbeforeunload = null;
            hidePopUp(doneText = '', type='hide');
            return false;
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

function getManagers() {
    if ($("#manager")[0].nodeName === "SELECT") {
        $.post("getManagers.php", function (postData) {
            managers = jQuery.parseJSON(postData);
            selectMan = '<option value="">Please select an option</option>';
            for (var key in managers['EMPID']) {
                if (managers['F_NAME'][key] === null) {
                    managers['F_NAME'][key] = 'N/A';
                    managers['L_NAME'][key] = '';
                }
                selectMan += "<option value='"+managers['EMPID'][key]+"'>"+managers['EMPID'][key]+" | "+managers['EMAIL'][key]+" | "+managers['F_NAME'][key]+" "+managers['L_NAME'][key]+"</option>";
            }
            $("#manager").html(selectMan);
        });
    }

}

function getEmployees(ID) {
    $.post("getEmployees.php", {
        id: ID
    }, function (postData) {
        employees = jQuery.parseJSON(postData);
        selectMan = '';
        if (employees == 'No employees') {
            $(".mainTable").addClass("noEmps");
            selectMan = employees;
            
        } else {
            for (var key in employees['EMPID']) {
             if (employees['F_NAME'][key] === null) {
                employees['F_NAME'][key] = 'N/A';
                employees['L_NAME'][key] = '';
            }
             selectMan += "<tr class='tableRow'><td class='tableCell'><a class='tableLinkList' href=#>"+employees['EMPID'][key]+"<form class='displayNone' action='editEmp.php' method='post'><input type='hidden' name='feeid' value='"+employees['EMPID'][key]+"'></form></a></td><td class='tableCell'>"+employees['F_NAME'][key]+" "+employees['L_NAME'][key]+"</td>";
         }
        }
        $(".mainTable tbody").html(selectMan);
            $(".tableLinkList").on("click",function(event){
        //event.preventDefault();
        if (typeof editPopUp != 'undefined')
            {
                if(!editPopUp.closed)
                {
                    editPopUp.close();
                }
            }
        if (typeof editPopUp == 'undefined' || (editPopUp.closed))
            {
                form = $(this).find("form");
                form.attr('target', 'newwindow');
                editPopUp = window.open("","newwindow", "width=400,height=825,left="+width+",top="+height);
                form.submit();
            }
                return false;
    });
         
    })
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

function postChanges(event) {
        window.onbeforeunload = null;
        window.onbeforeunload = function () {return true;};
        console.log("submit");
        $(".fullPageOverlay").addClass("show");
        event.preventDefault();
        $(".submit.button").addClass("displayNone");
        $(".submit.wait").removeClass("displayNone");
        $form = $(this);
        $(".show").addClass("show");
        $(".invalid").removeClass("invalid");
        $("input").blur();
        var $inputs = $form.find("input, select, button, textarea");
        $values = 0;
        if (!formPara) {
            $("input[name='number:stores:id']").val(formattedID);
        }
        var $inputs = $(".input");
        for (var i = 0; i < $inputs.length; i++) {
            if ($inputs[i].value != '' && !$inputs[i].disabled) {
                $values++;
            }
        }
        if ($values != 0) {
            $.post("createStoreFunc.php", $('.newEmpForm').serialize(), function (data) {
                check = data;
                if (formPara) {
                    data = jQuery.parseJSON(data);
                    check = data[0];
                }
               if (check != "success"){
                   var errors = data
                   if (!formPara) {
                    errors = jQuery.parseJSON(data);
                    }
                    
                    if (Array.isArray(errors)) {
                        for (i = 0; i < errors.length; i++) {
                            if (errors[i] == "street") {
                                $("#" + errors[i]).addClass("invalid");
                                $(".error.empstart").html("Invalid street");
                                $(".error.empstart").addClass("show");
                            }
                            if (errors[i] == "suburb") {
                                $("#" + errors[i]).addClass("invalid");
                                $(".error." + errors[i]).html("Invalid suburb");
                                $(".error." + errors[i]).addClass("show");
                            }
                            if (errors[i] == "postcode") {
                                $("#" + errors[i]).addClass("invalid");
                                $(".error." + errors[i]).html("Numbers only");
                                $(".error." + errors[i]).addClass("show");
                            }
                            if (errors[i] == "state") {
                                $("#" + errors[i]).addClass("invalid");
                                $(".error.email").html("Invalid");
                                $(".error.email").addClass("show");
                            }
                            if (errors[i] == "phone") {
                                $("#" + errors[i]).addClass("invalid");
                                $(".error." + errors[i]).html("10 numbers only");
                                $(".error." + errors[i]).addClass("show");
                            }
                            if (errors[i] == "manager") {
                                $("#" + errors[i]).addClass("invalid");
                                $(".error." + errors[i]).html("Invalid");
                                $(".error." + errors[i]).addClass("show");
                            }
                        }
                    } else {
                        if (errors == "street") {
                            $("#" + errors).addClass("invalid");
                            $(".error.empstart").html("Invalid start date");
                            $(".error.empstart").addClass("show");
                        }
                        if (errors == "suburb") {
                            $("#" + errors).addClass("invalid");
                            $(".error." + errors).html("Invalid title");
                            $(".error." + errors).addClass("show");
                        }
                        if (errors == "postcode") {
                            $("#" + errors).addClass("invalid");
                            $(".error." + errors).html("Numbers only");
                            $(".error." + errors).addClass("show");
                        }
                        if (errors == "phone") {
                            $("#" + errors).addClass("invalid");
                            $(".error.email").html("Invalid");
                            $(".error.email").addClass("show");
                        }
                        if (errors == "manager") {
                            $("#" + errors).addClass("invalid");
                            $(".error.email").html("Invalid");
                            $(".error.email").addClass("show");
                        }
                    }
                $(".fullPageOverlay").removeClass("show");//Block user input
                $(".submit.wait").addClass("displayNone");
                $(".submit.button").removeClass("displayNone");
                $(".newEmpForm").removeClass("noClick");
                } else {
                    $(".submit.button").addClass("blue");
                    $(".submit.button").removeClass("displayNone");
                    $(".submit.wait").addClass("displayNone");
                    $(".newEmpForm").unbind();
                    $(".newEmpForm .input").attr("disabled", true);
                    $(".newEmpForm").addClass("noClick");
                    if (window.opener !== null) {
                        window.opener.location.reload(true);
                    }
                    $(".submit.button").val("");
                    setTimeout(function(){
                        $(".submit.button").addClass("tick");
                    },100)
                    setTimeout(function(){
                        $(".submit.button").addClass("Animate");
                        setTimeout(function(){
                            if (formPara) {
                                window.onbeforeunload = null;
                                post("editStore.php", {esid: data[1]})
                            } else {
                                window.onbeforeunload = null;
                                ResetPage();
                            }
                        },1050);
                    },3000
                    );
                }

                $(".input").focus(function () {
                    $input = $(this);
                    $input.siblings(".errorContainer").children(".error").removeClass("show");
                    $input.removeClass("invalid");
                    $(".saved").removeClass("show");
                });
            });
        }
    }
//Task to execute when the document is ready//
$(document).ready(function () {
    //Removing CSS animation trigger hack//
    $(".preload").removeClass("preload");
    //Calculating position of new window for manager popup//
    width = (screen.width/2) - 200;
    height = (screen.height/3) - 250;
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
     $(".Menu3dots").on("click", setMenu3dotsEvent);
    //Configuring actions for "Menu" option click//
    $(".smallOptionsLinks").on("click", function (event) {
        //event.preventDefault(event);
        if ($(this).attr('clickValue') == 'edit') {
            $(".newEmpForm input, select").attr("disabled", false);
            $(".newEmpForm").removeClass("view");
            $(".newEmpForm").addClass("edit");
            $("#SubmitForm").removeClass("displayNone");
            $(".storeEmps").addClass("displayNone");
            ChangeMenu();
            window.onbeforeunload = function () {
                return true;
            };
        } else if ($(this).attr('clickValue') == 'terminate') {
            showPopUp(question = "Are you sure you want to CLOSE this store?", loadingText = "", callback = function(){TerminateUser(formattedID);});
        }
        $(".smallOptions").removeClass("expand");
        $(".smallOptionsCont").removeClass("expand");
        setTimeout(function () {
            $(".Menu3dots").removeClass("displayNone");
        }, 150);
        return false;
    });
    //Loading existing information//
    getManagers();
    if (!formPara) {
    getEmployees(formattedID);
    LoadDetails(formattedID);
     $("#empID").attr('value', TransfstoreID);} else {
         $(".newEmpForm input, select").attr("disabled", false);
         $("#SubmitForm").removeClass("displayNone");
         $('#loadingPageOverlay').removeClass('PageLoadingAdd');
         $("input[name='form:submit:type']").remove();
         $("input[name='number:stores:id']").remove();
         window.onbeforeunload = function () {
            return true;
        };
     }
        //Removing menu if the user has been terminated
   
    //Changing the label for the date field//
    $(".text").on("keyup change", function () {
        if (this.value == '' && this.getAttribute("type") != "date") {
            $(".label[for='" + $(this).attr("id") + "']").removeClass("notEmpty");
        } else
            $(".label[for='" + $(this).attr("id") + "']").addClass("notEmpty");

    });
    //Form edit handling (from CreateNewEmp.js)//
    $(".TableLink").on("click",function(event){
        //event.preventDefault();
        console.log($("#popupid").val());
        if ($("#popupid").val() === "") {
            return false;
        }
        if (typeof editPopUp != 'undefined')
            {
                if(!editPopUp.closed)
                {
                    editPopUp.close();
                }
            }
        if (typeof editPopUp == 'undefined' || (editPopUp.closed))
            {
        form = $(this).find("form");
        form.attr('target', 'newwindow');
        editPopUp = window.open("","newwindow", "width=400,height=825,left="+width+",top="+height);
        form.submit();
            }
        return false;
    });
    oriQuestion = $("#timerStopPageOverlay").html(); //PART OF POPUP QUESTION, used to store original form
    
});

$(document).ready(function () {
    $(".newEmpForm").submit(postChanges);
});
//parseInt($(this).html().slice(1));