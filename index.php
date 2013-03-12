<?php 
	error_reporting(E_ALL);
 	ini_set("display_errors", 1);
	$server = explode('www.',$_SERVER['SERVER_NAME']);
	
	$server = (isset($server[1])) ?  $server[1] : $server[0];
	
	switch($server){
		case 'pluggedin.rga.com':
			$FBID = '439511386129295';
			$soundCloudID = '37b4cbf041d27eafb17741805c38ceda';
			$soundCloudRedirect = 'http://pluggedin.rga.com/soundcloudAuth/';
			define('mongoServer','mongodb://dbuser:fj47FH47hfh@ds053607.mongolab.com:53607/pluggedin');
		break;
		default:
			$FBID = '456738747726141';
			$soundCloudID = '303569302e749627d95c37b8b1666cbb';
			$soundCloudRedirect = 'http://pluggedin.com/soundcloudAuth';
			define('mongoServer','mongodb://dbuser:fj47FH47hfh@ds053607.mongolab.com:53607/pluggedin');//define('mongoServer', 'mongodb://127.0.0.1');
		break;
	}

	include_once('service/mongoControl.php');
	
	try{
		$md = new mongoDBcontrol('pluggedIn'); }
	catch (Exception $e){
		echo '		
			<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
			<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
			<title>Please try again</title>
			<style>
				body{
					background-color:#000;	
				}
			</style>
			</head>
			
			<body>
				<div style="margin:0 auto; width:640px;"><img src="images/plugged_in_error.jpg" /></div>
			</body>
			</html>';
		return false;
	}
	
	
	$results = $md->find('bandList');
		$results = iterator_to_array($results);
		
	$patern = "/[-!$%^&*()_+|~=`{}\[\]:\";'<>?,.\/]|@| /";
	foreach($results as $key => $value){
		$gigs = $results[$key]['response']['gigs'];
	}
	$html = '';
	$searchArray = array();
	foreach($gigs as $key => $gig){
		$cleanBandName = preg_replace($patern,'',$gig['band_name']);
		$cleanVenueName = preg_replace($patern,'',$gig['venue_name']);
		
		array_push($searchArray,$gig['band_name']);
		array_push($searchArray,$gig['venue_name']);
		
		$html .= '<div class="full-width gig '.$cleanBandName.' '.$cleanVenueName.'"><div id="eventTime" class="event-time grid-3">'.$gig['start_time'].'</div><div class="grid-7"><div id="bandName" class="band-name full-width">'.$gig['band_name'].'</div><div id="venueName" class="venue-name full-width"><a>'.$gig['venue_name'].'</a></div></div><div class="add-to-cal grid-2"><a class="ico-calendar"></a></div><div class="gigInfo"><input type="hidden" name="date" value="'.$gig['date'].'" /></div></div>';
	}
	
	$searchArray = array_unique($searchArray);
	
	print_r($html); return false;
	
	/*?>
	gigs = sxswMusic[n].response.gigs;
	
	var pattern = /[-!$%^&*()_+|~=`{}\[\]:";'<>?,.\/]|@| /g,
				cleanBandName = gig.band_name.replace(pattern,''),
				cleanVenueName = gig.venue_name.replace(pattern,'');
				
				html = '<div class="full-width gig '+cleanBandName+' '+cleanVenueName+'"><div id="eventTime" class="event-time grid-3">'+gig.start_time+'</div><div class="grid-7"><div id="bandName" class="band-name full-width">'+gig.band_name+'</div><div id="venueName" class="venue-name full-width"><a>'+gig.venue_name+'</a></div></div><div class="add-to-cal grid-2"><a class="ico-calendar"></a></div><div class="gigInfo"><input type="hidden" name="date" value="'+gig.date+'" /></div></div>'<?php */
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title> Plugged In - R/GA </title>
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
                <div class="grid-12 pad-8">&nbsp;</div>
            </div>
            <div id="filters" class="full-width">
                <div class="grid-container">
                    <div class="grid-12">
                    	<h1 class="connect-lbl">Connect to local bands you like playing nearby</h1>
                    </div>
                </div>
                <div class="social-btns grid-container">
                	<div class="grid-2">&nbsp;</div>
                	<div class="grid-4">
                		<input type="button" class="ico-font facebook" value="f"/>
                	</div>
                	<div class="grid-4">
                		<input type="button" class="ico-font soundcloud" value="!"/>
                	</div>
                	<div class="grid-2">&nbsp;</div>
                <div class="divider grid-container">
                    <div class="grid-5"><hr/></div>
                    <div class="grid-2"><h2>or</h2></div>
                    <div class="grid-5"><hr/></div>
                </div>
                <div class="search-bar grid-container">
                    <div class="grid-12">
                    	<div class="">
							<input type="text" id="search-form" placeholder="Search bands or local venues" />
						</div>
						<div class="">
							<input type="button" class="ico-font" value="s"/>
						</div>
					</div>
                </div>
            </div>
            <div id="masterList" class="full-width"> <!-- preferred name: schedule -->
                <div class="grid-container">
                   <!-- <div id="eventDate" class="event-date grid-12">Today</div>
                    <div class="full-width">
                        <div id="eventTime" class="event-time grid-3">7:30pm</div>
                        <div class="grid-7">
                        	<div id="bandName" class="band-name full-width">The Greatest Band in the World</div>
                        	<div id="venueName" class="venue-name full-width"><a>Lounge #1</a></div>
                        </div>
                        <div class="add-to-cal grid-2"><a class="ico-calendar"></a></div>
                    </div>-->
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
	var phpGigHTML = '<?php echo json_encode($results); ?>';
	var phpGigHTML = <?php echo json_encode($results); ?>;
	sxswObject.init();
</script>
</html>
