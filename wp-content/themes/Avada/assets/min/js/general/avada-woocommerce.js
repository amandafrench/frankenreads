function fusionResizeCrossfadeImages(a){var b=a.height();a.find("img").each(function(){var a=jQuery(this).height();a<b&&jQuery(this).css("margin-top",(b-a)/2+"px")})}function fusionResizeCrossfadeImagesContainer(a){var b=0;a.find("img").each(function(){var a=jQuery(this).height();a>b&&(b=a)}),a.css("height",b)}function fusionCalcWoocommerceTabsLayout(a){jQuery(a).each(function(){var a=jQuery(this).parent().width(),b=jQuery(this).find("li").length,c=a%b,d=(a-c)/b,e=a-d*(b-1);jQuery(this).css("width",a+"px"),jQuery(this).find("li").css("width",d+"px"),jQuery(this).find("li:last").css("width",e+"px").addClass("no-border-right")})}function getVariationsValues(a){var b=0,c=0;return jQuery(a).find(".variations").find("select").each(function(){0<(jQuery(this).val()||"").length&&c++,b++}),{variations:b,chosen:c}}function variationsChange(a,b,c){var d,e,f=window.sources,g=getVariationsValues(c);d=void 0!==b&&b.image&&b.image.gallery_thumbnail_src&&1<b.image.gallery_thumbnail_src.length?b.image.gallery_thumbnail_src:f[0],g.variations!==g.chosen&&(jQuery(c).trigger("update_variation_values"),jQuery(c).trigger("reset_data"),d=f[0]),void 0!==b&&b.image&&b.image.src&&1<b.image.src.length&&(e=0<a.filter('[data-o_src="'+b.image.gallery_thumbnail_src+'"]').length,e&&variationsImageReset(),-1<jQuery.inArray(b.image.gallery_thumbnail_src,f)||a.each(function(){var a,c,e,h,i;jQuery(this).hasClass("fusion-main-image-thumb")?(a=jQuery(".flex-viewport").find(".fusion-main-image"),c=a.find(".wp-post-image"),e=a.find("a").eq(0),h=a.find("> img"),i=a.find(".avada-product-gallery-lightbox-trigger"),jQuery(this).attr("src",d),jQuery(this).parent().trigger("click"),g.variations===g.chosen?(c.wc_set_variation_attr("src",b.image.src),c.wc_set_variation_attr("height",b.image.src_h),c.wc_set_variation_attr("width",b.image.src_w),c.wc_set_variation_attr("srcset",b.image.srcset),c.wc_set_variation_attr("sizes",b.image.sizes),c.wc_set_variation_attr("title",b.image.title),c.wc_set_variation_attr("alt",b.image.alt),c.wc_set_variation_attr("data-src",b.image.full_src),c.wc_set_variation_attr("data-large_image",b.image.full_src),c.wc_set_variation_attr("data-large_image_width",b.image.full_src_w),c.wc_set_variation_attr("data-large_image_height",b.image.full_src_h),e.wc_set_variation_attr("href",b.image.full_src),h.wc_set_variation_attr("src",b.image.full_src),i.wc_set_variation_attr("href",b.image.src)):variationsImageReset()):jQuery(this).attr("src",f[jQuery(this).data("index")])})),window.avadaLightBox.refresh_lightbox(),setTimeout(function(){window.avadaLightBox.refresh_lightbox()},500),setTimeout(function(){window.avadaLightBox.refresh_lightbox()},1500)}function variationsImageReset(){var a=jQuery(".flex-viewport").find(".fusion-main-image"),b=a.find(".wp-post-image"),c=a.find("a").eq(0),d=a.find("> img"),e=a.find(".avada-product-gallery-lightbox-trigger");b.wc_reset_variation_attr("src"),b.wc_reset_variation_attr("width"),b.wc_reset_variation_attr("height"),b.wc_reset_variation_attr("srcset"),b.wc_reset_variation_attr("sizes"),b.wc_reset_variation_attr("title"),b.wc_reset_variation_attr("alt"),b.wc_reset_variation_attr("data-src"),b.wc_reset_variation_attr("data-large_image"),b.wc_reset_variation_attr("data-large_image_width"),b.wc_reset_variation_attr("data-large_image_height"),c.wc_reset_variation_attr("href"),d.wc_reset_variation_attr("src"),e.wc_reset_variation_attr("href")}jQuery(window).load(function(){jQuery(".woocommerce-store-notice__dismiss-link").click(function(){var a=jQuery("#wpadminbar").length?jQuery("#wpadminbar").height():0;jQuery("#wrapper").css("margin-top",""),jQuery(".fusion-header").css("top",a)}),jQuery(".variations_form").find(".variations .single_variation_wrap .woocommerce-variation-description").remove(),jQuery(window).resize(function(){jQuery(".crossfade-images").each(function(){fusionResizeCrossfadeImagesContainer(jQuery(this)),fusionResizeCrossfadeImages(jQuery(this))})}),"function"==typeof jQuery.fn.equalHeights&&jQuery(".double-sidebars.woocommerce .social-share > li").equalHeights(),jQuery(".crossfade-images").each(function(){fusionResizeCrossfadeImagesContainer(jQuery(this)),fusionResizeCrossfadeImages(jQuery(this))}),jQuery(".product-images").each(function(){!jQuery(this).find("img").length&&jQuery(this).find(".onsale").length&&jQuery(this).css("min-height","45px")}),jQuery(".woocommerce .images #carousel a").click(function(a){a.preventDefault()}),jQuery(".adsw-attribute-option").length&&jQuery(".variations_form").on("show_variation",function(){jQuery(".product-type-variable .variations_form > .single_variation_wrap .woocommerce-variation-price").css("display","inline-block"),jQuery(".product-type-variable .variations_form > .single_variation_wrap .woocommerce-variation-price .price").css("margin-top","0"),jQuery(".product-type-variable .variations_form > .single_variation_wrap .woocommerce-variation-availability").css("display","inline-block")}),jQuery(".variations_form").on("change",".variations select",function(){setTimeout(function(){var a,b=jQuery(".images").find("#slider img:eq(0)"),c=b.parent(),d=b.attr("src"),e=jQuery(".images").find("#carousel img:eq(0)");c&&c.attr("href")&&(d=c.attr("href")),b.parent().attr("href",d),b.removeAttr("sizes"),b.removeAttr("srcset"),window.avadaLightBox.refresh_lightbox(),e.attr("src",d),e.removeAttr("sizes"),e.removeAttr("srcset"),a=jQuery(".images #slider").data("flexslider"),a&&a.resize(),(a=jQuery(".images #carousel").data("flexslider"))&&a.resize()},1),setTimeout(function(){var a;window.avadaLightBox.refresh_lightbox(),(a=jQuery(".images #slider").data("flexslider"))&&a.resize()},500),setTimeout(function(){window.avadaLightBox.refresh_lightbox()},1500)})}),jQuery(document).ready(function(){var a,b,c,d,e;for(jQuery(".fusion-update-cart").on("click",function(a){a.preventDefault(),jQuery(".cart .actions > .button").trigger("click")}),jQuery(".fusion-apply-coupon").on("click",function(a){a.preventDefault(),jQuery(".cart .actions .coupon #coupon_code").val(jQuery("#avada_coupon_code").val()),jQuery(".cart .actions .coupon .button").trigger("click")}),jQuery("body").on("click",".add_to_cart_button.ajax_add_to_cart",function(){var a=jQuery(this);a.closest(".product, li").find(".cart-loading").find("i").removeClass("fusion-icon-check-square-o").addClass("fusion-icon-spinner"),a.closest(".product, li").find(".cart-loading").fadeIn(),setTimeout(function(){a.closest(".product, li").find(".cart-loading").find("i").hide().removeClass("fusion-icon-spinner").addClass("fusion-icon-check-square-o").fadeIn(),jQuery(a).parents(".fusion-clean-product-image-wrapper, li").addClass("fusion-item-in-cart")},2e3)}),jQuery("li").mouseenter(function(){jQuery(this).find(".cart-loading").find("i").hasClass("fusion-icon-check-square-o")&&jQuery(this).find(".cart-loading").fadeIn()}).mouseleave(function(){jQuery(this).find(".cart-loading").find("i").hasClass("fusion-icon-check-square-o")&&jQuery(this).find(".cart-loading").fadeOut()}),jQuery(".woocommerce-store-notice").length&&jQuery(".woocommerce-store-notice").is(":visible")&&!jQuery(".fusion-top-frame").length&&(jQuery("#wrapper").css("margin-top",jQuery(".woocommerce-store-notice").outerHeight()),0<jQuery("#slidingbar-area").outerHeight()&&jQuery(".header-wrapper").css("margin-top","0"),jQuery(".sticky-header").length&&jQuery(".sticky-header").css("margin-top",jQuery(".woocommerce-store-notice").outerHeight())),jQuery(".catalog-ordering .orderby .current-li a").html(jQuery(".catalog-ordering .orderby ul li.current a").html()),jQuery(".catalog-ordering .sort-count .current-li a").html(jQuery(".catalog-ordering .sort-count ul li.current a").html()),jQuery(".woocommerce .avada-myaccount-data th.woocommerce-orders-table__cell-order-actions").text(avadaWooCommerceVars.order_actions),jQuery("body.rtl .avada-myaccount-data .my_account_orders .woocommerce-orders-table__cell-order-status").each(function(){jQuery(this).css("text-align","right")}),jQuery(".woocommerce input").each(function(){jQuery(this).has("#coupon_code")||(a=jQuery(this).attr("id"),jQuery(this).attr("placeholder",jQuery(this).parent().find("label[for="+a+"]").text()))}),jQuery(".woocommerce #reviews #comments .comment_container .comment-text").length&&jQuery(".woocommerce #reviews #comments .comment_container").append('<div class="clear"></div>'),b=avadaWooCommerceVars.title_style_type.split(" "),c="","",d="title-heading-left",e=0;e<b.length;e++)c+=" sep-"+b[e];c.indexOf("underline"),jQuery("body").hasClass("rtl")&&(d="title-heading-right"),jQuery(".woocommerce.single-product .related.products > h2").each(function(){jQuery(this).replaceWith(function(){return'<div class="fusion-title title'+c+'"><h'+avadaWooCommerceVars.related_products_heading_size+' class="'+d+'">'+jQuery(this).html()+"</h"+avadaWooCommerceVars.related_products_heading_size+'><div class="title-sep-container"><div class="title-sep'+c+' "></div></div></div>'})}),jQuery(".woocommerce.single-product .upsells.products > h2").each(function(){jQuery(this).replaceWith(function(){return'<div class="fusion-title title'+c+'"><h3 class="'+d+'">'+jQuery(this).html()+'</h3><div class="title-sep-container"><div class="title-sep'+c+' "></div></div></div>'})}),jQuery(".woocommerce-tabs #comments > h2").each(function(){jQuery(this).replaceWith(function(){return"<h3>"+jQuery(this).html()+"</h3>"})}),"block"===jQuery("body .sidebar").css("display")&&fusionCalcWoocommerceTabsLayout(".woocommerce-tabs .tabs-horizontal"),jQuery(".sidebar .products,.fusion-footer-widget-area .products,#slidingbar-area .products").each(function(){jQuery(this).removeClass("products-4"),jQuery(this).removeClass("products-3"),jQuery(this).removeClass("products-2"),jQuery(this).addClass("products-1")}),jQuery(".products-6 li, .products-5 li, .products-4 li, .products-3 li, .products-3 li").removeClass("last"),jQuery(".subcategory-products").each(function(){jQuery(this).addClass("products-"+avadaWooCommerceVars.woocommerce_shop_page_columns)}),jQuery(".woocommerce-tabs ul.tabs li a").unbind("click"),jQuery("body").on("click",".woocommerce-tabs > ul.tabs li a",function(){var a=jQuery(this),b=a.closest(".woocommerce-tabs");return jQuery("ul.tabs li",b).removeClass("active"),jQuery("> div.panel",b).hide(),jQuery("div"+a.attr("href"),b).show(),a.parent().addClass("active"),!1}),jQuery(".continue-checkout").length||jQuery(document).on("checkout_error",function(){var a=jQuery("#wpadminbar").length?jQuery("#wpadminbar").height():0,b=jQuery(".fusion-header-wrapper").find("div"),c=0;jQuery("html, body").stop(),b.each(function(){"fixed"===jQuery(this).css("position")&&(c=jQuery(this).height())}),jQuery(".woocommerce-error").length&&jQuery("html, body").animate({scrollTop:jQuery(".woocommerce-error").offset().top-a-c},500)}),jQuery(".woocommerce-checkout-nav a,.continue-checkout").on("click",function(a){var b,c,d,e=jQuery("#wpadminbar").length?jQuery("#wpadminbar").height():0,f=jQuery(".fusion-header-wrapper").find("div"),g=0;f.each(function(){"fixed"===jQuery(this).css("position")&&(g=jQuery(this).height())}),a.preventDefault(),jQuery(".avada-checkout-error").parent().remove(),0<jQuery(".validate-required:visible").length&&jQuery.each(jQuery(".validate-required:visible"),function(a,b){var c=jQuery(b).find(":input");"hidden"===c.attr("type")||"radio"===c.attr("type")||"password"===c.attr("type")?jQuery(b).addClass("woocommerce-validated"):c.trigger("change")}),jQuery(".woocommerce .woocommerce-billing-fields, .woocommerce .woocommerce-shipping-fields").find(".input-text, select, input:checkbox").parent(".validate-required:not(.woocommerce-validated)").is(":visible")?(jQuery(".woocommerce .avada-checkout .woocommerce-checkout").prepend('<ul class="woocommerce-error"><li class="avada-checkout-error">'+avadaWooCommerceVars.woocommerce_checkout_error+"</li><ul>"),jQuery("html, body").animate({scrollTop:jQuery(".woocommerce-error").offset().top-e-g},500)):(b=jQuery(this).attr("data-name"),c=b,c="order_review"===b?"#"+b:"."+b,jQuery("form.checkout .col-1, form.checkout .col-2, form.checkout #order_review_heading, form.checkout #order_review").hide(),jQuery("form.checkout").find(c).fadeIn(),"order_review"===c&&jQuery("form.checkout").find("#order_review_heading ").fadeIn(),jQuery(".woocommerce-checkout-nav li").removeClass("is-active"),jQuery(".woocommerce-checkout-nav").find("[data-name="+b+"]").parent().addClass("is-active"),jQuery(this).hasClass("continue-checkout")&&0<jQuery(window).scrollTop()&&(d=jQuery(".woo-tabs-horizontal").length?jQuery(".woocommerce-checkout-nav"):jQuery(".woocommerce-content-box.avada-checkout"),jQuery("html, body").animate({scrollTop:d.offset().top-e-g},500))),calcSelectArrowDimensions()}),jQuery("body").on("click","input[name=ship_to_different_address]",function(){jQuery(this).is(":checked")&&setTimeout(function(){calcSelectArrowDimensions()},1)}),Modernizr.mq("only screen and (max-width: 479px)")&&jQuery(".overlay-full.layout-text-left .slide-excerpt p").each(function(){var a,b=jQuery(this).html(),c=b.split(/[\s\.\?]+/),d=c.length,e="";if(d>11){for(a=0;a<10;a++)e+=c[a]+" ";jQuery(this).html(e)}})}),jQuery(window).load(function(){var a,b;jQuery(".avada-product-gallery").length&&(a=jQuery(".flex-control-nav").find("img").length?jQuery(".flex-control-nav").find("img"):jQuery('<img class="fusion-main-image-thumb">').attr("src",jQuery(".flex-viewport").find(".flex-active-slide").data("thumb")),jQuery(".flex-viewport").find(".flex-active-slide").addClass("fusion-main-image"),jQuery(".flex-control-nav").find("li:eq(0) img").addClass("fusion-main-image-thumb"),setTimeout(function(){jQuery(".variations select").trigger("change")},100),jQuery(".variations_form").on("found_variation",function(a,c){b=c}),jQuery(".variations_form").on("change",".variations select",function(){variationsChange(a,b,jQuery(this).parents(".variations_form"))})),jQuery(".avada-single-product-gallery-wrapper").find(".flex-control-thumbs").length&&jQuery(".avada-single-product-gallery-wrapper").css("margin-bottom",jQuery(".avada-single-product-gallery-wrapper").find(".flex-control-thumbs").height()+10),jQuery(".avada-product-gallery").each(function(){var a=jQuery(this).find(".flex-control-thumbs"),b=Math.max.apply(null,a.find("li").map(function(){return jQuery(this).height()}).get());jQuery(".woocommerce-product-gallery__image").css("min-height",""),jQuery(document).trigger("resize"),a.animate({opacity:1},500),a.wrap('<div class="avada-product-gallery-thumbs-wrapper"></div>'),a.parent().css("height",b)})}),jQuery(document).ready(function(){function a(a){var b,c=a.width(),d=a.find(".flex-control-thumbs li"),e=a.data("columns"),f=d.size();b=(c-8*(e-1))/e,d.css("width",b),a.find(".flex-control-thumbs").css("width",f*b+8*(f-1)+"px")}function b(a,b,c){var d,e,f=a.find(".flex-control-thumbs"),g=f.find("li"),h=a.data("columns"),i=f.find("li").outerWidth(),j=a.offset().left,k=Math.floor((b.offset().left-j)/i),l=[];g.length>h&&("next"===c?(k<g.length-(k+1)?d=-1*b.position().left:(e=g.length-h,d=-1*jQuery(g.get(e)).position().left),f.stop(!0,!0).animate({left:d},{queue:!1,duration:500,easing:"easeInOutQuad",complete:function(){jQuery(this).find("li").each(function(){l.push(jQuery(this).offset().left)}),jQuery(this).find("li").each(function(a){l[a]<j&&jQuery(this).appendTo(f)}),jQuery(this).css("left","0")}})):(d=-1*(i+8),b.parent().prependTo(f),f.css("left",d),f.stop(!0,!0).animate({left:0},{queue:!1,duration:500,easing:"easeInOutQuad"})))}var c;setTimeout(function(){jQuery(".woocommerce-product-gallery__trigger").empty()},10),jQuery(".avada-product-gallery").length&&(jQuery(".woocommerce-product-gallery__image").on("click",".zoomImg",function(){if("ontouchstart"in document.documentElement||navigator.msMaxTouchPoints)return c=jQuery(this).siblings(".avada-product-gallery-lightbox-trigger"),c.hasClass("hover")?(c.removeClass("hover"),c.trigger("click"),!1):(jQuery(".woocommerce-product-gallery__image").find(".avada-product-gallery-lightbox-trigger").removeClass("hover"),c.addClass("hover"),!0);jQuery(this).siblings(".avada-product-gallery-lightbox-trigger").trigger("click")}),jQuery("body").on("click",function(a){jQuery(a.target).hasClass("woocommerce-product-gallery__image")||jQuery(".avada-product-gallery-lightbox-trigger").removeClass("hover")})),jQuery(".avada-product-gallery").each(function(){var c=jQuery(this),d=jQuery(".flex-control-nav").find("img").length?jQuery(".flex-control-nav").find("img"):void 0;window.sources=[],void 0!==d?d.each(function(a){jQuery(this).data("index",a),window.sources.push(jQuery(this).attr("src"))}):window.sources.push(jQuery(this).find(".flex-viewport .flex-active-slide").data("thumb")),jQuery(".flex-viewport").find(".clone").find(".avada-product-gallery-lightbox-trigger").addClass("fusion-no-lightbox").removeAttr("data-rel"),a(c),jQuery(window).resize(function(){a(c)}),c.on("click touchstart",".flex-control-thumbs li",function(){var a=jQuery(this);b(c,a,"next")}),c.find(".flex-direction-nav li a").on("click touchstart",function(){var a=jQuery(this).parents(".avada-product-gallery").find(".flex-control-thumbs img.flex-active");a.offset().left+a.outerWidth()>c.offset().left+c.outerWidth()&&(jQuery(this).hasClass("flex-next")?b(c,a,"next"):b(c,a,"prev"))})})}),jQuery(document).ajaxComplete(function(){jQuery(".fusion-update-cart").unbind("click"),jQuery(".fusion-update-cart").on("click",function(a){a.preventDefault(),jQuery(".cart .actions > .button").trigger("click")}),setTimeout(function(){jQuery(".crossfade-images").each(function(){fusionResizeCrossfadeImagesContainer(jQuery(this)),fusionResizeCrossfadeImages(jQuery(this))})},1e3)});