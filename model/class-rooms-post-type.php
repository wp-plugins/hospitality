<?php

/**
 * Encapsulates attributes and behavior of the room post type.
 *
 * @link       http://guestaba.com
 * @since      1.0.0
 *
 * @package    Hospitality
 * @subpackage Hospitality/model
 */

/**
 * Room Post Type class
 *
 * Defines attribues and behavior of the Room post type
 *
 *
 * @since      1.0.0
 * @package    Hospitality
 * @subpackage Hospitality/model
 * @author     Wes Kempfer <wkempferjr@tnotw.com>
 */
class Rooms_Post_Type {
	
	/**
	 *  String to define post type name.
	 *  @since	1.0.0
	 *  @access	protected
	 *  @var String  $post_type  Stores post_type name
	 */
	protected $post_type ;
	
	/**
	 * Array for storing UI labels for Rooms custom post type
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var     array    $labels   Stores UI labels for Room CPT
	 */
	protected $labels;
	
	/**
	 * Array for storing argument passed to register_post_type
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var     array    $args   Stores UI labels for Room CPT
	 */
	protected $args;
	
	/**
	 * Constructor for Room Post Type
	 * Initializes labels and args for registration.
	 * @since    1.0.0
	 */
	
	public function __construct() {
		
		$this->post_type = 'rooms';
		
		$theme = wp_get_theme();
		$text_domain = $theme->get('TextDomain');

		$this->labels = array(
		    'name'                => __( 'Room Listings', $text_domain ),
            'singular_name'       => __( 'Room', $text_domain ),
            'menu_name'           => __( 'Rooms', $text_domain ),
            'parent_item_colon'   => __( 'Parent Room:', $text_domain ),
            'all_items'           => __( 'All Rooms', $text_domain ),
            'view_item'           => __( 'View Room', $text_domain ),
            'add_new_item'        => __( 'Add New Room', $text_domain ),
            'add_new'             => __( 'Add New', $text_domain ),
            'edit_item'           => __( 'Edit Room', $text_domain ),
            'update_item'         => __( 'Update Room', $text_domain ),
            'search_items'        => __( 'Search Rooms', $text_domain ),
            'not_found'           => __( 'No rooms found', $text_domain ),
            'not_found_in_trash'  => __( 'No rooms found in Trash', $text_domain )
		);
		
		$this->args = array(
		     'label'               => __( 'Rooms', $text_domain ),
            'labels'              => $this->labels,
            'description'         => __('Room description goes here.', GUESTABA_HSP_TEXTDOMAIN ),
            'supports'            => array( 'title', 'excerpt', 'author', 'trackbacks', 'revisions', 'custom-fields', 'page-attributes', 'thumbnail' ),
            'hierarchical'        => true,
            'public'              => true,

            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,
            'show_in_admin_bar'   => true,

            'query_var'           => true,
            'publicly_queryable'  => true,

            'exclude_from_search' => false,
            'has_archive'         => true,

            'can_export'          => true,
            'menu_position'       => 5,
            'rewrite'             => array(
                'slug'            => 'rooms',
                'with_front'      => true,
                'pages'           => true,
                'feeds'           => true,
            ),
            'capability_type'     => 'post',
            'taxonomies'          => array( 'category', 'post_tag' )
		);

	}

	/*
	 * Register post type
	 * 
	 * @since 1.0.0
	 * 
	 * @param none
	 * @return void
	 */
	public function register() {
		register_post_type( $this->post_type, $this->args);
	}
	
	
	/*
	 * Remove post actions
	 * 
	 * @since 1.0.0
	 * 
	 * @param none
	 * @return void
	 */	
	public function remove_post_actions($actions) {
		if ( 'rooms' === get_post_type() ) {
            unset( $actions['trash'] );
        }
        return $actions;;
	}
	
	
	/*
	 * Get page by slug. Post support function.
	 * 
	 * @since 1.0.0
	 */	
	public function get_page_by_slug($page_slug, $output = OBJECT, $post_type = 'page' ) {
    	global $wpdb;
    	$page = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_type= %s", $page_slug, $post_type ) );
    	if ( $page )
            return get_page($page, $output);
    	return null;
	}

	
	/*
	 * Add to query. Post support function.
	 * 
	 * @since 1.0.0
	 */
	public function add_to_query( $query ) {
		if ( is_home() && $query->is_main_query()  && $query->is_search  && $query->is_category )
			$query->set( 'post_type', array( 'post', 'page', 'rooms' ) );
		return $query;
	}

	/*
	 * Query post type. Post support function.
	 * 
	 * @since 1.0.0
	 */
    public function query_my_post_types( &$query ) {
	    // Do this for all category and tag pages, can be extended to is_search() or is_home() ...
	    if ( is_category() || is_tag() ) {
	        $post_type = $query->get( 'post_type' );
	        // ... if no post_type was defined in query then set the defaults ...
	        if ( empty( $post_type ) ) {
	            $query->set( 'post_type', array(
	                    'post',
	                    'rooms'
	                ) );
	        }
	    }
    }
    
    
    /*
	 * Get post type templates. Post support function.
	 * 
	 * 
	 * @since 1.0.0
	 */
    public function get_custom_post_type_template($template_path) {
  
	    if ( get_post_type() == 'rooms' ) {
	        if ( is_single() ) {
	            // checks if the file exists in the theme first,
	            // otherwise serve the file from the plugin
	            if ( $theme_file = locate_template( array ( 'single-rooms.php' ) ) ) {
	                $template_path = $theme_file;
	            } else {
	                $template_path = trailingslashit( dirname( plugin_dir_path( __FILE__ ) ) ). 'single-rooms.php';
	            }	         	
	        }
	    	elseif ( is_archive() ) {
	            if ( $theme_file = locate_template( array ( 'archive-rooms.php' ) ) ) {
	                $template_path = $theme_file;
	            } else { 
	            	$template_path = trailingslashit( dirname( plugin_dir_path( __FILE__ ) ) ) . 'archive-rooms.php';
	        	}
	    	}    
		}
		return $template_path;
    }	
  


	
    /*
	 * Truncate post. Post support function.
	 * 
	 * Probably never used for this post type. 
	 * 
	 * @since 1.0.0
	 */	
	public function truncate_post( $amount, $echo = true, $post = '' ) {
	    global $shortname;
	    if ( '' == $post ) global $post;
	    $post_excerpt = '';
	    $post_excerpt = apply_filters( 'the_excerpt', $post->post_excerpt );
	    if ( 'on' == et_get_option( $shortname . '_use_excerpt' ) && '' != $post_excerpt ) {
	        if ( $echo ) echo $post_excerpt;
	        else return $post_excerpt;
	    } else {
	
	        if ( 'rooms' == get_post_type() ) {
	            $truncate = get_post_meta($post->ID, 'meta_room_desc', true);
	        } else {
	            $truncate = $post->post_content;
	        }
	
	        // remove caption shortcode from the post content
	        $truncate = preg_replace('@\[caption[^\]]*?\].*?\[\/caption]@si', '', $truncate);
	        // apply content filters
	        $truncate = apply_filters( 'the_content', $truncate );
	        // decide if we need to append dots at the end of the string
	        if ( strlen( $truncate ) <= $amount ) {
	            $echo_out = '';
	        } else {
	            $echo_out = '...';
	            // $amount = $amount - 3;
	        }
	        // trim text to a certain number of characters, also remove spaces from the end of a string ( space counts as a character )
	        $truncate = rtrim( wp_trim_words( $truncate, $amount, '' ) );
	        // remove the last word to make sure we display all words correctly
	        if ( '' != $echo_out ) {
	            $new_words_array = (array) explode( ' ', $truncate );
	            array_pop( $new_words_array );
	            $truncate = implode( ' ', $new_words_array );
	            // append dots to the end of the string
	            $truncate .= $echo_out;
	        }
	        if ( $echo ) echo $truncate;
	        else return $truncate;
	    };
	}
}
?>