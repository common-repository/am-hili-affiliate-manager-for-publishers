<?php
// If this file is called directly, exit.
if (!defined( 'ABSPATH' )) { exit(); }

//check if the user is logged-in otherwise exit
if (!is_user_logged_in() or !isset($_POST['act'])){exit();};

if (!wp_verify_nonce( $_POST['_am_hili_'], 'am_hili_nonce')){exit();};

global $wpdb;

//delete advertiser by post from advertisers list (jQuery request)
if ($_POST['act']=="delete" and isset($_POST['adv_id'])){
$wpdb->query($wpdb->prepare("delete from ".AM_HILI_ADVERTISERS." where adv_id=%d",$_POST['adv_id']));
echo "done";
exit();
}

//update advertiser by post from advertisers list (jQuery request)
if ($_POST['act'] == "save_advertiser"){ 
	$wpdb->query($wpdb->prepare("update ".AM_HILI_LINKS." set adv_id=$_POST[adv_id] where link_id=%d",$_POST['link_id']));
	echo "done";
	exit();
}

//getting list of advertisers for advertisers list (jQuery request)
if ($_POST['act'] == "get_advertisers"){ 
ob_start();
?>
			<form onsubmit="return saveAmHiLiCatAdv(this)">
            <input type="hidden" name="link_id" value="<?php echo $_POST['link_id'];?>" />
            <input type="hidden" name="act" value="save_advertiser"/>
            <input type="hidden" name="inc" value="advertisers"/>
            <input type="hidden" name="action" value="am_hili_ajax"/>
            <input type="hidden" name="_am_hili_" value="<?php echo wp_create_nonce('am_hili_nonce');?>"/>
            
            <select name="adv_id" size="1">
            <option value="0"><?php _e('None','am_hili');?></option>
            <?php 
			if($amHiliadvertisers = $wpdb->get_results($wpdb->prepare("select adv_id,advertiser_name from ".AM_HILI_ADVERTISERS." order by %s",'advertiser_name'))){ 
				foreach($amHiliadvertisers as $amHiliadvertiser){
			?>
            <option value="<?php echo $amHiliadvertiser->adv_id;?>" <?php echo($amHiliadvertiser->adv_id == $_POST['adv_id'])?"selected='selected'":"";?>><?php echo $amHiliadvertiser->advertiser_name;?></option>
            <?php };};?>
            </select>
            <input type="submit" class="button-primary" value="<?php _e('Save','am_hili');?>"/>
            </form>
<?php
ob_end_flush();
exit();
}