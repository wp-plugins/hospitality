<?php


/**
 * This class receives ajax requests for the plugin. 
 * 
 * @since      1.0.0
 * @package    Hospitality
 * @subpackage Hospitality/includes
 * @author     Wes Kempfer <wkempferjr@tnotw.com>
 */

class Hospitality_Public_Ajax_Controller {
	
	/*
	 * Function: execute_request
	 * 
	 * This function is registered as the ajax responder for the
	 * plugin in Wordpress. It calls subordinate functions in order
	 * to satisfy the request. The return string from the subordinate
	 * function is output as a client response directly in this function.
	 * 
	 * If an exception is caught by this function, data related to the
	 * exception are formated and sent as an error response to the
	 * client. 
	 * 
	 * @param none directly. Reads $_REQUEST for 'fn' (function) parameter. 
	 * 
	 * Request currently processed: 
	 * get_slider_config, get_amenity_set_list, get_pricing_model. 
	 * See corresponding functions in this class. 
	 * 
	 */
	
	public static function execute_request() {
		
		try {
			switch($_REQUEST['fn']){
				case 'get_slider_config':
					// If  not set, consider it an invalid request.
					if ( !isset( $_REQUEST['documentURL'] ) && !isset( $_REQUEST['postID'] ) ) {
						throw new Exception(_e('Invalid rooms slider request.', GUESTABA_HSP_TEXTDOMAIN ) );
					}
					$output = self::get_slider_config();
					if ( $output === false ) {
						throw new Exception(_e('Could not get slider config for post.', GUESTABA_HSP_TEXTDOMAIN ) );
					}
					break;
				case 'get_amenity_set_list':
					// If  not set, consider it an invalid request.
					if ( !isset( $_REQUEST['postID'] ) ) {
						throw new Exception(_e('Invalid get amenity set list request.', GUESTABA_HSP_TEXTDOMAIN ) );
					}
					$postID = $_REQUEST['postID'];
					$output = self::get_amenity_set_list( $postID );
					if ( $output === false ) {
						throw new Exception(_e('Could not get amenity set list with specified post ID.', GUESTABA_HSP_TEXTDOMAIN ) );
					}
					break;
				case 'get_pricing_model':
					// If  not set, consider it an invalid request.
					if ( !isset( $_REQUEST['postID']) || $_REQUEST['postID'] == 'undefined' ) {
						throw new Exception(_e('Invalid get price model request.', GUESTABA_HSP_TEXTDOMAIN ) );
					}
					$postID = $_REQUEST['postID'];
					$output = self::get_pricing_model( $postID );
					if ( $output === false || empty( $output) ) {
						throw new Exception(__('Could not get price model with specified post ID.', GUESTABA_HSP_TEXTDOMAIN ) );
					}
					break;
				case 'get_post_edit_options':
					$output = self::get_post_edit_options();
					if ( $output === false || empty( $output) ) {
						throw new Exception(__('Could not get post edit options.', GUESTABA_HSP_TEXTDOMAIN ) );
					}
					break;

					break;
				default:
					$output = __('Unknown ajax request sent from client.', GUESTABA_HSP_TEXTDOMAIN );
					break;
	
			}
		} 
		catch ( Exception $e ) {
			$errorData = array(
				'errorData' => 'true',
				'errorMessage' => $e->getMessage(),
				'errorTrace' => $e->getTraceAsString()
			);
			$output = $errorData;
		}

		// Convert $output to JSON and echo it to the browser 
	
		$output=json_encode($output);
		if(is_array($output)){
			print_r($output);	
 		}
		else {
			echo $output;
	     }
		die;
	}


	/**
	 *
	 * Function: get_post_edit_options
	 *
	 * @return array returns options that are required for javascript configuration on client-side input elements.
	 */
	private static function get_post_edit_options() {

		$option = get_option( GUESTABA_HSP_OPTIONS_NAME );
		$post_edit_options = array();
		$post_edit_options['room_excerpt_max_char_count'] = $option['hsp_room_excerpt_len'];
		return $post_edit_options;
	}

	private static function get_slider_config() {

		$slider_config_array = array();
		if ( isset($_REQUEST['sliderPostIDs']) && count( $_REQUEST['sliderPostIDs']) > 0 ) {
			foreach ( $_REQUEST['sliderPostIDs'] as $postID ) {
				$slider_config_array[] = self::get_slider_config_meta( $postID );
			}

		}
		else {
			$slider_config_array[] = self::get_slider_config_meta( $_REQUEST['postID'] );
		}

		return $slider_config_array;

	}

	/*
	 * Function: get_slider_config_meta()
	 * 
	 * Retrieved the slider configuration settings: navigaition on/off, 
	 * slider animation effect, duration, pause on hover, width of slider.
	 * @todo move this to Rooms Post Type class
	 * 
	 * 
	 *  @param none via $_REQUEST.
	 *  @return array slider_config
	 *  
	 *  Exceptions thrown: database-related exceptions may be thrown by this function. 
	 */
	
	private static function get_slider_config_meta( $postID ) {

		$slider_config = array();
		
		$meta = get_post_meta( $postID );
		if ( $meta === false) {
			Hospitality_Logger::log_error(__('No metadata for post', GUESTABA_HSP_TEXTDOMAIN));
			return false ;
		}

		$slider_config['post_id'] = $postID;

		/* navigaion true|false */
		$slider_config['navigation_on'] = true;
		
		/* effect */
		$slider_config['effect'] = $meta['meta_room_slider_animation_effect'][0];
		
		/* slide duration, in ms */
		$slider_config['slide_duration'] = $meta['meta_room_slider_animation_speed'][0] ;
		
		/* auto animation */
		$slider_config['auto_animation'] =  ( $meta['meta_room_slider_animation_auto'][0] == "on" ) ? true : false;
		
		/* animation duration, in ms */
		$slider_config['animation_duration'] = $meta['meta_room_slider_animation_duration'][0] ;
		
		/* pause on hover */
		$slider_config['pause_on_hover'] =  ( $meta['meta_room_slider_animation_pause'][0] == "on" ) ? true : false;
		
		$slider_config['width'] = GUESTABA_HSP_SLIDER_WIDTH;
		$slider_config['height'] = GUESTABA_HSP_SLIDER_HEIGHT;
		
		return $slider_config ;	
	
	}
	
	/*
	 * Get and return amenity set for specified post ID. 
	 * 
	 * @param string postID
	 * @return array amenity set list for specified rooms post. 
	 *
	 * @since 1.0.0
	 */
	private static function get_amenity_set_list( $postID ) {
			return Amenity_Sets_Post_Type::get_amenity_list($postID);
	}
	

	/*
	 * Get and return pricing model for specified post ID. 
	 * 
	 * @param string postID
	 * @return array containing pricing model for specified rooms post. 
	 *
	 * @since 1.0.0
	 */
	private static function get_pricing_model( $postID ) {
			return Pricing_Models_Post_Type::get_pricing_model($postID);
	}
	
	/*
	 * Provides object access to exec request static function. 
	 * 
	 * @param none
	 * @return non
	 * @see execute_request()
	 */
	
	public function hospitality_ajax() {
		self::execute_request();
	}
	
}
?>