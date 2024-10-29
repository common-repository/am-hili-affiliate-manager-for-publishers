<?php
// If this file is called directly, exit.
if ( !defined( 'ABSPATH' ) ) { exit(); }

class am_hili_web {
	
	public $hili_options = array();
	public $link_type = "";
	public $cloak_page = "";
	public $max_insert_links = "";
	public $replace_keywords = "";
	
	public function __construct() {
	global $am_hili_options;
	
	$this->link_type = $am_hili_options->link_type;
	$this->cloak_page = $am_hili_options->cloak_page;
	$this->max_insert_links = $am_hili_options->am_hili_max_insert_links;
	$this->replace_keywords = $am_hili_options->am_hili_replace_keywords;
	
		//enqueueing the required scripts for the hide type links
	if ($this->link_type == "hide")
	add_action( 'wp_enqueue_scripts', array($this, 'register_am_hili_js') );

	//managing links in posts and widgets using shortcodes
	add_filter( 'the_content', array($this, 'do_am_hili_auto_insert'), 11 );
	add_filter( 'widget_text', 'shortcode_unautop');
	add_filter( 'widget_text', 'do_shortcode' );
	add_shortcode( 'amhili', array($this, 'am_hili_shortcode') );
	}

/**
 * Register scripts.
*/
	public function register_am_hili_js() {
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script('am_link_url_script',AM_HILI_URL.'/js/am-hili-url.js');
		wp_enqueue_script('ajax-script',AM_HILI_URL.'/js/am-hili-web.js');	
		wp_localize_script( 'ajax-script', 'ajaxurl', admin_url( 'admin-ajax.php' ));
	}


//function for shrtening the links using base64_encode for short type links
	private function shorten($id){
		
		$short = str_replace('=','',base64_encode($id));
		return $short;
		
	}


/*function do_amhili
handling affiliate links in the theme itself or shortcodes with no content*/
	private function do_amhili($link_id,$showImage=false){
		
	global $wpdb;
	$content = "";

	if($link_id and $hiliRow = $wpdb->get_row("select * from ".AM_HILI_LINKS." where active='yes' and (link_id='$link_id' or default_affiliate='yes') order by default_affiliate ASC")){

		//handling affiliate link with attached image
		if($showImage == true and trim($hiliRow->link_image)!='')
		$amHiliContent = '<img src="'.$hiliRow->link_image.'" title="'.trim($hiliRow->link_text).'" />';
		else
		$amHiliContent = $hiliRow->link_text;
		
		//handling the link
		if ($this->link_type == "hide"){
				$content = '<span class="am-hili" id="'.$link_id.'">'.trim($amHiliContent).'</span>';	
			} else {
				if($this->link_type == "cloak")
				$hili_href = $this->cloak_page."/".trim(str_replace(' ','-',trim($hiliRow->link_text)));
				else /*shortening the links*/
				$hili_href = "!".$this->shorten($hiliRow->link_id);
				
				$content = '<a href="'.get_site_url().'/'.$hili_href.'" class="am-hili" rel="nofollow">'.trim($amHiliContent).'</a>';
			}	
	}
	//returning the final link
	return $content;
	}


	public function am_hili_shortcode( $atts, $content = null ) {
	global $hili,$wpdb;

	$link_text = "";	
	if (isset($atts['id']) and trim($atts['id'])!=""){	
		
		//handling shortcode with content 
		if ($content){			
		//getting the url from the content
		preg_match('/<a (.*?)>/si', $content, $matchedLink);
		if(isset($matchedLink[0])){
			$content = str_replace(array($matchedLink[0],'</a>'),'',$content);
			$link_text = trim($content);
		}
		
		//checking if there is an image in the content
		preg_match('/<img (.*?)>/si', $content, $image);
		if (isset($image[0])){
			$content = str_replace($image[0],'',$content);
		}
		
		//handling affiliate link if an url found in the content
		if ($this->link_type == "hide"){
				$content = '<span class="am-hili" id="'.$atts['id'].'">'.trim($content).'</span>';	
			} else {
				if($this->link_type == "cloak")
				$hili_href = $this->cloak_page."/".trim(str_replace(' ','-',$link_text));
				else
				$hili_href = "!".$this->shorten($atts['id']);
				
				$content = '<a href="'.get_site_url().'/'.$hili_href.'" class="am-hili" rel="nofollow">'.trim($content).'</a>';
			}		
		}
		else{
			//let the function do_amhili handle the link if the shortcode has no content
			$content = $this->do_amhili($atts['id'],isset($atts['image']) and trim($atts['image'])?true:false);
			}
		
			return $content;
		}
	//returning the final link
	
	}

/*auto insert links*/
	public function do_am_hili_auto_insert($content){
		global $wpdb,$post;

		//check if this post is excluded from auto isert
		if (get_post_meta($post->ID, '_am_hili_exclude_post')){
			return $content;
		}

		//get categories id for the current post
			$hiliCats = array();
			if ($categories = get_the_category($post->ID)){
				foreach($categories as $cat)
				$hiliCats[] = $cat->cat_ID;
			}
			
			$whr = "and (apply_on_categories = '' or apply_on_categories like '%,".implode(",%' or apply_on_categories like '%,",$hiliCats).",%')";
			
		//link insert counter
		$links_inserted = 0;
		
		if($am_hili_links = $wpdb->get_results("select link_id,link_url,link_text,apply_on_categories,link_keywords from ".AM_HILI_LINKS." where active='yes' and link_keywords!='' $whr ORDER BY link_priority DESC")){
			

			//adding space before and after the elements and the content itself to search for keywords from the first word to the last word of the content.
			//the content will be trimed berore return it back
			//search will be for words separeted by spaces to avoid keywords in images names of element names etc.
			
			$content = " ".str_replace(array('>','<'),array('> ',' <'),$content)." ";
			
			foreach ($am_hili_links as $am_hili_link){
				
					//putting keywords in array
					$link_keywords = explode(',',$am_hili_link->link_keywords);

					if(count($link_keywords) >0){
						foreach($link_keywords as $hili_keyword){							
							$hili_keyword = trim($hili_keyword);
						
							//checking if there are matched keywords in this post
							if (strstr($content,$hili_keyword)){
									
								//inserting the links if keyword found
								if ($this->link_type == "hide"){
									$content = preg_replace('/'.$hili_keyword.'/','<span class="am-hili" id="'.$am_hili_link->link_id.'">'.trim($hili_keyword).'</span>',$content,$this->replace_keywords);	
								} else {
									if($this->link_type == "cloak")
									$hili_href = $this->cloak_page."/".trim(str_replace(' ','-',trim($am_hili_link->link_text)));
									else
									$hili_href = "!".$this->shorten($am_hili_link->link_id);
									
									$content = preg_replace('/'.$hili_keyword.'/','<a href="'.get_site_url().'/'.$hili_href.'" class="am-hili" rel="nofollow">'.trim($hili_keyword).'</a>',$content,$this->replace_keywords);
									
								}
									$links_inserted++;
									if ($links_inserted > $this->max_insert_links)
									break;		
							}
						}
					}
				
			}
		}
	//returning the content of the post with the auto inserted links
	return trim($content);
	}
/*end auto insert links*/
}