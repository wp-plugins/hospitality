<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       http://guestaba.com
 * @since      1.0.0
 *
 * @package    Hospitality
 * @subpackage Hospitality/admin
 * @author     Wes Kempfer <wkempferjr@tnotw.com>
 */
class Hospitality_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $hospitality    The ID of this plugin.
	 */
	private $hospitality;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/** Handle for hospitality-admin javascript.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $hospitality_admin_js_handle
	 */
	private $hospitality_admin_js_handle = 'hospitality-room-admin-js';
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $hospitality       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $hospitality, $version ) {

		$this->hospitality = $hospitality;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the Dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
		wp_enqueue_style( $this->hospitality, plugin_dir_url( __FILE__ ) . 'css/hospitality-admin.min.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script( 'gst-character-counter', plugin_dir_url( __FILE__ ) . 'js/vendor/jquery.simplyCountable.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->hospitality, plugin_dir_url( __FILE__ ) . 'js/hospitality-admin.min.js', array( 'jquery' ), $this->version, false );




	}
	
	/**
	 * Runs wp_localize_script in order to pass localized strings to javascripts. 
	 * 
	 * @since    1.0.0
	 */
	public function localize_scripts () {
		
		$wp_js_info = array('site_url' => __(site_url()));
		
		wp_localize_script( $this->hospitality , 'hsp_admin_objectl10n', array(
			'wpsiteinfo' => $wp_js_info,
			'get_amenity_set_list_error' => __('Error retrieving amenity set list.',  GUESTABA_HSP_TEXTDOMAIN ),
			'get_pricing_model_error' => __('Error retrieving pricing model.',  GUESTABA_HSP_TEXTDOMAIN ),
			'server_error' => __('Server error:', GUESTABA_HSP_TEXTDOMAIN ),
			'note_no_amnenity_set_selected' => __('No amenity set selected.'),
			'note_no_pricing_model_selected' => __('No pricing model selected.')
		));
	}

}
