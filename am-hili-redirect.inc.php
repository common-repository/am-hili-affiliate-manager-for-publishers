<?php
//redirection for clocked or shortened affiliate links

// If this file is called directly, exit.
if ( ! defined( 'ABSPATH' ) ) { exit(); }

//check settings options
if (!isset($am_hili_options)){ return; };

$link_url = "";

if($am_hili_options->link_type == "cloak"){
	$link_text = str_replace('-',' ',trim($am_hili_base_uri));
	if($hiliRow = $wpdb->get_row($wpdb->prepare("select link_url,link_text,link_id from ".AM_HILI_LINKS." where link_text='%s'",$link_text))){
		$link_url = $hiliRow->link_url;
		$link_id = $hiliRow->link_id;
		}
}

if($am_hili_options->link_type == "short"){
	$hiIDs = explode('!',$am_hili_base_uri);
	$link_id = base64_decode($hiIDs[1]);
	
	if (is_numeric($link_id)){	
		if($hiliRow = $wpdb->get_row($wpdb->prepare("select link_url,link_id from ".AM_HILI_LINKS." where link_id=%d",$link_id))){	
		$link_url = $hiliRow->link_url;
		$link_id = $hiliRow->link_id;
		}
	}
}

//handling the statistics
if (trim($link_url)!="" and isset($link_id)){
	
	include_once dirname(__FILE__)."/classes/class-statistics.inc.php";
	$am_hili_stats = new am_hili_do_statistics($link_id);
	
}

if (trim($link_url)=="")
$link_url = get_site_url();

//reditercting to the advertiser website
header( "X-Robots-Tag: noindex, nofollow", true );
header( "Location: " .  $link_url, $am_hili_options->redirect_code );
exit();
