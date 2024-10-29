<?php
// If this file is called directly, exit.
if ( !defined( 'ABSPATH' ) ) { exit(); }

/**
pulling affiliate links data from database tables
**/
class am_hili_links_data {
	
	private $order_by = "";
	public $hili_ob = "";
		
	public function __construct(){
		//setting links order
		if (isset($_GET['ob'])){
			$hili_ob = $_GET['ob'];
			switch($hili_ob){
			case 'advertiser':
				$order_by = AM_HILI_ADVERTISERS.".advertiser_name ASC";
				break;
			case 'category':
				$order_by = AM_HILI_CATEGORIES.".category_name ASC";
				break;
			case 'added_on':
				$order_by = AM_HILI_LINKS.".added_on ASC";
				break;
			case 'active':
				$order_by = AM_HILI_LINKS.".active ASC";
				break;
			case 'clicks':
				$order_by = "totat_clicks DESC";
				break;		
			}
		}	
		else
		{
			$order_by = AM_HILI_LINKS.".link_text ASC";
			$hili_ob = 'name';
		}
		
		$this->order_by = $order_by;
		$this->hili_ob = $hili_ob;
	}
	
	public	function do_get_data($link_type="l"){
		
		global $wpdb;
				
		$amHiliSql = "select ".AM_HILI_LINKS.".*, 
				  ".AM_HILI_POSTS.".post_title,
				  ".AM_HILI_ADVERTISERS.".advertiser_name,
				  ".AM_HILI_CATEGORIES.".category_name,
				  ".AM_HILI_LINKS.".link_id as link_id, 
				  ".AM_HILI_LINKS.".added_on as added_on, 
				  SUM(".AM_HILI_STATS.".clicks) as totat_clicks
				  from ".AM_HILI_LINKS." 
				  LEFT JOIN ".AM_HILI_POSTS." ON ".AM_HILI_LINKS.".post_id = ".AM_HILI_POSTS.".ID 
				  LEFT JOIN ".AM_HILI_ADVERTISERS." ON ".AM_HILI_LINKS.".adv_id = ".AM_HILI_ADVERTISERS.".adv_id 
				  LEFT JOIN ".AM_HILI_CATEGORIES." ON ".AM_HILI_LINKS.".cat_id = ".AM_HILI_CATEGORIES.".cat_id 
				  LEFT JOIN ".AM_HILI_STATS." ON ".AM_HILI_LINKS.".link_id = ".AM_HILI_STATS.".link_id 
				  where link_type='$link_type' and ".AM_HILI_LINKS.".link_text !='' and ".AM_HILI_LINKS.".link_url !='' and ".AM_HILI_LINKS.".blog_id =".BLOG_ID."
				  GROUP BY ".AM_HILI_LINKS.".link_id
				  order by ".$this->order_by;
	
		return $wpdb->get_results($amHiliSql);
		}

}