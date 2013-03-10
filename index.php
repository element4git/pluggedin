<!--<?php 
	/*
	error_reporting(E_ALL);
	ini_set("display_errors", 1);

	switch($_SERVER['SERVER_NAME']){
		case 'pluggedin.azurewebsites.net':
			$FBID = '439511386129295';
			define('mongoServer','mongodb://dbuser:fj47FH47hfh@ds041167.mongolab.com:41167/pluggedin');
		break;
		default:
			$FBID = '205806419459572';
			define('mongoServer', 'mongodb://127.0.0.1');
		break;
	}

	include_once('service/mongoControl.php');
	$md = new mongoDBcontrol('pluggedIn');
	$results = $md->find('bandList');
		$results = iterator_to_array($results);
	*/
?>-->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
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
            <div id="logo" class="grid-container">
                <div class="logo-lrg grid-12">
                	<img src="/images/logos/pluggedin_logo.png" />
                </div>
                <div class="logo-sml grid-12 displayNone">Logo</div>
            </div>
            <div id="filters" class="full-width">
                <div class="grid-container">
                    <div class="grid-12">
                    	<span>Connect to local bands you like<br />playing nearby.</span>
                    </div>
                </div>
                <div class="social-btns grid-container">
                	<div class="grid-3 pad-1"></div>
                	<div class="grid-3">
                		<div class="btn">
                			<div class="icn ico-facebook"></div>
                		</div>
                	</div>
                	<div class="grid-3">
                		<div class="btn">
                			<div class="icn ico-soundcloud"></div>
                		</div>
                	</div>
                	<div class="grid-3 pad-1"></div>
                </div>
                <div class="devider-txt grid-container">
                    <div class="grid-12">
                    	<span>
                    		--- OR ---
                    	</span>
                    </div>
                </div>
                <div class="search-bar grid-container">
                    <div class="grid-12">
						<input type="text" id="search-form" placeholder="Search bands or local venues" />
						<input type="button" class="ico-font" value="s"/>
					</div>
                </div>
            </div>
            <div id="masterList" class="full-width"> <!-- preferred name: schedule -->
                <div class="grid-container">
                    <div class="grid-12">Today</div>
                    <div class="full-width">
                        <div class="grid-3">7:30pm</div>
                        <div class="grid-7">The Greatest Band in the World</div>
                        <div class="grid-2"><span class="ico-calendar"></span></div>
                    </div>
                </div>
                <div class="grid-container">
                    <div class="grid-12">Tomorrow</div>
                    <div class="full-width">
                        <div class="grid-3">8:00pm</div>
                        <div class="grid-7">The 2nd Greatest Band in the World</div>
                        <div class="grid-2"><span class="ico-calendar"></span></div>
                    </div>
                    <div class="full-width">
                        <div class="grid-3">8:30pm</div>
                        <div class="grid-7">The 3rdGreatest Band in the World</div>
                        <div class="grid-2"><span class="ico-calendar"></span></div>
                    </div>
                </div>
                <div class="displayNone">
                <?php 
					//generate HTML
					foreach($results as $key=>$value) $gig = $results[$key]['response']['gigs'];
					
					$pattern = '/&|\'| /';
					
					foreach($gig as $key=>$value){
						$cleanVenueName = preg_replace($pattern,'',$gig[$key]['venue_name']);
						$cleanBandName = preg_replace($pattern,'',$gig[$key]['band_name']);
						$html = '<div class="gig '.$cleanBandName.' '.$cleanVenueName.'">
									<div class="time">'.$gig[$key]['start_time'].'</div><div class="gigInfo"><span class="band">'.$gig[$key]['band_name'].'</span><span class="venue">'.$gig[$key]['venue_name'].'</span></div><div class="calendar"></div>
								</div>';
					
					echo $html;
					}
				?>
				</div>
            </div>
        </div>
        <div id="footer" class="full-width">
            <div class="wrapper">
	           	<div class="label">Presented by&nbsp;&nbsp;</div>
	            <div class="logo ico-rga-logo"> </div>
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
<script type="text/javascript" src="code/jquery-1.9.1.js"></script>
<script type="text/javascript" src="code/jquery-ui-1.10.1.custom.min.js"></script>
<script type="text/javascript" src="code/global.js"></script>
<script type="text/javascript" src="code/object.js"></script>
<script type="text/javascript">


var sxswMusic = <?php echo json_encode($results); ?>;
sxswObject.init();

</script>

</html>
