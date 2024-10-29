<?php
// If this file is called directly, exit.
if ( ! defined( 'ABSPATH' ) ) { exit(); }
?>

<?php $showHiliAddEditBtn = true; include dirname(__FILE__)."/am-hili-links.menu.php";?>
<h3><?php _e('AM-HILI (Category)','am_hili');?> - <?php echo strtoupper($_GET['act']);?></h3>
<script>
	function checkHiliForm(){
		if(jQuery("#category_name").val() == ""){
			alert('<?php _e('Category name is required','am_hili');?>');
		return false;
		}
	return true;
	}
</script>
<form action="<?php echo AM_HILI_ADMIN_URL;?>&inc=categories&do=save" method="post" onsubmit="return checkHiliForm()">
<?php wp_nonce_field( 'am_hili_nonce','_am_hili_');?>
<?php
//getting category data if the action is edit
if (isset($_GET['act']) and $_GET['act']=="edit" and isset($_GET['cat_id'])){
	
global $wpdb;	
if($hiliRow = $wpdb->get_row("select * from ".AM_HILI_CATEGORIES." where cat_id='$_GET[cat_id]'")){ ?>
<input type="hidden" name="cat_id" value="<?php echo $hiliRow->cat_id;?>" />

<?php };};?>

<input type="hidden" name="act" value="<?php echo (isset($hiliRow))?"edit":"add";?>" />
<table class="widefat amhiliTable" cellspacing="0">
<tbody>
	
	<tr>
    	<th style="width:50px"><?php _e('Name:','am_hili');?></th>
        <td><input type="text" name="category_name" id="category_name" style="width:75%" value="<?php echo (isset($hiliRow))?$hiliRow->category_name:"";?>"/></td>
    </tr>

</tbody>
</table> 
<br/>
<input type="submit" class="button" value="<?php echo ($_GET['act']=="add")?__('Save','am_hili'):__('Update','am_hili');?>" />
</form>  