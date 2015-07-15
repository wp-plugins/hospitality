<?php 

/**
 * This class defines and maintains access to the plugin 
 * settings. 
 * 
 * @link       http://guestaba.com
 * @since      1.0.0
 * @package    Hospitality
 * @subpackage Hospitality/includes
 * @author     Wes Kempfer <wkempferjr@tnotw.com>
 */
class Hospitality_Settings {
	
	
	/*
	 * Sets the name of plugin option.
	 */
	private $options_name = GUESTABA_HSP_OPTIONS_NAME;
	
	/*
	 * Default values for plugin options are defined here. 
	 * These values are recorded in wp_option at activation time. 
	 * 
	 */
	private $default_excerpt_len = 200 ;
	private $default_use_widget_area = false;
	private $default_remove_data_on_uninstall = false;
	private $default_amenities_title ;
	
	/**
	 * Constructor
	 * 
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->default_amenities_title = __( 'Amenities', GUESTABA_HSP_TEXTDOMAIN );
	}
	
	
	/*
	 * Get the plugin option name. 
	 * 
	 * @return string plugin option name.
	 */
	public function get_options_name() {
		return $this->options_name;
	}
	
	
	/*
	 * This function is called at activation time. It records
	 * the plugin settings default values in the wp_options table. 
	 * If the plugin options already exist in the database, they 
	 * are not overwritten. 
	 * 
	 * @since 1.0.0
	 */
	public function add_option_defaults() {
		
		if ( current_user_can('activate_plugins') ) {	
			$options = array();
			$options['hsp_room_excerpt_len'] = $this->default_excerpt_len;
			$options['hsp_amenities_title'] = $this->default_amenities_title;
			$options['hsp_remove_data_on_uninstall'] = $this->default_remove_data_on_uninstall;
			
			add_option( $this->options_name, $options );
		}
		
	}
	
	/*
	 * This function was intended to be called to delete the 
	 * options from the database. 
	 * 
	 * @todo Can this delete_options() be removed. 
	 * @since 1.0.0
	 */
	
	public function delete_options() {
		if ( current_user_can('delete_plugins') ) {
			delete_option($this->options_name );			
		}
	}
	


	
	/*
	 * 
	 * Return the title that will be used in the amenities listing
	 * 
	 * @since 1.0.0
	 * 
	 * @param none
	 * @return string amenities_title
	 */
	
	public function get_amenities_title() {
		$option = get_option( $this->options_name);
		return $option['hsp_amenities_title'];		
	}
	
	/*
	 * Return the room listing room desciption lenght. It is used to
	 * determine how many words to display before truncating the 
	 * description and displaying a "Read More" link. 
	 * 
	 * @since 1.0.0
	 * 
	 * @param none
	 * @return integer room_desc_excerpt_len
	 */
	public function get_room_excerpt_len() {
		$option = get_option( $this->options_name);
		return $option['hsp_room_excerpt_len'];		
	}
	
	/*
	 * Return "remove data on uninstall" flag. If true, all
	 * data and settings associated with the plugin are to be delete.
	 * 
	 * @since 1.0.0
	 * 
	 * @param none
	 * @return boolean remove_plugin_data_on_uninstall
	 */
	public function get_remove_data_on_uninstall() {
		$option = get_option( $this->options_name);
		return $option['hsp_remove_data_on_uninstall'];		
	}
	
	/*
	 * This method defines the plugin setting page. 
	 * 
	 * @since 1.0.0
	 * 
	 * @param none
	 * @return void
	 */
	public function settings_init(  ) { 

		register_setting( 'hsp-settings-group', $this->options_name, array( $this, 'sanitize') );
		
		add_settings_section(
			'hsp-settings-general-section', 
			__( 'Hospitality General Settings', GUESTABA_HSP_TEXTDOMAIN ), 
			array($this, 'hsp_settings_general_info'), 
			'hsp-settings-page'
		);		
		
		add_settings_field( 
			'hsp_remove_data_at_uninstall', 
			__( 'Remove plugin posts, settings, and other data on deactivation.', GUESTABA_HSP_TEXTDOMAIN ),
			array($this, 'hsp_remove_data_render'), 
			'hsp-settings-page', 
			'hsp-settings-general-section'
		);
	
		/*
		 * Room listing options 
		 */
		add_settings_section(
			'hsp-settings-list-section', 
			__( 'Hospitality Room List Settings', GUESTABA_HSP_TEXTDOMAIN ), 
			array($this, 'hsp_settings_list_section_info'), 
			'hsp-settings-page'
		);
	
	

		add_settings_field( 
			'hsp_room_excerpt_len', 
			__( 'Room excerpt maximum character count', GUESTABA_HSP_TEXTDOMAIN ),
			array($this, 'hsp_room_excerpt_len_render'), 
			'hsp-settings-page', 
			'hsp-settings-list-section' 
		);
	

		
		/*
		 * Room pages options
		 */
		add_settings_section(
			'hsp-settings-room-section', 
			__( 'Hospitality Room Pages Settings', GUESTABA_HSP_TEXTDOMAIN ), 
			array($this, 'hsp_settings_room_section_info'), 
			'hsp-settings-page'
		);		
		

	
		add_settings_field( 
			'hsp_amenities_title', 
			__( 'Amenities list title', GUESTABA_HSP_TEXTDOMAIN ), 
			array($this, 'hsp_amenities_title_render'), 
			'hsp-settings-page', 
			'hsp-settings-room-section'  
		);
	}
	
	/*
	 * Calls add_options_page to register the page and menu item.
	 * 
	 * @since 1.0.0
	 * 
	 * @param none
	 * @return integer room_desc_excerpt_len
	 */
	public function add_hsp_options_page( ) {

		// Add the top-level admin menu
		$page_title = 'Hospitality Plugin Setings';
		$menu_title = 'Hospitality';
		$capability = 'manage_options';
		$menu_slug = 'hospitality-settings';
		$function = 'settings_page';
		add_options_page($page_title, $menu_title, $capability, $menu_slug, array($this, $function)) ;


	}
	
	/*
	 * Defines and displays the plugin settings page.
	 * @since 1.0.0
	 * 
	 * @param none
	 * @return none
	 */
	public function settings_page(  ) { 
	
		?>
		<div class="wrap">
		<form action='options.php' method='post'>
			
			<h2>Hospitality Settings</h2>
			<div id="hsp-settings-container">
				<?php

				settings_fields( 'hsp-settings-group' );
				do_settings_sections( 'hsp-settings-page' );
				submit_button();
				?>
			</div>
			<div id="hsp-settings-info-container">
				<h3>Hospitality from Guestaba</h3>
				<h3>Help Improve this Plugin</h3>
					<p>Send us your ideas, feature requests and...donations :)</p>
					<p><a id="hsp-setting-contact" href="https://guestaba.com/contact" target="_blank">Contact us</a></p>
					<p><a id="hsp-setting-help" href="https://support.guestaba.com/documentation" target="_blank">Hospitality Documentation</a></p>
					<p><a id="hsp-setting-help" href="https://support.guestaba.com" target="_blank">Support</a></p>
				
			<div id="hsp-donate">
					<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
						<input type="hidden" name="cmd" value="_s-xclick">
						<input type="hidden" name="hosted_button_id" value="FUWXV5MTNZWW4">
						<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
						<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
					</form>
				</div>
			</div>

			
		</form>
		</div>
		<?php

	}

	
	
	/*
	 * Render the remove data on unsinstal checkbox field. 
	 * @since 1.0.0
	 */	
	public function hsp_remove_data_render(  ) { 
	
		$options = get_option( $this->options_name );
		?>
		<input id="remove_hsp_data_input" type="checkbox" name="guestaba_hsp_settings[hsp_remove_data_on_uninstall]" <?php checked( $options['hsp_remove_data_on_uninstall'], 1 ); ?> value='1'>
		<br><label for="remove_hsp_data_input"><em>Leave this unchecked unless you really want to remove the posts you have created using this plugin.</em></label>
		<?php
	
	}
	
	/*
	 * Render the amenities title field. 
	 * @since 1.0.0
	 */	
	public function hsp_amenities_title_render(  ) { 
	
		$options = get_option( $this->options_name );
		?>
		<input type="text" size="40" name="guestaba_hsp_settings[hsp_amenities_title]" value="<?php echo $options['hsp_amenities_title']; ?>">
		<?php
	
	}
	
	/*
	 * Render the room description excerpt length field 
	 * @since 1.0.0
	 */		
	public function hsp_room_excerpt_len_render(  ) { 
	
		$options = get_option( $this->options_name );
		?>
		<input type="text" size="4" name="guestaba_hsp_settings[hsp_room_excerpt_len]" value='<?php echo $options['hsp_room_excerpt_len']; ?>'>
		<?php
	
	}
	

	
	
	/*
	 * Sanitize user input before passing values on to update options.
	 * @since 1.0.0
	 */	
	public function sanitize( $input ) {
		
		$new_input = array();
		
		if( isset( $input['hsp_remove_data_on_uninstall'] ) ) {
        	 $new_input['hsp_remove_data_on_uninstall'] = sanitize_text_field( $input['hsp_remove_data_on_uninstall'] );
        }
        else {
        	// set to default 
        	$new_input['hsp_remove_data_on_uninstall'] = false ;
        }

        

         if( isset( $input['hsp_amenities_title'] ) )
            $new_input['hsp_amenities_title'] = sanitize_text_field( $input['hsp_amenities_title'] );     
            
        if( isset( $input['hsp_room_excerpt_len'] ) )
            $new_input['hsp_room_excerpt_len'] = absint( $input['hsp_room_excerpt_len'] );
                    
		return $new_input ;
	}
	
	/*
	 * Render general settings section info. 
	 * @since 1.0.0
	 */	
	public function hsp_settings_general_info () {
		echo '<p>' . __("General settings for Hospitality Plugin", GUESTABA_HSP_TEXTDOMAIN) . '</p>';
	}
	
	/*
	 * Render room listing settings section info. 
	 * @since 1.0.0
	 */
	public function hsp_settings_list_section_info () {
		echo '<p>' . __("Default settings for room listing options.", GUESTABA_HSP_TEXTDOMAIN) . '</p>';
	}
	
	/*
	 * Render room page settings section info. 
	 * @since 1.0.0
	 */
	public function hsp_settings_room_section_info () {
		echo '<p>' . __("Default settings for room pages.", GUESTABA_HSP_TEXTDOMAIN) . '</p>';
	}
	
	
	/*
	 * Places link to settings page under the Plugins->Installed Plugins listing entry.
	 * It is intended to be called via add_filter. 
	 * 
	 * @param array $links an array of existing action links.
	 * 
	 * @return $links with 
	 * @since 1.0.0
	 */
	public function action_links( $links ) {	
	
		array_unshift( $links,'<a href="http://guestaba.com/hospitality-documentation" target="_blank">FAQ</a>' );
		array_unshift($links, '<a href="'. get_admin_url(null, 'options-general.php?page=hospitality-settings') .'">Settings</a>');
		
    	return $links;
		
		
	}

	
}

?>