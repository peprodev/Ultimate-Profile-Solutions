/**
 * @Author: Amirhosseinhpv
 * @Date:   2021/12/27 12:03:54
 * @Email:  its@hpv.im
 * @Last modified by:   Amirhosseinhpv
 * @Last modified time: 2021/12/30 20:14:41
 * @License: GPLv2
 * @Copyright: Copyright © Amirhosseinhpv (https://hpv.im), all rights reserved.
 */


(function($) {
	var _pepro_ajax_request = null;
	$(document).ready(function() {
		$(document).on("click", "#pssverifysms", function(e) {
			e.preventDefault();
			if (_pepro_ajax_request != null) { _pepro_ajax_request.abort(); }
			$(this).prop("disabled", true);
			_pepro_ajax_request = $.ajax({
				type: "POST",
				dataType: "json",
				url: _i18n.ajax,
				data: {
					action: _i18n.td,
					nonce: _i18n.nonce,
					wparam: "add-cart",
					lparam: JSSDATA,
				},
				success: function(e) {
					if (e.success === true) {
						console.info(e.data.msg);
						/*window.location.href = r.url;*/
					} else {
						console.error(e.data.msg);
					}
				},
				error: function(e) {
					console.error("Unknown Error Occured!");
				},
				complete: function(e) {
					//console.log("ajax complete");
				},
			});
		});
	});
})(jQuery);
