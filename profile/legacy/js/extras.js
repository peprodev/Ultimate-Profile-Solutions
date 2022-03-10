/**
 * @Author: Amirhosseinhpv
 * @Date:   2021/09/17 21:05:59
 * @Email:  its@hpv.im
 * @Last modified by:   Amirhosseinhpv
 * @Last modified time: 2021/09/17 21:10:04
 * @License: GPLv2
 * @Copyright: Copyright © Amirhosseinhpv (https://hpv.im), all rights reserved.
 */
jQuery.noConflict();
(function ($) {
  $(function () {
    $(document).on("change", "input#avatar[type='file']", function (e) {
      if (this.files && this.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) { $("#avatar_b").attr("src", e.target.result); };
        reader.readAsDataURL(this.files[0]);
      }
    });
  });
})(jQuery);
