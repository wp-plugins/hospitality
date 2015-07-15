<?php


/**
 * Class Hospitality_Rooms_Meta_Box
 *
 * This class defines the appearance and behavior of the metbox associated with
 * the rooms custom post type.
 */
class Hospitality_Rooms_Meta_Box extends Hospitality_Meta_Box {

	/**
	 * Class constructor
	 * @since 1.0.0
	 * @param none
	 * @return an instance of Hospitality_Rooms_Metabox
	 *
	 */
	public function __construct() {

		$this->setPostType( 'rooms' );
		$this->setMetaBoxID(  'rooms_cpt_meta_box' );
		$this->setMetaBoxTitle(  __( 'Rooms Display Options', GUESTABA_HSP_TEXTDOMAIN ) );
		$this->setNonceId( 'rooms_mb_nonce');
		$this->init_tooltips();
	}


	/**
	 * Function remove_meta_boxes
	 *
	 * Removes other metaboxes on the dashboard that are not pertinent to the rooms custom post type.
	 *
	 * @param none
	 * @return void
	 */
	public function remove_meta_boxes () {
		remove_meta_box('revisionsdiv', 'rooms', 'norm');
		remove_meta_box('slugdiv', 'rooms', 'norm');
		remove_meta_box('authordiv', 'rooms', 'norm');
		remove_meta_box('postcustom', 'rooms', 'norm');
		remove_meta_box('postexcerpt', 'rooms', 'norm');
		remove_meta_box('trackbacksdiv', 'rooms', 'norm');
		remove_meta_box('commentsdiv', 'rooms', 'norm');
		remove_meta_box('pageparentdiv', 'rooms', 'norm');
	}


	/**
	 * Function meta_box_render
	 *
	 * This is the render callback function for the rooms CPT metabox.
	 *
	 * @param none
	 * @return void
	 */
	public function meta_box_render( ) {

		global $post ;


		wp_nonce_field( basename( __FILE__ ), $this->getNonceId() );

		$post_ID = $post->ID;

		$enq_media_args = array( 'post' => $post_ID );
		wp_enqueue_media( $enq_media_args );

		/**
		 *
		 * Content settings section
		*/
		echo '<div class="gst_settings_container">';

		$this->section_heading(__('Content Settings', GUESTABA_HSP_TEXTDOMAIN), 'gst-mb-content-settings');

		$this->text_area(    __('Slogan', GUESTABA_HSP_TEXTDOMAIN ),
							get_post_meta( $post_ID, 'meta_room_slogan', true),
							2,
							70,
							'meta_room_slogan'
						);


		/** @todo this functionality should be supported in the superclass */
		$this->label( __('Description', GUESTABA_HSP_TEXTDOMAIN), 'meta_room_desc');
		$desc = get_post_meta( $post_ID, 'meta_room_desc', true);
		wp_editor( $desc, 'room_description', array(
			'wpautop'       => true,
			'media_buttons' => false,
			'textarea_name' => 'meta_room_desc',
			'textarea_rows' => 10,
			'teeny'         => true
		) );




		$this->text_area (   __('Excerpt',
							GUESTABA_HSP_TEXTDOMAIN) ,
							get_post_meta( $post_ID, 'meta_room_excerpt', true),
							3,
							70,
							'meta_room_excerpt',
							'',
							true
						);

		$this->page_select( __('Override Details Page',
							GUESTABA_HSP_TEXTDOMAIN) ,
							get_post_meta( $post_ID, 'meta_room_detail_page', true),
							'meta_room_detail_page' );

		echo '</div>';

		/**
		 * Amenities setting section
		 */

		echo '<div class="gst_settings_container">';

		$this->section_heading(__('Amenity Settings', GUESTABA_HSP_TEXTDOMAIN), 'gst-mb-amenity-settings');

		$icon_types[0] = array( 'label' => __('Arrow', GUESTABA_HSP_TEXTDOMAIN), 'value' => 'arrow');
		$icon_types[1] = array( 'label' => __('Check Mark', GUESTABA_HSP_TEXTDOMAIN), 'value' => 'checkmark');
		$icon_types[2] = array( 'label' => __('X', GUESTABA_HSP_TEXTDOMAIN), 'value' => 'x');

		$this->select(  __('Amenity List Icon', GUESTABA_HSP_TEXTDOMAIN),
						get_post_meta( $post_ID, 'meta_room_amenity_list_icon', true),
						$icon_types,
						'meta_room_amenity_list_icon',
						'checkmark'
						);

		$this->post_select( __('Amenity Set', GUESTABA_HSP_TEXTDOMAIN ),
							get_post_meta($post_ID, 'meta_room_amenity_select', true ),
							'meta_room_amenity_select',
							'amenity-sets'
							);

		$this->content_container( 'adm_amenity_set_list');

		$amenity_item_labels = array( 'title' => 'Title') ;

		$this->sortable_editable_list( __('Room Specific Amenities', GUESTABA_HSP_TEXTDOMAIN),
										$this->get_amenity_list( $post_ID ),
										'meta_room_amenity_list',
										array( $this, 'item_as_text_input'),
										'amenity-edit-list',
										$amenity_item_labels
										);
		echo '</div>';

		/**
		 * Pricing settings container
		 */

		echo '<div class="gst_settings_container">';
		$this->section_heading('Pricing Settings', 'gst-mb-pricing-settings');

		$this->post_select( __('Select pricing model', GUESTABA_HSP_TEXTDOMAIN),
							get_post_meta($post_ID, 'meta_room_pricing_select', true ),
							'meta_room_pricing_select',
							'pricing-models'
		);

		$this->content_container( 'adm_pricing_model');

		echo '</div>';


		echo '<div id="gst_slider_settings" class="gst_settings_container">';

		$this->section_heading('Slider Settings', 'gst-mb-slider-settings');

		$image_item_labels = array( 'title' => 'Title',
									'meta_room_slider_image' => 'Image URL',
									'meta_room_slider_description' => 'Description'
									);


		$this->sortable_editable_list( __('Slider Images', GUESTABA_HSP_TEXTDOMAIN),
										$this->get_slider( $post_ID ),
										'meta_room_slider',
										array( $this, 'item_as_image_upload'),
										'slider-edit-list',
										$image_item_labels,
										'gst-sort-edit-image-add'
										);

		$animation_effects[0] = array( 'label' => __('Fade', GUESTABA_HSP_TEXTDOMAIN), 'value' => 'fade');
		$animation_effects[1] = array( 'label' => __('Slide', GUESTABA_HSP_TEXTDOMAIN), 'value' => 'slide');

		$this->select(  __('Animation Effects', GUESTABA_HSP_TEXTDOMAIN),
						get_post_meta( $post_ID, 'meta_room_slider_animation_effect', true),
						$animation_effects,
						'meta_room_slider_animation_effect',
						'slide'
						);

		$this->number_input(  __('Animation Duration (in ms)', GUESTABA_HSP_TEXTDOMAIN),
							get_post_meta( $post_ID, 'meta_room_slider_animation_duration', true),
							'meta_room_slider_animation_duration',
							700
							);


		$this->on_off( __('Auto Animation', GUESTABA_HSP_TEXTDOMAIN ),
						get_post_meta( $post_ID, 'meta_room_slider_animation_auto', true),
						'meta_room_slider_animation_auto',
						'on'
						);


		$this->number_input(  __('Auto Animation Speed (in ms)', GUESTABA_HSP_TEXTDOMAIN),
							get_post_meta( $post_ID, 'meta_room_slider_animation_speed', true),
							'meta_room_slider_animation_speed',
							3000
							);


		$this->on_off(  __('Pause on hover', GUESTABA_HSP_TEXTDOMAIN ),
						get_post_meta( $post_ID, 'meta_room_slider_animation_pause', true),
						'meta_room_slider_animation_pause',
						'on'
		);

		$this->text_input( __('Alternative Image Shortcode (for example, "[metaslider]")', GUESTABA_HSP_TEXTDOMAIN),
							get_post_meta( $post_ID, 'meta_room_alternate_image_shortcode', true),
						'meta_room_alternate_image_shortcode'
		);

		echo '</div>';
	}



	/**
	 * Function post_meta_save
	 *
	 * This is  post meta data save callback function.
	 *
	 * @param integer $post_id the post ID for the submitted meta data.
	 */
	public function post_meta_save( $post_id ) {

		// Checks save status
		$is_autosave = wp_is_post_autosave( $post_id );
		$is_revision = wp_is_post_revision( $post_id );
		$is_valid_nonce = ( isset( $_POST[ $this->getNonceId()] ) && wp_verify_nonce( $_POST[ $this->getNonceId() ], basename( __FILE__ ) ) ) ? 'true' : 'false';

		// Exits script depending on save status
		if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
			return;
		}


		$this->update_meta_text( $post_id, 'meta_room_slogan');
		$this->update_meta_text_html( $post_id, 'meta_room_desc');
		$this->update_meta_text( $post_id, 'meta_room_excerpt');
		$this->update_meta_page_reference( $post_id, 'meta_room_detail_page');

		$this->update_meta_text( $post_id, 'meta_room_amenity_list_icon');
		$this->update_meta_post_reference( $post_id, 'meta_room_amenity_select', 'amenity-sets');
		$this->update_meta_post_reference( $post_id, 'meta_room_pricing_select', 'pricing-models');
		$this->update_meta_text( $post_id, 'meta_room_slider_animation_effect');
		$this->update_meta_text( $post_id, 'meta_room_alternate_image_shortcode');

		$this->update_meta_text( $post_id, ' meta_room_slider_animation_auto');
		$this->update_meta_text( $post_id, 'meta_room_slider_animation_pause');

		$this->update_meta_integer( $post_id, 'meta_room_slider_animation_duration');
		$this->update_meta_integer( $post_id, 'meta_room_slider_animation_speed');

		$this->update_meta_array( $post_id, 'meta_room_amenity_list', array('title'));

		$this->update_meta_array( $post_id, 'meta_room_slider', array('meta_room_slider_image','title', 'meta_room_slider_description'));


		// $this->slider_post_meta_save();

	}


	/**
	 *
	 * Function items_as_string
	 *
	 * @todo this function (item_as_string) does not seem to be used anywhere. Verify that and delete it if not.
	 *
	 * @param $item
	 *
	 * @return string
	 */
	protected function item_as_string($item ) {
		return strval($item );
	}

	/**
	 *
	 * Function item_as_text_input
	 *
	 * This is an item callback function for sortable_editable_list(). It returns a string containing the
	 * HTML text input elements for each member of a post meta data string array.
	 *
	 * @todo this is a generic function that can be moved into the superclass.
	 *
	 * @param string $name the name of the individual form element for the item.
	 * @param string $class a user defined class.
	 * @param mixed $item the post meta data array element.
	 *
	 * @return string
	 */

	protected function item_as_text_input( $name, $class, $item ) {

		$value = $item['title'];
		$input_tag = '<div class="gst-sort-edit-input gst_input_group">';
		// $input_tag .= '<label for="' . $name . '">' . $labels['title'] . '</label>';
		$input_tag .= '<input title="Drag this item to change it position in the order." type="text"  name="'. $name . '-title[]" id="' . $name . '" class="gst_input_group_item ' . $class . '" value="' . $value . '">';
		$input_tag .= '<button title="' . $this->get_tooltip('delete_text_button'). '" class="gst-sort-edit-delete" id="' . $name . '-button" formaction="javascript:void(0)">'  . __('X', GUESTABA_HSP_TEXTDOMAIN ) . '</button>';
		$input_tag .= '</div>';
		return $input_tag;
	}

	/**
	 * Function item_as_image_upload
	 *
	 * This a callback function for a sortable-editable list. It returns the a string containing the HTML form elements
	 * required to add/edit slider images.
	 *
	 *
	 * @param $name
	 * @param $class
	 * @param $item
	 * @param $labels
	 *
	 * @return string
	 */
	protected function item_as_image_upload( $name, $class, $item, $labels ) {

		$image_url = $item['meta_room_slider_image'];
		$title = $item['title'];
		$description = $item['meta_room_slider_description'];

		$input_tag = '<div id="' . $name . '" class="gst-sort-edit-image gst_input_group">';


		// $input_tag .= '<div class="gst-image-upload-attr">';
		$input_tag .= '<a href="'  . $image_url . '" alt="" title=""><img src="' . $image_url . '"  class="gst-slider-thumbnail"</img></a><br/>';
		$input_tag .= '<label class="gst_img_input_label gst_img_input_label_url" for="' . $name . '-meta_room_slider_image">' . $labels['meta_room_slider_image'] . '</label><br/>';
		$input_tag .= '<input type="text" name="' . $name . '-meta_room_slider_image[]" id="' . $name . '-meta_room_slider_image" class="gst_input_group_item gst_image_url_target ' . $class . '" value="' . $image_url . '">';
		$input_tag .= '<button title="' . $this->get_tooltip('edit_image_button') . '" class="gst-sort-edit-image-upload" id="' . $name . '-button" formaction="javascript:void(0)">'  . __('Select Image', GUESTABA_HSP_TEXTDOMAIN ) . '</button>';
		$input_tag .= '<button title="' . $this->get_tooltip('delete_image_button') . '" class="gst-sort-edit-delete" id="' . $name . '-button" formaction="javascript:void(0)">'  . __('X', GUESTABA_HSP_TEXTDOMAIN ) . '</button>';
		// $input_tag .= '</div>';

		// $input_tag .= '<div class="gst-image-upload-attr">';
		$input_tag .= '<br/><label class="gst_img_input_label gst_img_input_label_title" for="' . $name . '_title" >' . $labels['title'] . '</label></br>';
		$input_tag .= '<input type="text"  name="'. $name . '-title[]" placeholder="' . $labels['title'] .  '" class="gst_input_group_item gst_clear_input_target gst_slide_title_input ' . $class . '" value="' . $title . '">';
		// $input_tag .= '</div>';

		// $input_tag .= '<div class="gst-image-upload-attr">';
		$input_tag .= '<br/><label class="gst_img_input_label gst_img_input_label_desc" for="' . $name . '-meta_room_slider_description">' . $labels['meta_room_slider_description'] . '</label><br/>';
		$input_tag .= '<input type="text" name="' . $name . '-meta_room_slider_description[]" placeholder="' . $labels['meta_room_slider_description'] .  '"  class="gst_input_group_item  gst_clear_input_target gst_slide_desc_input ' . $class . '" value="' . $description . '">';
		// $input_tag .= '</div>';


		$input_tag .= '</div>';
		return $input_tag;
	}

	/*
	 * Function init_tooltips
	 *
	 * This function initializes the tooltips for the UI elements of this metabox.
	 *
	 * @param none
	 *
	 * @return void
	 */
	protected function init_tooltips() {

		$tooltips = array(
			'add_button' => __( 'Click this button to add a new item to this list.', GUESTABA_HSP_TEXTDOMAIN ),
			'edit_image_button' => __( 'Click this button select or upload a different image. For best results, choose images 600 px wide by 150 px high.', GUESTABA_HSP_TEXTDOMAIN ),
			'delete_image_button' => __( 'Click this button to remove this image from the slider.', GUESTABA_HSP_TEXTDOMAIN ),
			'delete_text_button' => __( 'Click this button to remove this item from the list.', GUESTABA_HSP_TEXTDOMAIN ),
			'meta_room_detail_page' => __( 'Choose from this list only a custom page containing details for this room. Leave this unselected unless such a page has been set up for this room.', GUESTABA_HSP_TEXTDOMAIN ),
			'meta_room_amenity_list_icon' => __( 'This is the icon that will appear as the bullet for each listed amenity.', GUESTABA_HSP_TEXTDOMAIN ),
			'meta_room_amenity_select' => __( 'This is a list of standard amenity sets defined in the Amenity Sets post type page.', GUESTABA_HSP_TEXTDOMAIN ),
			'meta_room_amenity_list' => __( 'These are amenities that come only with this room. Items in this list can be dragged to change the list order. Click the + button at the bottom of the list to add a new item.', GUESTABA_HSP_TEXTDOMAIN ),
		    'meta_room_pricing_select' => __( 'Pricing models define how a room price varies based on things like seasons, upcoming events, and days of the week. They are set up in the Pricing Models post type page.', GUESTABA_HSP_TEXTDOMAIN ),
			'meta_room_slider' => __( 'Items in this list can be dragged to change the list order. Click the + button at the bottom of the list to add a new item.', GUESTABA_HSP_TEXTDOMAIN ),
			'meta_room_slider_animation_duration' => __( 'This is the amount of time it takes the animation to completely transition from one slide to the next.', GUESTABA_HSP_TEXTDOMAIN ),
			'meta_room_slider_animation_auto' => __( 'This turns the slider animation on or off. If off, visitors must manually click the navigation buttons to transition between slides.', GUESTABA_HSP_TEXTDOMAIN ),
			'meta_room_slider_animation_speed' => __( 'This is the amount of time a slide is displayed before the animation transitions to the next slide.', GUESTABA_HSP_TEXTDOMAIN ),
			'meta_room_alternate_image_shortcode' => __( 'The Guestaba Rooms slider can be completely replaced with another slider, or anything else, by entering a valid shortcode here.', GUESTABA_HSP_TEXTDOMAIN )
		);

		$this->set_tooltips( $tooltips );
	}

	/*
	 * Function get_amenity_list
	 *
	 * @access private
	 *
	 * @param integer $post_ID the post ID of the current room CPT.
	 *
	 * @return array an array of amenity set post as retrieved by get_post_meta.
	 */
	private function get_amenity_list( $post_ID ) {

		$list = get_post_meta($post_ID, 'meta_room_amenity_list', true);

		if ( $list == false || !isset( $list) || empty($list)  ) {
			$list = array();
			$list[0] = array( 'title' => '');
			return $list;
		}
		else {
			return $list;
		}


	}

	/*
	 * Function get_slider
	 *
	 * @access private
	 *
	 * @param integer $post_ID the post ID of the current room CPT.
	 *
	 * @return array an array containing the slider image data as returned by get_post_meta.
	 */

	private function get_slider( $post_ID ) {
		$list = get_post_meta($post_ID, 'meta_room_slider', true);

		if ( $list == false || !isset( $list) || empty($list)  ) {
			$list = array();
			$list[0] = array(   'meta_room_slider_image' => '',
								'title' => '',
								'meta_room_slider_description' => '');
			return $list;
		}
		else {
			return $list;
		}

	}
}