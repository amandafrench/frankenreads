jQuery(document).ready(function(){function a(){var a=jQuery("html").getNiceScroll();a.length?(a.resize(),jQuery("html").addClass("no-overflow-y")):(jQuery("html").niceScroll({background:"#555",scrollspeed:60,mousescrollstep:40,cursorwidth:9,cursorborder:"0px",cursorcolor:"#303030",cursorborderradius:8,preservenativescrolling:!0,cursoropacitymax:1,cursoropacitymin:1,autohidemode:!1,zindex:999999,horizrailenabled:!1}),jQuery("html").removeClass("no-overflow-y"))}var b;b=avadaNiceScrollVars.smooth_scrolling,setTimeout(function(){1!==b&&"1"!==b&&!0!==b||Modernizr.mq("screen and (max-width: "+(800+parseInt(avadaNiceScrollVars.side_header_width,10))+"px)")||!(jQuery("body").outerHeight(!0)>jQuery(window).height())||navigator.userAgent.match(/(Android|iPod|iPhone|iPad|IEMobile|Opera Mini)/)?(jQuery("html").removeClass("no-overflow-y"),jQuery("body").outerHeight(!0)<jQuery(window).height()&&jQuery("html").css("overflow-y","hidden")):a()},50),jQuery(window).resize(function(){var b=avadaNiceScrollVars.smooth_scrolling;1!==b&&"1"!==b&&!0!==b||Modernizr.mq("screen and (max-width: "+(800+parseInt(avadaNiceScrollVars.side_header_width,10))+"px)")||!(jQuery("body").outerHeight(!0)>jQuery(window).height())||navigator.userAgent.match(/(Android|iPod|iPhone|iPad|IEMobile|Opera Mini)/)?(jQuery("html").getNiceScroll().remove(),jQuery("html").removeClass("no-overflow-y"),jQuery("body").outerHeight(!0)<jQuery(window).height()?jQuery("html").css("overflow-y","hidden"):jQuery("html").css("overflow-y","auto"),jQuery("#ascrail2000").css("opacity","1")):(a(),setTimeout(function(){a()},750))}),jQuery(".fusion-portfolio .fusion-filters a").click(function(){setTimeout(function(){a()},50)})});