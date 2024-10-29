<?php
// If this file is called directly, exit.
if ( ! defined( 'ABSPATH' ) ) { exit(); }

//check for update twice a day (same schedule as normal WP plugins)
register_activation_hook(__FILE__, 'am_hili_check_activation');

function am_hili_check_activation() {
	
	wp_schedule_event(time(), 'twicedaily', 'am_hili_check_update_event');
	
}

//remove cron task upon deactivation
register_deactivation_hook(__FILE__, 'am_hili_check_deactivation');
function am_hili_check_deactivation() {
	
	wp_clear_scheduled_hook('am_hili_check_update_event');
	
}


//checking updates
class am_hili_check_update {
	
	public $remote_results = array();
	
	//exlude this plugin from updates 
	public function __construct(){
		add_filter( 'http_request_args',array($this, 'am_hili_exclude_updates'), 5, 2 );
	}	
	
	function am_hili_exclude_updates( $r, $url ) {
		if ( 0 !== strpos( $url, 'http://api.wordpress.org/plugins/update-check' ) )
			return $r; 
	  
		$plugins = unserialize( $r['body']['plugins'] );
		unset( $plugins->plugins[ plugin_basename( __FILE__ ) ] );
		unset( $plugins->active[ array_search( plugin_basename( __FILE__ ), $plugins->active ) ] );
		$r['body']['plugins'] = serialize( $plugins );
		return $r;
	}	
	
	private function get_plugin_info($val) {
		if ( ! function_exists( 'get_plugins' ) )
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		
		$am_hili_folder = get_plugins( '/' . plugin_basename( AM_HILI_PATH ));
		$am_hili_file = AM_HILI_FILE;
		
		return $am_hili_folder[$am_hili_file][$val];
	}
	
	//contact remote website to check if there is any updates
	public function check_update($am_hili_api_key) {
	
 			$am_hili_rem_vars = array(
			'method' => 'POST',
			'timeout' => 45,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking' => true,
			'headers' => array(),
			'body' => array( 'website' => $_SERVER['HTTP_HOST'], 'api_key' => $am_hili_api_key,'plugin_version' => $this->get_plugin_info('Version') ),
			'cookies' => array()
			);

		if($am_hili_remote_results = wp_remote_post('https://am-plugins.com/update',$am_hili_rem_vars)){

		$remoteResults = json_decode($am_hili_remote_results['body']);		

		$remote_results = $remoteResults[0];
		$this->remote_results = $remote_results;
		
			if (isset($remote_results->version)){
			
			$am_hili_folder = plugin_basename( AM_HILI_PATH );
			
			$am_hili_file = AM_HILI_FILE;
			if ( defined( 'WP_INSTALLING' ) ) return false;
	
			list($version, $url) = explode('|', $remote_results->version);
			
				if($this->get_plugin_info("Version") != $version) 
				{		
					$plugin_transient = get_site_transient('update_plugins');
					$update_vars = array(
						'slug' => $am_hili_folder,
						'new_version' => $version,
						'url' => $this->get_plugin_info("AuthorURI"),
						'package' => $url
					);
			
					$object = (object) $update_vars;
					$plugin_transient->response[$am_hili_folder.'/'.$am_hili_file] = $object;
					set_site_transient('update_plugins', $plugin_transient);
				}
			}
			return $this->remote_results;
		}
	}
}

//checking updates
add_action('am_hili_check_event', 'am_hili_check_plugin_update');
if (!function_exists("am_hili_check_plugin_update")){
	
	function am_hili_check_plugin_update($am_hili_api_key) {

			if (trim($am_hili_api_key!="")){
				$am_hili_api_key_results = new am_hili_check_update;
				return $am_hili_api_key_results->check_update($am_hili_api_key);
			}
			
			}
			
}