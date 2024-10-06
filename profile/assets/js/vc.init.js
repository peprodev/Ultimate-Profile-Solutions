var $peprocoreprofile_custom = "pepro-profile";
(function($) {
	vc.atts.peprocoreprofile_custom = {
    init: function(param, $field) {
		var $firstData = $(`div[data-vc-shortcode='${$peprocoreprofile_custom}'] input[name="ids"]`).val();
		if ($(`div[data-vc-shortcode='${$peprocoreprofile_custom}'] div.vc_ui-button-group>.pepro-footerlink-vc`).length == 0) {
			$(`div[data-vc-shortcode='${$peprocoreprofile_custom}'] div.vc_ui-button-group`).append(`<a class="vc_general vc_ui-button vc_ui-button-default vc_ui-button-shape-rounded vc_ui-button-fw pepro-footerlink-vc" href="https://pepro.co/" target="_blank"><img src="${peprmiage}" width="25px">© PeproCo</a>`);
		}
		$(`div[data-vc-shortcode='${$peprocoreprofile_custom}'] select#wpbselectcategories`).select2({ placeholder: peprotxt__selectanoption, dir: peprotxt__dir, minimumResultsForSearch: Infinity, });
		$(`div[data-vc-shortcode='${$peprocoreprofile_custom}'] select#wpbselectcategories`).on("change click",function (e) {
			e.preventDefault();
			$va = $(`div[data-vc-shortcode='${$peprocoreprofile_custom}'] select#wpbselectcategories`).val();
			$(`div[data-vc-shortcode='${$peprocoreprofile_custom}'] input[name="ids"]`).val($va);
		});
		if ($firstData){
			$(`div[data-vc-shortcode='${$peprocoreprofile_custom}'] select#wpbselectcategories`).val($firstData.split(",")).trigger("change");
		}
    }
  };

})(window.jQuery);
