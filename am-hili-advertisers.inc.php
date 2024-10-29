<?php
// If this file is called directly, exit.
if ( ! defined( 'ABSPATH' ) ) { exit(); }
?>

<?php $showHiliAddEditBtn = true; include dirname(__FILE__)."/am-hili-links.menu.php";?>
<h3><?php _e('AM-HILI (Affiliate advertisers)','am_hili');?></h3>
<script>
function deleteAmHiLiadvertiser(adv_id){
	if (confirm('<?php _e('Are you sure! Delete advertiser','am_hili');?>')==1){
		var am_hili_data = {
			'action': 'am_hili_ajax',
			'inc': 'advertisers',
			'act':'delete',
			'_am_hili_':'<?php echo wp_create_nonce('am_hili_nonce');?>',
			'adv_id':adv_id
		};
		
		jQuery.post(ajaxurl,am_hili_data,function(data){
		if(data){
			alert(data);
		if (data=="done")
		location.reload(true)
		};
		})
	}
	
	return false;
}
</script>
<table class="widefat" cellspacing="0">
    <thead>
    <tr>
            <th>::</th>
            <th><?php _e('Name','am_hili');?></th>
            <th><?php _e('Website','am_hili');?></th>
            <th><?php _e('Email','am_hili');?></th>
            <th><?php _e('Added on','am_hili');?></th>
            <th><?php _e('Affiliates','am_hili');?></th>
    </tr>
    </thead>

    <tfoot>
    <tr>
            <th>::</th>
            <th><?php _e('Name','am_hili');?></th>
            <th><?php _e('Website','am_hili');?></th>
            <th><?php _e('Email','am_hili');?></th>
            <th><?php _e('Added on','am_hili');?></th>
            <th><?php _e('Affiliates','am_hili');?></th>
    </tr>
    </tfoot>

    <tbody>
<?php
	global $wpdb;
	$am_nr =0;
	$am_tr_class = "alternate";
	
	//getting total links per advertiser
	$am_hili_totals=array();
	$am_hili_afi_totals = $wpdb->get_results("select adv_id, count(adv_id) as totals from ".AM_HILI_LINKS." where adv_id > 0 group by adv_id");
	if ($am_hili_afi_totals){
		foreach ($am_hili_afi_totals as $am_hili_tatal_value){
			$am_hili_totals[$am_hili_tatal_value->adv_id] = $am_hili_tatal_value->totals;
		}
	}
	
	//getting date format from saved options
			if ($am_hili_options = json_decode(get_am_hili_options('am_hili_options'))){
			$am_hili_date_format = $am_hili_options->am_hili_date_format;
			} else {
				$am_hili_date_format = "m/d/Y";
			}
		
	$amHiliSql = "select adv_id, advertiser_name, advertiser_website, advertiser_email, added_on from ".AM_HILI_ADVERTISERS ." order by advertiser_name ASC";
	$am_hili_man_rows = $wpdb->get_results($amHiliSql);
	if ($am_hili_man_rows){
		foreach ($am_hili_man_rows as $am_hili_value){
			$am_nr++;
			if ($am_tr_class == "alternate")
			$am_tr_class = "";
			else
			$am_tr_class = "alternate";
?>
        <tr class="<?php echo $am_tr_class;?>" valign="top">
            <td style="width:20px"><?php echo $am_nr;?></td>
            <td><?php echo $am_hili_value->advertiser_name;?>
            <div class="row-actions">  
             <a href="<?php echo AM_HILI_ADMIN_URL; ?>&inc=<?php echo $_GET['inc'];?>-add-edit&act=edit&adv_id=<?php echo $am_hili_value->adv_id;?>"><?php _e('Edit advertiser','am_hili');?></a> | 
             <a  onclick="deleteAmHiLiadvertiser('<?php echo $am_hili_value->adv_id;?>')"><?php _e('Delete advertiser','am_hili');?></a>
            </div>
            </td>
             <td width="100"><?php echo (trim($am_hili_value->advertiser_website)!="")?"<a href=\"".$am_hili_value->advertiser_website."\">".str_replace(array('https://','http://','//','www.'),'',$am_hili_value->advertiser_website)."</a>":"NA";?></td>
            <td width="100"><?php echo (trim($am_hili_value->advertiser_email)!="")?"<a href=\"mailto:".$am_hili_value->advertiser_email."\">".$am_hili_value->advertiser_email."</a>":"NA";?></td>
            <td width="140"><?php echo date($am_hili_date_format. " H:i", strtotime($am_hili_value->added_on));?></td>
            <td width="40"><?php echo (isset($am_hili_totals[$am_hili_value->adv_id]))?$am_hili_totals[$am_hili_value->adv_id]:"0";?></td>
        </tr>
<?php };};?>   
    </tbody>
</table>