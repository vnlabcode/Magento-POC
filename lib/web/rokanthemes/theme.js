define([
    'jquery',
    'rokanthemes/owl',
    'rokanthemes/lazyloadimg',
	'rokanthemes/jquery_sameheight',
	'rokanthemes/jquery_sameminheight',
], function ($) {
    'use strict';
    $('body').on('contentUpdated', function (event, id) {
    	$('.products-grid').each(function (){
    		$(this).find('.product-item .product-item-details').sameMinHeight();
		});
		setTimeout(function(){
			$('.products-grid').each(function (){
				$(this).find('.product-item .product-item-details').sameMinHeight();
			});
			
		}, 1000);

		var owl = $(id).find('.owl-carousel');
		if(owl.hasClass('owl-loaded')){
			setTimeout(function(){
				var owl_ini_load = $(id).find('.owl-carousel .item-row');
				owl_ini_load.each(function( index ) {
					$(id).find('.item-row').eq(index).fadeIn(400*(index+1));
					$(id).find('.tab-loadding').remove();
				});
				$(id).css("min-height", '');
			}, 900);
		}
		else{
			var owl_ini_load = $(id).find('.owl-carousel .item-row');
			owl.on('initialized.owl.carousel', function(event) {
				var this_load = $(this)
				owl_ini_load.each(function( index ) {
					this_load.find('.item-row').eq(index).fadeIn(400*(index+1));
				});

				var total_owl_content_update = this_load.find('.owl-item.active').length;
		    	this_load.find('.owl-item').removeClass('first-active-item');
		    	this_load.find('.owl-item').removeClass('last-active-item');
		    	this_load.find('.owl-item.active').each(function(index) {
					if (index === 0) {
						$(this).addClass('first-active-item');
					}
					if (index === total_owl_content_update - 1 && total_owl_content_update > 1) {
						$(this).addClass('last-active-item');
					}
				});
			});
			owl.each(function(index, el) {
				
				var $_this = $(this);
				var $center = $_this.data('center');
				var $mousedrag = $_this.data('mousedrag');
				var $stagepadding = $_this.data('stagepadding');
				var $item = $_this.data('items');
				var $touchdrag = $_this.data('touchdrag');
				var $rtl = $_this.data('rtl');
				var $dots = $_this.data('dots');
				var $rewind = $_this.data('rewind');
				var $autoplayhoverpause = $_this.data('autoplayhoverpause');
				var $nav = $_this.data('nav');
				var $margin = parseInt($_this.data('margin') ? $_this.data('margin') : 0);
				var $bigdesk_items = $_this.data('bigdesktop')
					? $_this.data('bigdesktop')
					: $item
						? $item
						: 4;
				var $desksmall_items = $_this.data('smalldesktop')
					? $_this.data('smalldesktop')
					: $item
						? $item
						: 3;
				var $bigtablet_items = $_this.data('bigtablet')
					? $_this.data('bigtablet')
					: $item
						? $item
						: 3;
				var $tablet_items = $_this.data('tablet')
					? $_this.data('tablet')
					: $item
						? $item
						: 3;
				var $tabletsmall_items = $_this.data('smalltablet')
					? $_this.data('smalltablet')
					: $item
						? $item
						: 2;
				var $mobile_items = $_this.data('mobile')
					? $_this.data('mobile')
					: $item
						? $item
						: 1;
				var $tablet_margin = Math.floor($margin / 1.5);
				var $mobile_margin = Math.floor($margin / 3);
				var $default_items = $item ? $item : 4;
				var $autoplay = $_this.data('autoplay');
				var $autoplayTimeout = $_this.data('autoplaytimeout')
					? $_this.data('autoplaytimeout')
					: 5000;
				var $smartSpeed = $_this.data('speed') ? $_this.data('speed') : 250;
				var $loop = $_this.data('loop');

				var $next_text = $_this.data('navnext')
					? $_this.data('navnext')
					: 'Next';
				var $prev_text = $_this.data('navprev')
					? $_this.data('navprev')
					: 'Prev';
				$_this.owlCarousel({
					autoplayHoverPause: $autoplayhoverpause,
					center: $center,
					rewind: $rewind,
					touchDrag: $touchdrag,
					mouseDrag: $mousedrag,
					stagePadding: $stagepadding,
					loop: $loop,
					nav: $nav,
					dots: $dots,
					margin: $margin,
					rtl: $rtl,
					items: $default_items,
					autoplay: $autoplay,
					autoplayTimeout: $autoplayTimeout,
					smartSpeed: $smartSpeed,
					lazyLoad: true,
					navText: [$next_text, $prev_text],
					responsive: {
						0: {
							items: $mobile_items,
							margin: $mobile_margin
						},
						576: {
							items: $tabletsmall_items,
							margin: $mobile_margin
						},
						768: {
							items: $tablet_items,
							margin: $tablet_margin
						},
						992: {
							items: $bigtablet_items,
							margin: $tablet_margin
						},
						1024: {
							items: $desksmall_items,
							margin: $margin
						},
						1200: {
							items: $default_items,
							margin: $margin
						},
						1440: {
							items: $bigdesk_items,
							margin: $margin
						},
					}
				});
				if($_this.data('sameheight'))
				{
					var classSameHeight = $_this.data('sameheight');
					setTimeout(function(){
						$(classSameHeight).sameMinHeight();
					}, 1000);
				}
				$_this.on('resized.owl.carousel', function (){
					$_this.find('.product-item-details').sameMinHeight();
				});
				function brandSliderClasses() { 
					$_this.each(function() {
						var total = $(this).find('.owl-item.active').length;
						$(this).find('.owl-item').removeClass('first-active-item');
						$(this).find('.owl-item').removeClass('last-active-item');
						$(this).find('.owl-item.active').each(function(index) {
							if (index === 0) {
								$(this).addClass('first-active-item')
							}
							if (index === total - 1 && total > 1) {
								$(this).addClass('last-active-item')
							}
						})
					})
				}
				$_this.on('translated.owl.carousel', function(event) {
					brandSliderClasses();
				}); 
			});
		}
    });
	
	$('body').on('contentUpdatedOwl', function (event, id) {
		var owl = $(id).find('.owl-carousel');
		if(owl.hasClass('owl-loaded')){
			setTimeout(function(){
				var owl_ini_load = $(id).find('.owl-carousel .item-load');
				owl_ini_load.each(function( index ) {
					$(id).find('.item-load').eq(index).fadeIn(400*(index+1));
				});
			}, 900);
		}
		else{
			var owl_ini_load = $(id).find('.owl-carousel .item-load');
			owl.on('initialized.owl.carousel', function(event) {
				var this_load = $(this)
				owl_ini_load.each(function( index ) {
					this_load.find('.item-load').eq(index).fadeIn(400*(index+1));
				});

				var total_owl_content_update_owl = this_load.find('.owl-item.active').length;
		    	this_load.find('.owl-item').removeClass('first-active-item');
		    	this_load.find('.owl-item').removeClass('last-active-item');
		    	this_load.find('.owl-item.active').each(function(index) {
					if (index === 0) {
						$(this).addClass('first-active-item');
					}
					if (index === total_owl_content_update_owl - 1 && total_owl_content_update_owl > 1) {
						$(this).addClass('last-active-item');
					}
				});

			});
			owl.each(function(index, el) {
				var $_this = $(this);
				var $center = $_this.data('center');
				var $mousedrag = $_this.data('mousedrag');
				var $stagepadding = $_this.data('stagepadding');
				var $item = $_this.data('items');
				var $touchdrag = $_this.data('touchdrag');
				var $rtl = $_this.data('rtl');
				var $dots = $_this.data('dots');
				var $rewind = $_this.data('rewind');
				var $autoplayhoverpause = $_this.data('autoplayhoverpause');
				var $nav = $_this.data('nav');
				var $margin = parseInt($_this.data('margin') ? $_this.data('margin') : 0);
				var $bigdesk_items = $_this.data('bigdesktop')
					? $_this.data('bigdesktop')
					: $item
						? $item
						: 4;
				var $desksmall_items = $_this.data('smalldesktop')
					? $_this.data('smalldesktop')
					: $item
						? $item
						: 3;
				var $bigtablet_items = $_this.data('bigtablet')
					? $_this.data('bigtablet')
					: $item
						? $item
						: 3;
				var $tablet_items = $_this.data('tablet')
					? $_this.data('tablet')
					: $item
						? $item
						: 3;
				var $tabletsmall_items = $_this.data('smalltablet')
					? $_this.data('smalltablet')
					: $item
						? $item
						: 2;
				var $mobile_items = $_this.data('mobile')
					? $_this.data('mobile')
					: $item
						? $item
						: 1;
				var $tablet_margin = Math.floor($margin / 1.5);
				var $mobile_margin = Math.floor($margin / 3);
				var $default_items = $item ? $item : 4;
				var $autoplay = $_this.data('autoplay');
				var $autoplayTimeout = $_this.data('autoplaytimeout')
					? $_this.data('autoplaytimeout')
					: 5000;
				var $smartSpeed = $_this.data('speed') ? $_this.data('speed') : 250;
				var $loop = $_this.data('loop');

				var $next_text = $_this.data('navnext')
					? $_this.data('navnext')
					: 'Next';
				var $prev_text = $_this.data('navprev')
					? $_this.data('navprev')
					: 'Prev';
				$_this.owlCarousel({
					autoplayHoverPause: $autoplayhoverpause,
					center: $center,
					rewind: $rewind,
					touchDrag: $touchdrag,
					mouseDrag: $mousedrag,
					stagePadding: $stagepadding,
					loop: $loop,
					nav: $nav,
					dots: $dots,
					margin: $margin,
					rtl: $rtl,
					items: $default_items,
					autoplay: $autoplay,
					autoplayTimeout: $autoplayTimeout,
					smartSpeed: $smartSpeed,
					lazyLoad: true,
					navText: [$next_text, $prev_text],
					responsive: {
						0: {
							items: $mobile_items,
							margin: $mobile_margin
						},
						576: {
							items: $tabletsmall_items,
							margin: $mobile_margin
						},
						768: {
							items: $tablet_items,
							margin: $tablet_margin
						},
						992: {
							items: $bigtablet_items,
							margin: $tablet_margin
						},
						1024: {
							items: $desksmall_items,
							margin: $margin
						},
						1200: {
							items: $default_items,
							margin: $margin
						},
						1440: {
							items: $bigdesk_items,
							margin: $margin
						},
					}
				});

				function setFirstAndLastItemOwlBrand() {
					var total_owl_document_ready = $_this.find('.owl-item.active').length;
					$_this.find('.owl-item').removeClass('first-active-item');
			    	$_this.find('.owl-item').removeClass('last-active-item');
			    	$_this.find('.owl-item.active').each(function(index) {
						if (index === 0) {
							$(this).addClass('first-active-item');
						}
						if (index === total_owl_document_ready - 1 && total_owl_document_ready > 1) {
							$(this).addClass('last-active-item');
						}
					});
				}

				$_this.on('translated.owl.carousel', function(event) {
					setFirstAndLastItemOwlBrand();
				});
			});
		}
    });

	$(window).resize(function(){
		$('.products-grid').each(function (){
			$(this).find('.product-item .product-item-details').sameMinHeight();
		});
		setTimeout(function(){
			$('.products-grid').each(function (){
				$(this).find('.product-item .product-item-details').sameMinHeight();
			});
		}, 1000);
	});
	$(window).bind('load', function() {
		$('.products-grid').each(function (){
			$(this).find('.product-item .product-item-details').sameMinHeight();
		});
		setTimeout(function(){
			$('.products-grid').each(function (){
				$(this).find('.product-item .product-item-details').sameMinHeight();
			});
		}, 1000);
	});
	$(document).ready(function () {
		var owl_ini = $('.owl-carousel');
		var owl_ini_load = $('.owl-carousel .item-load');

		owl_ini.on('initialized.owl.carousel', function(event) {
			var this_load = $(this)
			owl_ini_load.each(function( index ) {
				this_load.find('.item-load').eq(index).fadeIn(400*(index+1));
			});
			var total_owl_document_ready = this_load.find('.owl-item.active').length;
	    	this_load.find('.owl-item').removeClass('first-active-item');
	    	this_load.find('.owl-item').removeClass('last-active-item');
	    	this_load.find('.owl-item.active').each(function(index) {
				if (index === 0) {
					$(this).addClass('first-active-item');
				}
				if (index === total_owl_document_ready - 1 && total_owl_document_ready > 1) {
					$(this).addClass('last-active-item');
				}
			});
			setTimeout(function(){
				this_load.find("img[data-src]").lazy({
					bind:event,
					attribute: 'data-src',
					visibleOnly: true,
					threshold: 0,
					enableThrottle: true,
					throttle: 500,
					afterLoad: function(element) {
						$(element).addClass("lazy-loaded");
						$(element).closest(".absolute-content-image").removeClass("lazyload-content");
						setTimeout(function(){
							$(element).addClass("transition");
						}, 1000);
					}
				});  
				
			}, 1500); 
		});

		owl_ini.each(function(index, el) {

			var $_this = $(this);
			var $center = $_this.data('center');
			var $mousedrag = $_this.data('mousedrag');
			var $stagepadding = $_this.data('stagepadding');
			var $item = $_this.data('items');
			var $touchdrag = $_this.data('touchdrag');
			var $rtl = $_this.data('rtl');
			var $dots = $_this.data('dots');
			var $rewind = $_this.data('rewind');
			var $autoplayhoverpause = $_this.data('autoplayhoverpause');
			var $nav = $_this.data('nav');
			var $margin = parseInt($_this.data('margin') ? $_this.data('margin') : 0);
			var $bigdesk_items = $_this.data('bigdesktop')
				? $_this.data('bigdesktop')
				: $item
				? $item
				: 4;
			var $desksmall_items = $_this.data('smalldesktop')
				? $_this.data('smalldesktop')
				: $item
				? $item
				: 3;
			var $bigtablet_items = $_this.data('bigtablet')
				? $_this.data('bigtablet')
				: $item
				? $item
				: 3;
			var $tablet_items = $_this.data('tablet')
				? $_this.data('tablet')
				: $item
				? $item
				: 3;
			var $tabletsmall_items = $_this.data('smalltablet')
				? $_this.data('smalltablet')
				: $item
				? $item
				: 2;
			var $mobile_items = $_this.data('mobile')
				? $_this.data('mobile')
				: $item
				? $item
				: 1;
			var $tablet_margin = Math.floor($margin / 1.5);
			var $mobile_margin = Math.floor($margin / 3);
			var $default_items = $item ? $item : 4;
			var $autoplay = $_this.data('autoplay');
			var $autoplayTimeout = $_this.data('autoplaytimeout')
				? $_this.data('autoplaytimeout')
				: 5000;
			var $smartSpeed = $_this.data('speed') ? $_this.data('speed') : 250;
			var $loop = $_this.data('loop');

			var $next_text = $_this.data('navnext')
				? $_this.data('navnext')
				: 'Next';
			var $prev_text = $_this.data('navprev')
				? $_this.data('navprev')
				: 'Prev';
			$_this.owlCarousel({
				autoplayHoverPause: $autoplayhoverpause,
				center: $center,
				rewind: $rewind,
				touchDrag: $touchdrag,
				mouseDrag: $mousedrag,
				stagePadding: $stagepadding,
				loop: $loop,
				nav: $nav,
				dots: $dots,
				margin: $margin,
				rtl: $rtl,
				items: $default_items,
				autoplay: $autoplay,
				autoplayTimeout: $autoplayTimeout,
				smartSpeed: $smartSpeed,
				lazyLoad: true,
				navText: [$next_text, $prev_text],
				responsive: {
					0: {
						items: $mobile_items,
						margin: $mobile_margin
					},
					576: {
						items: $tabletsmall_items,
						margin: $mobile_margin
					},
					768: {
						items: $tablet_items,
						margin: $tablet_margin
					},
					992: {
						items: $bigtablet_items,
						margin: $tablet_margin
					},
					1024: {
						items: $desksmall_items,
						margin: $margin
					},
					1200: {
						items: $default_items,
						margin: $margin
					},
					1440: {
						items: $bigdesk_items,
						margin: $margin
					},
				},
			});
			if($_this.data('sameheight'))
			{
				var classSameHeight = $_this.data('sameheight');
				setTimeout(function(){
					$(classSameHeight).sameMinHeight();
				}, 1000);
			}

			function setFirstAndLastItemOwl() {
				var total_owl_document_ready = $_this.find('.owl-item.active').length;
				$_this.find('.owl-item').removeClass('first-active-item');
		    	$_this.find('.owl-item').removeClass('last-active-item');
		    	$_this.find('.owl-item.active').each(function(index) {
					if (index === 0) {
						$(this).addClass('first-active-item');
					}
					if (index === total_owl_document_ready - 1 && total_owl_document_ready > 1) {
						$(this).addClass('last-active-item');
					}
				});
			}

			$_this.on('resized.owl.carousel', function (){
				$_this.find('.product-item-details').sameMinHeight();
			});

			$_this.on('translated.owl.carousel', function(event) {
				setFirstAndLastItemOwl();
			});
		});
		$('.footer-links h4').click(function () {
			if(!$(this).hasClass('active')){
				$(this).addClass('active');
				$(this).closest('.footer-links').find('.footer-contents').show(300);
			}else{
				$(this).removeClass('active');
				$(this).closest('.footer-links').find('.footer-contents').hide(300); 
			}
			return false;
		});
		$('.brand-product-tab .brand-list-tab-container-title a').click(function () {
			if(!$(this).closest('.item').hasClass('active')){
				var id = $(this).attr('href');
				var height = $( '.brand-list-tab-container-content div.show-blocks').height();
				$('.brand-list-tab-container-content .item.content.show-blocks').addClass('hide-blocks');	
				$('.brand-list-tab-container-content .item.content.show-blocks').removeClass('show-blocks');
				$(id).removeClass('hide-blocks');
				$(id).addClass('show-blocks');

				$('.brand-product-tab').addClass('loadding');
				$('.brand-product-tab .brand-list-tab-container-title').find('.item').removeClass('active');
				$(this).closest('.item').addClass('active');
				$(id).addClass('click-show-block');
				$('.brand-list-tab-container-content').css("min-height", height);
				$('.brand-list-tab-container-content .click-show-block').append("<div class='tab-loadding' style='min-height: "+height+"px;'><div class='loading-icon'><span></span><span></span><span></span><span></span></div>");
				$(id).trigger('contentUpdatedOwl',[id]);
				setTimeout(function(){
					$(id).find('.tab-loadding').remove();
					$(id).removeClass('click-show-block');
					$('.brand-product-tab').removeClass('loadding');
				}, 800);  
			}
			return false
		});
		$('.panel-group .panel h5').click(function () {
			if(!$(this).hasClass('active')){
				$(this).addClass('active');
				$(this).closest('.panel').find('.panel-collapse').show(300);
			}else{
				$(this).removeClass('active');
				$(this).closest('.panel').find('.panel-collapse').hide(300); 
			}
			return false;
		});
		$('.toggle-nav-footer').click(function () {
			$('.nav-toggle').click();
			return false;
		});
		var currentLocation = window.location.href;
		$(".navigation.custommenu ul li").each(function(){
			var for_url = $(this).find('a').attr('href');
			if(currentLocation == for_url || currentLocation+'/' == for_url){
				if($(this).hasClass('level0')){
					$(this).addClass('active'); 
				}else{
					$(this).closest('li.level0').addClass('active');
				}
			}
		});
		$('#back-top').click(function () {
			$('body,html').animate({
				scrollTop: 0
			}, 800);
			return false;
		});
		$.fn.hasId = function(id) {
			return this.attr('id') == id;
		  };
		$('div').each(function(){
			var hasID = $(this).hasId('category-image');
			if(hasID){
				$('.category-view-breadcrumbs-banner').addClass('image-banner');
				$(".header-container").addClass("banner-image")
			}
		});
		$('div').each(function(){
			var hasClass = $(this).hasClass('top-banner-blog-container')
			if(hasClass){
				$(".header-container").addClass("banner-image")
			}
		});
		if($('#purchase-fake-order').length > 0){
			var show_number_seconds = parseInt($('#purchase-fake-order').attr('data-seconds-displayed'));
			var show_number_hide = parseInt($('#purchase-fake-order').attr('data-seconds-hide'));
			var url_fake = $('#purchase-fake-order').attr('data-url');
			var interval_fake_order = setInterval(getProductRandom, show_number_seconds*1000);
			$(document).on('click', '#purchase-fake-order .purchase-close', function(){
				clearInterval(interval_fake_order);
				$('#purchase-fake-order').removeClass('fadeInUp');
				$('#purchase-fake-order').addClass('fadeOutDown');
			});
			function getProductRandom(){
				if(!$('#purchase-fake-order').hasClass('fadeInUp')){
					$.getJSON(url_fake, function( data ) {
						$('#purchase-fake-order .product-purchase').html(data.html);
						$('#purchase-fake-order').removeClass('fadeOutDown');
						$('#purchase-fake-order').addClass('fadeInUp');
						$('#purchase-fake-order').removeAttr("style");
						setTimeout(function(){
							$('#purchase-fake-order').removeClass('fadeInUp');
							$('#purchase-fake-order').addClass('fadeOutDown'); 
						}, show_number_hide*1000);
					});
				}
				else{
					$('#purchase-fake-order').removeClass('fadeInUp');
					$('#purchase-fake-order').addClass('fadeOutDown'); 
				}
			}
		}

		var scrolled_sticky = false;
		var scrolled_back = false;
		
		$(window).scroll(function () {
			if ($(this).scrollTop() > 400 && !scrolled_back) {
				$('#back-top').addClass('show');
				scrolled_back = true;
			}
			if ($(this).scrollTop() <= 400 && scrolled_back) {
				$('#back-top').removeClass('show');
				scrolled_back = false;
			}
			var num = $('.page-header').outerHeight();
			var screenWidth = $(window).width();
			
			if ($(this).scrollTop() > num && screenWidth >= 768 ){  
				$(".header-container").addClass("sticky");
			}
			else{
				$(".header-container").removeClass("sticky");
			}
		});
	});
	var background = $('.contact-index-index .page-title-wrapper').css('background-image');
	if(background){
		$('.contact-index-index .page-title-wrapper').addClass('background-contact');
	}
});