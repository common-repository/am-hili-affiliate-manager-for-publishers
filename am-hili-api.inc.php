<?php
// If this file is called directly, exit.
if ( ! defined( 'ABSPATH' ) ) { exit(); }
?>
<!--list of affiliate links in posts and pages-->
<script>
function checkAmHiliApiKeyForm(obj){
		if (jQuery("#am_hili_api_key").val()==""){
			alert("<?php _e('API Key is required.','am_hili');?>");
			return false;
		}
		checkAmHiliApiKey(obj);
		return false;
}
</script>
<?php include dirname(__FILE__)."/am-hili-links.menu.php";?>
<h3><?php _e('AM-HILI - API Key','am_hili');?></h3>
<?php
$am_hili_api_key = get_am_hili_options('am_hili_api_key');
?>
<form  name="am_hili_api_key_form" onsubmit="return checkAmHiliApiKeyForm(this)">
<input type="hidden" name="inc" value="api"/>
<input type="hidden" name="action" value="am_hili_ajax"/>
<input type="hidden" name="_am_hili_" value="<?php echo wp_create_nonce('am_hili_nonce');?>"/>            
<table class="widefat" cellspacing="0">
	<tr>
    	<td><b><?php _e('API Key','am_hili');?>:*</b> <input type="text" name="am_hili_api_key" id="am_hili_api_key" value="<?php echo (isset($am_hili_api_key))?$am_hili_api_key:"";?>" style="width:50%" data-required="yes"/> 
        <a href="https://am-plugins.com/hili"><?php _e('Get API Key','am_hili');?></a>
        </td>        
    </tr>
    <tr>
    	<td id="am_hili_api_key_info"></td>
    </tr>
</table>    
<br/>
<input type="submit" class="button" value="<?php _e('Save','am_hili');?>" />
</form>