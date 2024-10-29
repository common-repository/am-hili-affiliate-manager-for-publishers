<?php
// If this file is called directly, exit.
if ( !defined( 'ABSPATH' ) ) { exit(); }

//creating the required tables
class am_hili_create_drop_tables {
	
	public function create_am_hili_tables() {	
			global $wpdb;
		
		$charset = '';
		if ( !empty($wpdb -> CHARset) )
			$charset = "DEFAULT CHARACTER SET $wpdb->CHARset";
		if ( !empty($wpdb -> collate) )
			$charset .= " COLLATE $wpdb->collate";
	
		//main links table
		$am_hili_links = "CREATE TABLE am_hili_affiliate_links (
		  link_id MEDIUMINT(7) NOT NULL AUTO_INCREMENT,
		  post_id BIGINT(20) NOT NULL DEFAULT '0',
		  adv_id INT(7) NOT NULL DEFAULT '0',
		  cat_id INT(7) NOT NULL DEFAULT '0',
		  blog_id INT(3) NOT NULL DEFAULT '0',
		  link_text VARCHAR(255) NOT NULL DEFAULT '',
		  link_url VARCHAR(255) NOT NULL DEFAULT '',
		  link_image VARCHAR(255) NOT NULL DEFAULT '',
		  apply_on_categories TINYTEXT NOT NULL,
		  link_type CHAR(1) NOT NULL DEFAULT 'c',
		  link_priority TINYINT(1) NOT NULL DEFAULT '1',
		  notes TEXT NOT NULL,
		  added_on TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
		  added_by VARCHAR(255) NOT NULL DEFAULT '',
		  active VARCHAR(3) NOT NULL DEFAULT 'yes',
		  default_affiliate CHAR(3) NOT NULL DEFAULT 'no',
		  link_keywords TEXT NOT NULL,
		  PRIMARY KEY (link_id)
		) $charset;";
		
		//affiliate advertisers/providers table
		$am_hili_advertisers = "CREATE TABLE am_hili_affiliate_advertisers (
		  adv_id INT(5) NOT NULL AUTO_INCREMENT,
		  advertiser_name VARCHAR(255) NOT NULL DEFAULT '',
		  advertiser_telephone VARCHAR(255) NOT NULL DEFAULT '',
		  advertiser_email VARCHAR(255) NOT NULL DEFAULT '',
		  advertiser_website VARCHAR(255) NOT NULL DEFAULT '',
		  my_username VARCHAR(255) NOT NULL DEFAULT '',
		  notes TEXT NOT NULL,
		  added_on TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
		  added_by VARCHAR(255) NOT NULL DEFAULT '',
		  PRIMARY KEY (adv_id)
		) $charset;";
		
		//affiliate categories table
		$am_hili_categories = "CREATE TABLE am_hili_affiliate_categories (
		  cat_id INT(5) NOT NULL AUTO_INCREMENT,
		  category_name VARCHAR(255) NOT NULL DEFAULT '',
		  PRIMARY KEY (cat_id)
		) $charset;";
		
		//plugin options table
		$am_hili_options = "CREATE TABLE am_hili_options (
		  option_id INT(5) NOT NULL AUTO_INCREMENT,
		  owid INT(7) NOT NULL,
		  option_group VARCHAR(255) NOT NULL DEFAULT '',
		  option_name VARCHAR(255) NOT NULL DEFAULT '',
		  option_value VARCHAR(255) NOT NULL DEFAULT '',
		  PRIMARY KEY (option_id)
		) $charset;";
		
		//link statistics table
		$am_hili_affiliate_statistics = "CREATE TABLE am_hili_affiliate_statistics (
		  stats_id BIGINT(20) NOT NULL AUTO_INCREMENT,
		  link_id INT(7) NOT NULL,
		  post_id BIGINT(20) NOT NULL,
		  stats_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
		  remote_ip VARCHAR(20) NOT NULL DEFAULT '',
		  country CHAR(3) NOT NULL DEFAULT '',
		  clicks INT(7) NOT NULL DEFAULT '1',
		  PRIMARY KEY (stats_id)
		) $charset;";
		
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($am_hili_links);
		dbDelta($am_hili_advertisers);
		dbDelta($am_hili_categories);
		dbDelta($am_hili_options);		
		dbDelta($am_hili_affiliate_statistics);
		dbDelta($am_hili_bookkeeping);
		
		$wpdb->query($wpdb->prepare("insert into am_hili_options (option_group, option_name, option_value) 
		Values ('%s','am_hili_options', '".json_encode(array('link_type'=>'hide','cloak_page'=>'recommended','redirect_type'=>'302','am_hili_max_insert_links'=>'5','am_hili_replace_keywords'=>'3','am_hili_date_format'=>'m/d/Y'),JSON_UNESCAPED_UNICODE )."')",'links'));
	}

//removing tables when removing the plugin
	public function drop_am_hili_tables() {
			global $wpdb;
			$wpdb->query("DROP TABLE IF EXISTS am_hili_affiliate_links");
			$wpdb->query("DROP TABLE IF EXISTS am_hili_affiliate_advertisers");
			$wpdb->query("DROP TABLE IF EXISTS am_hili_affiliate_categories");
			$wpdb->query("DROP TABLE IF EXISTS am_hili_options");
			$wpdb->query("DROP TABLE IF EXISTS am_hili_affiliate_statistics");		
			$wpdb->query("DROP TABLE IF EXISTS am_hili_bookkeeping");		
	}	
}
?>