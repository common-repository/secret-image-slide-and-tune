(
	function($) {

		// Admin backend secret code processing
		$( document ).ready( function() {

			// Secondary secret button
			$( "#hiddenSecretImageSlideAndTunePublishAction" ).parent().css( "display", "none" );

			// Declare empty array
			var konamiCodeArray = [];

			// Decalre counter for character limit of 10
			var konamiCodeCounter = 0;

			// Saving switch
			var submitAction = false;

			// Records keypresses for the secret code input field
			$( "#secret_image_slide_and_tune_secret_code" ).keydown(function(e) {

				if ( konamiCodeCounter < 10 ) {

					e.preventDefault();

					konamiCodeArray.push( e.key );

					$( "#secret_image_slide_and_tune_secret_code" ).val( konamiCodeArray );

					konamiCodeCounter++;

					if( konamiCodeCounter == 10 ) {

						konamiCodeArray = konamiCodeArray.join( ', ' );

						$( "#secret_image_slide_and_tune_secret_code" ).val( konamiCodeArray );

						$( "#secret_image_slide_and_tune_secret_code" ).attr( "readonly", "readonly" );

						$( "#codeComplete" ).css( "display", "block" );
					}
				}
			});

			// Quick save keyboard shortcut
			$( document ).keydown(function(e) {

				// Windows: CTRL + S
				if ( ( e.ctrlKey && e.which == 83 ) ) {

					e.preventDefault();

					// Run the command if this statement holds true
					if( !submitAction ) {

						konamiCodeInputValue = $( "#secret_image_slide_and_tune_secret_code" ).val().split( "," );

						if( konamiCodeCounter == 10 || konamiCodeInputValue.length == 10 ) {

							$( ".dennis-nedry" ).slideUp();

							submitAction = true;

							$( "#codeComplete" ).css( "display", "none" );

							$( "#secret_image_slide_and_tune_secret_code" ).removeAttr( "readonly" );

							konamiCodeCounter = 0;

							konamiCodeArray = [];
						}
						else if( konamiCodeCounter < 10 ) {

							$( ".dennis-nedry" ).slideUp();

							var konamiCodeOutcome = 10 - konamiCodeCounter;

							if ( konamiCodeOutcome == 1 ) {
								$( "#secret_image_slide_and_tune_secret_code" ).after( "<p class='dennis-nedry description notice notice-error'>Nuh uh uh! Your coding sequence is not complete. You need about " + ( konamiCodeOutcome ) + " more character.</p>" );
							}
							else {
								$( "#secret_image_slide_and_tune_secret_code" ).after( "<p class='dennis-nedry description notice notice-error'>Nuh uh uh! Your coding sequence is not complete. You need about " + ( konamiCodeOutcome ) + " more characters.</p>" );
							}
						}
					}

					if( submitAction ) {

						submitAction = false;

						$( "#hiddenSecretImageSlideAndTunePublishAction" ).trigger( 'click' );
					}
				}
			});

			// Field check for when the user clicks save, but there are some items that are not up to 'code' ( pun intended )
			$( "#secretImageSlideAndTunePublishAction, #hiddenSecretImageSlideAndTunePublishAction" ).on( "click", function(e) {

				e.preventDefault();

				if( !submitAction ) {

					konamiCodeInputValue = $( "#secret_image_slide_and_tune_secret_code" ).val().split(",");

					if( konamiCodeCounter == 10 || konamiCodeInputValue.length == 10 ) {

						$( ".dennis-nedry" ).slideUp();

						submitAction = true;

						$( "#codeComplete" ).css( "display", "none" );

						$( "#secret_image_slide_and_tune_secret_code" ).removeAttr( "readonly" );

						konamiCodeCounter = 0;

						konamiCodeArray = [];

						shipoopi( e );
					}
					else if( konamiCodeCounter < 10 ) {

						$( ".dennis-nedry" ).slideUp();

						var konamiCodeOutcome = 10 - konamiCodeCounter;

						if ( konamiCodeOutcome == 1 ) {
							$( "#secret_image_slide_and_tune_secret_code" ).after( "<p class='dennis-nedry description notice notice-error'>Nuh uh uh! Your coding sequence is not complete. You need about " + ( konamiCodeOutcome ) + " more character.</p>" );
						}
						else {
							$( "#secret_image_slide_and_tune_secret_code" ).after( "<p class='dennis-nedry description notice notice-error'>Nuh uh uh! Your coding sequence is not complete. You need about " + ( konamiCodeOutcome ) + " more characters.</p>" );
						}

						$( "#wpadminbar" ).click();
					}
				}
				
				if( submitAction ) {

					submitAction = false;
				}
			});

			// Reset button
			$( "#secretImageSlideAndTunePublishReset" ).on( "click", function(e) {

				konamiCodeArray = [];

				konamiCodeCounter = 0;

				submitAction = false;

				shipoopi_two( e );

			});
		});

		// Detect start position change
		$( '#secret_image_slide_and_tune_start_position' ).on( "change", function () {
			$( "#saveReminder5" ).css( "display", "block" );
		});

		// Detect end position change
		$( '#secret_image_slide_and_tune_end_position' ).on( "change", function () {
			$( "#saveReminder6" ).css( "display", "block" );
		});

		// Detect speed change
		$( '#secret_image_slide_and_tune_speed' ).on( "change", function () {
			$( "#saveReminder7" ).css( "display", "block" );
		});

		function shipoopi_two( event ) {
			event.preventDefault();

			var $form = $( "#ajax-form" );

			var form_data = getFormData( $form );

			function getFormData( $form ) {

				var unindexed_array = $form.serializeArray();
				var indexed_array = {};

				$.map( unindexed_array, function( n, i ) {
					indexed_array[ n[ 'name' ] ] = n[ 'value' ];
				});

				return indexed_array;
			}

			// console.log( 'This is form data: ' + JSON.stringify( form_data ) );

			// var loginForm = $( '#ajax-form' ).serializeArray();
			// var loginFormObject = {};
			// $.each( loginForm,
			// 	function(i, v) {
			// 		loginFormObject[v.name] = v.value;
			// 	}
			// );

			// console.log( "OLD DATA: " + $(this).serialize() );

			$.ajax({
				type: "POST",							// use $_POST request to submit data
				dataType: 'json',
				url: secret_ajax_data.ajax_url,			// URL to "wp-admin/admin-ajax.php"
				data: {
					action: 'secret_ajax_two', 				// Send data to wp_ajax_*, wp_ajax_nopriv_* functions
					plugin_data: form_data,
					nonce: secret_ajax_data.nonce
				},
				success: function( response ) {

					if( ! response.status || response.status === 'error' ) {
						
						alert( response.status_message || "Unable to complete request" );

						$( "#custom-plugin-settings-success" ).slideUp( 'fast' );
						$( "#custom-plugin-settings-error" ).slideDown( 'fast' );

						return false;
					}
					else {
						
						$( '#secret_image_slide_and_tune_secret_code' ).val( 'ArrowUp, ArrowUp, ArrowDown, ArrowDown, ArrowLeft, ArrowRight, ArrowLeft, ArrowRight, b, a' );

						imagePath = '/wp-content/plugins/secret-image-slide-and-tune/images/raptor.png';
						
						audioPath = '/wp-content/plugins/secret-image-slide-and-tune/audio/raptor-sound.mp3';

						$( '#secret_image_slide_and_tune_image_url' ).val( imagePath );
						$( '#secret_image_slide_and_tune_image_preview' ).css( 'background-image', 'url(' + imagePath + ')' );
						
						$( '#secret_image_slide_and_tune_image_filename' ).val( response.info.image_filename );
						$( '#secret_image_slide_and_tune_image_filename_output' ).html( response.info.image_filename );

						$( '#secret_image_slide_and_tune_image_size' ).val( response.info.image_size );
						$( '#secret_image_slide_and_tune_image_size_output' ).html( response.info.image_size );

						$( '#secret_image_slide_and_tune_audio_url' ).val( audioPath );
						$( '#secret_image_slide_and_tune_audio_filename' ).val( response.info.audio_filename );
						$( '#secret_image_slide_and_tune_audio_output' ).html( response.info.audio_filename );

						$( "#secret_image_slide_and_tune_start_position" ).val( response.info.horizontal_start_position );
						$( "#secret_image_slide_and_tune_end_position" ).val( response.info.horizontal_end_position );
						
						$( '#secret_image_slide_and_tune_speed' ).val( response.info.secret_speed );						

						$( "#wpadminbar" ).click();
						$( "#custom-plugin-settings-error" ).slideUp( 'fast' );
						$( "#custom-plugin-settings-success" ).slideUp( 'fast' );
						$( "#custom-plugin-settings-success" ).slideDown( 'slow' );
						$( ".dennis-nedry" ).slideUp();
						$( "#saveReminder3, #saveReminder4, #saveReminder5, #saveReminder6, #saveReminder7" ).css( "display", "none" );
					}
				}
			});
		}

		function shipoopi( event ) {

			event.preventDefault();

			var $form = $( "#ajax-form" );

			var form_data = getFormData( $form );

			function getFormData( $form ) {

				var unindexed_array = $form.serializeArray();
				var indexed_array = {};

				$.map( unindexed_array, function( n, i ) {
					indexed_array[ n[ 'name' ] ] = n[ 'value' ];
				});

				return indexed_array;
			}

			// console.log( 'This is form data: ' + JSON.stringify( form_data ) );

			// var loginForm = $( '#ajax-form' ).serializeArray();
			// var loginFormObject = {};
			// $.each( loginForm,
			// 	function(i, v) {
			// 		loginFormObject[v.name] = v.value;
			// 	}
			// );

			// console.log( "OLD DATA: " + $(this).serialize() );

			$.ajax({
				type: "POST",							// use $_POST request to submit data
				dataType: 'json',
				url: secret_ajax_data.ajax_url,			// URL to "wp-admin/admin-ajax.php"
				data: {
					action: 'secret_ajax', 				// Send data to wp_ajax_*, wp_ajax_nopriv_* functions
					plugin_data: form_data,
					nonce: secret_ajax_data.nonce
				},
				success: function( response ) {

					if( ! response.status || response.status === 'error' ) {
						
						alert( response.status_message || "Unable to complete request" );

						$( "#custom-plugin-settings-success" ).slideUp( 'fast' );
						$( "#custom-plugin-settings-error" ).slideDown( 'fast' );

						return false;
					}
					else {
						
						$( "#wpadminbar" ).click();
						$( "#custom-plugin-settings-error" ).slideUp( 'fast' );
						$( "#custom-plugin-settings-success" ).slideUp( 'fast' );
						$( "#custom-plugin-settings-success" ).slideDown( 'slow' );
						$( "#saveReminder3, #saveReminder4, #saveReminder5, #saveReminder6, #saveReminder7" ).css( "display", "none" );
					}
				}
			});
		}
	}

)( jQuery );