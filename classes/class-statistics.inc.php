<?php
// If this file is called directly, exit.
if ( !defined( 'ABSPATH' ) ) { exit(); }

/**
updating statistics
**/
class am_hili_do_statistics {
	
	public function __construct($link_id = NULL){
		
		global $wpdb;
		//updating statistics
		if ($link_id and is_numeric($link_id)){
		$wpdb->query($wpdb->prepare("INSERT INTO ".AM_HILI_STATS." (link_id, remote_ip) values ('$link_id', '%s')",$_SERVER['REMOTE_ADDR']));
		}
	}
}