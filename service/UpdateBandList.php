<?php
	include_once('mongoControl.php');
	$sxsw_api_url = 'http://www.threechords.org/sxsw-api/index.php/api/getallgigs';
	define('mongoServer', 'mongodb://127.0.0.1');
	
    $ch=curl_init(); 
    curl_setopt($ch, CURLOPT_URL, $sxsw_api_url); 
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
    $response = curl_exec ($ch); 
    curl_close ($ch); 
    $bandlist = json_decode($response);
	
	//print_r($bandlist);
	
	$md = new mongoDBcontrol('pluggedin');
	
	$md->insert('bandList',$bandlist);
	
	echo 'done';
	
?>