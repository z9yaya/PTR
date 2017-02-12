<?php 
include 'functions/functions.php';
if (session_id() == '')
    {
        session_start();
    }
?>
<!DOCTYPE html>
<html lang="en">
	<head>
        <title>Bootstrap Case</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="stylesheets/style.css">
        <script type="text/javascript" src="javascript/jquery-1.9.0.min.js"></script>
        <script type="text/javascript" src="javascript/jquery.address-1.6.min.js"></script>
        <script src="javascript/animation_menu.js"></script>
        <script type="text/javascript">
                function ChangeMainBackground(bgcolor = "white")
                {
                    $("#MainContent").css("background-color", bgcolor);
                }
            
                function LoadMessagesContent()
                {
                    $("#loadingPageOverlay").addClass("PageLoadingAdd");
                $("document").ready(function(){ 
                    document.getElementById('MessageContent').classList.remove('HiddenContent');
                    $("#MainContent").addClass("HiddenContent");
                    if ($("#messagesLink").hasClass("unreadHid"))
                            messagesLink.classList.remove("unreadHid");
                    if(document.getElementById("MainSideMenu").classList.contains("HiddenIcons"))
                        {
                            ExpandMenu();
                        }
                })
                $('#loadingPageOverlay').removeClass('PageLoadingAdd');
                }
                function ChangeMainTitle(text, color = '#f2f2f2')
                {
                    $('#mainPageTitle').html(text);
                    if (color != "#f2f2f2")
                        {$('#mainPageTitle').css("color", "white");}
                    else{
                         {$('#mainPageTitle').css("color", "black");}
                    }
                    $('.header_container').css('background-color', color);
                    
                }
                function LoadPage(page, title, ContentDiv = "MainContent", callback = null, color = '#f2f2f2')
                {
                    $("#loadingPageOverlay").addClass("PageLoadingAdd");
                    $("#"+ContentDiv).load(page, callback);
                    ChangeMainTitle(title, color);
                    if(document.getElementById("MainSideMenu").classList.contains("HiddenIcons"))
                        {
                            ExpandMenu();
                        }
                        $('#loadingPageOverlay').removeClass('PageLoadingAdd');
                }
            $("document").ready(function(){ 
                $.address.init(function(event) {}).change(function(event) {
                    if (event.value == "/messages")
                        {
                            LoadMessagesContent();
                            ChangeMainTitle("Messages");
                            
                        }
                    else if (event.value == "/dashboard")
                        {
                            LoadPage($('[rel="address:' + event.value + '"]').attr('href'),$('[rel="address:' + event.value + '"]').attr('metatitle'),"MainContent", function(){ResizeSquares();}, "#1970b3");
                            ChangeMainBackground("#1b74b9");
                        }
                    else
                        {
                            LoadPage($('[rel="address:' + event.value + '"]').attr('href'),$('[rel="address:' + event.value + '"]').attr('metatitle'));
                            ChangeMainBackground();
                        }
	    	      
                    console.log(event.value);
                })
                $('.ContentWithin').click(function(){
                    event.preventDefault();
                    if ($(this).attr("id") == "messagesLink")
                        {
                            $("#MessageContent").classList.remove("HiddenContent");
                            ChangeMainTitle("Messages");
                        }
                    else
                        {
                            if (!$("#MessageContent").hasClass("HiddenContent"))
                                {
                                    $("#MainContent").removeClass("HiddenContent");
                                    $("#MessageContent").addClass("HiddenContent");
                                }
                            if ($(this).attr("id") == "dashboardLink")
                            {
                                 ChangeMainBackground("#1b74b9");
                                LoadPage($(this).attr('href'),$(this).attr('metatitle'),"MainContent", function(){ResizeSquares();},"#1970b3"); 
                            }
                            else
                            {
                                LoadPage($(this).attr('href'),$(this).attr('metatitle'));
                                ChangeMainBackground();
                            }
                        }
	    	     
	            });
 
	    });
        </script>
        <noscript>JavaScript is off. Please enable to view full site.</noscript>
	</head>
	<body>
		<div class="header">
		<div class="side_menu" id="MainSideMenu">
            <div class="side_menu_container">
                <div><a href="webpages/dashboard.html" id="dashboardLink" metatitle="Dashboard" rel="address:/dashboard" class="menu_item ContentWithin"><svg class="menu_svg" xmlns="http://www.w3.org/2000/svg" id="Capa_1" viewBox="0 0 48 48" x="0px" y="0px" width="48" height="48" version="1.1" xmlns:xml="http://www.w3.org/XML/1998/namespace" xml:space="preserve"><defs id="defs39"></defs><g id="g4" transform="matrix(0.044594 0 0 0.044594 13.9995 13.9995)"><path id="path2" style="fill: #ffffff;" d="M 444.277 215.253 L 242.72 52.441 L 231.534 43.152 c -4.22 -3.506 -10.34 -3.506 -14.559 0 L 158.813 91.453 V 71.031 c 0 -6.294 -5.104 -11.397 -11.396 -11.397 h -43.449 c -6.293 0 -11.396 5.104 -11.396 11.397 v 75.233 L 4.191 218.371 c -4.875 3.979 -5.605 11.157 -1.625 16.035 c 2.254 2.764 5.531 4.193 8.836 4.193 c 2.533 0 5.082 -0.841 7.203 -2.565 l 34.477 -28.126 v 188.684 c 0 6.294 5.102 11.397 11.396 11.397 h 121.789 c 6.295 0 11.398 -5.104 11.398 -11.397 v -88.426 h 53.18 v 88.426 c 0 6.294 5.104 11.397 11.398 11.397 h 121.789 c 6.295 0 11.397 -5.104 11.397 -11.397 V 205.101 l 34.521 27.884 c 2.108 1.702 4.643 2.532 7.158 2.532 c 3.321 0 6.622 -1.447 8.87 -4.235 c 3.959 -4.898 3.195 -12.074 -1.701 -16.029 Z M 115.366 82.428 h 20.652 v 27.164 l -20.652 16.716 Z m 257.27 107.53 v 195.235 h -98.994 v -88.427 c 0 -6.294 -5.104 -11.396 -11.397 -11.396 h -75.977 c -6.295 0 -11.396 5.104 -11.396 11.396 v 88.427 H 75.877 V 189.958 l 44.309 -36.798 c 0 0 103.748 -85.009 104.41 -86.141 Z"></path></g><g id="g6" transform="translate(0 -406.464)"></g><g id="g8" transform="translate(0 -406.464)"></g><g id="g10" transform="translate(0 -406.464)"></g><g id="g12" transform="translate(0 -406.464)"></g><g id="g14" transform="translate(0 -406.464)"></g><g id="g16" transform="translate(0 -406.464)"></g><g id="g18" transform="translate(0 -406.464)"></g><g id="g20" transform="translate(0 -406.464)"></g><g id="g22" transform="translate(0 -406.464)"></g><g id="g24" transform="translate(0 -406.464)"></g><g id="g26" transform="translate(0 -406.464)"></g><g id="g28" transform="translate(0 -406.464)"></g><g id="g30" transform="translate(0 -406.464)"></g><g id="g32" transform="translate(0 -406.464)"></g><g id="g34" transform="translate(0 -406.464)"></g></svg>Home</a></div>
                
                
                <div><a href="#" class="menu_item"><svg class="menu_svg" xmlns="http://www.w3.org/2000/svg" id="Capa_1" viewBox="0 0 48 48" x="0px" y="0px" width="48" height="48" version="1.1" xmlns:xml="http://www.w3.org/XML/1998/namespace" xml:space="preserve"><defs id="defs41"></defs><g id="g8" transform="translate(0 -54.375)" /><g id="g10" transform="translate(0 -54.375)" /><g id="g12" transform="translate(0 -54.375)" /><g id="g14" transform="translate(0 -54.375)"></g><g id="g16" transform="translate(0 -54.375)" /><g id="g18" transform="translate(0 -54.375)" /><g id="g20" transform="translate(0 -54.375)" /><g id="g22" transform="translate(0 -54.375)" /><g id="g24" transform="translate(0 -54.375)" /><g id="g26" transform="translate(0 -54.375)" /><g id="g28" transform="translate(0 -54.375)" /><g id="g30" transform="translate(0 -54.375)" /><g id="g32" transform="translate(0 -54.375)" /><g id="g34" transform="translate(0 -54.375)" /><g id="g36" transform="translate(0 -54.375)" /><circle id="path850" style="fill: none; fill-opacity: 1; stroke: #ffffff; stroke-dasharray: none; stroke-miterlimit: 4; stroke-opacity: 1; stroke-width: 1;" cx="24" cy="24" r="9.46835" /><rect id="rect868" style="fill: #ffffff; fill-opacity: 1; stroke: none; stroke-dasharray: none; stroke-miterlimit: 4; stroke-opacity: 0; stroke-width: 0.940401;" x="23.5423" y="18.7806" width="1" height="7.67802" /><rect id="rect868-3" style="fill: #ffffff; fill-opacity: 1; stroke: none; stroke-dasharray: none; stroke-miterlimit: 4; stroke-opacity: 0; stroke-width: 0.67504;" transform="rotate(-90)" x="-26.4577" y="19.6085" width="1" height="3.95624" /></svg>Roster</a></div>
                
                <div><a href="pages/TEMPLATE.php" metatitle="template" class="menu_item ContentWithin"><svg class="menu_svg" xmlns="http://www.w3.org/2000/svg" id="Capa_1" viewBox="0 0 48 48" x="0px" y="0px" width="48" height="48" version="1.1" xmlns:xml="http://www.w3.org/XML/1998/namespace" xml:space="preserve"><defs id="defs45"></defs><g id="g10" transform="matrix(0.0415327 0 0 0.0415327 13.7365 13.7365)"><g id="g8"><polygon id="polygon2" style="fill: #ffffff;" points="85.211,36.024 85.211,457.685 180.89,457.851 180.89,36.024" /><path id="path4" style="fill: #ffffff;" d="M 199.726 0 V 494.237 L 408.81 456.683 L 409.026 37.816 Z M 385.829 437.447 L 222.725 466.735 V 27.528 L 386.007 57.03 Z" /><ellipse id="ellipse6" style="fill: #ffffff;" cx="247.119" cy="247.119" rx="12.715" ry="16.545" /></g></g><g id="g12" transform="translate(0 -447.902)" /><g id="g14" transform="translate(0 -447.902)" /><g id="g16" transform="translate(0 -447.902)" /><g id="g18" transform="translate(0 -447.902)" /><g id="g20" transform="translate(0 -447.902)" /><g id="g22" transform="translate(0 -447.902)" /><g id="g24" transform="translate(0 -447.902)" /><g id="g26" transform="translate(0 -447.902)" /><g id="g28" transform="translate(0 -447.902)" /><g id="g30" transform="translate(0 -447.902)" /><g id="g32" transform="translate(0 -447.902)" /><g id="g34" transform="translate(0 -447.902)" /><g id="g36" transform="translate(0 -447.902)" /><g id="g38" transform="translate(0 -447.902)" /><g id="g40" transform="translate(0 -447.902)" /></svg>Leave</a></div>
                
                <div><a href="#" class="menu_item"><div class="icons">$</div>Pay</a></div>
                
                
                <div class="lower_menu">
                    <div><a  id="messagesLink" onclick="LoadMessagesContent()" rel="address:/messages" metatitle="Messages" class="menu_item menu_line"><svg class="menu_svg" xmlns="http://www.w3.org/2000/svg" id="Capa_1" viewBox="0 0 48 48" x="0px" y="0px" width="48" height="48" version="1.1" xmlns:xml="http://www.w3.org/XML/1998/namespace" xml:space="preserve"><defs id="defs39"></defs><g id="g4" style="fill: #ffffff;" transform="matrix(0.0414226 0 0 0.0414226 13.9465 13.9465)"><path id="path2" style="fill: #ffffff;" d="M 0 81.824 V 403.587 H 485.411 V 81.824 Z M 242.708 280.526 L 43.612 105.691 H 441.799 Z M 163.397 242.649 L 23.867 365.178 V 120.119 Z m 18.085 15.884 l 61.22 53.762 l 61.22 -53.762 l 138.002 121.187 H 43.487 Z M 322.008 242.655 L 461.543 120.119 v 245.059 Z" /></g><g id="g6" transform="translate(0 -439.904)" /><g id="g8" transform="translate(0 -439.904)" /><g id="g10" transform="translate(0 -439.904)" /><g id="g12" transform="translate(0 -439.904)" /><g id="g14" transform="translate(0 -439.904)" /><g id="g16" transform="translate(0 -439.904)" /><g id="g18" transform="translate(0 -439.904)" /><g id="g20" transform="translate(0 -439.904)" /><g id="g22" transform="translate(0 -439.904)" /><g id="g24" transform="translate(0 -439.904)" /><g id="g26" transform="translate(0 -439.904)" /><g id="g28" transform="translate(0 -439.904)" /><g id="g30" transform="translate(0 -439.904)" /><g id="g32" transform="translate(0 -439.904)" /><g id="g34" transform="translate(0 -439.904)" /></svg>Messages</a></div>
                    
                    <div><a href="webpages/account.html" id="accountLink" metatitle="Account" rel="address:/account" class="menu_item ContentWithin"><svg class="menu_svg" xmlns="http://www.w3.org/2000/svg" id="Capa_1" viewBox="0 0 48 48" x="0px" y="0px" width="48" height="48" version="1.1" xmlns:xml="http://www.w3.org/XML/1998/namespace" xml:space="preserve"><defs id="defs39"></defs><g id="g4" style="stroke: #ffffff; stroke-dasharray: none; stroke-miterlimit: 4; stroke-opacity: 1; stroke-width: 22.4466;" transform="matrix(0.0490052 0 0 0.0490052 13.4889 13.489)"><path id="path2" style="fill: none; stroke: #ffffff; stroke-dasharray: none; stroke-miterlimit: 4; stroke-opacity: 1; stroke-width: 22.4466;" d="m 414.101 373.866 l -106.246 -56.188 l -4.907 -15.332 c -1.469 -5.137 -3.794 -10.273 -8.576 -11.623 c -1.519 -0.428 -3.441 -3.201 -3.689 -5.137 l -2.836 -29.813 c -0.156 -2.553 0.868 -4.844 2.216 -6.453 c 8.14 -9.754 12.577 -21.051 14.454 -33.967 c 0.944 -6.494 4.323 -12.483 6.059 -18.879 l 6.812 -35.649 c 0.711 -4.681 0.573 -8.289 -4.659 -10.103 c -1.443 -0.503 -2.699 -2.894 -2.699 -6.479 l 0.069 -67.264 C 308.988 60.699 300.368 48.11 288.142 39.348 C 264.788 22.609 222.967 30.371 236.616 3.067 C 237.422 1.46 237.165 -1.332 231.554 0.732 C 210.618 8.435 154.853 28.789 140.844 39.348 C 128.306 48.797 120 60.699 118.887 76.979 l 0.069 67.264 c 0 2.96 -1.255 5.976 -2.7 6.479 c -5.233 1.813 -5.37 5.422 -4.659 10.103 l 6.814 35.649 c 1.732 6.396 5.113 12.386 6.058 18.879 c 1.875 12.916 6.315 24.213 14.453 33.967 c 1.347 1.609 2.372 3.9 2.216 6.453 l -2.836 29.813 c -0.249 1.936 -2.174 4.709 -3.69 5.137 c -4.783 1.35 -7.109 6.486 -8.577 11.623 l -4.909 15.332 l -106.25 56.188 c -2.742 1.449 -4.457 4.297 -4.457 7.397 v 39.343 c 0 4.621 3.748 8.368 8.37 8.368 h 391.4 c 4.622 0 8.37 -3.747 8.37 -8.368 v -39.343 c -0.002 -3.1 -1.717 -5.948 -4.458 -7.397 Z" /></g><g id="g6" transform="translate(0 -388.759)" /><g id="g8" transform="translate(0 -388.759)" /><g id="g10" transform="translate(0 -388.759)" /><g id="g12" transform="translate(0 -388.759)" /><g id="g14" transform="translate(0 -388.759)" /><g id="g16" transform="translate(0 -388.759)" /><g id="g18" transform="translate(0 -388.759)" /><g id="g20" transform="translate(0 -388.759)" /><g id="g22" transform="translate(0 -388.759)" /><g id="g24" transform="translate(0 -388.759)" /><g id="g26" transform="translate(0 -388.759)" /><g id="g28" transform="translate(0 -388.759)" /><g id="g30" transform="translate(0 -388.759)" /><g id="g32" transform="translate(0 -388.759)" /><g id="g34" transform="translate(0 -388.759)" /></svg>Account</a></div>
                    
                    <div><a href="pages/login.php" rel="address:/login" metatitle="Log in" class="menu_item ContentWithin"><svg class="menu_svg" xmlns="http://www.w3.org/2000/svg" id="Layer_1" viewBox="0 0 48 48" x="0px" y="0px" width="48" height="48" version="1.1" xmlns:xml="http://www.w3.org/XML/1998/namespace" xml:space="preserve"><defs id="defs41"></defs><g id="g6" style="fill: #ffffff;" transform="matrix(0.0407176 0 0 0.0407176 11.999 13.9995)"><polygon id="polygon2" style="fill: #ffffff;" points="491.213,3.107 146.213,3.107 146.213,175.607 176.213,175.607 176.213,33.107 461.213,33.107 461.213,458.107 176.213,458.107 176.213,315.607 146.213,315.607 146.213,488.107 491.213,488.107" /><polygon id="polygon4" style="fill: #ffffff;" points="57.427,230.607 91.82,196.213 70.607,175 0,245.607 70.607,316.213 91.82,295 57.426,260.607 318.713,260.607 318.713,230.607" /></g><g id="g8" transform="translate(0 -445.162)" /><g id="g10" transform="translate(0 -445.162)" /><g id="g12" transform="translate(0 -445.162)" /><g id="g14" transform="translate(0 -445.162)" /><g id="g16" transform="translate(0 -445.162)" /><g id="g18" transform="translate(0 -445.162)" /><g id="g20" transform="translate(0 -445.162)" /><g id="g22" transform="translate(0 -445.162)" /><g id="g24" transform="translate(0 -445.162)" /><g id="g26" transform="translate(0 -445.162)" /><g id="g28" transform="translate(0 -445.162)" /><g id="g30" transform="translate(0 -445.162)" /><g id="g32" transform="translate(0 -445.162)" /><g id="g34" transform="translate(0 -445.162)" /><g id="g36" transform="translate(0 -445.162)" /></svg>Sign out</a></div>
                </div>
            </div>
		</div>
        <div id=click_overlay onclick="ExpandMenu()"></div>
        <div id="loadingPageOverlay" class="PageLoadingNormal"></div>
		<div class="header_container">
			<div><a class="menu_item menu_extend" onclick="ExpandMenu()"><svg xmlns="http://www.w3.org/2000/svg" id="svg8" viewBox="0 0 12.7 12.7" width="48" height="48" version="1.1"><defs id="defs2"></defs><g id="layer1" style="display: inline;" transform="translate(-74.0273 -102.378)"><rect id="rect3693" style="fill: #ffffff; stroke-width: 2.83299;" transform="matrix(1 0 -0.0114884 0.999934 0 0)" x="78.9823" y="107.415" width="5.28901" height="0.263111" /><rect id="rect3693-7" style="fill: #ffffff; stroke-width: 2.83299;" transform="matrix(1 0 -0.0114884 0.999934 0 0)" x="78.9822" y="108.737" width="5.28901" height="0.263111" /><rect id="rect3693-7-9" style="fill: #ffffff; stroke-width: 2.83299;" transform="matrix(1 0 -0.0114884 0.999934 0 0)" x="78.9847" y="110.06" width="5.28901" height="0.263111" /></g></svg></a></div>
			<div class="header_title" id="mainPageTitle">Title</div>
		</div>
		</div>
         <?php if (session_id() == '')
                    {
                        session_start();
                    }
                    if(isset($_SESSION['position']) && $_SESSION['position'] != 'customer')
                    {
                        echo '<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
                                <meta http-equiv="Pragma" content="no-cache" />
                                <meta http-equiv="Expires" content="0" />
                                <script type="text/javascript" src="javascript/eventsource.js"></script>
                                <script type="text/javascript" src="functions/chat/script.js"></script>';
                    }?>
        <div id="MainContent" class="content">
        </div> 
        <div id="MessageContent" class="content HiddenContent">
                <?php AddChat();?>
                </div>
        <script>
            $("#MainContent").empty().append('<object data="../tib6/index.php">');</script>
        <script type="text/javascript">
                    function ResizeSquares()
                    {
                        var Width = (($(window).width() - 48) / 2) - 5;
                        var  WidthBig = (($(window).width() - 48) / 3) - 7.33;
                        console.log(WidthBig);
                        console.log(Width);
                        if (Width > 175 & WidthBig > 175 & WidthBig <= 325)
                            {
                                $('.square_container').css('height', WidthBig);
                                $('.rectangle_container').css('height', WidthBig);
                                console.log("WidthBig");
                            }
                        if (Width <= 175)
                            {
                                $('.square_container').css('height', Width);
                                $('.rectangle_container').css('height', Width);
                                console.log("Width");
                            }
                        else if(WidthBig > 325)
                            {
                                $('.square_container').css('height', 325);
                                $('.rectangle_container').css('height', 325);
                                console.log("WidthBig");
                            }
                    }
                    $( window ).resize(ResizeSquares);
                </script>
       
	</body>
</html>