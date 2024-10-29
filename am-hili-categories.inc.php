<?php
// If this file is called directly, exit.
if ( ! defined( 'ABSPATH' ) ) { exit(); }
?>

<?php $showHiliAddEditBtn = true; include dirname(__FILE__)."/am-hili-links.menu.php";?>
<h3><?php _e('AM-HILI (Categories)','am_hili');?></h3>
<script>
function deleteAmHiLiCategory(cat_id){
	if (confirm('<?php _e('Are you sure! Delete category','am_hili');?>')==1){
		
		var am_hili_data = {
			'action': 'am_hili_ajax',
			'inc': 'categories',
			'act':'delete',
			'_am_hili_':'<?php echo wp_create_nonce('am_hili_nonce');?>',
			'cat_id':cat_id
		};
		
		jQuery.post(ajaxurl,am_hili_data,function(data){
		if(data){
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
            <th><?php _e('Affiliates','am_hili');?></th>
    </tr>
    </thead>

    <tfoot>
    <tr>
            <th>::</th>
            <th><?php _e('Name','am_hili');?></th>
            <th><?php _e('Affiliates','am_hili');?></th>
    </tr>
    </tfoot>

    <tbody>
<?php
	global $wpdb;
	$am_nr =0;
	$am_tr_class = "alternate";
	
	//getting total links per category
	$am_hili_totals=array();
	$am_hili_afi_totals = $wpdb->get_results("select cat_id, count(cat_id) as totals from ".AM_HILI_LINKS." where cat_id > 0 group by cat_id ASC");
	if ($am_hili_afi_totals){
		foreach ($am_hili_afi_totals as $am_hili_tatal_value){
			$am_hili_totals[$am_hili_tatal_value->cat_id] = $am_hili_tatal_value->totals;
		}
	}
	
	//getting list of link categories
	$am_hili_cat_rows = $wpdb->get_results("select * from ".AM_HILI_CATEGORIES);
	if ($am_hili_cat_rows){
		foreach ($am_hili_cat_rows as $am_hili_cat_value){
			$am_nr++;
			if ($am_tr_class == "alternate")
			$am_tr_class = "";
			else
			$am_tr_class = "alternate";
?>
        <tr class="<?php echo $am_tr_class;?>" valign="top">
            <td style="width:20px"><?php echo $am_nr;?></td>
            <td><?php echo $am_hili_cat_value->category_name;?>
            <div class="row-actions">  
             <a href="<?php echo AM_HILI_ADMIN_URL; ?>&inc=<?php echo $_GET['inc'];?>-add-edit&act=edit&cat_id=<?php echo $am_hili_cat_value->cat_id;?>"><?php _e('Edit category','am_hili');?></a> | 
             <a  onclick="deleteAmHiLiCategory('<?php echo $am_hili_cat_value->cat_id;?>')"><?php _e('Delete category','am_hili');?></a>
            </div>
            </td>
            <td width="40"><?php echo (isset($am_hili_totals[$am_hili_cat_value->cat_id]))?$am_hili_totals[$am_hili_cat_value->cat_id]:"0";?></td>
        </tr>
<?php };};?>   
    </tbody>
</table>