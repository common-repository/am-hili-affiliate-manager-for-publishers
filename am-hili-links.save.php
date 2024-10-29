<?php
// If this file is called directly, exit.
if (!defined( 'ABSPATH' )) { exit(); }

//check if the user is logged-in otherwise exit
if (!is_user_logged_in() or !isset($_POST['act'])){exit();};

if (!wp_verify_nonce( $_POST['_am_hili_'],'am_hili_nonce')){exit();};

global $wpdb;


if(isset($_POST['link_url'])){

//adding new adevertiser add while adding a link	
	if ($_POST['adv_id']=="new_advertiser" and $wpdb->query($wpdb->prepare("insert into ".AM_HILI_ADVERTISERS." (advertiser_name) values  ('%s')",$_POST['new_advertiser']))){
		$_POST['adv_id'] = $wpdb->insert_id;
	}
//adding new category add while adding a link	
	if ($_POST['cat_id']=="new_category" and $wpdb->query($wpdb->prepare("insert into ".AM_HILI_CATEGORIES." (category_name) values ('%s')",$_POST['new_category']))){
		$_POST['cat_id'] = $wpdb->insert_id;
	}

//managing custom link (link types: c = custom, a = auto, l = link in post)
if ($_POST['link_type']=="c"){	

//addning new link
	if ($_POST['act']=="add"){
		
		//resetting default link for all links, if one is selected. the default link will replace none deactived or deleted affiliate links
		if ($_POST['default_affiliate']=="yes")
		$wpdb->query($wpdb->prepare("update ".AM_HILI_LINKS." set default_affiliate='%s'",'no'));
		
		//inserting a link
		$wpdb->query($wpdb->prepare("insert into ".AM_HILI_LINKS."  (link_url, link_text, link_image, adv_id, cat_id, blog_id, default_affiliate, link_type) values  ('$_POST[link_url]', '$_POST[link_text]', '$_POST[link_image]', '$_POST[adv_id]', '$_POST[cat_id]', '".BLOG_ID."', '$_POST[default_affiliate]',  '%s')",'c'));
}

//updating a link	
	if ($_POST['act']=="edit"){
		//resetting default link for all links, if one is selected. the default link will replace none deactived of deleted affiliate links 
		if ($_POST['default_affiliate']=="yes")
		$wpdb->query($wpdb->prepare("update ".AM_HILI_LINKS." set default_affiliate='%s'",'no'));
		
		//updaing the link data
		$wpdb->query($wpdb->prepare("update ".AM_HILI_LINKS." set link_url='$_POST[link_url]', link_text='$_POST[link_text]', link_image='$_POST[link_image]', adv_id=$_POST[adv_id],cat_id='$_POST[cat_id]', default_affiliate='$_POST[default_affiliate]' where link_id=%d",$_POST['link_id']));
	}
}

//managing auto insert link	
if ($_POST['link_type']=="a"){	

//collecting the cat ids where the link will be inserted
	if (count($_POST['apply_on_categories'])>0)
	$_POST['apply_on_categories'] = ','.implode(',',$_POST['apply_on_categories']).',';

//adding new link					  
	if ($_POST['act']=="add"){
		$wpdb->query($wpdb->prepare("insert into ".AM_HILI_LINKS."  (link_url, link_text, adv_id, cat_id, blog_id, link_keywords, link_priority, apply_on_categories, link_type) values ('$_POST[link_url]', '$_POST[link_text]', '$_POST[adv_id]', '$_POST[cat_id]', '".BLOG_ID."','$_POST[link_keywords]', '$_POST[link_priority]', '$_POST[apply_on_categories]', '%s')",'a'));
	}

//updating a link
	if ($_POST['act']=="edit"){		
		$wpdb->query($wpdb->prepare("update ".AM_HILI_LINKS." set link_url='$_POST[link_url]', link_text='$_POST[link_text]', adv_id=$_POST[adv_id], cat_id='$_POST[cat_id]', link_keywords='$_POST[link_keywords]', link_priority='$_POST[link_priority]', apply_on_categories='$_POST[apply_on_categories]' where link_id=%d",$_POST['link_id']));
	}
	
}
}

//returining to appreciate list of links page, depening on the type of the link
if ($_POST['link_type']=="c")
	wp_safe_redirect(AM_HILI_ADMIN_URL."&inc=links-custom");	
elseif ($_POST['link_type']=="a")
	wp_safe_redirect(AM_HILI_ADMIN_URL."&inc=links-auto");
else
	wp_safe_redirect(AM_HILI_ADMIN_URL);

