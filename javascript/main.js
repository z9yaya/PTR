/*This file and the following functions have been written by Yannick Mansuy*/

function showMenu(type = 1) {
    if (type === 1) {
        urlLocation = window.location.href;
        ViewingMenu = true;
        $("#MainSideMenu").addClass("HiddenIcons");
        click_overlay.style.zIndex = "2";
    } else {
        delete urlLocation;
        ViewingMenu = false;
        click_overlay.style.zIndex = "-1";
        $("#MainSideMenu").removeClass("HiddenIcons");
    }
}


function showPopUp(question = "Are you sure you want to end your shift?", loadingText = "Saving shift, please wait...",callback = null)
{
    $(".questionLabel").html(question);
    $("#timerStopPageOverlay").addClass("timerStopQuestion");
setTimeout(function() {
    $(".popUpContainer").addClass("showPopUp");
    $("#timerStopConfirm").on("click", function() {
        $(".answerContainer").addClass('noClickTouch');
        setTimeout(function() {
            $(".answerContainer").addClass("HiddenContent");
        }, 1000);
        $(".answerContainer").addClass("remove");
        $(".questionLabel").html(loadingText);
        $('.loadingStopTimer').addClass('animationCirle');
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

function SelectionIndic($a) {
    $(".menu_selection").removeClass("menu_selection");
    $a.addClass("menu_selection");
}

function ChangeMainBackground(bgcolor = "white") {
    $("#MainContent").css("background-color", bgcolor);
}

function LoadMessagesContent() {
    $("#loadingPageOverlay").addClass("PageLoadingAdd");
    $("document").ready(function() {
        $('#MessageContent').removeClass('HiddenContent');
        $("#MainContent").addClass("HiddenContent");
        if ($("#messagesLink").hasClass("unreadHid"))
            $("#messagesLink").removeClass("unreadHid");
        HideContactsBar(true);
        if ($("#MainSideMenu").hasClass("HiddenIcons")) {
            showMenu(0);
        }
    });
    setTimeout(function() {
        $('#loadingPageOverlay').removeClass('PageLoadingAdd');
    }, 100);
}

function ChangeMainTitle(text, color = '#f2f2f2') {
    $('#mainPageTitle').html(text);
    if (color != "#f2f2f2") {
        $('#mainPageTitle').css("color", "white");
    } else {
        {
            $('#mainPageTitle').css("color", "black");
        }
    }
    $('.header_container').css('background-color', color);
    $("#chromeColor").attr("content", color);

}

function LoadPage(page, title, type = "load", ContentDiv = "MainContent", callback = null, color = '#f2f2f2') {
    $("#loadingPageOverlay").addClass("PageLoadingAdd");
    $("#"+ ContentDiv).removeClass("HiddenContent");
    if (type == "append") {
        $("#" + ContentDiv).empty().append('<iframe class="iframe" src="' + page + '">');
        //$("#" + ContentDiv).empty().append('<object class="iframe" data="' + page + '">');
        $("body").addClass("scrollHidden");
        $(".content").addClass("scrollHidden");
        callback;
    } else if (type == "load") {
        $("#" + ContentDiv).load(page, callback);
        $("body").removeClass("scrollHidden");
        $(".content").removeClass("scrollHidden");
    }
    $("#MessageContent").addClass("HiddenContent");
    ChangeMainTitle(title, color);
    if (document.getElementById("MainSideMenu").classList.contains("HiddenIcons")) {
        showMenu(0);
    }
    setTimeout(function() {
        $('#loadingPageOverlay').removeClass('PageLoadingAdd');
    }, 500);
}

function LoadPageOnURL(LoadURL)
{
    if ($("#headerTimer")) {
            if (LoadURL == "/roster") {
                $("#timerContent").addClass("NormalTimer");
                $("#timerContent").removeClass("SmallTimer");
                $("#headerTimer").addClass("normalTimer");
                $("#headerTimer").removeClass("smallTimer");
            } else {
                $("#timerContent").removeClass("NormalTimer");
                $("#timerContent").addClass("SmallTimer");
                $("#headerTimer").removeClass("normalTimer");
                $("#headerTimer").addClass("smallTimer");
            }
        }
        if (LoadURL == "/messages") {
            LoadMessagesContent();
            ChangeMainTitle("Messages");
        } else if (LoadURL == "/dashboard") {
            LoadPage($('[rel="address:' + LoadURL + '"]').attr('location'), $('[rel="address:' + LoadURL + '"]').attr('metatitle'), undefined, undefined, undefined, "#478ec6");
            ChangeMainBackground("#1b74b9");
        } else {
            LoadPage($('[rel="address:' + LoadURL + '"]').attr('location'), $('[rel="address:' + LoadURL + '"]').attr('metatitle'), $('[rel="address:' + LoadURL + '"]').attr('loadType'));
            ChangeMainBackground();
        }
        SelectionIndic($('[rel="address:' + LoadURL + '"]')); 
}

function checkRoster() {
    
}
//////NON FUNCTION//////
////Used to load a page when clicked on a link (used in Dashboard)
window.addEventListener('popstate', function(event) {
    if (event.currentTarget.ViewingMenu) {
        history.pushState(null, null, event.currentTarget.urlLocation);
        showMenu(0);        
    } else {
        LoadPageOnURL(window.location.hash.slice(1));
    }       
}, false);

    $("#loadingPageOverlay").addClass("PageLoadingAdd");
    if ($("#headerTimer")) {
        $("#headerTimer").load("webpages/timer/timer.php", function() {
            console.log(window.location.hash.slice(1));
            if (window.location.hash.slice(1) == "/roster") {
                $(".content").addClass("timer");
                $("#timerContent").addClass("NormalTimer");
                $("#timerContent").removeClass("SmallTimer");
                $("#headerTimer").addClass("normalTimer");
                $("#headerTimer").removeClass("smallTimer");
            }
        });
    }

    
$(window).on('load', function(event){
        LoadURL = window.location.hash.slice(1);
        LoadPageOnURL(LoadURL);
        document.title = "PTR - "+$('[rel="address:' + LoadURL + '"]').attr('metatitle');
    });
    $('.ContentWithin').click(function(event) {
        event.preventDefault();
        ViewingMenu = false;
        delete urlLocation;
        history.pushState(null, "PTR - "+$(this).attr('metatitle'), $(this).attr('href')); 
        document.title = "PTR - "+$(this).attr('metatitle'); 
        if ($(this).attr("id") == "messagesLink") {
            $("#MessageContent").removeClass("HiddenContent");
            ChangeMainTitle("Messages");
            SelectionIndic($(this));
        } else {
            if (!!window.EventSource) {
                if (typeof(sourceNew) != "undefined") {
                    sourceNew.close();
                }
            }
            if (!$("#MessageContent").hasClass("HiddenContent")) {
                $("#MainContent").removeClass("HiddenContent");
                $("#MessageContent").addClass("HiddenContent");
            }
            if ($(this).attr("id") == "dashboardLink") {
                LoadPage($(this).attr('location'),
                    $(this).attr('metatitle'),
                    $(this).attr('loadType'),
                    undefined, 
                    undefined, 
                    "#478ec6");
                ChangeMainBackground("#1b74b9");
                SelectionIndic($(this));
            }
            else if ($(this).attr("id") == "logOutLink") {
                showPopUp("Are you sure you want to sign out?","Signing you out, please wait...",function(){
                    window.location.replace("webpages/start.php");
                })
            }
            else {
                LoadPage($(this).attr('location'),
                    $(this).attr('metatitle'),
                    $(this).attr('loadType'));
                ChangeMainBackground();
                SelectionIndic($(this));
            }
        }
        if ($(this).attr("id") == "rosterLink" && $("#headerTimer").html() != undefined) {
                $(".content").addClass("timer");
                $("#timerContent").addClass("NormalTimer");
                $("#timerContent").removeClass("SmallTimer");
                $("#headerTimer").addClass("normalTimer");
                $("#headerTimer").removeClass("smallTimer");
            } else {
                $(".content").removeClass("timer");
                $("#timerContent").removeClass("NormalTimer");
                $("#timerContent").addClass("SmallTimer");
                $("#headerTimer").removeClass("normalTimer");
                $("#headerTimer").addClass("smallTimer");
        }
    });

$(document).ready(function() {
    var resim = document.getElementsByClassName('side_menu_container')[0];
    var clickOverlay = document.getElementById("click_overlay");
    var touchGrab = document.getElementById("touchGrab");
    var hAmmer = new Hammer(resim, {inputClass: Hammer.TouchInput, passive: true});
    var clickHammer = new Hammer(clickOverlay, {inputClass: Hammer.TouchInput, passive: true});
    var bodyHammer = new Hammer(touchGrab, {inputClass: Hammer.TouchInput, passive: true});

    hAmmer.on('swiperight', function(e){
        showMenu();
    });
    hAmmer.on('swipeleft', function(e){
         showMenu(0);
    });
    clickHammer.on('swipeleft', function(e){
         showMenu(0);
    });
    bodyHammer.on('swiperight', function(e){
        showMenu();
    });
})

