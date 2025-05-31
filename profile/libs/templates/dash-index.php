<?php
/*
 * @Author: Amirhossein Hosseinpour <https://amirhp.com>
 * @Date Created: 2023/06/27 02:45:26
 * @Last modified by: amirhp-com <its@amirhp.com>
 * @Last modified time: 2025/05/31 16:28:56
 */
class dash_index extends PeproDevUPS {
  public function __construct() {
    parent::__construct(false);
    global $PeproDevUPS_Profile, $current_profile_url, $PeproDevUPS_Login, $wp;
    $current_profile_url = home_url($wp->request);
    echo "<style>" . @file_get_contents(plugin_dir_path(__FILE__) . "/css/style.css") . "</style>";
    wp_enqueue_style("peprofile_theme", plugins_url("/css/style.css", __FILE__), array(), time());
    wp_add_inline_style("peprofile_theme", '/*' . PHP_EOL . '* Global CSS @ PeproDev Ultimate Profile Solutions (https://pepro.dev/ups)' . PHP_EOL . '*/' . PHP_EOL . $this->read("custom_css"));
    wp_enqueue_style("pepro-font-awesome", PEPRODEVUPS_ASSETS_URL . "fa-pro/css/all.min.css", [], $this->version);
    $avatar = "";
    $cur_user = get_current_user_id();
    $avatar  = get_avatar($cur_user, 48);
    $matches = array();
    $loggedin_text = sprintf(__("Hi %s", "peprodev-ups"), "{first_name}");

    add_filter("post_type_link", function ($post_link, $post, $leave_name, $sample) {
      if ('sfwd-courses' == get_post_type($post)) {
        return add_query_arg(["view" => $post->ID]);
      }
      // if ('sfwd-lessons' == get_post_type( $post ) ) {return add_query_arg(["lesson"=> $post->ID]);}
      return $post_link;
    }, 10, 4);

    if (!empty($loggedin_text)) {
      preg_match('#\{(.*?)\}#', $loggedin_text, $matches);
      foreach ($matches as $match) {
        $user_meta = get_the_author_meta($match, $cur_user);
        $loggedin_text = str_replace("{{$match}}", $user_meta, $loggedin_text);
      }
    }

    $allowed_slugs_whitelist = array_unique(apply_filters("peprofile_dashboard_slugs", array("edit", "me", "address")));
    $current_requested_slug = isset($_GET['section']) ? sanitize_text_field(trim($_GET['section'])) : "";

    ?>
    <style>
      .profile-page-wrapper .tab-names {
        display: flex;
        align-items: center;
      }

      .profile-page-wrapper .tab-names a.change-tab {
        padding: 10px 20px;
        background: #f1f2f3;
      }

      .profile-page-wrapper .tab-names a.change-tab.active {
        background: transparent;
      }

      .profile-page-wrapper .tab-content .tab-entry {
        display: none;
      }

      .profile-page-wrapper .tab-content .tab-entry.active {
        display: block;
      }

      .profile-page-wrapper .tabbed-wrapper {
        background: var(--e-a-bg-hover, #f1f2f3);
        width: 100%;
        border-radius: 0.85714rem;
        padding: 0.5rem;
      }

      .profile-page-wrapper .tabbed-wrapper .tab-names a.change-tab {
        background-color: transparent;
        border-radius: 0.4rem;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
      }

      .tab-content .tab-entry.card:first-child {
        border-top-right-radius: 0 !important;
      }

      .profile-page-wrapper .tabbed-wrapper .tab-names a.change-tab.active {
        background: white;
      }

      .profile-page-wrapper .tabbed-wrapper .tab-entry.card.active {
        background: white;
        padding: 0.5rem;
        border-radius: 0.457rem;
      }

      .profile-page-wrapper .tabbed-wrapper button {
        max-width: 300px !important;
      }

      .profile-page-wrapper .tab-content .tab-entry.card:only-child {
        border-top-right-radius: 0.457rem !important;
      }
    </style>
    <script>
      (function($) {
        $(document).ready(function() {
          $(document).on("click tap", ".tab-names .change-tab", function(e) {
            e.preventDefault();
            var me = $(this),
              id = me.data("tab"),
              parent = me.parents(".tabbed-wrapper");
            $(parent).find(".change-tab.active").removeClass("active");
            me.addClass("active");
            $(parent).find(".tab-content .tab-entry.active").removeClass("active");
            $(parent).find(`.tab-content .tab-entry[data-tab='${id}']`).addClass("active");
          });
        });
      })(jQuery);
    </script>
    <div class="profile-page-wrapper page-<?php echo $current_requested_slug; ?>">
      <aside class="menu-sidebar d-none d-lg-block">
        <div class="menu-sidebar__content js-scrollbar1">
          <nav class="navbar-sidebar">
            <ul class="list-unstyled navbar__list">
              <li class="list-hamburger" style="display: none;"><a class="toggle-navbar" href="#"><span class="hamburger-box hamburger--squeeze"><span class="hamburger-inner"></span></span><span class="hamburger-text"><?php echo  __("Menu", "peprodev-ups"); ?></span></a></li>
              <li class="user-icon"><?php echo  "{$avatar}<span class='user_name_display'>{$loggedin_text}</span>"; ?></li>
              <?php
              do_action("pepro_reglogin_navbar_front_before");
              // hooked into @ peprofile_get_template_part_nav
              $PeproDevUPS_Profile->peprofile_get_template_part("nav", "bar");
              do_action("pepro_reglogin_navbar_front_after");
              ?>
            </ul>
            <?php
            $t_section = isset($_GET['section']) ? sanitize_text_field(trim($_GET['section'])) : "home";
            $t_section = $t_section == "edit" ? "edituser" : $t_section;
            $t_section = $t_section == "address" ? "edituser" : $t_section;
            ?>
            <script>
              function make_active_li(e) {
                var div = document.querySelectorAll(`[data-ref='<?php echo $t_section; ?>']`),
                  i;
                for (i = 0; i < div.length; ++i) {
                  div[i].className = div[i].className + ' active';
                }
              }
              window.onload = make_active_li;
              make_active_li();
              setTimeout(function() {
                make_active_li()
              }, 500);
              setTimeout(function() {
                make_active_li()
              }, 1000);
            </script>
          </nav>
        </div>
      </aside>
      <div class="main-content section-<?php echo $current_requested_slug; ?>">
        <div class="section__content section__content--p30">
          <?php

          if (class_exists("WC_Admin_Profile")) {
            (new \WC_Admin_Profile)->save_customer_meta_fields(get_current_user_id());
            wc_print_notices();
          }

          if (empty($current_requested_slug) || !in_array($current_requested_slug, $allowed_slugs_whitelist)) {
            $PeproDevUPS_Profile->peprofile_get_template_part("dash", "home");
          } else {
            add_filter("body_class", function ($classes) use ($current_requested_slug) {
              $classes[] = "pepro-profile-page";
              $classes[] = "section-{$current_requested_slug}";
              return $classes;
            });

            if (has_action("peprofile_dashboard_content_{$current_requested_slug}_force")) {
              do_action("peprofile_dashboard_content_{$current_requested_slug}_force");
            } else {
              if ("orders" == $current_requested_slug && isset($_GET['view']) && !empty(trim($_GET['view']))) {
                $located_requested_slug_template = $PeproDevUPS_Profile->peprofile_get_template_part("dash", "orders-view");
              } elseif (("courses" == $current_requested_slug || "course-list" == $current_requested_slug) && isset($_GET['view']) && !empty(trim($_GET['view'])) && isset($_GET['lesson']) && !empty(trim($_GET['lesson']))) {
                // $located_requested_slug_template = $PeproDevUPS_Profile->peprofile_get_template_part("dash", "course-lesson-view");
              } elseif (("courses" == $current_requested_slug || "course-list" == $current_requested_slug) && isset($_GET['view']) && !empty(trim($_GET['view']))) {
                $located_requested_slug_template = $PeproDevUPS_Profile->peprofile_get_template_part("dash", "course-view");
              } else {
                $located_requested_slug_template = $PeproDevUPS_Profile->peprofile_get_template_part("dash", $current_requested_slug);
              }
              if (!$located_requested_slug_template) {
                if (has_action("peprofile_dashboard_content_{$current_requested_slug}")) {
                  do_action("peprofile_dashboard_content_{$current_requested_slug}");
                } else {
                  $PeproDevUPS_Profile->peprofile_get_template_part("dash", "home");
                }
              }
            }
          }
          ?>
        </div>
      </div>
    </div>
    <?php
    wp_enqueue_script("jquery");
    wp_enqueue_script("peprodev-main", plugins_url("/js/main.js", __FILE__), array("jquery"));
    wp_register_script("peprodev--custom", plugins_url("/js/custom-js.js", __FILE__), array("jquery"), time(), true);
    wp_localize_script("peprodev--custom", "_i18n", array(
      "td"                  => "peprocoreprofile",
      "ajax"                => admin_url("admin-ajax.php"),
      "prductnames"         => __("Product name", "peprodev-ups"),
      "wishlistempty"       => __("No products added to the wishlist", "peprodev-ups"),
      "fillreq"             => __("Please fill out all required fields.", "peprodev-ups"),
      "max_size_err"        => sprintf(__("Error , File is too large. Maximum file size is %s MB", "peprodev-ups"), "2"),
      "nonce"               => wp_create_nonce("pepro_profile"),
      "current_profile_url" => $current_profile_url,
    ));
    wp_enqueue_script("peprodev--custom");
    $custom_js = $this->read("custom_js");
    wp_add_inline_script(
      "peprodev--custom",
      '(function ($) {"use strict";' . PHP_EOL .
        '/* Global JS @ PeproDev Ultimate Profile Solutions (https://pepro.dev/ups) */' . PHP_EOL .
        $custom_js . PHP_EOL . '})(jQuery);',
      "after"
    );
  }
}
return new dash_index;
