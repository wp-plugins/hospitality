<?php

/**
* Class Hospitality_Rooms_Meta_Box
 *
 * This class defines the appearance and behavior of the metabox associated with the amenity sets post type.
 */

class Hospitality_Amenity_Sets_Meta_Box extends Hospitality_Meta_Box {

	/**
	 * Class constructor
	 * @since 1.0.0
	 * @param none
	 * @return an instance of Hospitality_Amenity_Sets_Metabox
	 *
	 */

	function __construct() {
		$this->setPostType( 'amenity-sets' );
		$this->setMetaBoxID(  'amenity_sets_cpt_meta_box' );
		$this->setMetaBoxTitle(  __( 'Amenity Sets Options', GUESTABA_HSP_TEXTDOMAIN ) );
		$this->setNonceId( 'amenity_sets_mb_nonce');
		$this->init_tooltips();
	}

	/**
	 * Function meta_box_render
	 *
	 * This is the render callback function for the amenty-sets CPT metabox.
	 *
	 * @param none
	 * @return void
	 */

	public function meta_box_render() {

		global $post ;

		wp_nonce_field( basename( __FILE__ ), $this->getNonceId() );
		$post_ID = $post->ID;

		$enq_media_args = array( 'post' => $post_ID );
		wp_enqueue_media( $enq_media_args );


		echo '<div class="gst_settings_container">';

		$this->section_heading(__('Amenity Set Settings', GUESTABA_HSP_TEXTDOMAIN), 'gst-mb-content-settings');

		$this->text_area(   __('Description',
							GUESTABA_HSP_TEXTDOMAIN) ,
							get_post_meta( $post_ID, 'meta_amenity_set_desc', true),
							10,
							40,
							'meta_amenity_set_desc'
						);

		$amenity_item_labels = array( 'title' => 'Title') ;

		$this->sortable_editable_list( __('Amenity Set List', GUESTABA_HSP_TEXTDOMAIN),
										$this->get_amenity_list( $post_ID ),
										'meta_amenity_set_list',
										array( $this, 'item_as_text_input'),
										'amenity-edit-list',
										$amenity_item_labels
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

		$is_autosave = wp_is_post_autosave( $post_id );
		$is_revision = wp_is_post_revision( $post_id );
		$is_valid_nonce = ( isset( $_POST[ $this->getNonceId()] ) && wp_verify_nonce( $_POST[ $this->getNonceId() ], basename( __FILE__ ) ) ) ? 'true' : 'false';

		// Exits script depending on save status
		if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
			return;
		}


		$this->update_meta_text( $post_id, 'meta_amenity_set_desc');
		$this->update_meta_array( $post_id, 'meta_amenity_set_list', array('title'), array( $this, 'validate_amenity'));
	}


	/**
	 *
	 * Function validate_amenity.
	 *
	 * This function is passed as a callback to update_meta_array(). If the title field
	 * in the amenity item is blank, it returns false.
	 *
	 * @param $item
	 *
	 * @return bool
	 */public  function validate_amenity ( $item ) {

		if ( ! empty( $item['title'] ) ) {
			return true;
		}
		else {
			return false;
		}
	}


	/*
	 * Function init_tooltips
	 *
	 * This function initializes the tooltips for the UI elements of this metabox.
	 *
	 * @todo this function is not completed.
	 *
	 * @param none
	 *
	 * @return void
	 */
	protected function init_tooltips() {

	}

	/*
		 * Function get_amenity_list
		 *
		 * @access private
		 *
		 * @param integer $post_ID the post ID of the current amenity-set CPT.
		 *
		 * @return array an array of amenity set post as retrieved by get_post_meta.
		 */

	private function get_amenity_list( $post_ID ) {

		$list = get_post_meta($post_ID, 'meta_amenity_set_list', true);

		if ( $list == false || !isset( $list) || empty($list)  ) {
			$list = array();
			$list[0] = array( 'title' => '');
			return $list;
		}
		else {
			return $list;
		}


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
		remove_meta_box('revisionsdiv', 'amenity-sets', 'norm');
		remove_meta_box('slugdiv', 'amenity-sets', 'norm');
		remove_meta_box('authordiv', 'amenity-sets', 'norm');
		remove_meta_box('postcustom', 'amenity-sets', 'norm');
		remove_meta_box('postexcerpt', 'amenity-sets', 'norm');
		remove_meta_box('trackbacksdiv', 'amenity-sets', 'norm');
		remove_meta_box('commentsdiv', 'amenity-sets', 'norm');
		remove_meta_box('pageparentdiv', 'amenity-sets', 'norm');
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

}