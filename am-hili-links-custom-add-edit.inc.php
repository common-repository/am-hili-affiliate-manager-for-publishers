<?php
// If this file is called directly, exit.
if ( ! defined( 'ABSPATH' ) ) { exit(); }
?>

<?php wp_enqueue_media();?>

<?php $showHiliAddEditBtn = true; include dirname(__FILE__)."/am-hili-links.menu.php";?>
<h3><?php _e('Custom affiliate link','am_hili');?> (<?php echo strtoupper($_GET['act']);?>)</h3>
<script>
	function checkHiliForm(){
		if(jQuery("#link_text").val() == "" || jQuery("#link_url").val() == ""){
			alert('<?php _e('Link text and url are required','am_hili');?>');
		return false;
		}
	return true;
	}
</script>
<form action="<?php echo AM_HILI_ADMIN_URL;?>&inc=links&do=save" method="post" onsubmit="return checkHiliForm()">
<?php wp_nonce_field( 'am_hili_nonce','_am_hili_');?>
<?php
global $wpdb;

//getting link data if the action is edit
if (isset($_GET['act']) and $_GET['act']=="edit" and isset($_GET['link_id'])){
if($hiliRow = $wpdb->get_row("select * from ".AM_HILI_LINKS." where link_id='$_GET[link_id]'")){ ?>
<input type="hidden" name="link_id" value="<?php echo $hiliRow->link_id;?>" />
<?php };};?>
<input type="hidden" name="link_type" value="c" />
<input type="hidden" name="act" value="<?php echo (isset($hiliRow))?"edit":"add";?>" />
<table class="widefat amhiliTable" cellspacing="0">
<tbody>
	
	<tr>
    	<th style="width:50px"><?php _e('Text:','am_hili');?>*</th>
        <td><input type="text" name="link_text" id="link_text" style="width:75%" value="<?php echo (isset($hiliRow))?$hiliRow->link_text:"";?>"/></td>
    </tr>
	
	<tr>
    	<th style="width:50px"><?php _e('Url:','am_hili');?>*</th>
        <td><input type="text" name="link_url" id="link_url" style="width:75%" value="<?php echo (isset($hiliRow))?$hiliRow->link_url:"";?>"/></td>
    </tr>    
    
	<tr>
    	<th style="width:50px"><?php _e('Image:','am_hili');?></th>
    	<td><input type="text" name="link_image" id="link_image" class="link_image" style="width:25%" value="<?php echo (isset($hiliRow))?$hiliRow->link_image:"";?>"/>
        <input type="button" onclick="gk_media_init('#link_image',this);" class="AmHiliImageBtn" value="<?php _e('Select image','am_hili');?>"/>
        <div id="amHiliImageHolder" style="display:<?php echo (isset($hiliRow) and trim($hiliRow->link_image)!='')?"block":"none";?>;float:left;padding:5px;border:1px solid #eee">
        <img id="am_hili_img" style="max-height:120px;max-width:120px; float:left" src="<?php echo (isset($hiliRow))?$hiliRow->link_image:"";?>"/>
        <br/>
        <span style="cursor:pointer;color:white;background:red;padding:2px;text-align:center;font-size:11px;line-height:11px;display:block" onclick="removeAmhilImage()"><?php _e('Remove','am_hili');?></span>
        </div>
        </td>
	</tr> 
     
    <tr>
    	<th style="width:50px"><?php _e('Advertiser:','am_hili');?></th>
        <td>
        	<input type="text" style="display:none;width:200px" value="" name="new_advertiser" id="new_advertiser"/>
        	<select name="adv_id" size="1" onchange="if(this.value=='new_advertiser')
            {jQuery('#new_advertiser').css('display','inline-block')}else{jQuery('#new_advertiser').css('display','none')}">
            <option value="0"><?php _e('Select advertiser','am_hili');?></option>  
            <option value="new_advertiser"><?php _e('New advertiser','am_hili');?></option>          
            <?php 
			if($amHiliadvertisers = $wpdb->get_results("select adv_id,advertiser_name from ".AM_HILI_ADVERTISERS." order by advertiser_name")){ 
				foreach($amHiliadvertisers as $amHiliadvertiser){
			?>
            <option type="hidden" value="<?php echo $amHiliadvertiser->adv_id;?>" <?php echo(isset($hiliRow) and $amHiliadvertiser->adv_id == $hiliRow->adv_id)?"selected='selected'":"";?>><?php echo $amHiliadvertiser->advertiser_name;?></option>
            <?php };};?>

            </select>
        </td>
   </tr>

    <tr>
    	<th style="width:50px"><?php _e('Category:','am_hili');?></th>
        <td>
        	<input type="text" style="display:none;width:200px" value="" name="new_category" id="new_category"/>
        	<select name="cat_id" size="1" onchange="if(this.value=='new_category')
            {jQuery('#new_category').css('display','inline-block')}else{jQuery('#new_category').css('display','none')}">
            <option value="0"><?php _e('Select category','am_hili');?></option>
            <option value="new_category"><?php _e('New category','am_hili');?></option>
            <?php 
			if($amHiliCategories = $wpdb->get_results("select * from ".AM_HILI_CATEGORIES." order by category_name")){ 
				foreach($amHiliCategories as $amHiliCategory){
			?>
            <option type="hidden" value="<?php echo $amHiliCategory->cat_id;?>" <?php echo(isset($hiliRow) and $amHiliCategory->cat_id == $hiliRow->cat_id)?"selected='selected'":"";?>><?php echo $amHiliCategory->category_name;?></option>
            <?php };};?>

            </select>
        </td>
   </tr>
         
    <tr>
    	<th style="width:50px"><?php _e('Default:','am_hili');?></th>
        <td>
        	<input type="radio" value="no" name="default_affiliate" id="default_affiliate_no" <?php echo($_GET['act']=="add" or (isset($hiliRow) and $hiliRow->default_affiliate=="no"))?"checked='checked'":"";?>/><label for="default_affiliate_no"><?php _e('No','am_hili');?></label> 
            <input type="radio" value="yes" name="default_affiliate" id="default_affiliate_yes" <?php echo($_GET['act']=="edit" and isset($hiliRow) and $hiliRow->default_affiliate=="yes")?"checked='checked'":"";?> /><label for="default_affiliate_yes"><?php _e('Yes','am_hili');?></label> 
            <br/><?php _e('The default affilate will fill-in for deactivated or deleted links, with shortcodes still in posts or pages.','am_hili');?>
        </td>
    </tr>

</tbody>
</table> 
<br/>
<?php _e('Field with (*) are required','am_hili');?><br/><br/>
<input type="submit" class="button" value="<?php echo ($_GET['act']=="add")?__('Save','am_hili'):__('Update','am_hili');?>" />
</form>  

<script language="JavaScript">
var gk_media_init = function(selector, button_selector)  {
    var clicked_button = false;
 
    jQuery(selector).each(function (i, input) {
        var button = jQuery(input).next(button_selector);
        button.click(function (event) {
            event.preventDefault();
            var selected_img;
            clicked_button = jQuery(this);
 
            // check for media manager instance
            if(wp.media.frames.gk_frame) {
                wp.media.frames.gk_frame.open();
                return;
            }
            // configuration of the media manager new instance
            wp.media.frames.gk_frame = wp.media({
                title: '<?php _e('Select image');?>',
                multiple: false,
                library: {
                    type: 'image'
                },
                button: {
                    text: '<?php _e('Use selected image');?>'
                }
            });
 
            // Function used for the image selection and media manager closing
            var gk_media_set_image = function() {
                var selection = wp.media.frames.gk_frame.state().get('selection');
 
                // no selection
                if (!selection) {
                    return;
                }
 
                // iterate through selected elements
                selection.each(function(attachment) {
                    var url = attachment.attributes.url;
					var id = attachment.attributes.id;
                    clicked_button.prev(selector).val(url);
					document.getElementById('am_hili_img').src = url;
					jQuery('#amHiliImageHolder').css('display','block');
                });
            };
 
            // closing event for media manger
           // wp.media.frames.gk_frame.on('close', gk_media_set_image);
            // image selection event
            wp.media.frames.gk_frame.on('select', gk_media_set_image);
            // showing media manager
            wp.media.frames.gk_frame.open();
        });
   });
};
gk_media_init('.link_image', '.AmHiliImageBtn');

function removeAmhilImage(){
			jQuery('#am_hili_img').src = '';
			jQuery('#amHiliImageHolder').css('display','none');
			jQuery('#link_image').val('');
}
</script>