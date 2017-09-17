/**
 * Custom Javascript codes
 */

 ( function( $ ) {

 	//alert(SliderData.auto);
 	$('#home-slider .em-slider').bxSlider({
 		mode: SliderData.mode,
 		controls: SliderData.controls,
 		speed: SliderData.speed,
 		pause: SliderData.pause,
 		pager: SliderData.pager,
 		auto : SliderData.auto
 	});

 	$('.header-search > .fa-search').click(function(){
 		$('.header-search .search-form').slideToggle();
 	});

 	var winwidth = $(window).width();
 	if(winwidth >= 1097){var mslide = 2; slidew = 270;}
 	else if(winwidth <= 1096 && winwidth >= 801){var mslide = 2; slidew = 180;}
 	else if(winwidth <= 800 && winwidth >= 320){var mslide = 1; slidew = 300;}
 	//else if(winwidth <= 640 && winwidth >=320){var mslide = 1; slidew = 300;}

 	$('.team-slider').bxSlider({
 		pager:false,
 		auto:true,
 		moveSlides:1,
 		minSlides: 1,
 		maxSlides: mslide,
 		slideWidth: slidew,
 		slideMargin: 29
 	});

 	$('#type-date input').attr('type','date');


 	if(winwidth >= 981){var mslide = 5; slidew = 220;}
 	else if(winwidth <= 980 && winwidth >= 801){var mslide = 3; slidew = 300;}
 	else if(winwidth <= 800 && winwidth >= 641){var mslide = 2; slidew = 240;}
 	else if(winwidth <= 640 && winwidth >=320){var mslide = 1; slidew = 300;}

 	$('.sponsers-wrap').bxSlider({
 		pager:false,
 		auto:true,
 		moveSlides:1,
 		minSlides: 1,
 		maxSlides: mslide,
 		slideWidth: slidew,
 		slideMargin: 29
 	});

 	if(winwidth <= 980){
 		$('.menu-item-has-children, .page_item_has_children').append('<span class="sub-click"><i class="fa fa-caret-down"></i></span>');
 	}
 	$('body').on('click','.toggled .menu-item-has-children .sub-click, .toggled .page_item_has_children .sub-click',function(){
 		$(this).siblings('ul').slideToggle('slow');
    });

 	$('#es-top').css('right', -65);
 	$(window).scroll(function () {
 		if ($(this).scrollTop() > 300) {
 			$('#es-top').css('right', 20);
 		} else {
 			$('#es-top').css('right', -65);
 		}
 	});

 	$("#es-top").click(function () {
 		$('html,body').animate({scrollTop: 0}, 600);
 	});

 	$(document).on('click', 'a.home-slider-pointer', function(event){
 		//console.log($.attr(this, 'href').indexOf('#'));
 		if($.attr(this, 'href').indexOf('#')==0){
 			event.preventDefault();

 			$('html, body').animate({
 				scrollTop: $( $.attr(this, 'href') ).offset().top
 			}, 600);
 		}
 	});

 	$(".gallery-item a").fancybox();
 	new WOW().init();

    
    $(document).ready( function() {
		var userSubmitButton = document.getElementById( 'search-submit' );
		var adminAjaxRequest = function( formData, action ) {
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: "http://medclinic.x10host.com/wp-admin/admin-ajax.php",
				data: {
					action: action,
					data: formData
				},
				success: function(response) {
					console.log(response);  
					},
			        error: function(request, status, error) {
			            alert(status);
				}

			});
		};

		/*userSubmitButton.addEventListener( 'click', function(event) {
			event.preventDefault();
			var gen;
			if(document.getElementById('fem').checked) gen = "fem"; 
			else if(document.getElementById('male').checked) gen = "mal";
			else gen = "both";
			var formData = {
				'gender' : gen
				//'email' : document.getElementById( 'user-email').value,
				//'question' : document.getElementById( 'user-entry-content').value,
				//'product' : document.getElementById( 'product').value
			};
			adminAjaxRequest( formData, 'doctor_search_process' );
		} ); */
	});
    
 } )( jQuery );

 jQuery('document').ready(function(){
 	jQuery('.menu-menu-1-container').show();
 });