/**
 * @Author: Amirhosseinhpv
 * @Date:   2020/05/03 13:33:07
 * @Email:  its@hpv.im
 * @Last modified by:   Amirhosseinhpv
 * @Last modified time: 2021/09/03 15:40:13
 * @License: GPLv2
 * @Copyright: Copyright © 2020 Amirhosseinhpv, All rights reserved.
 */

(function ($) {
  // USE STRICT
  "use strict";

  // Dropdown
  try {
    var menu = $('.js-item-menu');
    var sub_menu_is_showed = -1;

    for (var i = 0; i < menu.length; i++) {
      $(menu[i]).on('click', function (e) {
        e.preventDefault();
        $('.js-right-sidebar').removeClass("show-sidebar");
        if (jQuery.inArray(this, menu) == sub_menu_is_showed) {
          $(this).toggleClass('show-dropdown');
          sub_menu_is_showed = -1;
        }
        else {
          for (var i = 0; i < menu.length; i++) {
            $(menu[i]).removeClass("show-dropdown");
          }
          $(this).toggleClass('show-dropdown');
          sub_menu_is_showed = jQuery.inArray(this, menu);
        }
      });
    }
    $(".js-item-menu, .js-dropdown").click(function (event) {
      event.stopPropagation();
    });

    $("body,html").on("click", function () {
      for (var i = 0; i < menu.length; i++) {
        menu[i].classList.remove("show-dropdown");
      }
      sub_menu_is_showed = -1;
    });

  } catch (error) {
    console.log(error);
  }

  var wW = $(window).width();
    // Right Sidebar
    var right_sidebar = $('.js-right-sidebar');
    var sidebar_btn = $('.js-sidebar-btn');

    sidebar_btn.on('click', function (e) {
      e.preventDefault();
      for (var i = 0; i < menu.length; i++) {
        menu[i].classList.remove("show-dropdown");
      }
      sub_menu_is_showed = -1;
      right_sidebar.toggleClass("show-sidebar");
    });

    $(".js-right-sidebar, .js-sidebar-btn").click(function (event) {
      event.stopPropagation();
    });

    $("body,html").on("click", function () {
      right_sidebar.removeClass("show-sidebar");

    });


  // Sublist Sidebar
  try {
    var arrow = $('.js-arrow');
    arrow.each(function () {
      var _this = $(this);
      _this.on('click', function (e) {
        e.preventDefault();
        _this.find(".arrow").toggleClass("up");
        _this.toggleClass("open");
        _this.parent().find('.js-sub-list').slideToggle("250");
      });
    });

  } catch (error) {
    console.log(error);
  }


  try {
    // Hamburger Menu
    $('.hamburger').on('click', function () {
      $(this).toggleClass('is-active');
      $('.navbar-mobile').slideToggle('500');
    });
    $('.navbar-mobile__list li.has-dropdown > a').on('click', function () {
      var dropdown = $(this).siblings('ul.navbar-mobile__dropdown');
      $(this).toggleClass('active');
      $(dropdown).slideToggle('500');
      return false;
    });
  } catch (error) {
    console.log(error);
  }
})(jQuery);


(function ($) {
  // USE STRICT
  "use strict";

  // Chatbox
  try {
    $(document).on("click tap",".js-inbox-wrap .au-message__item",function(e) {
      e.preventDefault();
      var refid = $(this).data("ref");
      $(`.au-chat[data-ref=${refid}]`).addClass("shown").show();
      $(".backtonotifs").fadeIn();
      $(this).parent().parent().parent().toggleClass('show-chat-box');
    });
    $(document).on("click tap",".backtonotifs",function(e) {
      e.preventDefault();
      $(".au-inbox-wrap.js-inbox-wrap.show-chat-box").removeClass("show-chat-box");
      $(".au-chat.shown").hide();
      $(this).fadeOut();
    });
    $(document).on("click tap",".nick",function(e) {
      e.preventDefault();
      $(".au-inbox-wrap.js-inbox-wrap.show-chat-box").removeClass("show-chat-box");
      $(".au-chat.shown").hide();
      $(".backtonotifs").fadeOut();
    });

  } catch (error) {
    console.log(error);
  }

})(jQuery);
