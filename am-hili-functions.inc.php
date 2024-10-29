<?php
// If this file is called directly, exit.
if ( ! defined( 'ABSPATH' ) ) { exit(); }

//getting options
//options used to store default settings  and connection between tables
if (!function_exists('get_am_hili_options')):
	function get_am_hili_options($option,$option_field='name') {
		global $wpdb;
		
		if (!$option)
		return;
		
		$option_results = null;
		
		if (($option_field=='id' or $option_field=='name') and $am_hili_option = $wpdb->get_row("select option_value from ".AM_HILI_OPTIONS." where option_{$option_field} = '$option'")){
			$option_results = $am_hili_option->option_value;
		} elseif ($am_hili_options = $wpdb->get_results("select option_id, option_name,option_value from ".AM_HILI_OPTIONS." where option_{$option_field} = '$option' order by option_value ASC")){
			foreach ($am_hili_options as $am_hili_option_value){
			$option_results[$am_hili_option_value->option_id] = $am_hili_option_value->option_value;
			}
		}
		return $option_results;
	}
endif;

//this part for admin only
if(is_admin()):

//updating options
if (!function_exists('update_am_hili_options')):
	function update_am_hili_options($option_name,$option_value,$option_group = '',$option_field='name') {
		global $wpdb;
		$query_option_group = "";
		
		if (!$option_name and $option_value)
		return;
		
		$option_name = $wpdb->_real_escape(strtolower(str_replace(' ','_',$option_name)));
		$option_value = $wpdb->_real_escape($option_value);
		$option_group = $wpdb->_real_escape($option_group);

		if (trim($option_group)!="")
		$query_option_group = "and option_group='$option_group'";
		
		if ($option_row = $wpdb->get_row($wpdb->prepare("select * from ".AM_HILI_OPTIONS." where option_name = '%s' $query_option_group",$option_name))){		
			$wpdb->query($wpdb->prepare("update am_hili_options set option_value='$option_value' where option_name='%s' $query_option_group",$option_name));
			return $option_row->option_id;
		}
		else
		{
		$wpdb->query($wpdb->prepare("insert into ".AM_HILI_OPTIONS." (option_group,option_name,option_value) values ('$option_group', '%s', '$option_value')",$option_name));
			return $wpdb->insert_id;
		}
	}
endif;

//deleting options 
if (!function_exists('delete_am_hili_options')):
	function delete_am_hili_options($option_name,$option_group = '') {
		global $wpdb;
		
		if (!$option_name)
		return;
		
		if (trim($option_group)!="")
		$query_option_group = "and option_group='$option_group'";
		
		if (!$wpdb->query($wpdb->prepare("delete from ".AM_HILI_OPTIONS." where option_name='%s' $query_option_group",$option_name)));		
	}
endif;

endif;