$(function() {
	var timeout = null;

	$('input.shorturl').bind('keyup blur', function() {
		clearTimeout(timeout);
		var el = $(this);
		el.val(el.val().replace(/[^a-zA-Z0-9\-_.]/g, ''));

		timeout = setTimeout(function() {
			$('.right-inner-addon i.active').removeClass('active');

			if(el.data('checkunique')) {
				setShorturlStatus('loading');

				//Ajax call to verify if hash is unique.
				$.get(el.data('async-url'), {
						shorturl: el.val()
					}, function(data) {
						$('.shorturl-message-' + data.status).html(data.msg);
						setShorturlStatus(data.status);
					})
					.fail(function() {
						$('.shorturl-message-error').html('Serverside error. Please reload the page.');
						setShorturlStatus('error');
					});
			} else {
				if (el.val().length >= 2) {
					$('.shorturl-message-ok').html('This record will be accessible via <a href="' + el.data('base-url') + el.val() + '" target="_blank">' + el.data('base-url') + el.val() + '</a>.');
					setShorturlStatus('ok');
				} else {
					$('.shorturl-message-error').html('Shorturl must at least have two characters and can only contain a-z, A-Z, 0-9, ".", "-" and "_".');
					setShorturlStatus('error');
				}
			}
		}, 250);
	}).keyup();

	var setShorturlStatus = function(status) {
		$('.shorturl-' + status).addClass('active').siblings().removeClass('active');
		$('.shorturl-message-' + status).show().siblings('small').hide();
	}
});
