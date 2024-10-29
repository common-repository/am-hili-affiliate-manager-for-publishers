<?php
// If this file is called directly, exit.
if ( ! defined( 'ABSPATH' ) ) { exit(); }

//handle ajax calls
add_action( 'wp_ajax_nopriv_am_hili_web_ajax', 'am_hili_do_ajax' );
add_action( 'wp_ajax_am_hili_ajax', 'am_hili_do_ajax' );

if (!function_exists('am_hili_do_ajax')){
	
	function am_hili_do_ajax() {

	if (isset($_POST['inc']) and trim($_POST['inc']) != "")
		{
				if (file_exists(dirname(__FILE__)."/am-hili-{$_POST['inc']}.ajax.php"))
				include dirname(__FILE__)."/am-hili-{$_POST['inc']}.ajax.php";
				
				elseif (file_exists(dirname(__FILE__)."/bookkeeping/am-hili-{$_POST['inc']}.ajax.php"))
				include dirname(__FILE__)."/bookkeeping/am-hili-{$_POST['inc']}.ajax.php";
				
				exit();
		
		}
		
		wp_die();
	}
}


/*
work-round when the admin user logged-in and has no permission for ajax
and wp_ajax_nopriv not working.
*/
if ( !empty( $_REQUEST['action'] ) && $_REQUEST['action'] == "am_hili_web_ajax")
am_hili_do_ajax();

	
//include files for saving setting / categories / adevertisers etc
add_action('admin_init','am_hili_iclude_save_file');

if (!function_exists('am_hili_iclude_save_file')){
	
	function am_hili_iclude_save_file(){
		if (isset($_GET['page']) and trim($_GET['page'])=="am-hili-affiliate-manager-for-publishers" 
			and isset($_GET['do']) and trim($_GET['do'])=="save"
			and isset($_GET['inc']) and trim($_GET['inc'])!="")
			{
			
			if (file_exists(dirname(__FILE__)."/am-hili-{$_GET['inc']}.save.php"))
			include dirname(__FILE__)."/am-hili-{$_GET['inc']}.save.php";
			
			elseif (file_exists(dirname(__FILE__)."/bookkeeping/am-hili-{$_GET['inc']}.save.php"))
			include dirname(__FILE__)."/bookkeeping/am-hili-{$_GET['inc']}.save.php";
			
			exit();
		
			}
	
	}
}

//register menu
add_action( 'admin_menu', 'am_hili_menu' );

function am_hili_menu() {
		add_menu_page( 'AM HiLi', 'AM HiLi', 'manage_options', 'am-hili-affiliate-manager-for-publishers', 'am_hili_includes',AM_HILI_URL."/images/menu-logo.png");
}

//include the required file
function am_hili_includes() {
	?>
	<div style="padding-top:20px">
    <div class="wrap am_hili_wrap">
		<?php
            if (isset($_GET['inc']) and trim($_GET['inc'])!=""){ 
                if (file_exists(dirname(__FILE__)."/am-hili-{$_GET['inc']}.inc.php"))	
                include dirname(__FILE__)."/am-hili-{$_GET['inc']}.inc.php";
                elseif (file_exists(dirname(__FILE__)."/bookkeeping/am-hili-{$_GET['inc']}.inc.php"))	
                include dirname(__FILE__)."/bookkeeping/am-hili-{$_GET['inc']}.inc.php";
                elseif (file_exists(dirname(__FILE__)."/statistics/am-hili-{$_GET['inc']}.inc.php"))	
                include dirname(__FILE__)."/statistics/am-hili-{$_GET['inc']}.inc.php";
                else
                return;
            } else {
                include dirname(__FILE__)."/am-hili-links.inc.php";
            }
                include (dirname( __FILE__ ) . "/am-hili-footer.menu.php");
        ?>
    </div>
    </div>
    <?php
}


//adding button to mce editor
class am_hili_buttons {

	public function __construct() {
		add_action( 'init', array( $this, 'am_hili_mce_buttons' ) );
	}

	public function am_hili_mce_buttons() {
		global $pagenow, $typenow;

		// add media button for editor page
		if ( !in_array( $pagenow, array( 'post.php', 'page.php', 'post-new.php', 'post-edit.php' ) ) or $typenow == 'download' ) {
		return;
		}
		
		if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) {
		return;
		}
		
		if ( get_user_option('rich_editing') == 'true') {
			add_filter( 'mce_external_plugins', array( $this, 'am_hili_add_tinymce_plugin' ) );
			add_filter( 'mce_buttons', array( $this, 'am_hili_register_tinymce_button' ) );
			wp_enqueue_script('am_hili_script',AM_HILI_URL.'/js/am-hili-mce.js');
		}
	}


	public function am_hili_add_tinymce_plugin( $plugin_array ) {
		$plugin_array['am_hili_mce_button'] = AM_HILI_URL . '/js/am-hili-mce.js'; 

		return $plugin_array;
	}

	public function am_hili_register_tinymce_button( $buttons ) {
		array_push( $buttons, 'am_hili_mce_button' );
		return $buttons;
	}

}

if ( is_admin() ) {
	new am_hili_buttons();
}


//load js and css 
add_action('admin_enqueue_scripts', 'am_hili_js_css');
function am_hili_js_css() {
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'jquery-ui-datepicker', array( 'jquery' ) );
	wp_register_style('jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.0/themes/smoothness/jquery-ui.css');
	wp_enqueue_style( 'jquery-ui' );  
	wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');
	wp_enqueue_style('am-hili-css', AM_HILI_URL . '/css/style.css');

	if (isset($_GET['page']) and trim($_GET['page'])=="am-hili-affiliate-manager-for-publishers"){
		wp_enqueue_script( 'ajax-script', AM_HILI_URL . '/js/am-hili-admin.js', array('jquery') );
		wp_localize_script( 'ajax-script', 'amhili_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
	}
}

//footer scripts
add_action( 'admin_print_footer_scripts', 'am_hili_admin_footer_js' );

if (!function_exists('am_hili_admin_footer_js')){

	function am_hili_admin_footer_js(){
	?>

	<script>
	var AM_HILI_ADMIN_PAGE = '<?php echo AM_HILI_ADMIN_PAGE;?>';
	var AM_HILI_ADMIN_URL = '<?php echo AM_HILI_ADMIN_URL;?>';
	var AM_HILI_URL = '<?php echo AM_HILI_URL;?>';
	var am_hili_nonce = '<?php echo wp_create_nonce('am_hili_nonce');?>';
	</script>
    <script src="<?php echo AM_HILI_URL;?>/js/am-hili-url.js"></script>
 	<?php
    }
}


//check the post content for affiliate links
if (!function_exists('am_hili_get_shortcode')){
function am_hili_get_shortcode()
{
    global $wpdb,$post;	
	
	if (!$post or !isset($_POST['post_content']) or !isset($_POST['post_ID'])) {
		return;
	}
	
	// If this is an autosave, we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	
	$amHiliIdDs = array();
	$post_content = stripslashes($_POST['post_content']);
	$post_id = $_POST['post_ID'];
	
	if (preg_match_all( '/\[amhili(.*?)\/\]/s', $post_content, $contents))
    {
		foreach($contents[0] as $content){
			$post_content = str_replace($contents[0],'',$post_content);
		}
	}
	
    if (preg_match_all( '/\[amhili(.*?)\[\/amhili\]/s', $post_content, $contents))
    {
 		foreach($contents[0] as $content){
			if (preg_match('/id=("|\')(.*?)("|\')/i',$content,$amhiliIDs)){
			$amhiliID = trim($amhiliIDs[2]);
			$amHiliIdDs[] .= $amhiliID;
			}			
			
			if (preg_match('/<a (.*?)\>(.*?)\<\/a>/si', $content, $amhiliTexts)){
			$amhiliText = $wpdb->_real_escape(trim(strip_tags($amhiliTexts[2])));
			}
			
			if (preg_match('/href=("|\')(.*?)("|\')/i',$content,$amhiliUrls)){
			$amhiliUrl = trim($amhiliUrls[2]);		
			}

	//handling the database if id, text and url found	
	if (isset($amhiliID) and isset($amhiliUrl) and isset($amhiliText) and isset($post_id) and $post_id > 0){
		//update link if found
		if($hiliRow = $wpdb->get_row("select * from ".AM_HILI_LINKS." where link_id='$amhiliID'")){
			if ($hiliRow->link_url != $amhiliUrl or $hiliRow->link_text != $amhiliText){
				$wpdb->query($wpdb->prepare("update ".AM_HILI_LINKS." set link_url='$amhiliUrl', link_text='$amhiliText', post_id='$post_id' where link_id=%d",$amhiliID));
			}
		}
		else {
			//re-insert link if was removed accidently
		$wpdb->query($wpdb->prepare("insert into ".AM_HILI_LINKS."  (link_id,post_id, blog_id, link_url, link_text,link_type) 
					values('$amhiliID','$post_id', '".BLOG_ID."','$amhiliUrl', '$amhiliText','%s')",'l'));
		}
		}
	}
	}
	
	//removing not used links from the data base
	if($post_id!=0 and $hiliRows = $wpdb->get_results("select link_id,post_id from ".AM_HILI_LINKS." where post_id='$post_id'")){
		foreach ($hiliRows as $row){
			if (!in_array($row->link_id,$amHiliIdDs)){
			$wpdb->query($wpdb->prepare("delete from ".AM_HILI_LINKS." where link_id='".$row->link_id."' and post_id=%d",$post_id));
			}
		}
	}
	
	//remove all inline links have no post id asigned to it
	$wpdb->query($wpdb->prepare("delete from ".AM_HILI_LINKS." where link_type='l' and post_id=%d",0));
	
	//check if this post must be excluded from auto insert links
	if (!isset($_POST['am_hili_exclude_this_post'])){
		delete_post_meta( $post_id, '_am_hili_exclude_post');
	} else {
		update_post_meta( $post_id, '_am_hili_exclude_post','yes');
	}
}
}
add_action( 'save_post', 'am_hili_get_shortcode');