/*
 * PopupBox (for jQuery)
 * version: 1.2 (05/05/2008)
 * @requires jQuery v1.2 or later
 *
 * Examples at http://famspam.com/popupbox/
 *
 * Licensed under the MIT:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Copyright 2007, 2008 Chris Wanstrath [ chris@ozmm.org ]
 *
 * Usage:
 *  
 *  jQuery(document).ready(function() {
 *    jQuery('a[rel*=popupbox]').popupbox() 
 *  })
 *
 *  <a href="#terms" rel="popupbox">Terms</a>
 *    Loads the #terms div in the box
 *
 *  <a href="terms.html" rel="popupbox">Terms</a>
 *    Loads the terms.html page in the box
 *
 *  <a href="terms.png" rel="popupbox">Terms</a>
 *    Loads the terms.png image in the box
 *
 *
 *  You can also use it programmatically:
 * 
 *    jQuery.popupbox('some html')
 *
 *  The above will open a popupbox with "some html" as the content.
 *    
 *    jQuery.popupbox(function($) { 
 *      $.get('blah.html', function(data) { $.popupbox(data) })
 *    })
 *
 *  The above will show a loading screen before the passed function is called,
 *  allowing for a better ajaxy experience.
 *
 *  The popupbox function can also display an ajax page or image:
 *  
 *    jQuery.popupbox({ ajax: 'remote.html' })
 *    jQuery.popupbox({ image: 'dude.jpg' })
 *
 *  Want to close the popupbox?  Trigger the 'close.popupbox' document event:
 *
 *    jQuery(document).trigger('close.popupbox')
 *
 *  PopupBox also has a bunch of other hooks:
 *
 *    loading.popupbox
 *    beforeReveal.popupbox
 *    reveal.popupbox (aliased as 'afterReveal.popupbox')
 *    init.popupbox
 *
 *  Simply bind a function to any of these hooks:
 *
 *   $(document).bind('reveal.popupbox', function() { ...stuff to do after the popupbox and contents are revealed... })
 *
 */
(function($) {
	
		var hideDelay = 200;     
  		var hideTimer = null
		
  $.popupbox = function(data, rev, klass) {
    $.popupbox.loading()
	//alert(data+"-"+rev)
    if (data.ajax) fillPopupBoxFromAjax(data.ajax,data.title)
	//if (data.ajaxing) fillPopupBoxFromAjaxing(data.ajaxing,data.title)
	if (data.iframe) fillPopupBoxFromIframe(data.iframe,data.title,rev,klass)
    else if (data.image) fillPopupBoxFromImage(data.image)
    else if (data.div) fillPopupBoxFromHref(data.div)
    else if ($.isFunction(data)) data.call($)
    else $.popupbox.reveal(data, klass)
  }

  /*
   * Public, $.popupbox methods
   */

  $.extend($.popupbox, {
    settings: {
      opacity      : 0,
      overlay      : true,
      loadingImage : 'jquery/componentes/popupbox/loading.gif',
      closeImage   : 'jquery/componentes/popupbox/closelabel.gif',
      imageTypes   : [ 'png', 'jpg', 'jpeg', 'gif' ],
      popupboxHtml  : '\
    <div id="popupbox" style="display:none;"> \
      <div class="popup"> \
        <table> \
          <tbody> \
            <tr> \
              <td class="tl"/><td class="b"/><td class="tr"/> \
            </tr> \
            <tr> \
              <td class="b"/> \
              <td class="body"> \
			  	<div class="header"> \
					<div class="title_text"></div> \
                </div> \
                <div  id="popupboxcontent" class="content"> \
                </div> \
                <div class="footer"> \
					 <div style="display:none" class="img_close"> \
					  <a href="#" class="close btn btn-custom2"> \
						Cerrar/tecla ESC \
					  </a> \
					  </div> \
                </div> \
              </td> \
              <td class="b"/> \
            </tr> \
            <tr> \
              <td class="bl"/><td class="b"/><td class="br"/> \
            </tr> \
          </tbody> \
        </table> \
      </div> \
    </div>'
    },

    loading: function() {
      init()
      if ($('#popupbox .loading').length == 1) return true
      showOverlay()
      $('#popupbox .content').empty()
      $('#popupbox .body').children().hide().end().
        append('<div class="loading"><img src="'+$.popupbox.settings.loadingImage+'"/></div>')

      $('#popupbox').css({
        top:	getPageScroll()[1] + (getPageHeight() / 10),
        left:	385.5
      }).show()

      $(document).bind('keydown.popupbox', function(e) {
        if (e.keyCode == 27) $.popupbox.close()
        return true
      })

      $(document).trigger('loading.popupbox')
    },

    reveal: function(data, klass) {
		//alert(data)
      $(document).trigger('beforeReveal.popupbox')
      if (klass) $('#popupbox .content').addClass(klass)
	  $('#popupbox .title_text').html(klass) //Codigo Adaptado alas Necesidades
      //$('#popupbox .content').append(data)
	  $('#popupboxcontent').html(data)
      $('#popupbox .loading').remove()
      $('#popupbox .body').children().fadeIn('normal')
	 // $('#popupbox .body').children().css('display', 'inline')
      $('#popupbox').css('left', $(window).width() / 2 - ($('#popupbox table').width() / 2))
      $(document).trigger('reveal.popupbox').trigger('afterReveal.popupbox')
    },
	
	revealIframe: function(data, klass) {
		//alert(data)
      $(document).trigger('beforeReveal.popupbox')
      if (klass) $('#popupbox .content').addClass(klass)
	  $('#popupbox .title_text').html(klass) //Codigo Adaptado alas Necesidades
      //$('#popupbox .content').append(data)
	  $('#popupboxcontent').html(data)
      $('#popupbox .loading').remove()
      $('#popupbox .body').children().fadeIn('normal')
	 // $('#popupbox .body').children().css('display', 'inline')
      $('#popupbox').css('left', $(window).width() / 2 - ($('#popupbox table').width() / 2))
      $(document).trigger('reveal.popupbox').trigger('afterReveal.popupbox');
	  $("#popupbox").draggable(); 
    },
	
	
	onLeftPopupbox: function() {
		
		//$('#popupbox').css('left', ($(window).width() / 2 - ($('#popupbox table').width() / 2))/2);
		if($('#popupbox').width() < $(window).width()) {
     	$('#popupbox').css('left', ($(window).width() / 2 - ($('#popupbox table').width() / 2)));
	 } else {
		 $('#popupbox').css('left', ($(window).width() / 2 - ($('#popupbox table').width() / 2))/2);	 
	 }
		
		return false;
	},
	
	 onResizePopupbox: function() {
		 
		 
		  
		  
		var PopupBoxFrame = jQuery("#popupboxcontent").find('iframe'); 
		PopupBoxFrame.removeAttr("height"); 
	   	PopupBoxFrame.removeAttr("width"); 
	 
	 var IHeight,IWidth,PHeight,PWidth;
	 			if (navigator.appName.indexOf("Explorer") != -1){
			  		var innerDoc = (PopupBoxFrame.get(0).contentDocument) ? PopupBoxFrame.get(0).contentDocument : PopupBoxFrame.get(0).contentWindow.document;
					IHeight = (innerDoc.body.scrollHeight) + 15;
					IWidth = (innerDoc.body.scrollWidth) + 30;
					
					PHeight = (innerDoc.body.scrollHeight) + 15;
					PWidth = (innerDoc.body.scrollWidth) + 30;
					
						
			  } else if(navigator.userAgent.toLowerCase().indexOf('chrome') > -1){
				  frame = document.getElementById("iframepopupBox");
				  innerDoc = (frame.contentDocument) ? frame.contentDocument : frame.contentWindow.document;
				  
				  
				  	IHeight = ((innerDoc.body.scrollHeight) + 15) + "px";
					IWidth = ((innerDoc.body.scrollWidth) + 25) + "px";
					
					PHeight = ((innerDoc.body.scrollHeight) + 15) + "px";
					PWidth = ((innerDoc.body.scrollWidth) + 25) + "px";
 
			  } else if(navigator.userAgent.toLowerCase().indexOf('firefox/') > -1 || navigator.userAgent.indexOf('Opera')>=0){

				
					var ifr = document.getElementById("iframepopupBox").contentWindow.document || document.getElementById("iframepopupBox").contentDocument; 
					var widthViewport,heightViewport,widthTotal,heightTotal; 
					
					widthViewport=ifr.documentElement.clientWidth; 
					heightViewport=ifr.documentElement.clientHeight; 
					
					widthTotal=Math.max(ifr.documentElement.scrollWidth,ifr.body.scrollWidth,widthViewport); 
					heightTotal=Math.max(ifr.documentElement.scrollHeight,ifr.body.scrollHeight,heightViewport); 
					
					IHeight = (heightTotal + 15) + "px";
					IWidth = (widthTotal + 25) + "px";
					
					PHeight = (heightTotal + 15) + "px";
					PWidth = (widthTotal + 25) + "px";
					alert(widthTotal)
					
					alert(heightTotal)

					

			 }else {
				var innerDoc = (PopupBoxFrame.get(0).contentDocument) ? PopupBoxFrame.get(0).contentDocument : PopupBoxFrame.get(0).contentWindow.document;
				
					IHeight = ((innerDoc.body.scrollHeight) + 15) + "px";
					IWidth = ((innerDoc.body.scrollWidth) + 15) + "px";
					
					PHeight = ((innerDoc.body.scrollHeight) + 30) + "px";
					PWidth = ((innerDoc.body.scrollWidth) + 30) + "px";
				   
			  }
      	
      	//alert(innerDoc) 
      	//alert(IWidth)
	  	//alert(IHeight) 
		 
		
		PopupBoxFrame.attr('height', IHeight); 
	   	PopupBoxFrame.attr('width', IWidth); 
		window.parent.jQuery.popupbox.onLeftPopupbox();
		return false;
	 },
	
    close: function() {
      //Deshabilitado Codigo Original$(document).trigger('close.popupbox')
      //Deshabilitado Codigo Originalreturn false
	   //Codigo mofificacion Agregado//
		 $(document).unbind('keydown.popupbox')
			$('#popupbox').fadeOut(function() {
			  $('#popupbox .content').removeClass().addClass('content')
			  hideOverlay()
			  $('#popupbox .loading').remove()
			})
		//============================//
	return false;
    }
  })

  /*
   * Public, $.fn methods
   */

  $.fn.popupbox = function(settings) {
    init(settings)
	
    function clickHandler() {
      $.popupbox.loading(true)

      // support for rel="popupbox.inline_popup" syntax, to add a class
      // also supports deprecated "popupbox[.inline_popup]" syntax
      var klass = this.rel.match(/popupbox\[?\.(\w+)\]?/)
      if (klass) klass = klass[1]

      fillPopupBoxFromHref(this.href, klass,this.rev)
      return false
    }

    return this.click(clickHandler)
  }

  /*
   * Private methods
   */

  // called one time to setup popupbox on this page
  function init(settings) {
    if ($.popupbox.settings.inited) return true
    else $.popupbox.settings.inited = true

    $(document).trigger('init.popupbox')
    makeCompatible()

    var imageTypes = $.popupbox.settings.imageTypes.join('|')
    $.popupbox.settings.imageTypesRegexp = new RegExp('\.' + imageTypes + '$', 'i')

    if (settings) $.extend($.popupbox.settings, settings)
    $('body').append($.popupbox.settings.popupboxHtml)

    var preload = [ new Image(), new Image() ]
    preload[0].src = $.popupbox.settings.closeImage
    preload[1].src = $.popupbox.settings.loadingImage

    $('#popupbox').find('.b:first, .bl, .br, .tl, .tr').each(function() {
      preload.push(new Image())
      preload.slice(-1).src = $(this).css('background-image').replace(/url\((.+)\)/, '$1')
    })

    $('#popupbox .close').click($.popupbox.close)
    $('#popupbox .close_image').attr('src', $.popupbox.settings.closeImage)
  }
  
  // getPageScroll() by quirksmode.com
  function getPageScroll() {
    var xScroll, yScroll;
    if (self.pageYOffset) {
      yScroll = self.pageYOffset;
      xScroll = self.pageXOffset;
    } else if (document.documentElement && document.documentElement.scrollTop) {	 // Explorer 6 Strict
      yScroll = document.documentElement.scrollTop;
      xScroll = document.documentElement.scrollLeft;
    } else if (document.body) {// all other Explorers
      yScroll = document.body.scrollTop;
      xScroll = document.body.scrollLeft;	
    }
    return new Array(xScroll,yScroll) 
  }

  // Adapted from getPageSize() by quirksmode.com
  function getPageHeight() {
    var windowHeight
    if (self.innerHeight) {	// all except Explorer
      windowHeight = self.innerHeight;
    } else if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
      windowHeight = document.documentElement.clientHeight;
    } else if (document.body) { // other Explorers
      windowHeight = document.body.clientHeight;
    }	
    return windowHeight
  }
  
 

  // Backwards compatibility
  function makeCompatible() {
    var $s = $.popupbox.settings

    $s.loadingImage = $s.loading_image || $s.loadingImage
    $s.closeImage = $s.close_image || $s.closeImage
    $s.imageTypes = $s.image_types || $s.imageTypes
    $s.popupboxHtml = $s.popupbox_html || $s.popupboxHtml
  }

  // Figures out what you want to display and displays it
  // formats are:
  //     div: #id
  //   image: blah.extension
  //    ajax: anything else
  function fillPopupBoxFromHref(href, klass,rev) {
	// div 
	if (href.match(/#/)) { 
	var url = window.location.href.split('#')[0] 
	var target = href.replace(url, '') 
	$.popupbox.reveal($(target).clone().show(), klass) 

	// image 
	} else if (href.match($.popupbox.settings.imageTypesRegexp)) { 
	fillPopupBoxFromImage(href, klass) 
	 
	 
	// iframe 
	} else if (rev == 'iframe') { 
	fillPopupBoxFromIframe(href, klass, rev) 
	
	// ajax 
	} else { 
	fillPopupBoxFromAjax(href, klass) 
	} 
	
  }

  function fillPopupBoxFromImage(href, klass) {
    var image = new Image()
    image.onload = function() {
      $.popupbox.reveal('<div class="image"><img src="' + image.src + '" /></div>', klass)
    }
    image.src = href
  }

  function fillPopupBoxFromAjax(href, klass) {
	 // alert(href)
    $.get(href, function(data) { $.popupbox.reveal(data, klass) })
	//$.popupbox.reveal(href, klass)
  }
  
  
  function fillPopupBoxFromIframe(href,title, rev, klass) { 
	$.popupbox.revealIframe('<iframe name="iframepopupBox" id="iframepopupBox" scrolling="no" marginwidth="0" width="100%" height="100%" frameborder="0" src="' + href + '" marginheight="0" onload="window.parent.jQuery.popupbox.onResizePopupbox();"></iframe>', klass) 
	}

  function skipOverlay() {
    return $.popupbox.settings.overlay == false || $.popupbox.settings.opacity === null 
  }

  function showOverlay() {
    if (skipOverlay()) return

    if ($('popupbox_overlay').length == 0) 
      $("body").append('<div id="popupbox_overlay" class="popupbox_hide"></div>')

    $('#popupbox_overlay').hide().addClass("popupbox_overlayBG")
      .css('opacity', $.popupbox.settings.opacity)
      .click(function() { $(document).trigger('close.popupbox') })
      .fadeIn(200)
    return false
  }

  function hideOverlay() {
    if (skipOverlay()) return
    $('#popupbox_overlay').fadeOut(200, function(){
      $("#popupbox_overlay").removeClass("popupbox_overlayBG")
      $("#popupbox_overlay").addClass("popupbox_hide") 
     $("#popupbox_overlay").remove()
	 $('#popupboxcontent').empty()
	 //alert("Hola2")
    })
    return false
  }

  /*
   * Bindings
   */

  /*DesHabilitado Codigo Original $(document).bind('close.popupbox', function() {
    $(document).unbind('keydown.popupbox')
	//alert("Hola")
    $('#popupbox').fadeOut(function() {
		//alert("Hola")
     $('#popupbox .content').removeClass().addClass('content')
      hideOverlay()
     $('#popupbox .loading').remove()
    })
	 
  })*/

})(jQuery);
