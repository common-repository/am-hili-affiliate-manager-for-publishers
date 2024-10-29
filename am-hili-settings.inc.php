<?php
// If this file is called directly, exit.
if ( ! defined( 'ABSPATH' ) ) { exit(); }
?>
<!--settings list-->

<?php $hideHiliAddEditBtn = true; include dirname(__FILE__)."/am-hili-links.menu.php";?>
<h3><?php _e('AM-HILI (Settings)','am_hili');?></h3>
<script>
	function checkHiliForm(){
		if(jQuery("#link_text").val() == "" || jQuery("#link_url").val() == ""){
			alert('<?php _e('Link text and url are required','am_hili');?>');
		return false;
		}
	return true;
	}
</script>
<?php
//getting saved values from options table
if ($am_hili_options = json_decode(get_am_hili_options('am_hili_options'))){

	$am_link_type = $am_hili_options->link_type;
	$am_hili_cloak_page = $am_hili_options->cloak_page;
	$am_hili_redirect_type = $am_hili_options->redirect_type;
	$am_hili_max_insert_links = $am_hili_options->am_hili_max_insert_links;
	$am_hili_replace_keywords = $am_hili_options->am_hili_replace_keywords;
	$am_hili_date_format = $am_hili_options->am_hili_date_format;

} else {
	
	//default setting falues
	$am_link_type = "hide";
	$am_hili_cloak_page = "recommended";
	$am_hili_max_insert_links = 5;
	$am_hili_replace_keywords = 5;
	$am_hili_redirect_type = 302;
	$am_hili_date_format = "m/d/Y";

}
?>
<form action="<?php echo AM_HILI_ADMIN_URL;?>&inc=settings&do=save" method="post" onsubmit="return checkHiliForm()">
<?php wp_nonce_field( 'am_hili_nonce','_am_hili_');?>
<table class="widefat amhiliTable amhiliSetting" cellspacing="0">
<tbody>
	<tr>
    	<th><b><?php _e('What to do with the affiliate links','am_hili');?></b></th>
    </tr>
	
	<tr>
        <td><input type="radio" name="am_hili[link_type]" id="link_type_hide" value="hide" <?php echo ($am_link_type=='hide')?"checked='checked'":"";?>/><label for="link_type_hide"><b><?php _e('Hide','am_hili');?></b></label> (<?php _e('Javascript will be used to open advertiser website','am_hili');?>)</td>
    </tr>
	
	<tr>
        <td><input type="radio" name="am_hili[link_type]" id="link_type_cloak" value="cloak" <?php echo ($am_link_type=='cloak')?"checked='checked'":"";?>/><label for="link_type_cloak"><b><?php _e('Cloak','am_hili');?></b></label>
        <?php echo get_site_url();?>/<input type="text" name="am_hili[cloak_page]" value="<?php echo $am_hili_cloak_page;?>" style="width:100px"/>/<?php _e('Affiliate link text','');?>
        <br/>
        <?php _e('Make sure that you don\'t have a page or post with the same name as cloak page.','am_hili');?>
        </td>
    </tr>
    
    <tr>
        <td><input type="radio" name="am_hili[link_type]" id="link_type_short" value="short" <?php echo ($am_link_type=='short')?"checked='checked'":"";?>/><label for="link_type_short"><b><?php _e('Shortening','am_hili');?></b></label>
        <?php echo get_site_url();?>/!XYZft4ew (<?php _e('Encoded affiliate ID','am_hili');?>)
        
        </td>
    </tr>
    
    <tr>
        <td>
        <?php _e('Redirection type','');?> 
        <select size="1" name="am_hili[redirect_type]">
        <option value="301" <?php echo($am_hili_redirect_type=='301')?"selected='selected'":"";?> >301 <?php _e('Moved Permanently','am_hili');?></option>
		<option value="302" <?php echo($am_hili_redirect_type=='302')?"selected='selected'":"";?>>302 <?php _e('Moved Temporarily (HTTP 1.0)','am_hili');?></option>
        <option value="307" <?php echo($am_hili_redirect_type=='307')?"selected='selected'":"";?>>307 <?php _e('Moved Temporarily (HTTP 1.1 Only)','am_hili');?></option>
        </select>
        <?php _e('For more information <a href="https://moz.com/learn/seo/redirection" target="_blank">click here</a>','am_hili');?>
        </td>
    </tr>
    
	<tr>
    	<th><b><?php _e('Auto insert affiliate links','am_hili');?></b></th>
    </tr>
    
    <tr>
    	<td>
		<?php _e('Maximum number of inserted links per post','am_hili');?>
        <input type="text" name="am_hili[am_hili_max_insert_links]" value="<?php echo $am_hili_max_insert_links;?>" style="width:40px"/>        
        </td>
    </tr>
    
    <tr>
    	<td>
		<?php _e('Number of the same keyword should be replaced','am_hili');?>
        <input type="text" name="am_hili[am_hili_replace_keywords]" value="<?php echo $am_hili_replace_keywords;?>" style="width:40px"/><br/>
        <i><?php _e('eg. you have an affiliate link with the keyword (website) and the same keyword (website) is repeated 5 times in a post. <br/>How many of them should be replaced with the affiliate link?','am_hili');?></i>
        </td>
    </tr> 
    
    <tr>
    	<th><b><?php _e('Date format','am_hili');?></b></th> 
    </tr>
    
    <tr>
        <td>
        <?php 
			$dateFormats = array("m/d/Y","d/m/Y","Y/d/m","Y/m/d");
			$dfNr = 0;
			foreach($dateFormats as $df){ 
			$dfNr++;
			?>
			<input type="radio" name="am_hili[am_hili_date_format]" id="am_hili_date_format_<?php echo $dfNr;?>" value="<?php echo $df;?>" <?php echo ($am_hili_date_format==$df)?"checked='checked'":"";?>/>
            <label for="am_hili_date_format_<?php echo $dfNr;?>"><?php echo date($df);?></label><br/>
			<?php
            }
			
			foreach($dateFormats as $df){ 
			$df = str_replace('/','-',$df);
			$dfNr++;
			?>
			<input type="radio" name="am_hili[am_hili_date_format]" id="am_hili_date_format_<?php echo $dfNr;?>" value="<?php echo $df;?>" <?php echo ($am_hili_date_format==$df)?"checked='checked'":"";?>/>
            <label for="am_hili_date_format_<?php echo $dfNr;?>"><?php echo date($df);?></label><br/>
			<?php
            }
		
		?>
        
        </td>  
    </tr>
</tbody>
</table> 
<br/>
<input type="submit" class="button" value="<?php _e('Save','am_hili');?>" />
</form>  