<?php 
include 'functions/functions.php';
include 'functions/chat/AddChat.php';
if (session_id() == '')
    {
        session_start();
    }
 if(!isset($_SESSION['EMPID']) || empty($_SESSION['EMPID']))
     header("Location: webpages/start.php");
?>
<!DOCTYPE html>
<html lang="en">
	<head>
        <title>PTR</title>
        <meta charset="utf-8">
        <link rel="SHORTCUT ICON" href="images/favico.ico">
        <meta id="chromeColor" name="theme-color" content="#0063b1">
        <link rel="icon" href="images/favicon.png" type="image/ico">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="stylesheets/style.css" async>
        <?php if (session_id() == '')
                    {
                        session_start();
                    }
                    if(isset($_SESSION['EMAIL']))
                    {
        echo '<link rel="stylesheet" href="stylesheets/messages.css" async>';
                        //echo $_SERVER['REMOTE_ADDR'];
                    }?>
        <script type="application/javascript" src="javascript/header.js"></script>
        <script type="application/javascript">head.load("javascript/jquery-3.2.0.min.js",function(){
                /*head.load("javascript/hammer.min.js"); */head.load("javascript/main.js"),head.load("javascript/eventsource.js",function(){head.load("functions/chat/script.js")})});
        </script>
        <noscript>JavaScript is off. Please enable to view full site.</noscript>
	</head>
	<body>
		<div class="header noClickTouch">
		<div class="side_menu" id="MainSideMenu">
            <div class="side_menu_container">
                <div class="menu_itemLink_container"><a href="#/dashboard" location="webpages/dashboard.php" id="dashboardLink" metatitle="Dashboard" rel="address:/dashboard" class="menu_item ContentWithin"><svg class="menu_svg" xmlns="http://www.w3.org/2000/svg" id="Capa_1" viewBox="0 0 48 48" x="0px" y="0px" width="48" height="48" version="1.1" xmlns:xml="http://www.w3.org/XML/1998/namespace" xml:space="preserve"><defs id="defs39"></defs><g id="g4" transform="matrix(0.044594 0 0 0.044594 13.9995 13.9995)"><path id="path2" style="fill: #ffffff;" d="M 444.277 215.253 L 242.72 52.441 L 231.534 43.152 c -4.22 -3.506 -10.34 -3.506 -14.559 0 L 158.813 91.453 V 71.031 c 0 -6.294 -5.104 -11.397 -11.396 -11.397 h -43.449 c -6.293 0 -11.396 5.104 -11.396 11.397 v 75.233 L 4.191 218.371 c -4.875 3.979 -5.605 11.157 -1.625 16.035 c 2.254 2.764 5.531 4.193 8.836 4.193 c 2.533 0 5.082 -0.841 7.203 -2.565 l 34.477 -28.126 v 188.684 c 0 6.294 5.102 11.397 11.396 11.397 h 121.789 c 6.295 0 11.398 -5.104 11.398 -11.397 v -88.426 h 53.18 v 88.426 c 0 6.294 5.104 11.397 11.398 11.397 h 121.789 c 6.295 0 11.397 -5.104 11.397 -11.397 V 205.101 l 34.521 27.884 c 2.108 1.702 4.643 2.532 7.158 2.532 c 3.321 0 6.622 -1.447 8.87 -4.235 c 3.959 -4.898 3.195 -12.074 -1.701 -16.029 Z M 115.366 82.428 h 20.652 v 27.164 l -20.652 16.716 Z m 257.27 107.53 v 195.235 h -98.994 v -88.427 c 0 -6.294 -5.104 -11.396 -11.397 -11.396 h -75.977 c -6.295 0 -11.396 5.104 -11.396 11.396 v 88.427 H 75.877 V 189.958 l 44.309 -36.798 c 0 0 103.748 -85.009 104.41 -86.141 Z"></path></g><g id="g6" transform="translate(0 -406.464)"></g><g id="g8" transform="translate(0 -406.464)"></g><g id="g10" transform="translate(0 -406.464)"></g><g id="g12" transform="translate(0 -406.464)"></g><g id="g14" transform="translate(0 -406.464)"></g><g id="g16" transform="translate(0 -406.464)"></g><g id="g18" transform="translate(0 -406.464)"></g><g id="g20" transform="translate(0 -406.464)"></g><g id="g22" transform="translate(0 -406.464)"></g><g id="g24" transform="translate(0 -406.464)"></g><g id="g26" transform="translate(0 -406.464)"></g><g id="g28" transform="translate(0 -406.464)"></g><g id="g30" transform="translate(0 -406.464)"></g><g id="g32" transform="translate(0 -406.464)"></g><g id="g34" transform="translate(0 -406.464)"></g></svg>Dashboard</a></div>
                
                
                <div class="menu_itemLink_container"><a href="#/roster" location="webpages/dashboard.html" metatitle="Roster" id="rosterLink" rel="address:/roster" loadType="append" class="menu_item ContentWithin" ><svg class="menu_svg" xmlns="http://www.w3.org/2000/svg" id="Capa_1" viewBox="0 0 48 48" x="0px" y="0px" width="48" height="48" version="1.1" xmlns:xml="http://www.w3.org/XML/1998/namespace" xml:space="preserve"><defs id="defs41"></defs><g id="g8" transform="translate(0 -54.375)" /><g id="g10" transform="translate(0 -54.375)" /><g id="g12" transform="translate(0 -54.375)" /><g id="g14" transform="translate(0 -54.375)"></g><g id="g16" transform="translate(0 -54.375)" /><g id="g18" transform="translate(0 -54.375)" /><g id="g20" transform="translate(0 -54.375)" /><g id="g22" transform="translate(0 -54.375)" /><g id="g24" transform="translate(0 -54.375)" /><g id="g26" transform="translate(0 -54.375)" /><g id="g28" transform="translate(0 -54.375)" /><g id="g30" transform="translate(0 -54.375)" /><g id="g32" transform="translate(0 -54.375)" /><g id="g34" transform="translate(0 -54.375)" /><g id="g36" transform="translate(0 -54.375)" /><circle id="path850" style="fill: none; fill-opacity: 1; stroke: #ffffff; stroke-dasharray: none; stroke-miterlimit: 4; stroke-opacity: 1; stroke-width: 1;" cx="24" cy="24" r="9.46835" /><rect id="rect868" style="fill: #ffffff; fill-opacity: 1; stroke: none; stroke-dasharray: none; stroke-miterlimit: 4; stroke-opacity: 0; stroke-width: 0.940401;" x="23.5423" y="18.7806" width="1" height="7.67802" /><rect id="rect868-3" style="fill: #ffffff; fill-opacity: 1; stroke: none; stroke-dasharray: none; stroke-miterlimit: 4; stroke-opacity: 0; stroke-width: 0.67504;" transform="rotate(-90)" x="-26.4577" y="19.6085" width="1" height="3.95624" /></svg>Roster</a></div>
                
                <div class="menu_itemLink_container"><a href="#/leave" location="webpages/leave/annualbenefits.php" metatitle="Leave" loadType="append" rel="address:/leave" class="menu_item ContentWithin"><svg class="menu_svg" xmlns="http://www.w3.org/2000/svg" id="Capa_1" viewBox="0 0 48 48" x="0px" y="0px" width="48" height="48" version="1.1" xmlns:xml="http://www.w3.org/XML/1998/namespace" xml:space="preserve"><defs id="defs45"></defs><g id="g10" transform="matrix(0.0415327 0 0 0.0415327 13.7365 13.7365)"><g id="g8"><polygon id="polygon2" style="fill: #ffffff;" points="85.211,36.024 85.211,457.685 180.89,457.851 180.89,36.024" /><path id="path4" style="fill: #ffffff;" d="M 199.726 0 V 494.237 L 408.81 456.683 L 409.026 37.816 Z M 385.829 437.447 L 222.725 466.735 V 27.528 L 386.007 57.03 Z" /><ellipse id="ellipse6" style="fill: #ffffff;" cx="247.119" cy="247.119" rx="12.715" ry="16.545" /></g></g><g id="g12" transform="translate(0 -447.902)" /><g id="g14" transform="translate(0 -447.902)" /><g id="g16" transform="translate(0 -447.902)" /><g id="g18" transform="translate(0 -447.902)" /><g id="g20" transform="translate(0 -447.902)" /><g id="g22" transform="translate(0 -447.902)" /><g id="g24" transform="translate(0 -447.902)" /><g id="g26" transform="translate(0 -447.902)" /><g id="g28" transform="translate(0 -447.902)" /><g id="g30" transform="translate(0 -447.902)" /><g id="g32" transform="translate(0 -447.902)" /><g id="g34" transform="translate(0 -447.902)" /><g id="g36" transform="translate(0 -447.902)" /><g id="g38" transform="translate(0 -447.902)" /><g id="g40" transform="translate(0 -447.902)" /></svg>Leave</a></div>
                
                <div class="menu_itemLink_container"><a location="../tib/" rel="address:/pay" metatitle="Pay" loadType="append" href="#/pay"  class="menu_item ContentWithin"><div class="icons">$</div>Pay</a></div>
                <?php if($_SESSION['TYPE']=='CEO')
                    {
                        echo '<div class="menu_itemLink_container"><a href="#/employees" location="webpages/employees.php" metatitle="Employees" loadType="append" rel="address:/employees" class="menu_item ContentWithin"><svg class="menu_svg"
   xml:space="preserve"
   viewBox="0 0 48 47.999999"
   height="48"
   width="48"
   y="0px"
   x="0px"
   id="Capa_1"
   version="1.1"><metadata
     id="metadata5132"><rdf:RDF><cc:Work
         rdf:about=""><dc:format>image/svg+xml</dc:format><dc:type
           rdf:resource="http://purl.org/dc/dcmitype/StillImage" /><dc:title></dc:title></cc:Work></rdf:RDF></metadata><defs
     id="defs5130"><linearGradient
       id="linearGradient5736"><stop
         id="stop5732"
         offset="0"
         style="stop-color:#000000;stop-opacity:1;" /><stop
         id="stop5734"
         offset="1"
         style="stop-color:#000000;stop-opacity:0;" /></linearGradient><linearGradient
       gradientUnits="userSpaceOnUse"
       y2="214.48703"
       x2="418.559"
       y1="214.48703"
       x1="10.419"
       id="linearGradient5740"
       xlink:href="#linearGradient5736" /></defs><g
     style="fill:none;fill-opacity:1;stroke:#ffffff;stroke-width:13.78612827;stroke-linecap:square;stroke-linejoin:miter;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1;paint-order:stroke fill markers"
     transform="matrix(0.07979035,0,0,0.07979035,23.801671,20.080314)"
     id="g4511"><path
       id="path4509"
       d="m -15.872865,-48.429357 c -0.363775,0.0015 -0.85306,0.09909 -1.490395,0.333707 -9.512887,3.500087 -34.854165,12.753342 -41.219565,17.551129 -5.697003,4.293422 -9.459934,9.708942 -9.965658,17.106228 l 0.02221,30.564319 c 0,1.344965 -0.56688,2.707754 -1.223461,2.936304 -2.377765,0.82379 -2.436318,2.455481 -2.113254,4.582426 l 3.092024,16.216437 c 0.786979,2.906213 2.328963,5.613957 2.758351,8.564235 0.851956,5.868759 2.864472,11.005868 6.562207,15.437875 0.612051,0.73108 1.071895,1.776279 1.001019,2.936315 l -1.267951,13.547065 c -0.113096,0.879711 -1.001771,2.141227 -1.690604,2.335699 -2.173294,0.613429 -3.225806,2.937865 -3.892834,5.272016 l -2.224446,6.962566 -48.293398,25.536996 c -1.24591,0.65839 -2.02428,1.95038 -2.02428,3.35896 v 17.8848 c 0,2.09968 1.70372,3.80385 3.80386,3.80385 h 102.882074 c -0.0098,0.0626 -0.03531,0.11503 -0.04442,0.17802 h 68.558395 0.133483 65.323668 c -0.0877,-12.9792 0.30296,-19.67889 -1.61849,-20.68395 -6.81024,-2.51634 -27.721774,-6.71576 -34.34208,-9.72466 -1.886573,-0.85146 -3.274362,-2.72496 -4.982829,-4.02631 -0.753403,-0.5698 -1.658479,-1.12708 -2.558144,-1.26795 -3.068876,-0.48803 -4.429239,-1.57304 -5.160787,-4.60466 -0.954198,-3.97491 -1.630507,-7.941336 -0.800783,-12.078906 0.35808,-1.805709 1.024014,-2.964766 2.7806,-3.648148 3.644117,-1.420385 7.210467,-3.014856 10.810962,-4.537928 2.689919,-1.138704 2.862262,-2.089906 1.89081,-4.871612 C 85.742733,72.451417 83.424971,63.526994 82.671613,54.141337 82.205448,48.214413 82.351004,42.168835 80.914275,36.34552 H 80.691841 C 80.577948,35.808399 80.521229,35.275493 80.380457,34.743896 77.776869,24.477652 66.736287,18.891154 57.490583,18.305003 c -0.07631,-0.0054 -0.146125,-0.01708 -0.222434,-0.02278 -0.710693,-0.03872 -1.38083,0.0467 -2.068768,0.08884 -3.909476,0.537121 -7.096425,3.008888 -10.788707,4.093038 -4.830963,1.420385 -7.798588,5.850206 -9.609741,10.633004 -2.553612,6.783879 -2.088244,14.010667 -2.647129,21.043551 -0.753404,9.386556 -3.03251,18.310079 -6.139563,27.094129 -0.975951,2.787161 -0.820487,3.73293 1.868566,4.871612 3.600496,1.523982 7.189089,3.117542 10.833206,4.537928 1.756632,0.683359 2.417087,1.842439 2.7806,3.648148 0.824246,4.132115 0.142481,8.093087 -0.800783,12.078897 -0.152731,0.62801 -0.362864,1.12834 -0.57835,1.60163 L 17.316591,95.916338 15.092108,88.953726 C 14.42458,86.619575 13.349872,84.295117 11.177028,83.68171 10.486835,83.487295 9.6213605,82.225688 9.5086745,81.346011 L 8.2184692,67.798946 c -0.070614,-1.160036 0.3884899,-2.205223 1.0010076,-2.936315 3.6986482,-4.432007 5.7315862,-9.569116 6.5844522,-15.437874 0.428922,-2.950735 1.947301,-5.658023 2.736103,-8.564236 l 3.114274,-16.216437 c 0.323115,-2.126945 0.241795,-3.758181 -2.135498,-4.582426 -0.655684,-0.228584 -1.223464,-1.307358 -1.223464,-2.936304 l 0.04442,-30.564319 c -0.504775,-7.397286 -4.432656,-13.124965 -9.987901,-17.106228 -10.611569,-7.605847 -29.603311,-4.077013 -23.401494,-16.48338 0.27471,-0.547599 0.268219,-1.406035 -0.823106,-1.401411 z"
       style="fill:none;fill-opacity:1;stroke:#ffffff;stroke-width:13.78612827;stroke-linecap:square;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1;paint-order:stroke fill markers" /></g><g
     style="fill:url(#linearGradient5740);stroke:#00030a;stroke-width:22;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1;paint-order:stroke fill markers;stroke-linecap:square;stroke-linejoin:miter;fill-opacity:1"
     transform="matrix(0.03989517,0,0,0.03989517,3.9307019,21.399122)"
     id="g5095" /><g
     transform="translate(0,-380.97501)"
     id="g5097" /><g
     transform="translate(0,-380.97501)"
     id="g5099" /><g
     transform="translate(0,-380.97501)"
     id="g5101" /><g
     transform="translate(0,-380.97501)"
     id="g5103" /><g
     transform="translate(0,-380.97501)"
     id="g5105" /><g
     transform="translate(0,-380.97501)"
     id="g5107" /><g
     transform="translate(0,-380.97501)"
     id="g5109" /><g
     transform="translate(0,-380.97501)"
     id="g5111" /><g
     transform="translate(0,-380.97501)"
     id="g5113" /><g
     transform="translate(0,-380.97501)"
     id="g5115" /><g
     transform="translate(0,-380.97501)"
     id="g5117" /><g
     transform="translate(0,-380.97501)"
     id="g5119" /><g
     transform="translate(0,-380.97501)"
     id="g5121" /><g
     transform="translate(0,-380.97501)"
     id="g5123" /><g
     transform="translate(0,-380.97501)"
     id="g5125" /></svg>Employees</a></div>';
                    }
                ?>
                 
                
                
                <div class="lower_menu">
                    <div class="menu_itemLink_container"><a id="messagesLink" href="#/messages" location="#/messages" onclick="LoadMessagesContent()" rel="address:/messages" metatitle="Messages" class="menu_item ContentWithin menu_line"><svg class="menu_svg" xmlns="http://www.w3.org/2000/svg" id="Capa_1" viewBox="0 0 48 48" x="0px" y="0px" width="48" height="48" version="1.1" xmlns:xml="http://www.w3.org/XML/1998/namespace" xml:space="preserve"><defs id="defs39"></defs><g id="g4" style="fill: #ffffff;" transform="matrix(0.0414226 0 0 0.0414226 13.9465 13.9465)"><path id="path2" style="fill: #ffffff;" d="M 0 81.824 V 403.587 H 485.411 V 81.824 Z M 242.708 280.526 L 43.612 105.691 H 441.799 Z M 163.397 242.649 L 23.867 365.178 V 120.119 Z m 18.085 15.884 l 61.22 53.762 l 61.22 -53.762 l 138.002 121.187 H 43.487 Z M 322.008 242.655 L 461.543 120.119 v 245.059 Z" /></g><g id="g6" transform="translate(0 -439.904)" /><g id="g8" transform="translate(0 -439.904)" /><g id="g10" transform="translate(0 -439.904)" /><g id="g12" transform="translate(0 -439.904)" /><g id="g14" transform="translate(0 -439.904)" /><g id="g16" transform="translate(0 -439.904)" /><g id="g18" transform="translate(0 -439.904)" /><g id="g20" transform="translate(0 -439.904)" /><g id="g22" transform="translate(0 -439.904)" /><g id="g24" transform="translate(0 -439.904)" /><g id="g26" transform="translate(0 -439.904)" /><g id="g28" transform="translate(0 -439.904)" /><g id="g30" transform="translate(0 -439.904)" /><g id="g32" transform="translate(0 -439.904)" /><g id="g34" transform="translate(0 -439.904)" /></svg>Messages</a></div>
                    
                    <div class="menu_itemLink_container"><a href="#/account" location="webpages/account_logged.php" id="accountLink" metatitle="Account" rel="address:/account" loatType="load" class="menu_item ContentWithin"><svg class="menu_svg" xmlns="http://www.w3.org/2000/svg" id="Capa_1" viewBox="0 0 48 48" x="0px" y="0px" width="48" height="48" version="1.1" xmlns:xml="http://www.w3.org/XML/1998/namespace" xml:space="preserve"><defs id="defs39"></defs><g id="g4" style="stroke: #ffffff; stroke-dasharray: none; stroke-miterlimit: 4; stroke-opacity: 1; stroke-width: 22.4466;" transform="matrix(0.0490052 0 0 0.0490052 13.4889 13.489)"><path id="path2" style="fill: none; stroke: #ffffff; stroke-dasharray: none; stroke-miterlimit: 4; stroke-opacity: 1; stroke-width: 22.4466;" d="m 414.101 373.866 l -106.246 -56.188 l -4.907 -15.332 c -1.469 -5.137 -3.794 -10.273 -8.576 -11.623 c -1.519 -0.428 -3.441 -3.201 -3.689 -5.137 l -2.836 -29.813 c -0.156 -2.553 0.868 -4.844 2.216 -6.453 c 8.14 -9.754 12.577 -21.051 14.454 -33.967 c 0.944 -6.494 4.323 -12.483 6.059 -18.879 l 6.812 -35.649 c 0.711 -4.681 0.573 -8.289 -4.659 -10.103 c -1.443 -0.503 -2.699 -2.894 -2.699 -6.479 l 0.069 -67.264 C 308.988 60.699 300.368 48.11 288.142 39.348 C 264.788 22.609 222.967 30.371 236.616 3.067 C 237.422 1.46 237.165 -1.332 231.554 0.732 C 210.618 8.435 154.853 28.789 140.844 39.348 C 128.306 48.797 120 60.699 118.887 76.979 l 0.069 67.264 c 0 2.96 -1.255 5.976 -2.7 6.479 c -5.233 1.813 -5.37 5.422 -4.659 10.103 l 6.814 35.649 c 1.732 6.396 5.113 12.386 6.058 18.879 c 1.875 12.916 6.315 24.213 14.453 33.967 c 1.347 1.609 2.372 3.9 2.216 6.453 l -2.836 29.813 c -0.249 1.936 -2.174 4.709 -3.69 5.137 c -4.783 1.35 -7.109 6.486 -8.577 11.623 l -4.909 15.332 l -106.25 56.188 c -2.742 1.449 -4.457 4.297 -4.457 7.397 v 39.343 c 0 4.621 3.748 8.368 8.37 8.368 h 391.4 c 4.622 0 8.37 -3.747 8.37 -8.368 v -39.343 c -0.002 -3.1 -1.717 -5.948 -4.458 -7.397 Z" /></g><g id="g6" transform="translate(0 -388.759)" /><g id="g8" transform="translate(0 -388.759)" /><g id="g10" transform="translate(0 -388.759)" /><g id="g12" transform="translate(0 -388.759)" /><g id="g14" transform="translate(0 -388.759)" /><g id="g16" transform="translate(0 -388.759)" /><g id="g18" transform="translate(0 -388.759)" /><g id="g20" transform="translate(0 -388.759)" /><g id="g22" transform="translate(0 -388.759)" /><g id="g24" transform="translate(0 -388.759)" /><g id="g26" transform="translate(0 -388.759)" /><g id="g28" transform="translate(0 -388.759)" /><g id="g30" transform="translate(0 -388.759)" /><g id="g32" transform="translate(0 -388.759)" /><g id="g34" transform="translate(0 -388.759)" /></svg>Account</a></div>
                    
                    <div class="menu_itemLink_container"><a class="menu_item ContentWithin" id="logOutLink"><svg class="menu_svg" xmlns="http://www.w3.org/2000/svg" id="Layer_1" viewBox="0 0 48 48" x="0px" y="0px" width="48" height="48" version="1.1" xmlns:xml="http://www.w3.org/XML/1998/namespace" xml:space="preserve"><defs id="defs41"></defs><g id="g6" style="fill: #ffffff;" transform="matrix(0.0407176 0 0 0.0407176 11.999 13.9995)"><polygon id="polygon2" style="fill: #ffffff;" points="491.213,3.107 146.213,3.107 146.213,175.607 176.213,175.607 176.213,33.107 461.213,33.107 461.213,458.107 176.213,458.107 176.213,315.607 146.213,315.607 146.213,488.107 491.213,488.107" /><polygon id="polygon4" style="fill: #ffffff;" points="57.427,230.607 91.82,196.213 70.607,175 0,245.607 70.607,316.213 91.82,295 57.426,260.607 318.713,260.607 318.713,230.607" /></g><g id="g8" transform="translate(0 -445.162)" /><g id="g10" transform="translate(0 -445.162)" /><g id="g12" transform="translate(0 -445.162)" /><g id="g14" transform="translate(0 -445.162)" /><g id="g16" transform="translate(0 -445.162)" /><g id="g18" transform="translate(0 -445.162)" /><g id="g20" transform="translate(0 -445.162)" /><g id="g22" transform="translate(0 -445.162)" /><g id="g24" transform="translate(0 -445.162)" /><g id="g26" transform="translate(0 -445.162)" /><g id="g28" transform="translate(0 -445.162)" /><g id="g30" transform="translate(0 -445.162)" /><g id="g32" transform="translate(0 -445.162)" /><g id="g34" transform="translate(0 -445.162)" /><g id="g36" transform="translate(0 -445.162)" /></svg>Sign out</a></div>
                </div>
            </div>
		</div>
        <div id=click_overlay onclick="ExpandMenu()"></div>
            <div id="loadingPageOverlay" class="PageLoadingNormal PageLoadingAdd"><div class="loading"><div class="animationCircle"></div></div></div>
            <div id="timerStopPageOverlay" class="timerStopNormal">
                <div class="popUpContainer">
                    <div class="questionLabel"></div>
                    <div class="answerContainer">
                        <div id="timerStopConfirm" class="bigRoundButtons yesConfirm" title="Yes"></div>
                        <div id="timerStopCancel" class="bigRoundButtons noDeny" title="No"></div>
                    </div>
                    <div id="loadingStopTimer" class="loadingStopTimer animationCircle"></div>
                </div>
            </div>
		<div class="header_container">
			<div class="inlineDiv"><a class="menu_item menu_extend" onclick="ExpandMenu()"><svg xmlns="http://www.w3.org/2000/svg" id="svg8" viewBox="0 0 12.7 12.7" width="48" height="48" version="1.1"><defs id="defs2"></defs><g id="layer1" style="display: inline;" transform="translate(-74.0273 -102.378)"><rect id="rect3693" style="fill: #ffffff; stroke-width: 2.83299;" transform="matrix(1 0 -0.0114884 0.999934 0 0)" x="78.9823" y="107.415" width="5.28901" height="0.263111" /><rect id="rect3693-7" style="fill: #ffffff; stroke-width: 2.83299;" transform="matrix(1 0 -0.0114884 0.999934 0 0)" x="78.9822" y="108.737" width="5.28901" height="0.263111" /><rect id="rect3693-7-9" style="fill: #ffffff; stroke-width: 2.83299;" transform="matrix(1 0 -0.0114884 0.999934 0 0)" x="78.9847" y="110.06" width="5.28901" height="0.263111" /></g></svg></a></div>
			<div class="header_title inlineDiv" id="mainPageTitle"></div>
            <div class="smallTimer" id="<?php AddTimerCheck()?>"></div>
		</div>
		</div>
        <div id="MainContent" class="content">
        </div> 
        <div id="MessageContent" class="content HiddenContent"><?php AddChat();?></div>
	</body>
</html>