<?php
// If this file is called directly, exit.
if (!defined( 'ABSPATH' )) { exit(); }

//getting the link url
if(isset($_POST['id'])){
	
	$link_url = "";

	global $wpdb;
	if($hiliRow = $wpdb->get_row($wpdb->prepare("select link_url,link_id from ".AM_HILI_LINKS." where link_id=%d",$_POST['id']))){
	
	//updating statistics
	include_once dirname(__FILE__)."/classes/class-statistics.inc.php";
	$am_hili_stats = new am_hili_do_statistics($_POST['id']);
	
	$link_url = $hiliRow->link_url;
	}
	echo $link_url;
}