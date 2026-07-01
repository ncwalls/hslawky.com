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
		});

	};


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

	var reviewsSlider = function() {
		if($('.reviews-slider').length) {
			$('.reviews-slider').slick({
				dots: false,
				arrows: true,
				prevArrow: '<button class="slick-arrow slick-prev" aria-label="Previous" type="button"><svg xmlns="http://www.w3.org/2000/svg" width="21" height="38" viewBox="0 0 21 38" fill="none"><path d="M21 0H12.6116L0 19.0075L12.6116 38H21L8.34504 19.0075L21 0Z" fill="black"/></svg></button>',
				nextArrow: '<button class="slick-arrow slick-next" aria-label="Next" type="button"><svg xmlns="http://www.w3.org/2000/svg" width="21" height="38" viewBox="0 0 21 38" fill="none"><path d="M0 0H8.38843L21 19.0075L8.38843 38H0L12.655 19.0075L0 0Z" fill="black"/></svg></button>',
				autoplay: true,
				autoplaySpeed: 6000,
				pauseOnHover: true,
				adaptiveHeight: true,
				speed: 600,
			});
		}
	};


	var practiceAreaCardExpand = function(){
		$('.practice-areas-grid').on('click', '.card-more', function(){
			var btn = $(this);
			var card = btn.closest('.practice-area-card');
			var expanded = card.toggleClass('is-expanded').hasClass('is-expanded');
			btn.attr('aria-expanded', expanded ? 'true' : 'false');
		});
	};

	var practiceAreaFAQ = function(){
		$('.pa-faq-list').on('click', '.pa-faq-question', function(){
			var btn = $(this);
			var item = btn.closest('.pa-faq-item');
			var open = item.toggleClass('is-open').hasClass('is-open');
			btn.attr('aria-expanded', open ? 'true' : 'false');
		});
	};

	// Practice Areas mega menu — hover intent. The full-width panel drops from the
	// bottom of the (tall) header, so there's a vertical gap between the nav item
	// and the panel. A short close delay lets the pointer cross that gap without
	// the panel disappearing.
	var megaMenuHover = function(){
		var item = document.querySelector('.menu-item-mega');
		if(!item) return;

		var closeTimer;
		var open = function(){ clearTimeout(closeTimer); item.classList.add('is-open'); };
		var close = function(){
			clearTimeout(closeTimer);
			closeTimer = setTimeout(function(){ item.classList.remove('is-open'); }, 280);
		};

		item.addEventListener('mouseenter', open);
		item.addEventListener('mouseleave', close);

		var panel = item.querySelector('.mega-menu');
		if(panel){
			panel.addEventListener('mouseenter', open);
			panel.addEventListener('mouseleave', close);
		}

		// Keyboard: close when focus leaves the item entirely.
		item.addEventListener('focusout', function(e){
			if(!item.contains(e.relatedTarget)) close();
		});
	};

	$(document).ready(function(){
		homeHeroSlider();
		heroVideo();
		scrollAnim();
		scrollToAnchor();
		blockGallery();
		faqs();
		reviewsSlider();
		practiceAreaCardExpand();
		practiceAreaFAQ();
		megaMenuHover();
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