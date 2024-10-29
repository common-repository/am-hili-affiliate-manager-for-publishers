<?php
// If this file is called directly, exit.
if (!defined( 'ABSPATH' )) { exit(); }

//check if the user is logged-in otherwise exit
if (!is_user_logged_in() or !isset($_POST['act'])){exit();};

if (!wp_verify_nonce( $_POST['_am_hili_'], 'am_hili_nonce')){exit();};

global $wpdb;

//delete link - request from links list (jQuery request)
if ($_POST['act']=="delete"){
	$wpdb->query($wpdb->prepare("delete from ".AM_HILI_LINKS." where link_id=%d",$_POST['link_id']));
	echo "done";
	exit();
}

//change the status of a link (active/not active) request from links list (jQuery request)
if ($_POST['act']=="status" and isset($_POST['link_id']) and isset($_POST['active'])){
	$wpdb->query($wpdb->prepare("update ".AM_HILI_LINKS." set active='$_POST[active]' where link_id=%d",$_POST['link_id']));
	echo "done";
	exit();
}
