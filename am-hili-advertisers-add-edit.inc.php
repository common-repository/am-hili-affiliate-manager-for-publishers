<?php
// If this file is called directly, exit.
if ( !defined( 'ABSPATH' ) ) { exit(); }
?>

<?php $showHiliAddEditBtn = true; include dirname(__FILE__)."/am-hili-links.menu.php";?>
<h3><?php _e('AM-HILI (Affiliate advertiser)','am_hili');?> - <?php echo strtoupper($_GET['act']);?></h3>
<script>
	function checkHiliForm(){
		if(jQuery("#advertiser_name").val() == ""){
			alert('<?php _e('Advertiser name is required','am_hili');?>');
		return false;
		}
	return true;
	}
</script>
<form action="<?php echo AM_HILI_ADMIN_URL;?>&inc=advertisers&do=save" method="post" onsubmit="return checkHiliForm()">
<?php wp_nonce_field( 'am_hili_nonce','_am_hili_');?>
<?php
//checking if the action is edit and pull adevrtiser data from the database table

if (isset($_GET['act']) and $_GET['act']=="edit" and isset($_GET['adv_id'])){
	
global $wpdb;	
if($hiliRow = $wpdb->get_row("select * from ".AM_HILI_ADVERTISERS." where adv_id='$_GET[adv_id]'")){ ?>

<input type="hidden" name="adv_id" value="<?php echo $hiliRow->adv_id;?>" />
<?php };};?>

<input type="hidden" name="act" value="<?php echo (isset($hiliRow))?"edit":"add";?>" />
<table class="widefat amhiliTable" cellspacing="0">
<tbody>
	
	<tr>
    	<th style="width:50px"><?php _e('Name:','am_hili');?>*</th>
        <td><input type="text" name="advertiser_name" id="advertiser_name" style="width:75%" value="<?php echo (isset($hiliRow))?$hiliRow->advertiser_name:"";?>"/></td>
    </tr>
	
	<tr>
    	<th style="width:50px"><?php _e('Telephone:','am_hili');?></th>
        <td><input type="text" name="advertiser_telephone" id="advertiser_telephone" style="width:75%" value="<?php echo (isset($hiliRow))?$hiliRow->advertiser_telephone:"";?>"/></td>
    </tr>    
    
	<tr>
    	<th style="width:50px"><?php _e('E-Mail:','am_hili');?></th>
    	<td><input type="text" name="advertiser_email" id="advertiser_email" style="width:75%" value="<?php echo (isset($hiliRow))?$hiliRow->advertiser_email:"";?>"/>
        </td>
	</tr>

	<tr>
    	<th style="width:50px"><?php _e('Website:','am_hili');?></th>
    	<td><input type="text" name="advertiser_website" id="advertiser_website" style="width:75%" value="<?php echo (isset($hiliRow))?$hiliRow->advertiser_website:"";?>"/>
        </td>
	</tr>

	<tr>
    	<th style="width:50px"><?php _e('Username:','am_hili');?></th>
    	<td><input type="text" name="my_username" id="my_username" style="width:50%" value="<?php echo (isset($hiliRow))?$hiliRow->my_username:"";?>"/><br/><?php _e('Your username or email by the affiliate advertiser in case you lost your password.','am_hili');?>
        </td>
	</tr>
  
	<tr>
    	<th style="width:50px"><?php _e('Notes:','am_hili');?></th>
    	<td><textarea type="text" name="notes" id="notes" style="width:75%;height:100px"><?php echo (isset($hiliRow))?$hiliRow->my_username:"";?></textarea>
        </td>
	</tr>

</tbody>
</table> 
<br/>
<input type="submit" class="button" value="<?php echo ($_GET['act']=="add")?__('Save','am_hili'):__('Update','am_hili');?>" />
</form>  