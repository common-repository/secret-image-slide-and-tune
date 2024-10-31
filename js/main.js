(
	function($) {

		// Frontend secret code processing
		$(document).ready(function() {

			// The Default Konami Code sequence
			// var konamiCodeSequence = [ 'up', 'up', 'down', 'down', 'left', 'right', 'left', 'right', 'b', 'a' ];

			var finalKonamiCodeSequence = php_vars.theChosenCode;

			var finalKonamiCodeCSV = finalKonamiCodeSequence.split(", ");

			// A variable to remember the 'position' the user has reached so far.
			var keyCodePosition = 0;

			// Main image
			var mainImageMarkup = '<img id="theMegazord" style="display: none" src="' + php_vars.theChosenImage + '">';

			// Main tune
			var mainAudioMarkup = '<audio id="theMegazordCall" preload="auto"><source src="' + php_vars.theChosenTune + '" type="audio/mpeg" ></audio>';

			// The Megazord awaits to be summoned
			$( 'body' ).append( mainImageMarkup );

			// By the call of its owner
			$( 'body' ).append( mainAudioMarkup );

			// Keyboard event values
			// var keyCodeMapping = {
			// 	37: 'Left',
			// 	38: 'Up',
			// 	39: 'Right',
			// 	40: 'Down',
			// 	97: 'a',
			// 	98: 'b'
			// };

			// Add keydown or keypress event listener
			document.addEventListener( 'keydown', function(e) {

				// Get the value of the key code from the key map
				// var userEnteredCombinationValue = keyCodeMapping[e.which];
				// var userEnteredCombinationValue = String.fromCharCode( e.which );
				var userEnteredCombinationValue = e.key;

				// Get the value of the required key from the konami code
				var requiredKeyValue = finalKonamiCodeCSV[keyCodePosition];

				// You have to say the magic word!
				if ( userEnteredCombinationValue == requiredKeyValue) {

					// Move to the next key in the code sequence
					keyCodePosition++;

					// If the c-c-c-combo was done correctly, bless the rains down in Africa
					if ( keyCodePosition == finalKonamiCodeCSV.length ) {

						itsMorphinTime();

						// Wanna play again?
						keyCodePosition = 0;
					}
				}
				else {

					// Game Over: Insert 25 cents to play again
					keyCodePosition = 0;
				}
			});

			function itsMorphinTime() {

				// Clever girl
				var megazord = $( '#theMegazord' ).css({
					"position" : "fixed",
					"bottom"   : "-1000px",
					"right" : parseInt( php_vars.theChosenStartPosition, 10 ) + "px",
					"display"  : "block",
					"z-index"  : "9999"
				});

				// Get the guitar
				playSound();

				// Play the guitar solo
				function playSound() {

					document.getElementById( 'theMegazordCall' ).play();
				}

				// Movement hilarity
				megazord.animate( { "bottom" : "0" }, 300,
					function() {
						$(this).animate( { "bottom" : "-30px" }, 300,
							function() {

								// $(this).position().left == starting top-left-corner position of the image
								// theChosenEndPosition == where you want the top-left-corner image to stop at
								var top_left_image_position = ( ( $(this).position().left ) - parseInt( php_vars.theChosenEndPosition, 10 ) );

								$(this).delay(300).animate( { "right" : parseInt( top_left_image_position, 10 ) + parseInt( php_vars.theChosenStartPosition, 10 ) }, parseInt( php_vars.theChosenSpeed, 10 ),
									function() {
										megazord = $( '#theMegazord' ).css({
											"bottom": "-1000px",
											"right" : parseInt( php_vars.theChosenStartPosition, 10 ) + "px"
										});
									}
								);
							}
						);
					}
				);
			}
		});
	}
)( jQuery );