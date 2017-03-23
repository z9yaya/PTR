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
    })
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

}

function LoadPage(page, title, type = "load", ContentDiv = "MainContent", callback = null, color = '#f2f2f2') {
    $("#loadingPageOverlay").addClass("PageLoadingAdd");
    if (type == "append") {
        $("#" + ContentDiv).empty().append('<object class="iframe" data="' + page + '">');
        $("body").addClass("scrollHidden");
        $(".content").addClass("scrollHidden");
        callback;
    } else if (type == "load") {
        $("#" + ContentDiv).load(page, callback);
    }
    ChangeMainTitle(title, color);
    if (document.getElementById("MainSideMenu").classList.contains("HiddenIcons")) {
        ExpandMenu();
    }
    setTimeout(function() {
        $('#loadingPageOverlay').removeClass('PageLoadingAdd');
    }, 1000);
}
//////NON FUNCTION//////
//$(document).ready(function() {
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
    $.address.init(function(event) {}).change(function(event) {
        if ($("#headerTimer")) {
            if (event.value == "/roster") {
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
        if (event.value == "/messages") {
            LoadMessagesContent();
            ChangeMainTitle("Messages");
        } else if (event.value == "/dashboard") {
            LoadPage($('[rel="address:' + event.value + '"]').attr('href'), $('[rel="address:' + event.value + '"]').attr('metatitle'), undefined, undefined, undefined, "#478ec6");
            ChangeMainBackground("#1b74b9");
        } else {
            LoadPage($('[rel="address:' + event.value + '"]').attr('href'), $('[rel="address:' + event.value + '"]').attr('metatitle'), $('[rel="address:' + event.value + '"]').attr('loadType'));
            ChangeMainBackground();
        }
        SelectionIndic($('[rel="address:' + event.value + '"]'))
    });
    $('.ContentWithin').click(function() {
        event.preventDefault();
        if ($(this).attr("id") == "messagesLink") {
            $("#MessageContent").classList.remove("HiddenContent");
            ChangeMainTitle("Messages");
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
                ChangeMainBackground("#1b74b9");
                LoadPage($(this).attr('href'), $(this).attr('metatitle'), undefined, "MainContent", undefined, "#478ec6");
            } else {
                LoadPage($(this).attr('href'),
                    $(this).attr('metatitle'),
                    $(this).attr('loadType'));
                ChangeMainBackground();
            }
        }

    });

//});