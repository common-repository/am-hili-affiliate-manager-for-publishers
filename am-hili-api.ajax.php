<?php
// If this file is called directly, exit.
if (!defined( 'ABSPATH' )) { exit(); }

if (!is_user_logged_in() or !isset($_POST['_am_hili_'])){return;};
if (!wp_verify_nonce( $_POST['_am_hili_'],'am_hili_nonce')){return;};

require_once(dirname( __FILE__ ) . '/am-hili-updates.inc.php');

$amhili_update_result = am_hili_check_plugin_update($_POST['am_hili_api_key']);

if(isset($amhili_update_result->act)){
	if($amhili_update_result->act == "update")
		update_am_hili_options('am_hili_api_key', trim($_POST['am_hili_api_key']));
	else
		delete_am_hili_options('am_hili_api_key');
}

if(isset($amhili_update_result->info)){
	echo "info|".$amhili_update_result->info;
} else {
	echo "done";
}
exit();