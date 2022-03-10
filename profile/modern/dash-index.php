<?php
# @Last modified by:   Amirhosseinhpv
# @Last modified time: 2021/09/17 21:06:51
namespace PeproDev;
use PeproDev;
class dashboard extends PeproDevUPS_Profile
{
  function __construct()
  {
    parent::__construct();
    $this->cur_slug = isset($_GET['section']) ? sanitize_text_field(trim($_GET['section'])) : "home";

    $this->wp_enqueue_scripts();
    $this->handle_body();
  }
  public function handle_body()
  {
    // remove wp-seo
    if (class_exists("WPSEO_Options")){
      $front_end = YoastSEO()->classes->get( Yoast\WP\SEO\Integrations\Front_End_Integration::class );
      remove_action( 'wpseo_head', [ $front_end, 'present_head' ], -9999 );
    }
    ?>
    <pdwrapper>
      <pdaside>
        <ul class="navbar__list">
          <?php
            foreach ($this->peprofile_get_nav_items_array() as $key => $value) {
                $active = false;
                if ($this->cur_slug == $key) $active = true;
                echo "<li class='nav-item ".($active?"active":"")."'><a href='".esc_attr($value["url"])."'>".wp_kses_post($value["title"])."</a></li>";
            }
          ?>
        </ul>
      </pdaside>
      <pdmain>
        <?php
        if (!get_current_user_id()){
          $this->peprofile_get_template_part("dash", "loggedout");
        }
        else{
          $allowed_slugs_whitelist = array_unique(apply_filters( "peprofile_dashboard_slugs", array("edit","me")));
          if (empty($this->cur_slug) || !in_array($this->cur_slug, $allowed_slugs_whitelist)){
            $this->peprofile_get_template_part("dash","home");
          }
          else{
            if (has_action("peprofile_dashboard_content_{$this->cur_slug}_force")){
              do_action("peprofile_dashboard_content_{$this->cur_slug}_force");
            }
            else{
              if ( "orders" == $this->cur_slug && isset($_GET['view']) && !empty(trim($_GET['view']))){
                $located_requested_slug_template = $this->peprofile_get_template_part("dash", "orders-view");
              }
              else{
                $located_requested_slug_template = $this->peprofile_get_template_part("dash", $this->cur_slug);
              }
              if (!$located_requested_slug_template){
                if (has_action("peprofile_dashboard_content_{$this->cur_slug}")){
                  do_action("peprofile_dashboard_content_{$this->cur_slug}");
                }
                else{
                  $this->peprofile_get_template_part("dash","home");
                }
              }
            }
          }
        }
        ?>
      </pdmain>
    </pdwrapper>
    <?php
  }
  public function wp_enqueue_scripts()
  {
    /* heading */
    if($this->use_front_fa) wp_enqueue_style("pd-fafa", PEPRODEVUPS_URL . "/core/assets/css/all.min.css", [], $this->script_version);
    if($this->use_front_fo) wp_enqueue_style("pd-fafo", PEPRODEVUPS_URL . "/core/assets/css/fonts.css", [], $this->script_version);
    wp_enqueue_style("peprodev-theme", "$this->modern_assets/css/modern.css", [], $this->script_version);
    if (!empty($this->custom_css)) {
      wp_add_inline_style("peprodev-theme", '/*'.PHP_EOL.
      '* Global CSS @ PeproDev Ultimate Profile Solutions (https://pepro.dev/ups)'.PHP_EOL.
      '*/'.PHP_EOL. wp_unslash($this->custom_css) );
    }
    if (is_rtl()){
      wp_enqueue_style("peprodev-theme-rtl", "$this->modern_assets/css/modern-rtl.css", [], $this->script_version);
    }

    /* footer */
    wp_enqueue_script( "jquery" );
    wp_enqueue_script("peprodev-main", "$this->modern_assets/js/main.js", array("jquery"));
    wp_enqueue_script("peprodev-extras", "$this->modern_assets/js/extras.js", array("jquery"), $this->script_version, true);
    wp_register_script("peprodev-custom", "$this->modern_assets/js/custom-js.js", array("jquery"), $this->script_version, true);
    wp_localize_script("peprodev-custom", "_i18n", array(
      "td"                  => "peprocoreprofile",
      "ajax"                => admin_url( "admin-ajax.php"),
      "nonce"               => wp_create_nonce( "pepro_profile" ),
      "prductnames"         => __("Product name", "peprodev-ups"),
      "wishlistempty"       => __("No products added to the wishlist", "peprodev-ups"),
      "fillreq"             => __("Please fill out all required fields.", "peprodev-ups"),
      "max_size_err"        => __("Error, file is too large. Maximum allowed file size is #fs#","peprodev-ups"),
      ));
    wp_enqueue_script("peprodev-custom");
    $js1 = wp_unslash($this->custom_js);
    $js2 = wp_unslash($this->notifs_js);
    wp_add_inline_script("peprodev-custom", '(function ($) {"use strict";
      /*
       *  Global JS @ PeproDev Ultimate Profile Solutions (https://pepro.dev/ups)
      */
      '.$js1.'
      '.$js2.'
    })(jQuery);', "after");
  }
}
new dashboard;
