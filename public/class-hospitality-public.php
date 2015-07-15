<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Hospitality
 * @subpackage Hospitality/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Hospitality
 * @subpackage Hospitality/public
 * @author     Wes Kempfer <wkempferjr@tnotw.com>
 */
class Hospitality_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $hospitality    The ID of this plugin.
	 */
	private $hospitality;
	
	/**
	 * Handle for sliderJS javascript.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $slider_js_handle.
	 */
	private $slider_js_handle = 'js-slider-config';

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $hospitality       The name of the plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $hospitality, $version ) {

		$this->hospitality = $hospitality;
		$this->version = $version;

	}
	
	

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Hospitality_Public_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Hospitality_Public_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		global $post;

		wp_enqueue_style( $this->hospitality, plugin_dir_url( __FILE__ ) . 'css/hospitality-public.min.css', array(), $this->version, 'all' );

		$post_type = get_post_type();
		if ( $post_type == "rooms" || $post->post_name == GUESTABA_ROOM_DETAIL_PAGE_NAME || $post->post_name == GUESTABA_ROOMS_LISTING_PAGE_NAME || $this->has_hsp_shortcodes() ) {
		 	wp_enqueue_style( 'hsp-slick', plugin_dir_url( __FILE__ ) . 'lib/slick/slick.css', array(), $this->version, 'all' );
		 	wp_enqueue_style( 'slider-style', plugin_dir_url( __FILE__ ) . 'css/slider-style.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'hsp-foundation', plugin_dir_url( __FILE__ ) . 'css/foundation.min.css', array(), $this->version, 'all' );

		}
		
		
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Hospitality_Public_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Hospitality_Public_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */


		global $post ;

		$post_type = get_post_type();

		// Don't enqueue the slider code for the rooms listing page, only foundation.
		if ( $post->post_name == GUESTABA_ROOMS_LISTING_PAGE_NAME ) {
			wp_enqueue_script( 'hsp-foundation-modnzr', plugin_dir_url( __FILE__ ) . 'js/vendor/modernizr.js', array(), $this->version, false );
			wp_enqueue_script( 'hsp-foundation', plugin_dir_url( __FILE__ ) . 'js/foundation.min.js', array('jquery'), $this->version, true );
			wp_enqueue_script( 'hsp-foundation-eq', plugin_dir_url( __FILE__ ) . 'js/foundation/foundation.equalizer.js', array('hsp-foundation'), $this->version, true );

			wp_enqueue_script( 'hsp-foundation-init', plugin_dir_url( __FILE__ ) . 'js/foundation-init.js', array('hsp-foundation'), $this->version, true );
		}




		// Enqueue foundation and slider for room detail pages
		if ( $post_type == "rooms" || $post->post_name == GUESTABA_ROOM_DETAIL_PAGE_NAME || $this->has_hsp_shortcodes() ) {
			wp_enqueue_script( 'hsp-slick', plugin_dir_url( __FILE__ ) . 'lib/slick/slick.min.js', array( 'jquery' ), $this->version, true );
			wp_enqueue_script( 'hsp-foundation-modnzr', plugin_dir_url( __FILE__ ) . 'js/vendor/modernizr.js', array(), $this->version, false );
			wp_enqueue_script( 'hsp-foundation', plugin_dir_url( __FILE__ ) . 'js/foundation.min.js', array('jquery'), $this->version, true );

			wp_enqueue_script( 'hsp-foundation-init', plugin_dir_url( __FILE__ ) . 'js/foundation-init.js', array('hsp-foundation'), $this->version, true );

			wp_enqueue_script( $this->slider_js_handle, plugin_dir_url( __FILE__ ) . 'js/js-slider-config.js', array( 'hsp-slick' ), $this->version, true );
		}

		wp_enqueue_script( $this->hospitality, plugin_dir_url( __FILE__ ) . 'js/hospitality-public.min.js', array( 'jquery' ), $this->version, true );

	}
	
	
	/**
	 * Runs wp_localize_script in order to pass localized strings to javascripts. 
	 */
	public function localize_scripts () {

		global $post;
		global $wp_query ;

		/** Assumption: post is either of type 'rooms' in which case the post ID
		 * for the post being view is what we want to pass to the client. If the
		 * post name happens to be that of the room detail page, then get the hsp-rooms-id
		 * parameter for the correct post ID to send to the client.
		*/

		$slider_on = true ;

		$wp_post = get_page_by_path( GUESTABA_ROOM_DETAIL_PAGE_NAME,  OBJECT, 'page' ) ;
		if ( ( $wp_post != null && $wp_post->ID == $post->ID ) || $post->post_name == GUESTABA_ROOM_DETAIL_PAGE_NAME  ) {

			if( isset($wp_query->query_vars[ GUESTABA_ROOM_DETAIL_ID_VAR] ) ) {
				$rooms_post_id = urldecode( $wp_query->query_vars[ GUESTABA_ROOM_DETAIL_ID_VAR ] );
			}
			else if ( isset($wp_query->query_vars[ GUESTABA_ROOM_DETAIL_NAME_VAR] )  ) {
				$rooms_post_name = urldecode( $wp_query->query_vars[ GUESTABA_ROOM_DETAIL_NAME_VAR ] );
				$rooms_post = get_page_by_path( $rooms_post_name,  OBJECT, 'rooms' ) ;
				$rooms_post_id = $rooms_post->ID;
			}
			else {
				// This means details page was called with the hsp-rooms-id query var.
				// That should result in a blank page. No point in going any further.
				// @todo: How should this case be handled? Display error? Log error?
				return ;
			}
		}
		else {
			$rooms_post_id = get_the_ID();
			$slider_on = false;
		}

		$room_slider_IDs = $this->get_slider_shortcode_IDs();

		if ( count( $room_slider_IDs ) > 0 ) {
			$slider_on = true;
		}

		$wp_js_info = array('site_url' => __(site_url()));

		wp_localize_script( $this->slider_js_handle , 'objectl10n', array(
			'wpsiteinfo' => $wp_js_info,
			'postID' => $rooms_post_id,
			'sliderOn' => $slider_on,
			'sliderPostIDs' => $room_slider_IDs,
			'js_slider_config_error' => __('Error retrieving slider JS options.',  GUESTABA_HSP_TEXTDOMAIN ),
			'server_error' => __('Server error:', GUESTABA_HSP_TEXTDOMAIN )
		));
	}
	
	
	/**
	 * Register plugin widget areas.
	 * 
	 * @since 1.0.0
	 */
	public function register_widget_areas () {

		register_sidebar( array(
			'name' => 'Room First Widget Area',
			'id' => 'room_first_widget_area',
			'before_widget' => '<div class="hsp_widget">',
			'after_widget' => '</div>',
			'before_title' => '<h2 class="hsp_widget_title">',
			'after_title' => '</h2>',
		) );

		register_sidebar( array(
	        'name' => 'Room Second Widget Area',
	        'id' => 'room_second_widget_area',
	        'before_widget' => '<div class="hsp_widget">',
	        'after_widget' => '</div>',
	        'before_title' => '<h2 class="hsp_widget_title">',
	        'after_title' => '</h2>',
	    ) );
	
	
	    register_sidebar( array(
	        'name' => 'Room Third Widget Area',
	        'id' => 'room_third_widget_area',
	        'before_widget' => '<div class="hsp_widget">',
	        'after_widget' => '</div>',
	        'before_title' => '<h2 class="hsp_widget_title">',
	        'after_title' => '</h2>',
	    ) );
	    

	}

	private function has_hsp_shortcodes() {

		global $post;

		$post_type = get_post_type();

		$has_shortcodes = false;
		if ( $post_type == "post"|| $post_type == "page" ) {

			foreach( Hospitality_Shortcodes::get_shortcodes() as $shortcode ) {
				if ( has_shortcode( $post->post_content, $shortcode ) ) {
					$has_shortcodes = true;
					break;
				}
			}
		}
		return $has_shortcodes;
	}

	private function get_slider_shortcode_IDs() {

		global $post;
		$pattern = get_shortcode_regex();

		preg_match_all('/'.$pattern.'/s', $post->post_content, $matches );

		$ids = array();
		foreach ( $matches[0] as $match ) {
			if ( preg_match('/room_images/', $match )) {
				if ( preg_match( '/id=\"?[0-9]+\"?/',$match, $id_attr_match ) ) {
					if ( preg_match('/[0-9]+/', $id_attr_match[0], $id_match ) ) {
						$ids[] = $id_match[0];
					}
				}
			}
		}

		return $ids;
	}

      
}