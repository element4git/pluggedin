<?php
error_reporting(E_ALL);
 	ini_set("display_errors", 1);
	$server = explode('www.',$_SERVER['SERVER_NAME']);
	
	$server = (isset($server[1])) ?  $server[1] : $server[0];
	
	date_default_timezone_set('America/New_York');
	
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
		die();
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
		
		$searchArray[] = $gig['band_name'];
		$searchArray[] = $gig['venue_name'];
		
		//$html .= '<div class="full-width gig '.$cleanBandName.' '.$cleanVenueName.'"><div id="eventTime" class="event-time grid-3">'.$gig['start_time'].'</div><div class="grid-7"><div id="bandName" class="band-name full-width">'.$gig['band_name'].'</div><div id="venueName" class="venue-name full-width"><a>'.$gig['venue_name'].'</a></div></div><div class="add-to-cal grid-2"><a class="ico-calendar"><form name="gigInfo"><input type="hidden" name="band" value="'.$gig['band_name'].'" /><input type="hidden" name="venue" value="'.$gig['venue_name'].'" /><input type="hidden" name="date" value="'.$gig['date'].'" /><input type="hidden" name="startTime" value="'.$gig['start_time'].'" /></form></a></div></div>';
		$convert =  strtotime($gig['start_time']);
		$convertEnd =  strtotime($gig['end_time']);
		
		$myDate = str_replace('-','',$gig['date']);
		
	
		$new_time = date('His', $convert);
		$new_timeEnd = date('His', $convertEnd);
		$new_timeCo = date('H:i:s', $convert);
		
		$html .='<div class="cal-container gig '.$cleanBandName.' '.$cleanVenueName.'"><div id="eventTime" class="event-time grid-3">'.$gig['start_time'].' </div><div class="grid-7"><div id="bandName" class="band-name full-width">'.$gig['band_name'].'</div><div id="venueName" class="venue-name full-width"><a>'.$gig['venue_name'].'</a></div></div><div class="add-to-cal grid-2"><a class="ico-calendar"><form name="gigInfo"><input type="hidden" name="text" value="'.$gig['band_name'].'" /><input type="hidden" name="location" value="'.$gig['venue_name'].'" /><input type="hidden" name="dates" value="'.$myDate.'T'.$new_time.'/'.$myDate.'T'.$new_timeEnd.'" /></form></a></div><div class="date"><input type="hidden" value="'.$gig['date'].'T'.$new_timeCo.'" /></div></div>';
		
	}
	
	$html = str_replace("'","\\'",$html);
	
	$html =  '$(\'<div id="gightml">'.$html.'</div>\')';
	
	//print_r($html); return false;
	
	$searchArray = array_unique($searchArray);
	
	$searchArray = array_values($searchArray);
  ?>