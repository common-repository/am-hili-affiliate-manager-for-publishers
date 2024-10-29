<?php
// If this file is called directly, exit.
if (!defined( 'ABSPATH' )) { exit(); }

//check if the user is logged-in otherwise exit
if (!is_user_logged_in() or !isset($_POST['act'])){exit();};

if (!wp_verify_nonce( $_POST['_am_hili_'], 'am_hili_nonce')){exit();};

global $wpdb;

//delete category - request from categories list (jQuery request)
if ($_POST['act']=="delete" and isset($_POST['cat_id'])){
	$wpdb->query($wpdb->prepare("delete from ".AM_HILI_CATEGORIES." where cat_id=%d",$_POST['cat_id']));
	echo "done";
	exit();
}

//save category - request from categories list (jQuery request)
if ($_POST['act'] == "save_category"){ 
	$wpdb->query($wpdb->prepare("update ".AM_HILI_LINKS." set cat_id=$_POST[cat_id] where link_id=%d",$_POST['link_id']));
	echo "done";
	exit();
}

//getting list of categories for categories list (jQuery request)
if ($_POST['act'] == "get_categories"){ 
ob_start();
?>
			<form onsubmit="return saveAmHiLiCatAdv(this)">
            <input type="hidden" name="act" value="save_category" />
            <input type="hidden" name="link_id" value="<?php echo $_POST['link_id'];?>" />
            <input type="hidden" name="inc" value="categories"/>
            <input type="hidden" name="action" value="am_hili_ajax"/>
            <input type="hidden" name="_am_hili_" value="<?php echo wp_create_nonce('am_hili_nonce');?>"/>
            <select name="cat_id" size="1">
            <option value="0"><?php _e('None','am_hili');?></option>
            <?php 
			if($amHiliCategories = $wpdb->get_results($wpdb->prepare("select cat_id, category_name from ".AM_HILI_CATEGORIES." order by %s",'category_name'))){ 
				foreach($amHiliCategories as $amHiliCategory){
			?>
            <option value="<?php echo $amHiliCategory->cat_id;?>" <?php echo($amHiliCategory->cat_id == $_POST['cat_id'])?"selected='selected'":"";?>><?php echo $amHiliCategory->category_name;?></option>
            <?php };};?>
            </select>
            <input type="submit" class="button-primary" value="<?php _e('Save','am_hili');?>"/>
            </form>
<?php
ob_end_flush();
exit();
}
