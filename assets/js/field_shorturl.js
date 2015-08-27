$(function() {
	var timeout = null;

    $('input.shorturl').bind('keyup blur', function() {
		clearTimeout(timeout);
		var el = $(this);
		el.val(el.val().replace(/[^a-zA-Z0-9\-_.]/g,''));

		timeout = setTimeout(function () {
			$('.right-inner-addon i.active').removeClass('active');

			if(el.val().length >= 2) {
				setShorturlStatus('loading');

				//Ajax call to verify if hash is unique.
				setShorturlStatus('ok');
				var baseUrl = $('.shorturl-message-ok a').attr('data-base');
				$('.shorturl-message-ok a').attr('href', baseUrl+el.val()).html(baseUrl+el.val());

			} else {
				$('.shorturl-message-error').html('Shorturl must at least have two characters.');
				setShorturlStatus('error');
			}
		}, 250);
	}).keyup();

	var setShorturlStatus = function(status) {
		$('.shorturl-' + status).addClass('active').siblings().removeClass('active');
		$('.shorturl-message-' + status).show().siblings('small').hide();
	}
});
