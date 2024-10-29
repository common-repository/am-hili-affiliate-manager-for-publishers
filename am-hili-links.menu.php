<?php
// If this file is called directly, exit.
if ( ! defined( 'ABSPATH' ) ) { exit(); }
?>
<ul class="am_hili_menu">
<li style="float:right !important">
<?php 
//add button for adding new links
	if(isset($showHiliAddEditBtn)){
		if (strstr($_GET['inc'],"-add-edit")){
			$addEditeCancel = str_replace(array("-add-edit","&act=add","&act=edit"),"",$_GET['inc']);
			$addEditeTitle = __('Cancel','am_hili');			
		} else {
			$addEditeCancel = $_GET['inc']."-add-edit&act=add";
			$addEditeTitle = __('Add new','am_hili');
		};
?>
<a class="button amHiliAddEditeCancel" href="<?php echo AM_HILI_ADMIN_URL; ?>&inc=<?php echo $addEditeCancel;?>"><?php echo $addEditeTitle;?></a>
<?php };?>
<a class="button rightHiliBtn amhiliPremium" href="<?php echo AM_HILI_ADMIN_URL; ?>&inc=bookkeeping"><?php _e('Bookkeeping','am_hili');?></a>
</li>

<?php
//getting the selected button
$hiliBtnClasses = array("inline"=>"","custom"=>"","auto"=>"","statistics"=>"","categories"=>"","advertisers"=>"","settings"=>"");
if (isset($_GET['inc'])){
$hiliInc = str_replace(array('-add-edit','links-'),'',$_GET['inc']);
$hiliBtnClasses[$hiliInc] = "hiliBtnSelected";
} else {
$hiliBtnClasses['inline'] = "hiliBtnSelected";
}
?>

<li><a class="button <?php echo $hiliBtnClasses['inline'];?>" href="<?php echo AM_HILI_ADMIN_URL; ?>"><?php _e('Home','am_hili');?></a></li>
<li><a class="button <?php echo $hiliBtnClasses['custom'];?>" href="<?php echo AM_HILI_ADMIN_URL; ?>&inc=links-custom"><?php _e('Custom links','am_hili');?></a></li>
<li><a class="button <?php echo $hiliBtnClasses['auto'];?> amhiliPremium" href="<?php echo AM_HILI_ADMIN_URL; ?>&inc=links-auto"><?php _e('Auto insert links','am_hili');?></a></li>
<li><a class="button <?php echo $hiliBtnClasses['categories'];?>" href="<?php echo AM_HILI_ADMIN_URL; ?>&inc=categories"><?php _e('Categories','am_hili');?></a></li>
<li><a class="button <?php echo $hiliBtnClasses['advertisers'];?>" href="<?php echo AM_HILI_ADMIN_URL; ?>&inc=advertisers"><?php _e('Advertisers','am_hili');?></a></li>
<li><a class="button <?php echo $hiliBtnClasses['statistics'];?> amhiliPremium" href="<?php echo AM_HILI_ADMIN_URL; ?>&inc=statistics"><?php _e('Statistics','am_hili');?></a></li>
<li><a class="button <?php echo $hiliBtnClasses['settings'];?>" href="<?php echo AM_HILI_ADMIN_URL; ?>&inc=settings"><?php _e('settings','am_hili');?></a></li>
</ul>
<div id="amhiliInfoDiv" style="color:red;font-size:14px"></div>
<script>
var prem = '<?php _e('Premium version only. For more information <a href="//am-plugins.com/hili">click here</a>','am_hili');?>';
	jQuery(".amhiliPremium" ).click(function( event ) {
	event.preventDefault();
	jQuery('#amhiliInfoDiv').html(prem)  
});

jQuery(document).ready(function(e) {
	var am_hili_user_key = '<div style="text-align:right;margin-top:10px;padding:0px 20px"><a href="https://am-plugins.com/hili/download-amhili/"><?php _e('Go Premium','am_hili');?></a></div>';
    jQuery('.am_hili_wrap').append(am_hili_user_key);
});
</script>