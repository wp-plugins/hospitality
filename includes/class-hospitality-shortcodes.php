<?php

/**
 * This class defines and maintains the all plugin shortcodes which are:
 * 
 * 	amenties -- list amenties for current or specified room post
 * 	pricings -- list pricing model for current or specified room post
 * 	room_images -- display images/sliders for current of specified room post
 *  room_slogan -- display the room slogan
 *  room_desc -- display the room description
 *  room_thumbnail -- display the room thumbnail (derived from featured image)
 *  room_price_range -- display room price range (min and max from pricing model)
 *  room_listing -- will display a full listing of the current or specified room.
 * 
 * @link       http://guestaba.com
 * @since      1.0.0
 * @package    Hospitality
 * @subpackage Hospitality/includes
 * @author     Wes Kempfer <wkempferjr@tnotw.com>
 */
class Hospitality_Shortcodes {
	
	/* 
	 * Used to store an instance of Hospitalty_Settings
	 */
	private $settings ;

	/*
	 * A list of all of the shortcodes.
	 */
	private static $shortcodes = array(
		'room_amenities',
		'amenity_set',
		'pricings',
		'room_images',
		'room_slogan',
		'room_desc',
		'room_thumbnail',
		'room_price_range',
		'room_listing',
		'room_detail',
		'room_excerpt',
		'room_detail_link'
	);

	
	/*
	 * Constructor
	 */
	
	public function __construct() {
		$this->settings = new Hospitality_Settings();
	}
	
	/*
	 * Called to register all shortcodes for the plugin
	 * @since 1.0.0
	 */
	
	public function register_shortcodes () {

		foreach ( self::$shortcodes as $shortcode ) {
			add_shortcode( $shortcode, array( $this, $shortcode));
		}
		
	}

	/*
	 * Function: get_shortcodes
	 * @return array shortcodes an array of the plugin shortcode names.
	 */

	public static function get_shortcodes() {
		return self::$shortcodes;
	}

	/*
	 * Room Amenities list shortcode
	 * 
	 * attributes: 
	 * 	icon: checkmark | arrow | custom
	 *  icon url: url to list icon if custom specified
	 *  id: Room CPT post ID. Defaults to current post. ID should be for a room post.
	 *  
	 *  @since 1.0.0
	 */
 
	public function room_amenities ( $atts ) {
		
		$default_id = get_the_ID();

		/** @var $icon string */
		/** @var  $icon_url string */
		/** @var  $title_class string */
		/** @var $title string  */
		/** @var  $list_class string */
		/** @var  $id string  */

		$atts_actual = shortcode_atts(
			array(
				'icon' => get_post_meta($default_id, 'meta_room_amenity_list_icon', true),
				'icon_url' => '',
				'title_class' => 'amenities_title',
				'title' => $this->settings->get_amenities_title(),
				'list_class' => 'rooms_amenities_list',			
				'id'  => $default_id
			), 	
			$atts );
		
		extract( $atts_actual );



		switch ($icon) {
				case 'checkmark':
					$icon_url	= dirname( plugin_dir_url(__FILE__) ) . '/public/img/checkmark.png';
					break;

				case 'arrow':
					$icon_url	= dirname( plugin_dir_url(__FILE__) ). '/public/img/arrow.png';
					break;
					
				case 'custom':
					$icon_url = $atts_actual['icon_url'] ;
					break;
				
				default:
					$icon_url	= dirname( plugin_dir_url(__FILE__) ) . '/public/img/checkmark.png';
					break;
		}
			
		
		/** To retrieve amenities, get post ID for amenity set post type instance, then retieve them from the amenity set post */
		$room_amenity_post_ID		 = get_post_meta($id, 'meta_room_amenity_select', true);
		
		$amenities_set_list = array();		
		if ( ! empty( $room_amenity_post_ID ) ) {
			$amenities_set_list          = get_post_meta($room_amenity_post_ID, 'meta_amenity_set_list', true);
		}
		
		$room_specific_amenities     = get_post_meta($id, 'meta_room_amenity_list', true);
		if ( empty( $room_specific_amenities) ) {
			$room_specific_amenities = array();
		}
		$room_amenities				 = array_merge($amenities_set_list, $room_specific_amenities );
		
		$output_amenity = '';
		foreach( $room_amenities as $room_amenity ) { 
			$output_amenity .= '<li>' . $room_amenity['title'] . '</li>';
		}

		$output = '<h2 class="' . $title_class . '">' . $title . '</h2>';
		$output .= '<ul style="list-style-image: url(' . $icon_url . ')" class="' . $list_class . '">' . $output_amenity . '</ul>';
				
		return $output ;
	}


	/*
 * Amenity Set  shortcode
 *
 * attributes:
 * 	icon: checkmark | arrow | custom
 *  icon url: url to list icon if custom specified
 *  id: Amenity CPT post ID. Defaults to current post. ID Should be an ID for an amenity_set post.
 *
 *  @since 1.0.0
 */

	public function amenity_set ( $atts ) {

		$default_id = get_the_ID();

		/** @var $icon string */
		/** @var  $icon_url string */
		/** @var  $title_class string */
		/** @var $title string  */
		/** @var  $list_class string */
		/** @var  $id string  */

		$atts_actual = shortcode_atts(
			array(
				'icon' => 'checkmark',
				'icon_url' => '',
				'title_class' => 'amenities_title',
				'title' => $this->settings->get_amenities_title(),
				'list_class' => 'rooms_amenities_list',
				'id'  => $default_id
			),
			$atts );

		extract( $atts_actual );



		switch ($icon) {
			case 'checkmark':
				$icon_url	= dirname( plugin_dir_url(__FILE__) ) . '/public/img/checkmark.png';
				break;

			case 'arrow':
				$icon_url	= dirname( plugin_dir_url(__FILE__) ). '/public/img/arrow.png';
				break;

			case 'custom':
				$icon_url = $atts_actual['icon_url'] ;
				break;

			default:
				$icon_url	= dirname( plugin_dir_url(__FILE__) ) . '/public/img/checkmark.png';
				break;
		}



		$amenities_set_list = array();
		if ( ! empty( $id ) ) {
			$amenities_set_list          = get_post_meta($id, 'meta_amenity_set_list', true);
		}


		$output_amenity = '';
		foreach( $amenities_set_list as $room_amenity ) {
			$output_amenity .= '<li>' . $room_amenity['title'] . '</li>';
		}

		$output = '<h2 class="' . $title_class . '">' . $title . '</h2>';
		$output .= '<ul style="list-style-image: url(' . $icon_url . ')" class="' . $list_class . '">' . $output_amenity . '</ul>';

		return $output ;
	}

	/*
	 * Define the pricings shortcode. 
	 * 
	 * @param array $atts
	 * @return string shortcode output;
	 */
	public function pricings ( $atts ) {
		
		$default_id = get_the_ID();


		/** @var  $id string */
		/** @var  $currency_symbol */
		/** @var  $pricing_container_class string */
		/** @var  $pricing_heading_class string */
		/** @var  $pricing_table_class string */
		/** @var  $pricing_cell_class string */
		/** @var  $pricing_title_class string */
		/** @var  $pricing_date_group string */
		/** @var  $pricing_date_group_class string */
		/** @var  $pricing_date_class string */
		/** @var  $pricing_class string */
		$atts_actual = shortcode_atts(
			array(
				'currency_symbol' => $this->settings->get_currency_symbol(),
				'pricing_container_class' => 'rooms_pt_wrapper',
				'pricing_heading_class' => 'rooms_pricing_heading',
				'pricing_table_class' => 'rooms_pt',
				'pricing_cell_class' => 'rooms_pt_cell',
				'pricing_title_class' => 'rooms_price_title',
				'pricing_date_group_class' => 'rooms_price_date',
				'pricing_date_class' => 'rooms_price_date_item',
				'pricing_class' => 'rooms_price',
				'id'  => $default_id
			), 	
			$atts );
		
		extract( $atts_actual );
			
		
		/** To retrieve room pricings, get pricing model post ID, then get pricings from pricing model post */
		$pricing_model_post_ID       = get_post_meta($id, 'meta_room_pricing_select', true);
		$room_pricings               = get_post_meta($pricing_model_post_ID, 'meta_pricing_model_list', true);
		
		if ( ! empty( $room_pricings ) ) {
			$i = 0;
			$output_pricing = '<div class="' . $pricing_container_class . ' clearfix"><h2 class="'. $pricing_heading_class. '">' . __('Room Pricing', GUESTABA_HSP_TEXTDOMAIN )  . '</h2><ul class="rooms_pricing_list">';
			foreach( $room_pricings as $room_pricing ) { $output_pricing .= '
				<li>
					<div class="' . $pricing_table_class . '">
						<div class="'. $pricing_cell_class . '">
							<div class="rooms_pt_cell_inner clearfix">
								<h3 class="' . $pricing_title_class . '">' . $room_pricing['title'] . '</h3>'; 

								if ( ! empty( $room_pricing['meta_room_pricing_date01']['date_start'] ) ) {
									$room_pricing_date_one = $room_pricing['meta_room_pricing_date01']['date_start'] . '-' . $room_pricing['meta_room_pricing_date01']['date_end'];
								}
								else {
									$room_pricing_date_one = '';
								}

								if ( ! empty( $room_pricing['meta_room_pricing_date02']['date_start'] ) ) {
									$room_pricing_date_two = '<br>' . $room_pricing['meta_room_pricing_date02']['date_start'] . '-' . $room_pricing['meta_room_pricing_date02']['date_end'];
								}
								else {
									$room_pricing_date_two = '';
								}

								if ( ! empty( $room_pricing['meta_room_pricing_date03']['date_start'] ) ) {
									$room_pricing_date_three = $room_pricing['meta_room_pricing_date03']['date_start'] . '-' . $room_pricing['meta_room_pricing_date03']['date_end'];
								} 
								else {
									$room_pricing_date_three = '';
								}

								$currency = is_numeric($room_pricing['meta_room_price']) ? $currency_symbol : '';
								
								$output_pricing .= '
								<div class="' . $pricing_date_group_class . '">
									<span class="'. $pricing_date_class . '">' . $room_pricing_date_one . '</span>
									<span class="'. $pricing_date_class . '">' . $room_pricing_date_two . '</span>
									<span class="'. $pricing_date_class . '">' . $room_pricing_date_three . '</span>
								</div>										
								<div class="rooms_price_content">
									<span class="' . $pricing_class . '">' . $currency . $room_pricing['meta_room_price'] . '</span>
								</div>
							</div>
						</div>
					</div>
				</li>';
				$i++;
			}
			$output_pricing .= '</ul></div>';
			return $output_pricing;
		}

		/** if no pricing data, return empty string */
		return "";
	}
	
	/*
	 * Define the room images shortcode 
	 * @since 1.0.0
	 * @param array $atts
	 * @return string shortcode output.
	 */
	public function room_images( $atts ) {
			
		$default_id = get_the_ID();

		/** @var  $image_anchor_class string */
		/** @var  $image_class string */
		/** @var  $slider_container_class string */
		/** @var  $slider_class string */
		/** @var  $slider_image_class string */
		/** @var  $id string */
		$atts_actual = shortcode_atts( 
			array(
				'image_anchor_class' => 'rooms_single_image_ref',
				'image_class' => 'rooms_single_imager',
				'slider_container_class' => 'hsp_slider',
				'slider_class' => 'hsp_slide',
				'slider_image_class' => 'slider_item',
				'id'  => $default_id
			), 	
			$atts );
		
		extract( $atts_actual );		
		
		$room_image                  = get_post_meta($id, 'meta_room_image', true);
		$room_sliders                = get_post_meta($id, 'meta_room_slider', true);

		/** @todo These are not used here. Double check that they can be removed. */

		// Used client-side only
		// $room_animation_effect       = get_post_meta($id, 'meta_room_slider_animation_effect', true);
		// $room_animation_duration     = get_post_meta($id, 'meta_room_slider_animation_duration', true);
		// $room_animation_auto         = get_post_meta($id, 'meta_room_slider_animation_auto', true);
		// $room_animation_speed        = get_post_meta($id, 'meta_room_slider_animation_speed', true);
		// $room_animation_pause        = get_post_meta($id, 'meta_room_slider_animation_pause', true);

		$output = "";
		if( ! empty( $room_image ) ) {
			/** TODO: add titles, captions, and descriptions to single room image. */
			$output = '<a href="' . $room_image . '" class="' . $image_anchor_class . '"><img class="'. $image_class . '" src="' . $room_image . '"></a>' ;
			
		} elseif( ! empty( $room_sliders ) ) {
	
			
			$output = '<div id="' . GUESTABA_SLIDER_PREFIX . $id . '" class="rooms_slider_container">';
    		$output .='<div class="' . $slider_container_class . '">';
   														
			foreach( $room_sliders as $room_slider ) {
				$output .= '<div class="' . $slider_class . '">';				
				$output .= '<img class="'. $slider_image_class . '" alt="' . $room_slider['title'] . '" src="' . $room_slider['meta_room_slider_image'] . '"/>';
				$output .= '</div>';
			}	
				
			$output .= '</div>';			
		 	$output .= '</div>';	
		 } 
		
		 return $output;
	}
	
	/*
	 * Define the room slogan shortcode. 
	 * @since 1.0.0
	 * @param array $atts
	 * @return string shortcode output.
	 */
	public function room_slogan( $atts ) {
		
		$default_id = get_the_ID();

		/** @var $slogan_class string */
		/** @var $slogan_tag */
		/** @var $id string */
		
		$atts_actual = shortcode_atts( 
			array(
				'slogan_class' => 'room-slogan',
				'slogan_tag' => 'h2',
				'id'  => $default_id
			), 	
			$atts );
		
		extract( $atts_actual );
			
		$room_slogan = get_post_meta($id, 'meta_room_slogan', true);
		
		$output = "";
		if( ! empty( $room_slogan ) ) {
			$output = '<' . $slogan_tag . ' class="'. $slogan_class . '">' . $room_slogan . '</' . $slogan_tag . '>';
		}
		
		return $output;
	}


	/*
	 * Define the room slogan shortcode.
	 * @since 1.0.0
	 * @param array $atts
	 * @return string shortcode output.
	 */
	public function room_excerpt( $atts ) {

		$default_id = get_the_ID();

		// Use plugin option as default excerpt len
		$option = get_option( GUESTABA_HSP_OPTIONS_NAME );
		$excerpt_len_default = $option['hsp_room_excerpt_len'];

		/** @var $excerpt_class string */
		/** @var $excerpt_tag */
		/** @var $excerpt_len */
		/** @var $id string */

		$atts_actual = shortcode_atts(
			array(
				'excerpt_class' => 'room-excerpt',
				'excerpt_tag' => 'p',
				'excerpt_len' => $excerpt_len_default,
				'id'  => $default_id
			),
			$atts );

		extract( $atts_actual );

		$room_excerpt = get_post_meta($id, 'meta_room_excerpt', true);



		$room_excerpt = substr( $room_excerpt, 0, $excerpt_len);

		$output = "";
		if( ! empty( $room_excerpt ) ) {
			$output = '<' . $excerpt_tag . ' class="'. $excerpt_class . '">' . $room_excerpt . ' ' .  do_shortcode('[room_detail_link]') . '</' . $excerpt_tag . '>';
		}

		return $output;
	}


/*
 * Define the room detail link shortcode.
 * @since 1.0.0
 * @param array $atts
 * @return string shortcode output.
 */
	public function room_detail_link( $atts ) {

		$default_id = get_the_ID();

		/** @var $link_text string */
		/** @var $link_class */
		/** @var $id string */

		$atts_actual = shortcode_atts(
			array(
				'link_text' => __('[See details]', GUESTABA_HSP_TEXTDOMAIN ),
				'link_class' => 'room-detail-link',
				'id'  => $default_id
			),
			$atts );

		extract( $atts_actual );

		global $post;

		$room_detail_page = $this->get_room_detail_url( $id );

		if ( $room_detail_page == false) {
			$room_detail_page = "#";
			error_log(__FILE__ . ", line number:" . __LINE__ . " Could not get room detail URL.");
		}

		$target_post = get_post( $id );
		$output = '<a class="' . $link_class . '" href="' .  $room_detail_page . '" title="' . $target_post->post_name . '">' . $link_text . '</a>' ;


			return $output;
	}


	/*
	 * Define the room description shortcode. 
	 * @since 1.0.0
	 * @param array $atts
	 * @return string shortcode output.
	 */
	public function room_desc ( $atts ) {
		
		$default_id = get_the_ID();


		/** @var $room_desc_class string */
		/** @var $more_anchor_class string */
		/** @var $more_anchor_title string */
		/** @var $more_text string */
		/** @var $id string */
		/** @var $excerpt_length string */
		$atts_actual = shortcode_atts( 
			array(
				'room_desc_class' => 'room-desc',
				'more_anchor_class' => 'rooms_more_details',
				'more_anchor_title' => __('Click here for all room details.', GUESTABA_HSP_TEXTDOMAIN ),
				'more_text' => __('...Room Details', GUESTABA_HSP_TEXTDOMAIN ),
				'id'  => $default_id,
				'excerpt_length' => -1
			), 	
			$atts );
				
		extract( $atts_actual );
		
		$room_desc = get_post_meta( $id, 'meta_room_desc', true);

		$output = '';
		if( ! empty( $room_desc ) ) {

			global $post ;

			if ( $_SERVER['REQUEST_URI'] == '/' . GUESTABA_ROOMS_LISTING_PAGE_NAME . '/' ) {
				$room_detail_page = get_site_url() . '/' . GUESTABA_ROOM_DETAIL_PAGE_NAME . '/'  . $post->post_name . '/';
			}
			else {

				$room_detail_page_id = get_post_meta( $id, 'meta_room_detail_page', true );
				if ( empty( $room_detail_page_id ) ) {
					$room_detail_page = get_post_permalink();
				} else {
					$room_detail_page = get_post_permalink( $room_detail_page_id );
				}
			}


			/** @todo This code might support word-based excerpt maxiumum. Remove if feature is not requested within a few months. */
			if ( $excerpt_length > 0 ) {
				$max_desc_word_count = $excerpt_length ;
	            $more_link = '<a class="' . $more_anchor_class . '" href="' .  $room_detail_page . '" title="' . $more_anchor_title . '">' . $more_text . '</a>' ;
	            $word_count = str_word_count( strip_tags( $room_desc ) );
	            if ( $word_count > $max_desc_word_count ) {
	            	$trimmed_desc = wp_trim_words( $room_desc, $max_desc_word_count , $more_link );
	            }
	            else {
	            	$trimmed_desc = $room_desc . $more_link ; 
	            }
	            $room_desc =  $trimmed_desc ;
			}	
			$output =  '<p class="' . $room_desc_class . '">' . $room_desc . '</p>';
			

		}

		return $output;
	}
	
	/*
	 * Define the room thumbnail shortcode. 
	 * @since 1.0.0
	 * @param array $atts
	 * @return string shortcode output.
	 */
	public function room_listing( $atts ) {

		$default_id = get_the_ID();

		/** @var  $title */
		/** @var  $id */
		/** @var  $header_tag */
		/** @var  $entry_tag */
		$atts_actual = shortcode_atts(
			array(
				'title' => __('Rooms & Rates', GUESTABA_HSP_TEXTDOMAIN ),
				'header_tag' => 'h2',
				'entry_tag' => 'h3',
				'id'  => $default_id
			),
			$atts );

		extract( $atts_actual );

		$args=array(
			'post_type' => 'rooms',
			'post_status' => 'publish',
			'posts_per_page' => -1
		);

		$rm_query = new WP_Query( $args );

		$output = "";
		if ( $rm_query->have_posts() ) {
			$output = '<div id="rooms_rates">';
			$output .= '<header class="rooms-rates-header entry-header">';
			if ( ! empty( $title ) ) {
				$output .= '<' . $header_tag . ' class="rooms-rates-title entry-title">';
				$output .= $title;
				$output .= '</' . $header_tag . '>';
			}
			$output .= '</header>';

			$output .= '<div class="row" data-equalizer="card-section-eq">';

			while ( $rm_query->have_posts() ) : $rm_query->the_post();

				$output .= '<div class="small-12 medium-4 large-4 columns">';
				$output .= '<div class="card">';
				$output .= do_shortcode( '[room_thumbnail]' );
				$output .= '<div class="card-divider">';
				$output .= do_shortcode( '[room_price_range label=""]' );
				$output .= '</div>';
				$output .= '<div class="card-section" data-equalizer-watch="card-section-eq">';
				$output .= '<' . $entry_tag . '>' . get_the_title() . '</' . $entry_tag . '>';
				$output .= do_shortcode( '[room_excerpt]' );
				$output .= '</div>';

				$output .= '</div>'; // end card
				$output .= '</div>'; // end column

			endwhile;

			$output .= '</div>'; // end row


			$output .= '</div>';

		}
		wp_reset_postdata();

		return $output;



	}

	public function room_thumbnail( $atts ) {

		$default_id = get_the_ID();

		/** @var  $id */
		/** @var $thumbnail_anchor_class */
		/** @var $thumbnail_image_class */
		/** @var  $size */

		$atts_actual = shortcode_atts(
			array(
				'thumbnail_anchor_class' => 'rooms_img_link',
				'thumbnail_image_class' => 'room-thumbnail',
				'size' => array(200,200),
				'id'  => $default_id
			),
			$atts );

		extract( $atts_actual );

		$tn_output = '';
	    /** TODO: add fancy box functionality */
		if ( has_post_thumbnail( $id ) ) {
			$post_thumbnail_id = get_post_thumbnail_id( $id );
			$post_thumbnail_url = wp_get_attachment_url( $post_thumbnail_id );

			$large_image_url = wp_get_attachment_image_src( $post_thumbnail_id, 'large' );
			$tn_output .=  '<a class="' . $atts_actual['thumbnail_anchor_class'] . '" href="' . $this->get_room_detail_url( $id ) . '" title="' . the_title_attribute( 'echo=0' ) . '">';
			// $tn_output .= get_the_post_thumbnail( $id, $atts_actual['size'] , array( 'class' => $thumbnail_image_class ) );
			$tn_output .= '<img title="image title" alt="thumb image" class="wp-post-image ' . $thumbnail_image_class . '" src="' . $post_thumbnail_url . '">';
			$tn_output .= '</a>';
		}

		return $tn_output;
	}
	/*
	 * Define the room price range shortcode.
	 * @since 1.0.0
	 * @param array $atts
	 * @return string shortcode output.
	 */

	public function room_price_range( $atts ) {

		$default_id = get_the_ID();


		/** @var $default_price_range */
		/** @var $label_class */
		/** @var $label */
		/** @var $price_range_class */
		/** @var $id */
		$atts_actual = shortcode_atts(
			array(
				'default_price_range' => __('Call', GUESTABA_HSP_TEXTDOMAIN ),
				'label_class' => 'room_rates_label',
				'label' =>  __('Rates (depending on season:)', GUESTABA_HSP_TEXTDOMAIN ),
				'price_range_class' => 'rooms_price',
				'id'  => $default_id
			),
			$atts );

		extract( $atts_actual );

		/** To retrieve room pricings, get pricing model post ID, then get pricings from pricing model post */
		$pricing_model_post_ID       = get_post_meta($id, 'meta_room_pricing_select', true);
		$room_pricings               = get_post_meta($pricing_model_post_ID, 'meta_pricing_model_list', true);

	    if ( ! empty( $room_pricings ) ) {
        	$output_pricing = array();
            foreach( $room_pricings as $room_pricing ) {
            	if (is_numeric($room_pricing['meta_room_price'])) {
                	$output_pricing[] = $room_pricing['meta_room_price'];
                }

           	}
            $room_price_range = $this->settings->get_currency_symbol() . min($output_pricing) . ' - ' . max($output_pricing);

		}
		else {
			$room_price_range = $default_price_range;
		}

		$output =  '<p class="' . $label_class . '">' . $label . ' <span class="' . $price_range_class . '">' . $room_price_range . '</span></p>';

		return $output;


	}

	/*
	 * Define the room listing shortcode. Displays all rooms
	 * as specified by current settings.
	 *
	 * @since 1.0.0
	 * @param array $atts
	 * @return string shortcode output.
	 */

	public function room_detail( $atts ){


		/** @var  $id */
		/** @var $room_title_tag */
		/** @var  $room_detail_title  */

		$atts_actual = shortcode_atts(
			array(
				'id'  => '',
				'room_title_tag' => 'h3',
				'room_detail_title' => __( 'Back to room listing',GUESTABA_HSP_TEXTDOMAIN )
			),
			$atts );


		extract( $atts_actual );

		$output = '<div id="room-listing">';
		$output = '<' . $room_title_tag  . ' id="room-post-title">' . get_the_title( $id ) . '</' . $room_title_tag . '>';
		$output .= '<div id="back-to-rooms-list">';
		$output .= '<a href="'  . get_site_url() . '/' . GUESTABA_ROOMS_LISTING_PAGE_NAME . '/" title="' . $room_detail_title . '">' . $room_detail_title . '</a>';
		$output .= '</div>';
		$output .= '<div id="room_images_container">';

		$alternate_shortcode = get_post_meta($id, 'meta_room_alterate_image_shortcode', true);
		if ( ! empty( $alternate_shortcode )) {
			$output .= do_shortcode( $alternate_shortcode );
		}
		else {
			$output .= do_shortcode('[room_images id="'. $id . '"]');
		}

		$output .=  '</div>';

		$output .= '<div id="room_bottom_container">';
		$output .= '<div id="room_desc_container">';
		$output .= do_shortcode('[room_slogan id="' . $id . '"]');
		$output .= do_shortcode('[room_desc id="' . $id . '"]');
		$output .= '</div>';

		$output .= '<div class="room_first_widget_area">';

		ob_start();
		dynamic_sidebar('room_first_widget_area');
		$sb = ob_get_contents();
		ob_end_clean();
		$output .= $sb;

		$output .= '</div>';

        $output .= '<div class="row hsp_row">';

		$output .= '<div class="room_amenities large-8 columns">';
		$output .=  do_shortcode('[room_amenities id="' . $id . '"]');
		$output .= '</div>';



		$output .= '<div class="room_second_widget_area large-4 columns">';


		ob_start();
		dynamic_sidebar('room_second_widget_area');
		$sb = ob_get_contents();
		ob_end_clean();
		$output .= $sb;
		$output .= '</div>';



	    $output .= '</div>'; /* end row */



		$output .= '<div id="pricings_container">';
		$output .= do_shortcode('[pricings id="' . $id . '"]');
		$output .= '</div>';


		$output .= '<div class="room_third_widget_area">';

		ob_start();
		dynamic_sidebar('room_third_widget_area');
		$sb = ob_get_contents();
		ob_end_clean();
		$output .= $sb;
		$output .= '</div>';

		$output .= '</div>';

		$output .= '</div>'; /* end room_bottom_container */

		return $output;
	}

	/**
	 *
	 * Function: get_room_detail_url
	 *
	 * @return string the URL for the current room.
	 */

	private function get_room_detail_url( $id ) {



		$override_detail_page = get_post_meta( $id , 'meta_room_detail_page', true);

		if ( !isset( $override_detail_page )  || empty( $override_detail_page) || $override_detail_page == 0 ) {
			$target_post = get_post( $id );
			$room_detail_page = get_site_url() . '/' . GUESTABA_ROOM_DETAIL_PAGE_NAME . '/'  . $target_post->post_name . '/';

		}
		else {
			$room_detail_page = get_permalink( $override_detail_page ) ;
		}

		return $room_detail_page;
	}
	
}


?>