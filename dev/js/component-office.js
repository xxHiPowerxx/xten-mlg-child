(function ($) {
	jQuery(document).on('ready', function() {
		var funcs = {},
			readyFuncs = {};

		funcs.executeFuncs = function(obj) {
			for (var func in obj) {
				obj[func]();
			}
		}

		funcs.lazyLoadIframe = function($iframe) {
			var dataSrc = $iframe.attr('data-src');
			$iframe.attr('src', dataSrc);
		}
		funcs.bindLazyLoadIframe = function() {
			$('iframe[loading="lazy"][data-src]:not([src]), iframe[loading="lazy"][data-src][src=""]').each(function(){
				var $iframe = $(this),
					$office = $(this).closest('.component-office');
				$office.one('mouseover', function() {
					funcs.lazyLoadIframe($iframe);
				});
			});
		}
		readyFuncs.bindLazyLoadIframe = funcs.bindLazyLoadIframe;

		funcs.executeFuncs(readyFuncs);
	});
})(jQuery);