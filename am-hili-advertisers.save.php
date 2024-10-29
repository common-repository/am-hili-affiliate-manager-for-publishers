<?php
// If this file is called directly, exit.
if (!defined( 'ABSPATH' )) { exit(); }

if (!is_user_logged_in() or !isset($_POST['_am_hili_']) or !isset($_POST['act'])){exit();};

if (!wp_verify_nonce( $_POST['_am_hili_'],'am_hili_nonce')){exit();};

global $wpdb;

//adding a advertiser
if ($_POST['act']=="add"){
$wpdb->query($wpdb->prepare("INSERT INTO ".AM_HILI_ADVERTISERS." (adv_id, advertiser_name, advertiser_telephone, advertiser_email, advertiser_website, my_username, notes, added_on) 
VALUES (%d, '$_POST[advertiser_name]', '$_POST[advertiser_telephone]', '$_POST[advertiser_email]', '$_POST[advertiser_website]', '$_POST[my_username]', '$_POST[notes]', CURRENT_TIMESTAMP)",NULL));
}

//updating a advertiser profile
if ($_POST['act']=="edit" and isset($_POST['adv_id'])){
	
	$wpdb->query($wpdb->prepare("update ".AM_HILI_ADVERTISERS." set 
	advertiser_name = '$_POST[advertiser_name]', advertiser_telephone = '$_POST[advertiser_telephone]', advertiser_email = '$_POST[advertiser_email]', advertiser_website = '$_POST[advertiser_website]', my_username = '$_POST[my_username]', notes = '$_POST[notes]'
	 where adv_id='%d'",$_POST['adv_id']));
}

//updating options
if ($advertisers = $wpdb->get_results("select adv_id, advertiser_name from ".AM_HILI_ADVERTISERS)){
	foreach ($advertisers as $advertiser){
		
		/*inserting advertiser name in am-hili options table for bookkeeping*/
		$option_name = $wpdb->_real_escape(strtolower(str_replace(' ','_',$advertiser->advertiser_name)));
		$option_value = $wpdb->_real_escape($advertiser->advertiser_name);
		$option_group = 'bookkeeping_income';
		$owid = $advertiser->adv_id;
		
		if ($option_row = $wpdb->get_row("select owid from ".AM_HILI_OPTIONS." where owid = '".$advertiser->adv_id."'")){
					
			$wpdb->query($wpdb->prepare("update ".AM_HILI_OPTIONS." set option_name='$option_name', option_value='$option_value' where owid = %d",$advertiser->adv_id));
			
		}
		else
		{
			
		$wpdb->query($wpdb->prepare("insert into ".AM_HILI_OPTIONS." (owid,option_group,option_name,option_value) values (%d,'$option_group', '$option_name', '$option_value')",$advertiser->adv_id));
					
		}
	}
}
//return to the list of advertisers
wp_safe_redirect( AM_HILI_ADMIN_URL."&inc=advertisers" );
