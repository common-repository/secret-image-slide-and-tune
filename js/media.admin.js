(
	function($) {

		var imageUploader;
		var tuneUploader;

		// When the Choose Image button is clicked
		$( '.secret_image_slide_and_tune_image_button' ).on( 'click', function(e) {
			e.preventDefault();

			if( imageUploader ) {
				imageUploader.open();
				return;
			}

			imageUploader = wp.media.frames.file_frame = wp.media({
				title: 'Choose Secret Image',
				button: {
					text: 'Set Secret Image'
				},
				multiple: false,
				library: {
					type: [ 'image/jpeg', 'image/png' ]
				}
			});

			imageUploader.on( 'select',  function() {

				imageAttachment = imageUploader.state().get('selection').first().toJSON();

				$( "#secret_image_slide_and_tune_image_url" ).val( imageAttachment.url );
				$( "#secret_image_slide_and_tune_image_preview" ).css( 'background-image', 'url(' + imageAttachment.url + ')' );

				$( "#secret_image_slide_and_tune_image_filename" ).val( imageAttachment.filename );				
				$( "#secret_image_slide_and_tune_image_filename_output" ).html( imageAttachment.filename );				

				$( "#secret_image_slide_and_tune_image_size" ).val( imageAttachment.width + " x " + imageAttachment.height );
				$( "#secret_image_slide_and_tune_image_size_output" ).html( imageAttachment.width + " x " + imageAttachment.height );
			});

			imageUploader.open();

			// Detect image change
			$( "#saveReminder3" ).css( "display", "block" );
		});

		// When the Choose Audio button is clicked
		$( '.secret_image_slide_and_tune_audio_button' ).on( 'click', function(e) {
			e.preventDefault();

			if( tuneUploader ) {
				tuneUploader.open();
				return;
			}

			tuneUploader = wp.media.frames.file_frame = wp.media({
				title: 'Choose Secret Tune',
				button: {
					text: 'Set Secret Tune'
				},
				multiple: false,
				library: {
					type: [ 'audio/mpeg' ]
				}
			});

			tuneUploader.on( 'select',  function() {

				tuneAttachment = tuneUploader.state().get('selection').first().toJSON();

				$( "#secret_image_slide_and_tune_audio_url" ).val( tuneAttachment.url );
				$( "#secret_image_slide_and_tune_audio_filename" ).val( tuneAttachment.filename );
				$( "#secret_image_slide_and_tune_audio_output" ).html( tuneAttachment.filename );
			});

			tuneUploader.open();

			// Detect audio change
			$( "#saveReminder4" ).css( "display", "block" );
		});
	}

)( jQuery );