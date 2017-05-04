/*This file and the following functions have been written by Yannick Mansuy*/
function ExpandMenu() {
    var menu = $("#MainSideMenu");
    if (menu.hasClass("HiddenIcons")) {
        click_overlay.style.zIndex = "-1";
        menu.removeClass("HiddenIcons");
    } else {
        menu.addClass("HiddenIcons");
        click_overlay.style.zIndex = "2"
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
            ExpandMenu();
        }
    });
    setTimeout(function() {
        $('#loadingPageOverlay').removeClass('PageLoadingAdd');
    }, 1000);
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
        ExpandMenu();
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
//////NON FUNCTION//////
////Used to load a page when clicked on a link (used in Dashboard)
window.addEventListener('popstate', function(event) {
       LoadPageOnURL(window.location.hash.slice(1));
}, false);

    $("#loadingPageOverlay").addClass("PageLoadingAdd");
    if ($("#headerTimer")) {
        $("#headerTimer").load("webpages/timer/timer.php", function() {
            if ($.address.path() == "/roster") {
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
    });
    $('.ContentWithin').click(function(event) {
        event.preventDefault();
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
        history.pushState(null, "PTR - "+$(this).attr('metatitle'), $(this).attr('href')); 
    });

//var startDragAtBorder = false;
//$(document).on('touchstart', function(e) {
//   var xPos = e.originalEvent.touches[0].pageX;
//
//   if(xPos < 5) { // this can also be xPos == 0; whatever works for you
//    startDragAtBorder = true;   
//   }
//   else{
//    startDragAtBorder = false;
//   }
//});
//var resim = document.getElementsByClassName('side_menu_container');
//console.log(resim);
//var hAmmer =new Hammer.Manager(resim,{
//	recognizers: [
//		// RecognizerClass, [options], [recognizeWith, ...], [requireFailure, ...]
//		[Hammer.swipe,{ direction: Hammer.DIRECTION_RIGTH }],
//	]
//});
//    
//hAmmer.on('swipe', function(e){
//  if(startDragAtBorder && e.gesture.direction == 'right'){
//    // check that the drag event started at the edge and that the direction is to the right
//    ExpandMenu();
//  }
//});
//});