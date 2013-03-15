<?php 
	include_once('pluggedInit.php');
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="user-scalable=no" />
<meta property="og:title" content="Plugged In - R/GA"/>
<meta property="og:url" content="http://pluggedin.rga.com"/>
<meta name="description" content="Connect to locate bands you like playing nearby">
<meta property="og:image" content="images/icon.jpeg"/> 
<title> Plugged In - by R/GA </title>
<link rel="stylesheet" type="text/css" href="css/global.css" />
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
                    	<h1 class="connect-lbl">Connect to locate bands you like playing nearby</h1>
                    </div>
                </div>
                <div class="social-btns grid-container">
                	<div class="grid-12">
                		<ul>
	                		<li id="fbToggle">
	                			<a href="javascript:void(0)"><img class="toggle-on" src="/images/buttons/social_btn_fb.png"/></a>
	                			<a href="#"><img class="toggle-off displayNone" src="/images/buttons/social_btn_fb_active.png"/></a>
	                		</li>
	                		<li id="scToggle">
	                			<a href="javascript:void(0)"><img class="toggle-on" src="/images/buttons/social_btn_sc.png"/></a>
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
	                <!-- CHANGE: grid-container :: grid-container-pad0 !-->
	                <!-- <div class="grid-container-pad0">
	                	CHANGE: event-date grid12 :: event-date full-width -->
	                	<!-- CHANGE: Today :: <span>Today</span> -->
	                		<!-- CHANGE: class="grid-container" :: class="cal-container" -->
	                   	<!--div id="eventDate" class="event-date full-width"><span>Today</span></div>
	                    <div class="cal-container">
	                        <div id="eventTime" class="event-time grid-3">7:30pm</div>
	                        <div class="grid-7">
	                        	<div id="bandName" class="band-name full-width">The Greatest Band in the World</div>
	                        	<div id="venueName" class="venue-name full-width"><a>Lounge #1</a></div>
	                        </div>
	                        <div class="add-to-cal grid-2"><a class="ico-calendar"></a></div>
	                    </div
	                </div>-->
	                <!--div class="grid-container-pad0">
	                    <div id="eventDate" class="event-date full-width"><span>Today</span></div>
	                    <div class="cal-container">
	                        <div id="eventTime" class="event-time grid-3">7:30pm</div>
	                        <div class="grid-7">
	                        	<div id="bandName" class="band-name full-width">The Greatest Band in the World</div>
	                        	<div id="venueName" class="venue-name full-width"><a>Lounge #1</a></div>
	                        </div>
	                        <div class="add-to-cal grid-2"><a class="ico-calendar"></a></div>
	                    </div>
	                    <div class="cal-container">
	                        <div id="eventTime" class="event-time grid-3">7:30pm</div>
	                        <div class="grid-7">
	                        	<div id="bandName" class="band-name full-width">The Greatest Band in the World</div>
	                        	<div id="venueName" class="venue-name full-width"><a>Lounge #1</a></div>
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
	sxswObject.init();
</script>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-39340604-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</html>
