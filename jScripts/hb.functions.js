$.hbPOST = function(action, data, successCB, errorCB) {
    $.ajax({
        type: "POST",
        url: action,
        data: data,
        dataType: "json",
        success: successCB,
        error: errorCB
    });
}

$.fn.defaultText = function(value) {

    var element = this.eq(0);
    element.data('defaultText', value);

    element.focus(function() {
        if (element.val() == value) {
            element.val('').removeClass('defaultText');
        }
    }).blur(function() {
        if (element.val() == '' || element.val() == value) {
            element.addClass('defaultText').val(value);
        }
    });

    return element.blur();
}

$(function() {
	$(window).scroll(function() {
		if($(this).scrollTop() != 0) {
			$('#toTop').fadeIn();	
		} else {
			$('#toTop').fadeOut();
		}
	});
 
	$('#toTop').click(function() {
		$('body,html').animate({scrollTop:0},800);
	});	
});

var NOTIFICATIONS = {
    render: function(accion, data) {
        var arr = [];
        switch (accion) {
            case 'notification':
                arr = ['<div class="notification-bar-container">',
                   '<div class="notification-bar-bkg" style="display: block; height: 22px;"></div>',
                   '<div class="notification-bar" style="display: block;">',
                   '<div class="notification-bar-contents">',
                   '<div class="message message-info">',
                   data,
                   '</div></div></div></div><div class="notification-bar-container"></div>'];
                break;
        }
        return arr.join('');
    },
    
    displayNotification : function(msg, type) {

        var elem = $(NOTIFICATIONS.render('notification',msg), {});

        elem.click(function() {
            $(this).fadeOut(function() {
                $(this).remove();
            });
        });

        setTimeout(function() {
            elem.click();
        }, 5000);

        elem.hide().appendTo('#notifications').slideDown();
    }
}
