<?php
	include_once('mongoControl.php');
	$sxsw_api_url = 'http://www.threechords.org/sxsw-api/index.php/api/getallgigs';
	define('mongoServer', 'mongodb://127.0.0.1');
	//define('mongoServer', 'mongodb://dbuser:fj47FH47hfh@ds041167.mongolab.com:41167/pluggedin');
	
    $ch=curl_init(); 
    curl_setopt($ch, CURLOPT_URL, $sxsw_api_url); 
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
    $response = curl_exec ($ch); 
    curl_close ($ch); 
	
    //$bandlist = json_decode($response);
	$bandlist = json_decode($_REQUEST['bandlist']);
	
	//print_r($bandlist); return false;
	
	$md = new mongoDBcontrol('pluggedin');
	
	$md->insert('bandList',$bandlist);
	
	echo 'done';
	
?>