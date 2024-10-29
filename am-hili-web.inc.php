<?php
// If this file is called directly, exit.
if ( ! defined( 'ABSPATH' ) ) { exit(); }

//getting required vars
$am_hili_base = basename(dirname($_SERVER['REQUEST_URI']));
$am_hili_base_uri = basename($_SERVER['REQUEST_URI']);

//checking if the page is the plugin page itself, if yes this file will not be loaded
if (plugins_url($am_hili_base) == AM_HILI_URL)
return;

//checking if there are options for the affiliate links
if ($am_hili_options = json_decode(get_am_hili_options('am_hili_options'))):

//checking if the current page is affiliate link page to include the redirect file
if(trim($am_hili_base_uri)!="" && (($am_hili_options->link_type == 'cloak' && $am_hili_base == $am_hili_options->cloak_page)
	or ($am_hili_options->link_type == 'short' && '!' == $am_hili_base_uri[0]))){
	require (dirname( __FILE__ ) . "/am-hili-redirect.inc.php");
	return;
}

require dirname(__FILE__)."/classes/class.web.inc.php";
new am_hili_web();

endif;