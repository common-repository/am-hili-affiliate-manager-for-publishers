<?php
// If this file is called directly, exit.
if (!defined( 'ABSPATH' )) { exit(); }

//check if the user is logged-in otherwise exit
if (!is_user_logged_in()){exit();};

if (!wp_verify_nonce( $_POST['_am_hili_'], 'am_hili_nonce')){exit();};

global $wpdb;

//inserting and returning the link id for the newely added link in the post
if($wpdb->query($wpdb->prepare("insert into ".AM_HILI_LINKS." (link_id, blog_id, link_type) values (null, ".BLOG_ID.", '%s')",'l'))){
echo $wpdb->insert_id;
}