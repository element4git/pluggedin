<?php 
	include_once('pluggedInit.php');
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta id="Viewport" name="viewport" width="initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
<title> Plugged In - by R/GA </title>
<link rel="stylesheet" type="text/css" href="css/global.css" />
<link rel="stylesheet" type="text/css" href="css/ui-lightness/jquery-ui-1.10.1.custom.min.css" />
<!--[if lt IE 7]>
	<link rel="stylesheet" type="text/css" href="css/ie.css" />
<![endif]-->
</head>
<body>
	<div id="mainContainer" class="full-width">
        <div id="mainContent" class="full-width">
            <div id="topSpacer" class="grid-container">
                <div class="grid-12"></div>
            </div>
            <div id="filters" class="full-width">
                <div class="grid-container">
                    <div class="grid-12">
                    	<h1 class="connect-lbl">Connect to local bands you like playing nearby</h1>
                    </div>
                </div>
                <div class="social-btns grid-container">
                	<div class="grid-12">
                		<ul>
	                		<li id="fbToggle">
	                			<a href="#"><img class="toggle-on" src="/images/buttons/social_btn_fb.png"/></a>
	                			<a href="#"><img class="toggle-off displayNone" src="/images/buttons/social_btn_fb_active.png"/></a>
	                		</li>
	                		<li id="scToggle">
	                			<a href="#"><img class="toggle-on" src="/images/buttons/social_btn_sc.png"/></a>
	                			<a href="#"><img class="toggle-off displayNone" src="/images/buttons/social_btn_sc_active.png"/></a>
	                		</li>
	                		<!--li id="rdioToggle" class="displayNone">
	                			<a href="javascript:void(0)"><img id="rdioToggle-on" src="/images/buttons/social_btn_rdio.png"/></a>
	                			<a href="javascript:void(0)"><img id="rdioToggle-off" class="displayNone" src="/images/buttons/social_btn_rdio_active.png"/></a>
	                		</li-->
	                	</ul>
                	</div>
                </div>
                <div class="divider grid-container">
                    <div class="grid-5"><hr class="pad-left" /></div>
                    <div class="grid-2"><h2>or</h2></div>
                    <div class="grid-5"><hr class="pad-right" /></div>
                </div>
                <div class="search-bar grid-container">
                    <div class="grid-12">
                    	<form>
							<input type="text" id="searchForm" placeholder="Search bands or local venues" />
							<input type="button" class="displayNone co-font" value="s"/>
						</form>
					</div>
                </div>
   				<div id="masterList" class="full-width"> <!-- preferred name: schedule -->
	                <div class="grid-container">
	                   <!--div id="eventDate" class="event-date full-width"><span>Today</span></div>
	                    <div class="cal-container">
	                        <div id="eventTime" class="event-time grid-3">7:30pm</div>
	                        <div class="grid-7">
	                        	<div id="bandName" class="band-name full-width">The Greatest Band in the World</div>
	                        	<div id="venueName" class="venue-name full-width"><a>Lounge #1</a></div>
	                        </div>
	                        <div class="add-to-cal grid-2"><a class="ico-calendar"></a></div>
	                    </div-->
	                </div>
	                <!--<div class="grid-container">
	                    <div id="eventDate" class="event-date grid-12">3/12/2013</div>
	                    <div class="full-width">
	                        <div id="eventTime" class="event-time grid-3">8:00pm</div>
	                        <div class="grid-7">
	                        	<div id="bandName" class="band-name full-width">Bano</div>
	                        	<div id="venueName" class="venue-name full-width"><a>Dive Bar</a></div>
	                        </div>
	                        <div class="add-to-cal grid-2"><a class="ico-calendar"></a></div>
	                    </div>
	                    <div class="full-width">
	                        <div id="eventTime" class="event-time grid-3">9:15pm</div>
	                        <div class="grid-7">
	                        	<div id="bandName" class="band-name full-width">The 2nd Greatest Band in the World</div>
	                        	<div id="venueName" class="venue-name full-width"><a>Stage 6</a></div>
	                        </div>
	                        <div class="add-to-cal grid-2"><a class="ico-calendar"></a></div>
	                    </div>
	                </div>-->
	           </div>
		</div>
		<div id="footer" class="full-width">
	        <div class="wrapper">
	           	<a href="http://rga.com/careers" target="_new">
	           		<div class="label">Presented by&nbsp;&nbsp;</div>
	           		<div class="logo ico-rga-logo"> </div>
	           	</a>
	        </div>
	    </div>
	</div>
	<div id="fb-root"></div>
</body>
<script src="http://connect.facebook.net/en_US/all.js"></script>
<script>
  FB.init({
    appId  : '<?php echo $FBID; ?>',
    status : true, // check login status
    cookie : true, // enable cookies to allow the server to access the session
    xfbml  : true, // parse XFBML
    channelURL : 'http://WWW.MYDOMAIN.COM/channel.html', // channel.html file
    oauth  : true // enable OAuth 2.0
  });
</script>

<script src="http://connect.soundcloud.com/sdk.js"></script>
<script>
// initialize client with app credentials
SC.initialize({
  client_id: '<?php echo $soundCloudID; ?>',
  redirect_uri: '<?php echo $soundCloudRedirect ?>'
});

// initiate auth popup

</script>

<script type="text/javascript" src="code/jquery-1.9.1.js"></script>
<script type="text/javascript" src="code/jquery-ui-1.10.1.custom.min.js"></script>
<script type="text/javascript" src="code/global.js"></script>
<script type="text/javascript" src="code/object.js"></script>

<script type="text/javascript">
	var phpGigHTML = <?php echo $html; ?>;
	var phpSearchArray = <?php echo json_encode($searchArray); ?>;
	sxswObject.init();
</script>
</html>
