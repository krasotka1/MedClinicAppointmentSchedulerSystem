( function( $ ) {
$(document).ready( function() {
		var userSubmitButton = document.getElementById( 'search-submit' );

		var adminAjaxRequest = function( formData, action ) {
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: SliderData.adminAjax,
				data: {
					action: action,
					data: formData
					//submission: document.getElementById( 'xyq').value,
					//security: screenReaderText.security
				},
				success: function(response) {
					if ( true === response.success ) {
						alert( 'this was a success' );
					} else {
						alert( 'You Suck' );
					}
				}
			});
		};

		userSubmitButton.addEventListener( 'click', function(event) {
			event.preventDefault();
			var gen;
			if(document.getElementById('fem').checked) gen = true; 
			else if(document.getElementById('male').checked) gen = false;
			else gen = null;
			console.log("button pressed");
			var formData = {
				'gender' : gen
				//'email' : document.getElementById( 'user-email').value,
				//'question' : document.getElementById( 'user-entry-content').value,
				//'product' : document.getElementById( 'product').value
			};
			adminAjaxRequest( formData, 'doctor_search_process' );
		} );
	});
});