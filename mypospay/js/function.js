(function ($) {
    "use strict";
	
	var $window = $(window); 
	var $body = $('body'); 
	
	/* Preloader Effect */
	$window.on('load', function(){
		$(".preloader").fadeOut(600);
	});

	/* Sticky header */
	$window.scroll(function(){
    	if ($window.scrollTop() > 200) {
			$('.navbar').addClass('sticky-header');
		} else {
			$('.navbar').removeClass('sticky-header');
		}
	});
	
	/* Add Active class on Accordion */
	$('.accordion-4 .panel-heading a').click(function() {
		$('.panel-heading').removeClass('active');
		if(!$(this).closest('.panel').find('.panel-collapse').hasClass('in'))
			$(this).parents('.panel-heading').addClass('active');
	});
	
	/* Testimonial slider start */
	var swiper = new Swiper('.testimonial-slider', {
		slidesPerView: 1,
		spaceBetween: 30,
		autoplay: true,
		speed: 1000,
		pagination:{
			el: '.testimonial-pagination',
			clickable: true
		}
	});
	
	/* Popup video */
	$('.popup-video').magnificPopup({
        type: 'iframe',
        preloader: true,
    });
	
	/* Screenshot slider start */
	var app_swiper = new Swiper('.screenshot-slider', {
		slidesPerView: 1,
		spaceBetween: 80,
		centeredSlides: true,
		navigation: {
			nextEl: '.screenshot-button-next',
			prevEl: '.screenshot-button-prev',
		},
		breakpoints: {
			991: {
				slidesPerView: 3
			}
		},
		on:{
			init: function () {
				var slide_text = $(".swiper-slide-active .swiper-slide-text").html();
				$('.swiper-slide-right-text').html(slide_text);
			},
		},
    });
	
	app_swiper.on('slideChangeTransitionEnd', function () {
		var slide_text = $(".swiper-slide-active .swiper-slide-text").html();
		$('.swiper-slide-right-text').hide();
		$('.swiper-slide-right-text').html(slide_text);
		$('.swiper-slide-right-text').fadeIn(500);
	});
	
	$('.counting-number').counterUp({
		delay: 10,
		time: 2000
	});
	
	/* Contact form validation */
	var $contactform=$("#contactForm");
	$contactform.validator({focus: false}).on("submit", function (event) {
		if (!event.isDefaultPrevented()) {
			event.preventDefault();
			submitForm();
		}
	});

	function submitForm(){
		/* Initiate Variables With Form Content*/
		var name = $("#name").val();
		var email = $("#email").val();
		var subject = $("#subject").val();
		var message = $("#message").val();

		$.ajax({
			type: "POST",
			url: "form-process.php",
			data: "name=" + name + "&email=" + email + "&subject=" + subject + "&message=" + message,
			success : function(text){
				if (text == "success"){
					formSuccess();
				} else {
					submitMSG(false,text);
				}
			}
		});
	}

	function formSuccess(){
		$contactform[0].reset();
		submitMSG(true, "Message Sent Successfully!")
	}

	function submitMSG(valid, msg){
		if(valid){
			var msgClasses = "h3 text-center text-success";
		} else {
			var msgClasses = "h3 text-center text-danger";
		}
		$("#msgSubmit").removeClass().addClass(msgClasses).text(msg);
	}
	/* Contact form validation end */
	
	/* Animate with wow js */
    new WOW({mobile:false}).init(); 
	
	/* Parallaxie Js */
	if ($window.width() > 768) {
		$('.parallaxie').parallaxie({
			speed: 0.55,
			 offset: 0,
		});
	}
	
})(jQuery);