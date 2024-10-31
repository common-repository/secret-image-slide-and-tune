<?php
/**
 * Secret Image Slide and Tune
 * 
 * Plugin Name: Secret Image Slide and Tune
 * Plugin URI: https://www.wordpress.org/plugins/secret-image-slide-and-tune/
 * Description: Enter the secret code in the proper sequence in order to set off an image that will slide across your browser screen along with the tune of your favorite sound!
 * Version: 1.2.1
 * Author: Joseph Bisharat
 * Author URI: https://josephbisharat.com/
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: secret-image-slide-and-tune
 * Domain Path: /languages
 * Requires at least: 4.6
 * Tested up to: 5.8
 * Requires PHP: 5.2.4
 */

	if ( !defined( 'ABSPATH' ) ) {

		exit;
	}

	/* Define global variables */

	// Gets the basename of a plugin
	// Meaning, the function gets the directory and filename of the plugin where __FILE__ is passed in
	// Result: secret-image-slide-and-tune/secret-image-slide-and-tune.php
	define( 'SECRET_IMAGE_SLIDE_AND_TUNE_FILE', plugin_basename( __FILE__ ) );

	// Gets the URL directory path ( with trailing slash ) of the plugin where __FILE__ is passed in.
	// Result: https://website-url/wp-content/plugins/secret-image-slide-and-tune/
	define( 'SECRET_IMAGE_SLIDE_AND_TUNE_FOLDER', plugin_dir_url( __FILE__ ) );

	// Get the filesystem directory path (with trailing slash) for the plugin __FILE__ passed in.
	// Result: /home/user/var/www/wordpress/wp-content/plugins/my-plugin/
	define( 'SECRET_IMAGE_SLIDE_AND_TUNE_DIR', plugin_dir_path( __FILE__ ) );

	// Load the languages directory and its translation files
	// The function now tries to load the .mo file from the languages directory first.
	add_action( 'plugins_loaded', 'secret_image_slide_and_tune_textdomain' );
	function secret_image_slide_and_tune_textdomain() {

		// Third argument result: secret-image-slide-and-tune . /languages
		load_plugin_textdomain( 'secret-image-slide-and-tune', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	// Check the plugin admin page
	if( is_admin() && isset( $_GET[ 'page' ] ) && ( 'secret-image-slide-and-tune-settings' == $_GET[ 'page' ] ) ) {

		// Replace the footer with empty string
		add_filter( 'admin_footer_text', 'admin_footer' );
	}

	function admin_footer() {

		_e( '<div id="secret-image-slide-and-tune-plugin-review" class="secret-image-slide-and-tune-plugin-review"><em>Enjoying the plugin? Leave a <a href="https://wordpress.org/plugins/secret-image-slide-and-tune/#reviews" title="review" target="_blank" rel="noreferrer noopener">review</a>. Thanks!</em></div>', 'secret-image-slide-and-tune' );
	}

	// Setting links within the WordPress Plugin Listing page
	add_filter( 'plugin_action_links_' . SECRET_IMAGE_SLIDE_AND_TUNE_FILE, 'secret_image_slide_and_tune_settings_links', 10, 2 );
	function secret_image_slide_and_tune_settings_links( $links, $plugin_file ) {

		static $plugin;

		if ( !isset( $plugin ) ) {

			$plugin = SECRET_IMAGE_SLIDE_AND_TUNE_FILE;
		}

		if ( $plugin_file == $plugin ) {

			$settings_url   = admin_url( 'admin.php?page=secret-image-slide-and-tune-settings' );
			$settings_title = esc_attr__( 'Settings', 'secret-image-slide-and-tune' );
			$settings_text = esc_html__( 'Settings', 'secret-image-slide-and-tune' );

			$settings_link = '<a href="' . $settings_url . '" title="' . $settings_title . '">' . $settings_text . '</a>';

			// $pro_url   = esc_url( 'https://josephbisharat.com/' );
			// $pro_title = esc_attr__( 'Plugin', 'secret-image-slide-and-tune' );
			// $pro_text  = esc_html__( 'Plugin', 'secret-image-slide-and-tune' );
			// $pro_style = esc_attr( 'font-weight: bold;' );
			
			// $pro_link  = '<a target="_blank" rel="noopener noreferrer" href="'. $pro_url .'" title="'. $pro_title .'" style="'. $pro_style .'">'. $pro_text .'</a>';

			// array_unshift( $links, $pro_link, $settings_link );
			array_unshift( $links, $settings_link );
		}

		return $links;
	}

	/**
	 * Filters the array of row meta for each/specific plugin in the Plugins list table.
	 * Appends additional links below each/specific plugin on the plugins page.
	 *
	 * @access  public
	 * @param   array       $links_array            An array of the plugin's metadata
	 * @param   string      $plugin_file_name       Path to the plugin file
	 * @param   array       $plugin_data            An array of plugin data
	 * @param   string      $status                 Status of the plugin
	 * @return  array       $links_array
	 */
	// add_filter( 'plugin_row_meta', 'secret_image_slide_and_tune_meta_links', 10, 2 );
	function secret_image_slide_and_tune_meta_links( $links, $file ) {
		
		if ( $file == SECRET_IMAGE_SLIDE_AND_TUNE_FILE ) {
			
			$author_href  = esc_url( 'https://josephbisharat.com/' );
			$author_title = esc_attr__( 'Author Site', 'secret-image-slide-and-tune' );
			$author_text  = esc_html__( 'Author Site', 'secret-image-slide-and-tune' );
			
			$links[] = '<a href="'. $author_href .'" title="'. $author_title .'" target="_blank" rel="noopener noreferrer">'. $author_text .'</a>';
			
		}
		
		return $links;
		
	}

	// Register any scripts for the frontend
	add_action( 'wp_enqueue_scripts', 'secret_image_slide_and_tune_enqueue_scripts_frontend' );
	function secret_image_slide_and_tune_enqueue_scripts_frontend() {

		// The script that handles the audio and image animation
		// Relies on jQuery
		wp_register_script( 'the_main_script_file', SECRET_IMAGE_SLIDE_AND_TUNE_FOLDER . 'js/main.js', array( 'jquery' ), '2.5', true );

		// An array of information to pass to the_main_script_file that handles the audio and image animation
		$passDataToMainScriptFile = array(
			'theChosenCode'				=> esc_attr( get_option( 'secret_image_slide_and_tune_secret_code', 'Up, Up, Down, Down, Left, Right, Left, Right, b, a' ) ),
			'theChosenImage' 			=> esc_attr( get_option( 'secret_image_slide_and_tune_image_url', SECRET_IMAGE_SLIDE_AND_TUNE_FOLDER . 'images/raptor.png' ) ),
			'theChosenTune'				=> esc_attr( get_option( 'secret_image_slide_and_tune_audio_url', SECRET_IMAGE_SLIDE_AND_TUNE_FOLDER . 'audio/raptor-sound.mp3' ) ),
			'theChosenStartPosition'	=> get_option( 'secret_image_slide_and_tune_start_position', '-400' ),
			'theChosenEndPosition'		=> get_option( 'secret_image_slide_and_tune_end_position', '-400' ),
			'theChosenSpeed'			=> get_option( 'secret_image_slide_and_tune_speed', '2200' )
		);

		// Script to send the $passDataToMainScriptFile array to the main script file
		wp_localize_script( 'the_main_script_file', 'php_vars', $passDataToMainScriptFile );

		// Call forth the main script file
		wp_enqueue_script( 'the_main_script_file' );
	}

	// Register any scripts for the backend
	add_action( 'admin_enqueue_scripts', 'secret_image_slide_and_tune_enqueue_scripts_backend' );
	function secret_image_slide_and_tune_enqueue_scripts_backend() {

		// The script that handles the WordPress media configuration
		// Relies on jQuery
		wp_register_script( 'media_uploader_script', SECRET_IMAGE_SLIDE_AND_TUNE_FOLDER . 'js/media.admin.js', array( 'jquery' ), '2.5', true );

		// The main stylesheet
		wp_register_style( 'the_main_style_file', SECRET_IMAGE_SLIDE_AND_TUNE_FOLDER . 'css/style.css', '', '2.5', '' );

		// The script that saves the plugin settings without the page reloading
		// Also handles
		wp_register_script( 'my-wp-ajax-noob-john-cena-script', SECRET_IMAGE_SLIDE_AND_TUNE_FOLDER . "js/ajax-script.js", array( 'jquery' ), '2.5', true );

		// Enqueues all scripts, styles, settings, and templates necessary to use all media JS APIs.
		wp_enqueue_media();

		// Localize AJAX information
		$ajax_data = array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'secret-ajax-script-nonce' ),
		);

		wp_localize_script( 'my-wp-ajax-noob-john-cena-script', 'secret_ajax_data', $ajax_data );

		// Call forth the WordPress media script file
		wp_enqueue_script( 'media_uploader_script' );

		// Call forth the main stylesheet file
		wp_enqueue_style( 'the_main_style_file' );

		// Call form the AJAX file
		wp_enqueue_script( 'my-wp-ajax-noob-john-cena-script' );
	}

	/*
	 *	The class "notice-success" will display the message with a white background and a green left border
	 *	The class "notice-error" will display the message with a white background and a red left border
	 *	The class "notice-warning" will display the message with a white background and a yellow/orange border
	 *	The class "notice-info" will display the message with a white background and a blue left border
	 *	The class "is-dismissible" will automatically trigger a closing icon to be added to your message via JavaScript. 
	 *		The behavior, however, applies only on the current screen. 
	 *		It will not prevent a message from re-appearing once the page re-loads, or another page is loaded.
	*/
	add_action( 'admin_notices', 'success_admin_notice' );
	function success_admin_notice() {
		?>

		<div id="custom-plugin-settings-success" class="notice notice-success">
			<p><?php _e( 'Settings have been saved! Refresh the frontend to see your new changes.', 'secret-image-slide-and-tune' ); ?></p>
		</div>

		<?php
	}

	add_action( 'admin_notices', 'error_admin_notice' );
	function error_admin_notice() {
		?>

		<div id="custom-plugin-settings-error" class="notice notice-error">
			<p><?php _e( 'Nuh uh uh! You have to fill in all fields!', 'secret-image-slide-and-tune' ); ?></p>
		</div>

		<?php
	}

	// Add the menu in the dashboard
	add_action( 'admin_menu', 'secret_image_slide_and_tune_menu' );
	function secret_image_slide_and_tune_menu() {

		// Parent menu in navigation
		/* Arguments:
			Title in the browser tab,
			Parent menu navigation name,
			What level of users have access to this plugin,
			The slug name to refer to this menu by,
			The function to be called to output the content for this page,
			Parent menu navigation icon,
			Menu position
		*/
		add_menu_page( 'Secret Image Slide and Tune', 'Secret Image Slide and Tune', 4, 'secret-image-slide-and-tune-settings', 'secret_image_slide_and_tune_plugin_settings_page' , 'dashicons-format-image', 79 );

		// By the power vested in WordPress, activate!
		add_action( 'admin_init', 'secret_image_slide_and_tune_register_custom_settings' );
	}

	// AJAX action callback
	add_action( 'wp_ajax_secret_ajax', 'my_wp_ajax_secret_ajax_callback' );
	add_action( 'wp_ajax_nopriv_secret_ajax', 'my_wp_ajax_secret_ajax_callback' );
	function my_wp_ajax_secret_ajax_callback() {

		if( wp_doing_ajax() ) {

			if( ! wp_verify_nonce( $_REQUEST[ 'nonce' ], 'secret-ajax-script-nonce' ) ) {

				$error_message = array(
					'status' => 'error',
					'status_message' => 'Unable to verify request'
				);

				wp_send_json( $error_message );
				// wp_die();
			}
		}

		$secret_code = $_POST[ 'plugin_data' ][ 'secret_image_slide_and_tune_secret_code' ];
		$horizontal_start_position = $_POST[ 'plugin_data' ][ 'secret_image_slide_and_tune_start_position' ];
		$horizontal_end_position = $_POST[ 'plugin_data' ][ 'secret_image_slide_and_tune_end_position' ];
		$secret_speed = $_POST[ 'plugin_data' ][ 'secret_image_slide_and_tune_speed' ];
		$image_filename = $_POST[ 'plugin_data' ][ 'secret_image_slide_and_tune_image_filename' ];
		$image_size = $_POST[ 'plugin_data' ][ 'secret_image_slide_and_tune_image_size' ];
		$image_url = $_POST[ 'plugin_data' ][ 'secret_image_slide_and_tune_image_url' ];
		$audio_filename = $_POST[ 'plugin_data' ][ 'secret_image_slide_and_tune_audio_filename' ];
		$audio_url = $_POST[ 'plugin_data' ][ 'secret_image_slide_and_tune_audio_url' ];

		update_option( 'secret_image_slide_and_tune_secret_code', $secret_code );
		update_option( 'secret_image_slide_and_tune_start_position', $horizontal_start_position );
		update_option( 'secret_image_slide_and_tune_end_position', $horizontal_end_position );
		update_option( 'secret_image_slide_and_tune_speed', $secret_speed );
		update_option( 'secret_image_slide_and_tune_image_filename', $image_filename );
		update_option( 'secret_image_slide_and_tune_image_size', $image_size );
		update_option( 'secret_image_slide_and_tune_image_url', $image_url );
		update_option( 'secret_image_slide_and_tune_audio_filename', $audio_filename );
		update_option( 'secret_image_slide_and_tune_audio_url', $audio_url );

		$output = array( 
			'status' => 'success', 
			'info' => array( 
				'secret_code' => $secret_code,
				'horizontal_start_position' => $horizontal_start_position,
				'horizontal_end_position' => $horizontal_end_position,
				'secret_speed' => $secret_speed,
				'image_filename' => $image_filename,
				'image_size' => $image_size,
				'image_url' => $image_url,
				'audio_filename' => $audio_filename,
				'audio_url' => $audio_url
			)
		);

		wp_send_json( $output );

		// wp_die();	// Required to end AJAX request, unless you're using wp_send_json 
	}

	// AJAX action callback
	add_action( 'wp_ajax_secret_ajax_two', 'my_wp_ajax_secret_ajax_two_callback' );
	add_action( 'wp_ajax_nopriv_secret_ajax_two', 'my_wp_ajax_secret_ajax_two_callback' );
	function my_wp_ajax_secret_ajax_two_callback() {

		if( wp_doing_ajax() ) {

			if( ! wp_verify_nonce( $_REQUEST[ 'nonce' ], 'secret-ajax-script-nonce' ) ) {

				$error_message = array(
					'status' => 'error',
					'status_message' => 'Unable to verify request'
				);

				wp_send_json( $error_message );
				// wp_die();
			}
		}

		update_option( 'secret_image_slide_and_tune_secret_code', 'ArrowUp, ArrowUp, ArrowDown, ArrowDown, ArrowLeft, ArrowRight, ArrowLeft, ArrowRight, b, a' );
		update_option( 'secret_image_slide_and_tune_start_position', '-400' );
		update_option( 'secret_image_slide_and_tune_end_position', '-400' );
		update_option( 'secret_image_slide_and_tune_speed', '2200' );
		update_option( 'secret_image_slide_and_tune_image_filename', 'raptor.png' );
		update_option( 'secret_image_slide_and_tune_image_size', '400 x 600' );
		update_option( 'secret_image_slide_and_tune_image_url', SECRET_IMAGE_SLIDE_AND_TUNE_FOLDER . 'images/raptor.png' );
		update_option( 'secret_image_slide_and_tune_audio_filename', 'raptor-sound.mp3' );
		update_option( 'secret_image_slide_and_tune_audio_url', SECRET_IMAGE_SLIDE_AND_TUNE_FOLDER . 'audio/raptor-sound.mp3' );

		$output = array( 
			'status' => 'success',
			'info' => array( 
				'secret_code' => 'ArrowUp, ArrowUp, ArrowDown, ArrowDown, ArrowLeft, ArrowRight, ArrowLeft, ArrowRight, b, a',
				'horizontal_start_position' => '-400',
				'horizontal_end_position' => '-400',
				'secret_speed' => '2200',
				'image_filename' => 'raptor.png',
				'image_size' => '400 x 600',
				'image_url' => SECRET_IMAGE_SLIDE_AND_TUNE_FOLDER . 'images/raptor.png',
				'audio_filename' => 'raptor-sound.mp3',
				'audio_url' => SECRET_IMAGE_SLIDE_AND_TUNE_FOLDER . 'audio/raptor-sound.mp3'
			)
		);

		wp_send_json( $output );

		// wp_die();	// Required to end AJAX request, unless you're using wp_send_json 
	}

	// Form output
	function secret_image_slide_and_tune_plugin_settings_page() {
		?>
			<div class="wrap">
				<form id="ajax-form" method="post" action="options.php">

					<?php

						// Display any error messages
						settings_errors();

						// The function 'settings_fields' renders code to tell the form what to do, as well as a hidden input to make it secure using a nonce.
						// The argument passed to the function is a name for the settings group that will be registered later.
						settings_fields( 'my-secret-image-slide-and-tune-group' );

						// The function 'do_settings_sections' is where all the sections and fields are output ( textboxes, selects, checkboxes, etc ) so data can be entered by the user.
						// The function argument is arbitrary but needs to be unique.
						// We will use that when registering fields.
						do_settings_sections( 'secret-image-slide-and-tune-settings' );

						// Primary save button configuration
						$button_text = __( 'Save', 'secret-image-slide-and-tune' );
						$type = 'primary';
						$button_name = 'secret_image_slide_and_tune_button_update';
						$wrap = false;
						$other_attributes = array( 'id' => 'secretImageSlideAndTunePublishAction' );

						// Secondary reset button configuration
						$button_text_reset = __( 'Reset', 'secret-image-slide-and-tune' );
						$type_reset = 'secondary';
						$button_name_reset = 'secret_image_slide_and_tune_button_reset';
						$wrap_reset = false;
						$other_attributes_reset = array( 'id' => 'secretImageSlideAndTunePublishReset' );

						submit_button( $button_text, $type, $button_name, $wrap, $other_attributes );

						submit_button( $button_text_reset, $type_reset, $button_name_reset, $wrap_reset, $other_attributes_reset );

						// Hidden secondary save button configuration
						$button_text = "Hidden Save";
						$type = 'secondary';
						$button_name = 'hidden_secret_image_slide_and_tune_button_update';
						$wrap = true;
						$other_attributes = array( 'id' => 'hiddenSecretImageSlideAndTunePublishAction' );

						submit_button( $button_text, $type, $button_name, $wrap, $other_attributes );

						_e( '<p class="description" id="saveReminder">Remember to save!</p>', 'secret-image-slide-and-tune' );

						_e( '<p class="description">Tired of hitting the save button all the time?</p>', 'secret-image-slide-and-tune' );

						_e( '<p class="description"><strong>Windows:</strong> Try using <strong>CTRL + S</strong> to save quickly instead!</p>', 'secret-image-slide-and-tune' );

						_e( '<p class="description"><strong>Mac:</strong> Try using <strong>CONTROL + S</strong> to save quickly instead!</p>', 'secret-image-slide-and-tune' );

					?>

				</form>
			</div>
		<?php
	}

	function secret_image_slide_and_tune_register_custom_settings() {

		// First, we are using 'register_setting' to create a new record in the wp_options table for our settings, with ‘my-secret-image-slide-and-tune-group’ as the option_name.
		/* Arguments:
			The Settings Field Group ID this field will belong to
			Unique field ID
		*/
		register_setting( 'my-secret-image-slide-and-tune-group', 'secret_image_slide_and_tune_secret_code' );
		register_setting( 'my-secret-image-slide-and-tune-group', 'secret_image_slide_and_tune_image_url' );
		register_setting( 'my-secret-image-slide-and-tune-group', 'secret_image_slide_and_tune_image_size' );
		register_setting( 'my-secret-image-slide-and-tune-group', 'secret_image_slide_and_tune_image_filename' );
		register_setting( 'my-secret-image-slide-and-tune-group', 'secret_image_slide_and_tune_audio_url' );
		register_setting( 'my-secret-image-slide-and-tune-group', 'secret_image_slide_and_tune_audio_filename' );
		register_setting( 'my-secret-image-slide-and-tune-group', 'secret_image_slide_and_tune_start_position' );
		register_setting( 'my-secret-image-slide-and-tune-group', 'secret_image_slide_and_tune_end_position' );
		register_setting( 'my-secret-image-slide-and-tune-group', 'secret_image_slide_and_tune_speed' );

		// Secondly, you gotta initiate the section/form that's going to have fields assigned to it
		/* Arguments:
			Unique ID of the section/form
			Title printed inside the section/form
			The function to be called to output the HTML content for this section/form
			What page this section/form is supposed to take place in ( check page slug )
		*/
		add_settings_section( 'secret-image-slide-and-tune-form', 'Secret Image Slide and Tune Settings', 'secret_image_slide_and_tune_settings', 'secret-image-slide-and-tune-settings' );

		// Lastly, you gotta actually create the input options for the section/form/group
		/* Arguments:
			Unique ID
			Title of field
			The function to be called to output the html content for this section/form
			What page this field should show up on ( must be same page listed within section )
			What section/form this field belongs to
		*/
		add_settings_field( 'set-the-secret-code', 'Secret code', 'set_secret_image_slide_and_tune_code', 'secret-image-slide-and-tune-settings', 'secret-image-slide-and-tune-form' );
		add_settings_field( 'set-the-secret-image', 'Secret image', 'set_secret_image_slide_and_tune_image', 'secret-image-slide-and-tune-settings', 'secret-image-slide-and-tune-form' );
		add_settings_field( 'set-the-secret-audio', 'Secret tune', 'set_image_slide_and_tune_audio', 'secret-image-slide-and-tune-settings', 'secret-image-slide-and-tune-form' );
		add_settings_field( 'set-the-secret-start-position', 'Horizontal start position', 'set_secret_image_slide_and_tune_start_position', 'secret-image-slide-and-tune-settings', 'secret-image-slide-and-tune-form' );
		add_settings_field( 'set-the-secret-end-position', 'Horizontal end position', 'set_secret_image_slide_and_tune_end_position', 'secret-image-slide-and-tune-settings', 'secret-image-slide-and-tune-form' );
		add_settings_field( 'set-the-secret-speed', 'Image speed', 'set_secret_image_slide_and_tune_speed', 'secret-image-slide-and-tune-settings', 'secret-image-slide-and-tune-form' );
	}

	// Section/Form title
	function secret_image_slide_and_tune_settings() {

		_e( '<p>Initial code setup: ArrowUp, ArrowUp, ArrowDown, ArrowDown, ArrowLeft, ArrowRight, ArrowLeft, ArrowRight, b, a</p>', 'secret-image-slide-and-tune' );
	}

	// Secret code input
	function set_secret_image_slide_and_tune_code() {

		$secret_image_slide_and_tune_secret_code = esc_attr( get_option( 'secret_image_slide_and_tune_secret_code', 'ArrowUp, ArrowUp, ArrowDown, ArrowDown, ArrowLeft, ArrowRight, ArrowLeft, ArrowRight, b, a' ) );

		printf( __( '<input type="text" size="115" id="secret_image_slide_and_tune_secret_code" name="secret_image_slide_and_tune_secret_code" value="%s" placeholder="Enter your secret code" autocomplete="off" />', 'secret-image-slide-and-tune' ), $secret_image_slide_and_tune_secret_code );

		_e( '<p class="description">Input your own custom coding sequence. ( 10 characters )</p>', 'secret-image-slide-and-tune' );

		_e( '<p class="description" id="codeComplete">Remember to save!</p>', 'secret-image-slide-and-tune' );
	}

	// Image preview, name, and dimensions
	// Button to choose secret image
	function set_secret_image_slide_and_tune_image() {

		$secret_image_slide_and_tune_image_url = esc_attr( get_option( 'secret_image_slide_and_tune_image_url', SECRET_IMAGE_SLIDE_AND_TUNE_FOLDER . 'images/raptor.png' ) );

		printf( __( '<input type="hidden" id="secret_image_slide_and_tune_image_url" name="secret_image_slide_and_tune_image_url" value="%s" />', 'secret-image-slide-and-tune' ), $secret_image_slide_and_tune_image_url );

		printf( __( '<div id="secret_image_slide_and_tune_image_preview" class="secret_image_slide_and_tune_image_button" style="cursor: pointer; background-image: url(\'%s\'); background-repeat: no-repeat; background-size: contain; width: 150px; height: 135px; display: inline-block;"></div>', 'secret-image-slide-and-tune' ), esc_url( $secret_image_slide_and_tune_image_url ) );

		$secret_image_slide_and_tune_image_filename = esc_attr( get_option( 'secret_image_slide_and_tune_image_filename', 'raptor.png' ) );
		printf( __( '<input type="hidden" id="secret_image_slide_and_tune_image_filename" name="secret_image_slide_and_tune_image_filename" value="%s" />', 'secret-image-slide-and-tune' ), $secret_image_slide_and_tune_image_filename );

		printf( __( '<p class="description" id="secret_image_slide_and_tune_image_filename_output">%s</p>', 'secret-image-slide-and-tune' ), $secret_image_slide_and_tune_image_filename );

		$secret_image_slide_and_tune_image_size = esc_attr( get_option( 'secret_image_slide_and_tune_image_size', '400 x 600' ) );
		printf( __( '<input type="hidden" id="secret_image_slide_and_tune_image_size" name="secret_image_slide_and_tune_image_size" value="%s" />', 'secret-image-slide-and-tune' ), $secret_image_slide_and_tune_image_size );

		printf( __( '<p class="description" id="secret_image_slide_and_tune_image_size_output">%s</p>', 'secret-image-slide-and-tune' ), $secret_image_slide_and_tune_image_size );

		_e( '<input type="button" class="button button-secondary secret_image_slide_and_tune_image_button" value="Choose Image" />', 'secret-image-slide-and-tune' );

		_e( '<p class="description" id="saveReminder3">Remember to save!</p>', 'secret-image-slide-and-tune' );
	}

	// Audio filename
	// Button to choose secret audio clip
	function set_image_slide_and_tune_audio() {

		$secret_image_slide_and_tune_audio_url = esc_attr( get_option( 'secret_image_slide_and_tune_audio_url', SECRET_IMAGE_SLIDE_AND_TUNE_FOLDER . 'audio/raptor-sound.mp3' ) );
		printf( __( '<input type="hidden" id="secret_image_slide_and_tune_audio_url" name="secret_image_slide_and_tune_audio_url" value="%s" />', 'secret-image-slide-and-tune' ), $secret_image_slide_and_tune_audio_url );

		$secret_image_slide_and_tune_audio_filename = esc_attr( get_option( 'secret_image_slide_and_tune_audio_filename', 'raptor-sound.mp3' ) );
		printf( __( '<input type="hidden" id="secret_image_slide_and_tune_audio_filename" name="secret_image_slide_and_tune_audio_filename" value="%s" />', 'secret-image-slide-and-tune' ), $secret_image_slide_and_tune_audio_filename );

		printf( __( '<p class="description" id="secret_image_slide_and_tune_audio_output">%s</p>', 'secret-image-slide-and-tune' ), $secret_image_slide_and_tune_audio_filename );

		_e( '<input type="button" class="button button-secondary secret_image_slide_and_tune_audio_button" value="Choose Tune" />', 'secret-image-slide-and-tune' );

		_e( '<p class="description" id="saveReminder4">Remember to save!</p>', 'secret-image-slide-and-tune' );
	}

	// Prompt the user where they would like the secret image to start
	function set_secret_image_slide_and_tune_start_position() {

		$secret_image_slide_and_tune_start_position = get_option( 'secret_image_slide_and_tune_start_position', '-400' );

		// Field validation check
		if ( preg_match( '/^([+-]?[0-9]\d*)$/', $secret_image_slide_and_tune_start_position ) ) {

			$secret_image_slide_and_tune_start_position = (int) $secret_image_slide_and_tune_start_position;
		}
		else {

			$secret_image_slide_and_tune_start_position = -400;

			_e( '<p class="description notice notice-error">Ending position must be a number!</p>', 'secret-image-slide-and-tune' );

			_e( '<p class="description notice notice-error">Ending position reset back to -400.</p>', 'secret-image-slide-and-tune' );
		}

		printf( __( '<input type="number" id="secret_image_slide_and_tune_start_position" name="secret_image_slide_and_tune_start_position" value="%s" placeholder="Horizontal Start Position" />', 'secret-image-slide-and-tune' ), $secret_image_slide_and_tune_start_position );

		_e( '<p class="description" id="saveReminder5">Remember to save!</p>', 'secret-image-slide-and-tune' );

		_e( '<p class="description">The starting position begins near the right-hand side of your browser.</p>', 'secret-image-slide-and-tune' );
	}

	// Prompt the user where they would like the secret image to end
	function set_secret_image_slide_and_tune_end_position() {

		$secret_image_slide_and_tune_end_position = get_option( 'secret_image_slide_and_tune_end_position', '-400' );

		// Field validation check
		if ( preg_match( '/^([+-]?[0-9]\d*)$/', $secret_image_slide_and_tune_end_position ) ) {

			$secret_image_slide_and_tune_end_position = (int) $secret_image_slide_and_tune_end_position;
		}
		else {

			$secret_image_slide_and_tune_end_position = -400;

			_e( '<p class="description notice notice-error">Ending position must be a number!</p>', 'secret-image-slide-and-tune' );

			_e( '<p class="description notice notice-error">Ending position reset back to -400.</p>', 'secret-image-slide-and-tune' );
		}

		printf( __( '<input type="number" id="secret_image_slide_and_tune_end_position" name="secret_image_slide_and_tune_end_position" value="%s" placeholder="Horizontal End Position" />', 'secret-image-slide-and-tune' ), $secret_image_slide_and_tune_end_position );

		_e( '<p class="description" id="saveReminder6">Remember to save!</p>', 'secret-image-slide-and-tune' );

		_e( '<p class="description">The ending position ends towards the left-hand side of your browser.</p>', 'secret-image-slide-and-tune' );
	}

	// Prompt the user how fast they would like the secret image to slide across their browser
	function set_secret_image_slide_and_tune_speed() {

		$secret_image_slide_and_tune_speed = get_option( 'secret_image_slide_and_tune_speed', '2200' );

		if ( (int) $secret_image_slide_and_tune_speed === 0 && (string) $secret_image_slide_and_tune_speed === "0" ) {

			$secret_image_slide_and_tune_speed = 0;
		}
		else if ( (int) $secret_image_slide_and_tune_speed > 0 ) {

			$secret_image_slide_and_tune_speed = (int) $secret_image_slide_and_tune_speed;
		}
		else {

			$secret_image_slide_and_tune_speed = 2200;

			_e( '<p class="description notice notice-error">Speed must be a number greater than or equal to 0!</p> <p class="description notice notice-error">Speed reset back to 2200.</p>', 'secret-image-slide-and-tune' );
		}

		printf( __( '<input type="number" id="secret_image_slide_and_tune_speed" name="secret_image_slide_and_tune_speed" value="%s" placeholder="Image Speed" />', 'secret-image-slide-and-tune' ), $secret_image_slide_and_tune_speed );

		_e( '<p class="description" id="saveReminder7">Remember to save!</p>', 'secret-image-slide-and-tune' );

		_e( '<p class="description">Set the sliding animation speed to any number greater than or equal to 0.</p>', 'secret-image-slide-and-tune' );

		_e( '<p class="description">Lower # = Faster animation.</p>', 'secret-image-slide-and-tune' );

		_e( '<p class="description">Higher # = Slower animation.</p>', 'secret-image-slide-and-tune' );

		_e( '<p class="description"><strong>Hint:</strong> 2200 is a good place to start.</p>', 'secret-image-slide-and-tune' );
	}