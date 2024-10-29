<?php
// If this file is called directly, exit.
if (!defined( 'ABSPATH' )) { exit(); }

//check if the user is logged-in otherwise exit
if (!is_user_logged_in() or !isset($_POST['act'])){exit();};

if (!wp_verify_nonce( $_POST['_am_hili_'],'am_hili_nonce')){exit();};

global $wpdb;

//adding a category
if ($_POST['act']=="add"){
	$wpdb->query($wpdb->prepare("INSERT INTO ".AM_HILI_CATEGORIES." (cat_id, category_name) VALUES (%d, '$_POST[category_name]')",NULL));
}

//updating a category
if ($_POST['act']=="edit" and isset($_POST['cat_id'])){
	
	$wpdb->query($wpdb->prepare("update ".AM_HILI_CATEGORIES." set category_name = '$_POST[category_name]' where cat_id=%d",$_POST['cat_id']));
}

//returning to categories list
wp_safe_redirect( AM_HILI_ADMIN_URL."&inc=categories" );
