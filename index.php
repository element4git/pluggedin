<?php 
	error_reporting(E_ALL);
 ini_set("display_errors", 1);

	include_once('service/mongoControl.php');
	$md = new mongoDBcontrol('pluggedIn');
	$results = $md->find('bandList');
		$results = iterator_to_array($results);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title> TITLE </title>
<link rel="stylesheet" type="text/css" href="css/global.css" />
<link rel="stylesheet" type="text/css" href="css/ui-lightness/jquery-ui-1.10.1.custom.min.css" />
<!--[if lt IE 7]>
	<link rel="stylesheet" type="text/css" href="css/ie.css" />
<![endif]-->
</head>
<body>
	<div id="mainContainer" class="print">
		<div id="mainContent">
        	<div id="socialConnect">
            	<div id="facebook"> f </div>
            </div>
			Search a band <input id="bandSearch" />
            <div id="sxswMusic">
<?php 
	//generate HTML
	foreach($results as $key=>$value) $gig = $results[$key]['response']['gigs'];
	
	
	foreach($gig as $key=>$value){
		$html = '<div class="gig '.str_replace(' ','_',$gig[$key]['band_name']).' '.str_replace(' ','_',$gig[$key]['venue_name']).'">
					<div class="time">'.$gig[$key]['start_time'].'</div><div class="gigInfo"><span class="band">'.$gig[$key]['band_name'].'</span><span class="venue">'.$gig[$key]['venue_name'].'</span></div><div class="calendar"></div>
				</div>';
	
	echo $html;
	}
?>
            </div>
		</div>
	</div>

<div id="fb-root"></div>
</body>
<script src="http://connect.facebook.net/en_US/all.js"></script>
<script>
  FB.init({
    appId  : '205806419459572',
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
