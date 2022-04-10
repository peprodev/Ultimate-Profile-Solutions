/**
 * @Author: Amirhosseinhpv
 * @Date:   2021/08/28 00:08:00
 * @Email:  its@hpv.im
 * @Last modified by:   Amirhosseinhpv
 * @Last modified time: 2021/09/15 13:41:00
 * @License: GPLv2
 * @Copyright: Copyright © Amirhosseinhpv (https://hpv.im), all rights reserved.
 */


/*!

 =========================================================
 * Material Dashboard Dark Edition - v2.1.0
 =========================================================

 * Product Page: https://www.creative-tim.com/product/material-dashboard-dark
 * Copyright 2019 Creative Tim (http://www.creative-tim.com)

 * Coded by www.creative-tim.com

 =========================================================

 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

 */
 (function($) {

   (function() {
     isWindows = navigator.platform.indexOf('Win') > -1 ? true : false;

     if (isWindows) {
       // if we are on windows OS we activate the perfectScrollbar function
       // $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar();
       // $('html').addClass('perfect-scrollbar-on');
     } else {
       // $('html').addClass('perfect-scrollbar-off');
     }
   })();


   var breakCards = true;

   var searchVisible = 0;
   var transparent = true;

   var transparentDemo = true;
   var fixedTop = false;

   var mobile_menu_visible = 0,
     mobile_menu_initialized = false,
     toggle_initialized = false,
     bootstrap_nav_initialized = false;

   var seq = 0,
     delays = 80,
     durations = 500;
   var seq2 = 0,
     delays2 = 80,
     durations2 = 500;

   $(document).ready(function() {

     $('body').bootstrapMaterialDesign();

     $sidebar = $('.sidebar');

     md.initSidebarsCheck();

     window_width = $(window).width();

     // check if there is an image set for the sidebar's background
     md.checkSidebarImage();

     //    Activate bootstrap-select
     if ($(".selectpicker").length != 0) {
       $(".selectpicker").selectpicker();
     }

     //  Activate the tooltips
     // $('[rel="tooltip"]').tooltip();

     $("[rel=tooltip]").each(function(index, val) {
       tippy(this, {content: $(val).attr("title"), arrow: true,});
     });

     $('.form-control').on("focus", function() {
       $(this).parent('.input-group').addClass("input-group-focus");
     }).on("blur", function() {
       $(this).parent(".input-group").removeClass("input-group-focus");
     });

     // remove class has-error for checkbox validation
     $('input[type="checkbox"][required="true"], input[type="radio"][required="true"]').on('click', function() {
       if ($(this).hasClass('error')) {
         $(this).closest('div').removeClass('has-error');
       }
     });

   });

   $(document).on('click', '.navbar-toggler', function() {
     $toggle = $(this);

     if (mobile_menu_visible == 1) {
       $('html').removeClass('nav-open');

       $('.close-layer').remove();
       setTimeout(function() {
         $toggle.removeClass('toggled');
       }, 400);

       mobile_menu_visible = 0;
     } else {
       setTimeout(function() {
         $toggle.addClass('toggled');
       }, 430);

       var $layer = $('<div class="close-layer"></div>');

       if ($('body').find('.main-panel').length != 0) {
         $layer.appendTo(".main-panel");

       } else if (($('body').hasClass('off-canvas-sidebar'))) {
         $layer.appendTo(".wrapper-full-page");
       }

       setTimeout(function() {
         $layer.addClass('visible');
       }, 100);

       $layer.click(function() {
         $('html').removeClass('nav-open');
         mobile_menu_visible = 0;

         $layer.removeClass('visible');

         setTimeout(function() {
           $layer.remove();
           $toggle.removeClass('toggled');

         }, 400);
       });

       $('html').addClass('nav-open');
       mobile_menu_visible = 1;

     }

   });

   // activate collapse right menu when the windows is resized
   $(window).resize(function() {
     md.initSidebarsCheck();
   });



   md = {
     misc: {
       navbar_menu_visible: 0,
       active_collapse: true,
       disabled_collapse_init: 0
     },

     checkSidebarImage: function() {
       $sidebar = $('.sidebar');
       image_src = $sidebar.data('image');

       if (image_src !== undefined) {
         sidebar_container = '<div class="sidebar-background" style="background-image: url(' + image_src + ') "/>';
         $sidebar.append(sidebar_container);
       }
     },

     initSidebarsCheck: function() {
       if ($(window).width() <= 991) {
         if ($sidebar.length != 0) {
           md.initRightMenu();
         }
       }
     },

     showNotification: function(from, align) {
       type = ['', 'info', 'danger', 'success', 'warning', 'primary'];

       color = Math.floor((Math.random() * 5) + 1);

       $.notify({
         icon: "add_alert",
         message: "Welcome to <b>Material Dashboard</b> - a beautiful freebie for every web developer."

       }, {
         type: type[color],
         timer: 3000,
         placement: {
           from: from,
           align: align
         }
       });
     },

     checkScrollForTransparentNavbar: debounce(function() {
       if ($(document).scrollTop() > 260) {
         if (transparent) {
           transparent = false;
           $('.navbar-color-on-scroll').removeClass('navbar-transparent');
         }
       } else {
         if (!transparent) {
           transparent = true;
           $('.navbar-color-on-scroll').addClass('navbar-transparent');
         }
       }
     }, 17),

     initRightMenu: debounce(function() {

       $sidebar_wrapper = $('.sidebar-wrapper');

       if (!mobile_menu_initialized) {
         console.log('intra');
         $navbar = $('nav').find('.navbar-collapse').children('.navbar-nav');

         mobile_menu_content = '';

         nav_content = $navbar.html();

         nav_content = '<ul class="nav navbar-nav nav-mobile-menu">' + nav_content + '</ul>';

         navbar_form = $('nav').find('.navbar-form').length != 0 ? $('nav').find('.navbar-form')[0].outerHTML : null;

         $sidebar_nav = $sidebar_wrapper.find(' > .nav');

         // insert the navbar form before the sidebar list
         $nav_content = $(nav_content);
         $navbar_form = $(navbar_form);
         $nav_content.insertBefore($sidebar_nav);
         $navbar_form.insertBefore($nav_content);

         $(".sidebar-wrapper .dropdown .dropdown-menu > li > a").click(function(event) {
           event.stopPropagation();

         });

         // simulate resize so all the charts/maps will be redrawn
         window.dispatchEvent(new Event('resize'));

         mobile_menu_initialized = true;
       } else {
         if ($(window).width() > 991) {
           // reset all the additions that we made for the sidebar wrapper only if the screen is bigger than 991px
           $sidebar_wrapper.find('.navbar-form').remove();
           $sidebar_wrapper.find('.nav-mobile-menu').remove();

           mobile_menu_initialized = false;
         }
       }
     }, 200),

   }

   // Returns a function, that, as long as it continues to be invoked, will not
   // be triggered. The function will be called after it stops being called for
   // N milliseconds. If `immediate` is passed, trigger the function on the
   // leading edge, instead of the trailing.

   function debounce(func, wait, immediate) {
     var timeout;
     return function() {
       var context = this,
         args = arguments;
       clearTimeout(timeout);
       timeout = setTimeout(function() {
         timeout = null;
         if (!immediate) func.apply(context, args);
       }, wait);
       if (immediate && !timeout) func.apply(context, args);
     };
   };
 })(jQuery);
