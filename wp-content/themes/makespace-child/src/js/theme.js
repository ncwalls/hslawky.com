(function($){

	var homeHeroSlider = function() {
		$('.hero-slider').slick({
			arrows: false,
			// prevArrow: '<button class="slick-arrow slick-prev" aria-label="Previous" type="button"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20.93 70"><polygon points="20.77 70 8.15 35.01 20.93 0 13.02 0 0 35 13.02 70 20.77 70"/></svg></button>',
			// nextArrow: '<button class="slick-arrow slick-next" aria-label="Next" type="button"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20.93 70"><polygon points=".16 0 12.78 34.99 0 70 7.91 70 20.93 35 7.91 0 .16 0"/></svg></button>',
			dots: false,
			fade: true,
			speed: 2000,
			autoplay: true,
			autoplaySpeed: 4000,
			pauseOnHover: false,
			lazyLoad: 'ondemand',
		});
	};

	var heroVideo = function() {

		$('[data-action="hero-popup-play"]').on('click', function(e) {
			e.preventDefault();

			var heroVideoModal = document.getElementById('hero-video-modal');
			var heroVideoModalObj = $('#hero-video-modal');

			$.magnificPopup.open({
				mainClass: 'hero-popup',
				items: {
					src: '#hero-video-modal-container',
					type: 'inline',
				},
				callbacks: {
					open: function() {
						if(heroVideoModalObj.length){
							heroVideoModal.play();
						}
					},
					close: function() {
						if(heroVideoModalObj.length){
							heroVideoModal.pause();
							heroVideoModal.currentTime = 0;
						}
					},
				}
			}, 0);
			
		});
		
		$('[data-action="hero-popup-embed"]').magnificPopup({
			type: 'iframe',
			// mainClass: 'hero-popup'
			iframe: {
				patterns: {
					youtube: {
						index: 'youtube.com/',
						id: 'v=',
						src: '//www.youtube.com/embed/%id%?autoplay=1&rel=0'
					}
				}
			}
		});

	};

	// var videoSize = function (videoContainerSelector) {
	// 	var videoContainer = $(videoContainerSelector);
	
	// 	if( videoContainer.length > 0){

	// 		var vidType = '';
	// 		var videoContainerWidth = videoContainer.outerWidth();
	// 		var videoContainerHeight = videoContainer.outerHeight();
	// 		var videoContainerRatio = videoContainerHeight / videoContainerWidth;
	// 		var vidObj = videoContainer.find('.hero-video');
	// 		var vidEl;
	// 		var vidRatio = 0;
			
	// 		if(videoContainer.find("video").length > 0){
	// 			vidType = 'video';
	// 			// vidObj = videoContainer.find("video");
				
	// 			vidEl = document.getElementById('videoembed');
	// 			vidRatio = vidEl.videoHeight / vidEl.videoWidth;
	// 		}
	// 		else if(videoContainer.find("iframe").length > 0){
	// 			vidType = 'iframe';
	// 			// vidObj = videoContainer.find('videoembed');

	// 			vidEl = document.getElementById('videoframe');
	// 			// vidRatio = vidEl.offsetHeight / vidEl.offsetWidth;
	// 			vidRatio = 0.5625;
	// 		}
	// 		else{
	// 			return;
	// 		}
	// 		// var vidEl = document.getElementById(vidObj.attr("id"));
	// 		// var vidRatio = vidEl.videoHeight / vidEl.videoWidth;

	// 		if (isNaN(vidRatio) || vidRatio == 0) {
	// 			vidRatio = 0.5625;
	// 		}

	// 		var videoResize = function () {
	// 			videoContainerWidth = videoContainer.outerWidth();
	// 			videoContainerHeight = videoContainer.outerHeight();
	// 			videoContainerRatio = videoContainerHeight / videoContainerWidth;

	// 			if(vidType == 'video'){
	// 				vidRatio = vidEl.videoHeight / vidEl.videoWidth;
	// 			}
	// 			else if(vidType == 'iframe'){
	// 				vidRatio = 0.5625;
	// 			}

	// 			if (isNaN(vidRatio) || vidRatio == 0) {
	// 				vidRatio = 0.5625;
	// 			}

	// 			if (videoContainerRatio <= vidRatio) {
	// 				vidObj.removeClass("tall");
	// 				vidObj.addClass("wide");
	// 				vidObj.css({
	// 					'height' : (vidRatio*100)+'vw',
	// 					'width' : ''
	// 				});
	// 				// console.log();
	// 			} else {
	// 				vidObj.removeClass("wide");
	// 				vidObj.addClass("tall");
	// 				vidObj.css({
	// 					'height' : '',
	// 					'width' : (videoContainerHeight / vidRatio)
	// 				});
	// 			}
	// 		};

	// 		videoResize();
	// 		optimizedResize.add(videoResize);
	// 	}
	// };


	var scrollAnim = function(){

		///** convert to use intersectionObserver **///

		var win = $(window);

		var items = $('.scroll-animate-item');

		var itemScrollCheck = function(){

			var winHeight = win.innerHeight();
			var winTop = win.scrollTop();
			var scrollTriggerPos = (winHeight * .8) + winTop;

			items.each(function(i, el){

				var item = $(el);
				var itemTop = item.offset().top;

				if(itemTop <= scrollTriggerPos){
					item.addClass('vis');
				}
				else{
					item.removeClass('vis');
				}

			});

		};

		itemScrollCheck();
		optimizedScroll.add(itemScrollCheck);
	};


	var scrollToAnchor = function(){

		if( location.hash ){
			// window.scrollTo(0,0);

			$( 'body' ).removeClass( 'nav-open' );
			
			var locationHashObj = $(location.hash);
			
			if(locationHashObj.length > 0){
				//$('body').removeClass('nav-open');
				// $('body,html').animate({
				// 	scrollTop: locationHashObj.offset().top - 150
				// }, 500);

				var waitTime = 501;

				if(!$('body').hasClass('scrolled')){
					waitTime = 501;
					$('body').addClass('scrolled');
				}

				setTimeout(function(){
					var anchorPosition = locationHashObj.offset().top;
					var finalPosition = anchorPosition - $('.site-header').outerHeight() - 20;
					$("html, body").animate({scrollTop: finalPosition}, 1000);


				}, waitTime);
			}
		}

		$('.scroll-to-anchor a, a[href^="#"]').on('click', function(e){

			if($(this).hasClass('no-scroll')){
				return;
			}
			else if(this.hash){
				
				var hashTarget = $(this.hash);

				if( hashTarget.length ){
					e.preventDefault();
					var waitTime = 0;
					
					if(!$('body').hasClass('scrolled')){
						waitTime = 501;
						$('body').addClass('scrolled');
					}

					setTimeout(function(){
						var anchorPosition = hashTarget.offset().top;
						var finalPosition = anchorPosition - $('.site-header').outerHeight() - 20;
						$("html, body").animate({scrollTop: finalPosition}, 1000);

						$( 'body' ).removeClass( 'nav-open' );

					}, waitTime);
				}
			}
		});
	};

	var blockGallery = function() {
		$('.wp-block-gallery').magnificPopup({
			delegate: 'a',
			type: 'image',
			gallery: {
				enabled: true
			}
		});
	};

	var faqs = function() {

		$('[data-action="faq"]').on('click', function(e) {
			e.preventDefault();

			$(this).toggleClass('open');
		});
	};


	$(document).ready(function(){
		homeHeroSlider();
		heroVideo();
		// videoSize('.hero-video-container');
		scrollAnim();
		scrollToAnchor();
		blockGallery();
	});

    window.addEventListener('load', function() {
        var videoContainer = $('.hero-video-container');
        videoContainer.addClass('vis');
    }, false);

})(jQuery);

/* Lazy load bg images https://web.dev/lazy-loading-images/ */
// document.addEventListener("DOMContentLoaded", function() {
//   var lazyBackgrounds = [].slice.call(document.querySelectorAll(".lazy-background"));

//   if ("IntersectionObserver" in window) {
//     let lazyBackgroundObserver = new IntersectionObserver(function(entries, observer) {
//       entries.forEach(function(entry) {
//         if (entry.isIntersecting) {
//           entry.target.classList.add("visible");
//           lazyBackgroundObserver.unobserve(entry.target);
//         }
//       });
//     });

//     lazyBackgrounds.forEach(function(lazyBackground) {
//       lazyBackgroundObserver.observe(lazyBackground);
//     });
//   }
// });