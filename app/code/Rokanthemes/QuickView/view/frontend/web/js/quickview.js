define([
    'jquery',
    'mage/translate',
    'Magento_Ui/js/modal/modal',
    'mage/loader',
    'Magento_Customer/js/customer-data'
], function ($, $t, modal, loader, customerData) {
    'use strict';

    $.widget('rokanthemes.quickview', {
        options: {
            quickviewUrl: '',
            buttonText: '',
            isEnabled: false,
            actionInsert: 'append',
            classInsertPosition: '.product-image-wrapper',
            productItemInfo: '.product-item-info'
        },
        _create: function () {
            this.renderQuickViewIcon();
            this._EventListener();
        },
        renderQuickViewIcon: function () {
            var self = this,
                classInsertPosition = self.options.classInsertPosition,
                productItemInfo = self.options.productItemInfo;
            if(self.options.isEnabled == 1 && $('.page-main #product_addtocart_form').length == 0){
                $(classInsertPosition).each(function(){
                    var id_product = false;
                    if ($(this).closest(productItemInfo).find('.actions-primary input[name="product"]').val() !='') {
                        id_product = $(this).closest(productItemInfo).find('.actions-primary input[name="product"]').val();
                    }
                    if (!id_product) {
                        id_product = $(this).closest(productItemInfo).find('.price-box').data('product-id');
                    }
                    var html = '<div id="quickview-'+ id_product +'" class="quickview button_quickview"><a class="action link-quickview" data-product-id="' + id_product + '" data-quickview-url="'+self.options.quickviewUrl+'id/'+ id_product +'/quickview/1" href="javascript:void(0);" ><span>'+self.options.buttonText+'</span></a></div>';
                    if(id_product && self.options.actionInsert == 'append')
                    {
                        $(this).append(html);
                    }else if(id_product && self.options.actionInsert == 'after'){
                        $(this).after(html);
                    }else
                    {
                        $(this).before(html);
                    }
                })
            }
        },
        _EventListener: function () {
            var self = this;
            if(self.options.isEnabled == 1){
                $('body, #layer-product-list').on('contentUpdated', function () {
                    $('.button_quickview').remove();
                    self.renderQuickViewIcon();
                });

                $(document).on('click','.link-quickview', function() {
                    $(this).addClass('loading');
                    var prodUrl = $(this).attr('data-quickview-url');
                    var prodId = $(this).attr('data-product-id');
                    if (prodUrl.length) {
                        $(this).html('Loading');
                        self.openPopup($(this), prodId, prodUrl);
                    }
                });
                $(document).on('click','.modal-popup.quickview .tocart', function() {
                    $('.quickview-popup-wrapper').addClass('clicked');
                });
                $(document).on('click','#bundle-slide', function() {
                    var scrollPos = $('.modal-popup.quickview .bundle-options-container').position().top;
                    $('.modal-popup.quickview').animate({ // animate your right div
                        scrollTop: scrollPos // to the position of the target
                    }, 400);
                });
                $(document).on('click','.modal-popup.quickview .towishlist span', function() {
                    $('.quickview-popup-wrapper').addClass('clicked');
                });
                $(document).on('ajaxComplete', function (event, xhr, settings) {
                    if ($('.modal-popup.quickview.clicked .action-close').length > 0
                    ) {
                        $('.modal-popup.quickview .action-close').trigger('click');
                    }else if ((settings.url.match(/compare\/add/i) || settings.url.match(/wishlist\/add/i)  || settings.url.match(/cart\/add/i))
                        && _.isObject(xhr.responseJSON)
                    ) {
                        $('.modal-popup.quickview .action-close').trigger('click');
                    }
                });
            }
        },
        createIframe: function(product_id, product_url){
            return  $('<iframe />', {
                id: 'iFramequickview' + product_id,
                src: product_url + "?iframe=1"
            });
        },
        getWrapperIframe: function(product_id)
        {
            var wrapper = $("#quickview-popup-content");
            if($('#quickViewContainer' + product_id).length < 1)
            {
                wrapper.html($('<div />', {
                    id: 'quickViewContainer' + product_id,
                    class: 'quickviewContainer'
                }));
            }
            return $('#quickViewContainer' + product_id);
        },
		show: function (e, ctx) { 
            $('.link-quickview').addClass('loading');
            return false;
        },
		hide: function () {
            $('.link-quickview').removeClass('loading');
            return false;
        },
        openMagnificPopupp: function (element, product_id, product_url){
            $('.ajax-popup-link').magnificPopup({
                type: 'ajax',
                callbacks: {
                    ajaxContentAdded: function() {
                        $('body').trigger('contentUpdated');
                    }
                }
            });
        },
        openPopup: function (element, product_id, product_url) {
			$('.modal-popup.quickview').removeClass('clicked');
            var self = this;
            var modalContainer = this.getWrapperIframe(product_id);
            var options = {
                type: 'popup',
                responsive: true,
                innerScroll: false,
				modalClass: 'quickview quickview-popup-wrapper',
                title: $t('Quick View'),
                buttons: [{
                    text: $t('Close'),
                    class: 'close-modal',
                    click: function () {
                        this.closeModal();
                    }
                }]
            };

            var popup = modal(options, modalContainer);

            $('body').trigger('processStart');
            $.ajax({
                url: product_url,
                data: {},
                type: 'post',
                showLoader: false,
                dataType: 'json',
                success: function (res) {
                    $('.link-quickview').removeClass('loading');
                    if (res.content) {
                        modalContainer.html(res.content);
                        modalContainer.modal('openModal');
                        $('body').trigger('contentUpdated');
                    }
                },
                error: function (res) {
                    $('.link-quickview').removeClass('loading');
                    alert('Error in sending ajax request');
                }
            });
            $('body').trigger('processStop');
        }
    });

    return $.rokanthemes.quickview;
});
