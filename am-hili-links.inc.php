<?php
// If this file is called directly, exit.
if ( ! defined( 'ABSPATH' ) ) { exit(); }
?>
<!--list of affiliate links in posts and pages-->
<?php include dirname(__FILE__)."/am-hili-links.menu.php";?>
<h3><?php _e('AM-HILI (Inline Affiliate links)','am_hili');?></h3>
<?php
	
	//list of advertisers
	$am_hili_adv_filter = array();
	//list of categories
	$am_hili_cat_filter = array();
	//list of clicks
	$am_hili_clicks = array();

	//pulling links data
	include_once dirname(__FILE__)."/classes/class-links-data.inc.php";	
	
	$am_hili_data = new am_hili_links_data();
	$am_hili_rows = $am_hili_data->do_get_data('l');
	
	//order by
	$hili_ob = $am_hili_data->hili_ob;
	
	if ($am_hili_rows){
		//filling advertiser array and categories with names  
		foreach ($am_hili_rows as $am_hili_row_value){
			if (trim($am_hili_row_value->advertiser_name)!="" and !in_array($am_hili_row_value->advertiser_name,$am_hili_adv_filter)){
				$am_hili_adv_filter[$am_hili_row_value->adv_id] = $am_hili_row_value->advertiser_name;
			}
			
			if (trim($am_hili_row_value->category_name)!="" and !in_array($am_hili_row_value->category_name,$am_hili_cat_filter)){
				$am_hili_cat_filter[$am_hili_row_value->cat_id] = $am_hili_row_value->category_name;
			}
		}
	
			//getting date format from saved options
			if ($am_hili_options = json_decode(get_am_hili_options('am_hili_options'))){
			$am_hili_date_format = $am_hili_options->am_hili_date_format;
			} else {
				$am_hili_date_format = "m/d/Y";
			}
		
		if(count($am_hili_adv_filter)>0 or count($am_hili_cat_filter)>0){ ?>
    
    	<form>        
        <input type="hidden" name="page" value="<?php echo $_GET['page'];?>"/>
        <?php
		if(count($am_hili_cat_filter)>0){?>
		<select name="fc" size="1">
		<option value="all"><?php _e('All Categories','am_hili');?></option>
		<?php foreach ($am_hili_cat_filter as $cKey=>$cValue){?>
		<option value="<?php echo $cKey;?>" <?php echo (isset($_GET['fc']) and trim($_GET['fc'])==$cKey)?'selected="selected"':'';?>><?php echo $cValue;?></option>
		<?php };?>
		</select>
		<?php
		}
		if(count($am_hili_adv_filter)>0){?>
		<select name="fp" size="1">
		<option value="all" <?php echo (isset($_GET['fp']) and trim($_GET['fp'])=='all')?'selected="selected"':'';?>><?php _e('All advertisers','am_hili');?></option>
		<?php foreach ($am_hili_adv_filter as $pKey=>$pValue){ ?>
		<option value="<?php echo $pKey;?>" <?php echo (isset($_GET['fp']) and trim($_GET['fp'])==$pKey)?'selected="selected"':'';?>><?php echo $pValue;?></option>
		<?php };?>
		</select>
        <?php };?>
        
        <input type="submit" class="button" value="<?php _e('Filter links','am_hili');?>"/>
        <a class="button" href="<?php echo AM_HILI_ADMIN_URL; ?>" ><?php _e('Reset','am_hili');?></a>
        </form>
        <?php
	}
	}
	
?>

<table class="widefat" cellspacing="0">
    <thead>
    <tr>
            <th>::</th>
            <th><a href="<?php echo AM_HILI_ADMIN_URL?>" class="ob_name"><?php _e('Name','am_hili');?></a></th>
            <th><a href="<?php echo AM_HILI_ADMIN_URL."&ob=category" ?>" class="ob_category"><?php _e('Category','am_hili');?></a></th>
            <th><a href="<?php echo AM_HILI_ADMIN_URL."&ob=advertiser" ?>" class="ob_advertiser"><?php _e('advertiser','am_hili');?></a></th>
            <th><a href="<?php echo AM_HILI_ADMIN_URL."&ob=date" ?>" class="ob_date"><?php _e('Added on','am_hili');?></a></th>
            <th><a href="<?php echo AM_HILI_ADMIN_URL."&ob=clicks" ?>" class="ob_clicks"><?php _e('Clicks','am_hili');?></a></th>
    </tr>
    </thead>

    <tfoot>
    <tr>
            <th>::</th>
            <th><a href="<?php echo AM_HILI_ADMIN_URL?>" class="ob_name"><?php _e('Name','am_hili');?></a></th>
            <th><a href="<?php echo AM_HILI_ADMIN_URL."&ob=category" ?>" class="ob_category"><?php _e('Category','am_hili');?></a></th>
            <th><a href="<?php echo AM_HILI_ADMIN_URL."&ob=advertiser" ?>" class="ob_advertiser"><?php _e('advertiser','am_hili');?></a></th>
            <th><a href="<?php echo AM_HILI_ADMIN_URL."&ob=date" ?>" class="ob_date"><?php _e('Added on','am_hili');?></a></th>
            <th><a href="<?php echo AM_HILI_ADMIN_URL."&ob=clicks" ?>" class="ob_clicks"><?php _e('Clicks','am_hili');?></a></th>
    </tr>
    </tfoot>
<script>   
var hili_ob = ".ob_<?php echo $hili_ob;?>";
jQuery("th a").prepend('<i class="fa fa-caret-square-o-down" aria-hidden="true" style="font-size:12px;margin-right:5px"></i>');
jQuery(hili_ob).css('color','#900')
</script>
    <tbody>
<?php
	$am_nr =0;
	$am_tr_class = "alternate";
	
	/*pulling links data*/
	
	if ($am_hili_rows){
		foreach ($am_hili_rows as $am_hili_value){
			
			$showLink = true;
			if ((isset($_GET['fp']) and trim($_GET['fp'])!="all" and $am_hili_value->adv_id != $_GET['fp']) 
			or (isset($_GET['fc']) and trim($_GET['fc'])!="all" and $am_hili_value->cat_id != $_GET['fc'])){
			$showLink = false;
			}
			
			if ($showLink == true){
			$am_nr++;
			if ($am_tr_class == "alternate")
			$am_tr_class = "";
			else
			$am_tr_class = "alternate";
?>
        <tr class="<?php echo $am_tr_class;?>" valign="top">
            <td style="width:20px"><?php echo $am_nr;?></td>
            
            <td>
			<b>Link:</b> <?php echo $am_hili_value->link_text;?><br/>
            <b>Post:</b> <?php echo $am_hili_value->post_title;?>
            <div class="row-actions">
            <a href="<?php echo $am_hili_value->link_url;?>" target="_blank"><?php _e('Open link','am_hili');?></a> | 
            <a href="<?php echo get_post_permalink($am_hili_value->post_id);?>" target="_blank"><?php _e('Open post','am_hili');?></a> | 
            <a href="<?php echo get_edit_post_link($am_hili_value->post_id);?>"><?php _e('Edit post','am_hili');?></a> | 
            <a  onclick="deleteAmHiLi('<?php echo $am_hili_value->link_id;?>')"><?php _e('Delete link','am_hili');?></a>
            </div>
            </td>
            
            <td id="categories_<?php echo $am_hili_value->link_id;?>"> 
            <?php echo (trim($am_hili_value->category_name)!="")?$am_hili_value->category_name:'--';?>
            
            <div class="row-actions">
            <a  onclick="getAmHiLiCategories('<?php echo $am_hili_value->link_id;?>','<?php echo $am_hili_value->cat_id;?>')"><?php _e('Category','am_hili');?></a> </div>
            </td>
            
            <td id="advertisers_<?php echo $am_hili_value->link_id;?>">
            <?php echo (trim($am_hili_value->advertiser_name)!="")?$am_hili_value->advertiser_name:'--';?>
            
            <div class="row-actions">
            <a  onclick="getAmHiLiadvertisers('<?php echo $am_hili_value->link_id;?>','<?php echo $am_hili_value->adv_id;?>')"><?php _e('advertiser','am_hili');?></a> </div>
            </td>
            
            <td width="140">
			<?php echo date($am_hili_date_format. " H:i", strtotime($am_hili_value->added_on));?>
            </td>
            
             <td width="60"><?php echo (isset($am_hili_value->totat_clicks))?$am_hili_value->totat_clicks:"0";?></td>
        </tr>
<?php };};};?> 
  
    </tbody>
</table>