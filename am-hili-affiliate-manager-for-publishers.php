<?php
/**
 * Plugin Name: AM-HiLi
 * Plugin URI:  https://am-plugins.com/hili
 * Description: Affiliate manager for publishers . 
 * Version:     1.0
 * Author:      Ayoub Media
 * Author URI:  https://www.ayoubmedia.com/
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */
 
// If this file is called directly, exit.
if ( !defined( 'ABSPATH' ) ) { exit(); };


global $wpdb;
define('AM_HILI_FILE',basename(__FILE__));
define('AM_HILI_PATH',dirname(__FILE__));
define('AM_HILI_URL',plugins_url('',__FILE__));
define('AM_HILI_ADMIN_PAGE','am-hili-affiliate-manager-for-publishers');
define('AM_HILI_ADMIN_URL',get_admin_url().'admin.php?page=am-hili-affiliate-manager-for-publishers');
define('BLOG_ID', get_current_blog_id());

//define tables 
define('AM_HILI_LINKS', "am_hili_affiliate_links");
define('AM_HILI_ADVERTISERS', "am_hili_affiliate_advertisers");
define('AM_HILI_CATEGORIES', "am_hili_affiliate_categories");
define('AM_HILI_BOOKKEEPING', "am_hili_bookkeeping");
define('AM_HILI_OPTIONS', "am_hili_options");
define('AM_HILI_POSTS',$wpdb->prefix . "posts");
define('AM_HILI_STATS', "am_hili_affiliate_statistics");


/* Runs when plugin is activated */
register_activation_hook(__FILE__,'am_hili_plugin_install'); 

/* Runs on plugin uninstall*/
register_uninstall_hook( __FILE__, 'am_hili_plugin_remove' );

if (!function_exists('am_hili_plugin_install')):
	function am_hili_plugin_install() {
		
		require_once dirname( __FILE__ ) . "/classes/class-install-remove-plugin.inc.php";
		$am_hili_handle_tables = new am_hili_create_drop_tables();
		$am_hili_handle_tables->create_am_hili_tables();
		
	}
endif;

/* Runs on plugin uninstall*/		
if (!function_exists('am_hili_plugin_remove')):
	function am_hili_plugin_remove() {
		
		require_once dirname( __FILE__ ) . "/classes/class-install-remove-plugin.inc.php";
		$am_hili_handle_tables = new am_hili_create_drop_tables();
		$am_hili_handle_tables->drop_am_hili_tables();
		
	}
endif;

//required  (am-hili function file) for get and update options
require_once (dirname( __FILE__ ) . "/am-hili-functions.inc.php");


//load required file for admin and website
if (is_admin()){
	require_once (dirname( __FILE__ ) . "/am-hili-admin.inc.php");
}
else {
	require_once (dirname( __FILE__ ) . "/am-hili-web.inc.php");
}