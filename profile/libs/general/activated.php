<?php
# @Last modified by:   Amirhosseinhpv
# @Last modified time: 2021/09/19 08:49:03
?>
<div class="row">
  <div class="col-lg-6 col-md-6">
    <div class="card">
      <div class="card-header card-header-primary">
        <h4 class="card-title"><?php echo esc_html_x("Profile Dashboard","profile-section","peprodev-ups");?></h4>
        <p class="card-category"><?php echo esc_html_x("You can control profile front-end style from here.","profile-section","peprodev-ups");?></p>
      </div>

      <div class="card-body table-responsive">

        <table class="table pepcappearance table-striped">
          <thead class=""></thead>
          <tbody>

              <!-- Profile Logo Image -->
              <tr>
                <td><?php echo esc_html_x("Profile Logo Image","profile-section", "peprodev-ups");?></td>
                <td>
                  <input type="hidden" data-id="<?php echo esc_attr( get_option("{$this->activation_status}-logo-id","") );?>" id="profile-section-logo"
                  value="<?php echo esc_attr( get_option("{$this->activation_status}-logo", $this->icon) );?>"
                  class="form-control primary" placeholder="<?php echo esc_html_x("Logo URL","profile-section","peprodev-ups");?>" />
                  <div class="flex flex-sb">
                    <img style="border-radius: 5px;" src="<?php echo esc_attr( get_option("{$this->activation_status}-logo","") );?>" id="profile-img" width="86px"/>
                    <button type="button" id="selectlogoimg" style="padding: 12px; display: block;" class="btn btn-primary mediapicker icn-btn"
                    data-ref="#profile-section-logo"
                    data-ref4="#profile-img"
                    data-title="<?php echo esc_html_x("Select Custom Image","profile-section", "peprodev-ups");?>"
                    ><i class='material-icons'>cloud_upload</i> <?php echo esc_html_x("Select or Upload Custom Image","profile-section", "peprodev-ups");?></button>
                  </div>
                </td>
              </tr>
              <!-- Dashboard page -->
              <tr>
                <td><?php echo esc_html_x("Dashboard page","profile-section", "peprodev-ups");?></td>
                <td>
                  <?php
                    $dashpage = get_option("{$this->activation_status}-profile-dash-page","");
                    $dropdown_args = array(
                      'selected'         => $dashpage,
                      'name'             => 'profile_dash_page',
                      'sort_column'      => 'menu_order, post_title',
                    );
                    $pages = wp_dropdown_pages( $dropdown_args );
                    $slug = get_page_template_slug($dashpage);
                    $len = strlen("peprofile-");
                    $show_alert = false;
                    if((substr("$slug", 0, $len) !== "peprofile-")) {
                      $show_alert = true;
                    }
                   ?>
                   <small>
                   <a href="#" class="open-page"><?php echo __("View Page", $this->td);?></a> / <a href="#" class="edit-page"><?php echo __("Edit Page", $this->td);?></a> / <a href="#" class="edit-elementor"><?php echo __("Edit via Elementor", $this->td);?></a>
                   </small>
                </td>

              </tr>
                <script type="text/javascript">
                  (function($) {
                    $(document).on("click tap", ".open-page", function(e){
                      e.preventDefault();
                      var me = $(this);
                      var page_id = $("[name=profile_dash_page]").val();
                      window.open("<?php echo home_url("/?p=");?>"+page_id);
                    });
                    $(document).on("click tap", ".edit-page", function(e){
                      e.preventDefault();
                      var me = $(this);
                      var page_id = $("[name=profile_dash_page]").val();
                      window.open("<?php echo admin_url("post.php?action=edit&post=");?>"+page_id);
                    });
                    $(document).on("click tap", ".edit-elementor", function(e){
                      e.preventDefault();
                      var me = $(this);
                      var page_id = $("[name=profile_dash_page]").val();
                      window.open("<?php echo admin_url("post.php?action=elementor&post=");?>"+page_id);
                    });
                    $(".dashpagetemplatenotice").<?php echo esc_html( $show_alert ? "show" : "hide" );?>();
                  })(jQuery);
                </script>
              <!-- Show Welcome message -->
              <tr>
                <td><?php echo esc_html_x("Show Welcome message","profile-section", "peprodev-ups");?></td>
                <td>
                  <a class='btncheckbox'
                  data-text-on='<?php echo esc_html_x("Yes, Show it","profile-section", "peprodev-ups");?>'
                  data-text-off='<?php echo esc_html_x("No, Hide it","profile-section", "peprodev-ups");?>'
                  data-on='check_box'
                  data-off='check_box_outline_blank'
                  data-checked='<?php echo esc_attr( get_option("{$this->activation_status}-showwelcome","true") === "true" ? "true" : "false" );?>' id="showwelcome"></a>
                </td>
              </tr>
              <?php if ($this->_wc_activated()){ ?>
                <!-- Show WooCommerce Stats -->
                <tr>
                  <td><?php echo esc_html_x("Show WooCommerce Stats","profile-section", "peprodev-ups");?></td>
                  <td>
                    <a class='btncheckbox'
                    data-text-on='<?php echo esc_html_x("Yes, Show it","profile-section", "peprodev-ups");?>'
                    data-text-off='<?php echo esc_html_x("No, Hide it","profile-section", "peprodev-ups");?>'
                    data-on='check_box'
                    data-off='check_box_outline_blank'
                    data-checked='<?php echo esc_attr( get_option("{$this->activation_status}-woocommercestats","true") === "true" ? "true" : "false" );?>' id="woocommercestats"></a>
                  </td>
                </tr>
                <!-- Show WooCommerce Orders -->
                <tr>
                  <td><?php echo esc_html_x("Show WooCommerce Orders","profile-section", "peprodev-ups");?></td>
                  <td>
                    <a class='btncheckbox'
                    data-text-on='<?php echo esc_html_x("Yes, Show it","profile-section", "peprodev-ups");?>'
                    data-text-off='<?php echo esc_html_x("No, Hide it","profile-section", "peprodev-ups");?>'
                    data-on='check_box'
                    data-off='check_box_outline_blank'
                    data-checked='<?php echo esc_attr( get_option("{$this->activation_status}-woocommerceorders","true") === "true" ? "true" : "false" );?>' id="woocommerceorders"></a>
                  </td>
                </tr>
              <?php } ?>
              <!-- Show Custom Content -->
              <tr>
                <td><?php echo esc_html_x("Show Custom Content","profile-section", "peprodev-ups");?></td>
                <td>
                  <a class='btncheckbox'
                  data-text-on='<?php echo esc_html_x("Yes, Show it","profile-section", "peprodev-ups");?>'
                  data-text-off='<?php echo esc_html_x("No, Hide it","profile-section", "peprodev-ups");?>'
                  data-on='check_box'
                  data-off='check_box_outline_blank'
                  data-togglel='[showoncustomcontent]'
                  data-checked='<?php echo esc_attr( get_option("{$this->activation_status}-showcustomtext","true") === "true" ? "true" : "false" );?>' id="showcustomtext"></a>
                </td>
              </tr>
              <tr showoncustomcontent>
                <td><?php echo esc_html_x("Content Position","profile-section", "peprodev-ups");?></td>
                <td>
                  <select id="customposition">
                    <?php echo "<option " . selected(get_option("{$this->activation_status}-customposition","p2"),"p1",false)." value='p1'>" . esc_html_x("Before Welcome message","profile-section","peprodev-ups") . "</option>";?>
                    <?php echo "<option " . selected(get_option("{$this->activation_status}-customposition","p2"),"p2",false)." value='p2'>" . esc_html_x("After Welcome message","profile-section","peprodev-ups") . "</option>";?>
                    <?php echo "<option " . selected(get_option("{$this->activation_status}-customposition","p2"),"p3",false)." value='p3'>" . esc_html_x("After WooCommerce Stats","profile-section","peprodev-ups") . "</option>";?>
                    <?php echo "<option " . selected(get_option("{$this->activation_status}-customposition","p2"),"p4",false)." value='p4'>" . esc_html_x("After WooCommerce Orders","profile-section","peprodev-ups") . "</option>";?>
                  </select>
                </td>
              </tr>
              <tr showoncustomcontent>
                  <td colspan="2">
                    <?php
                      wp_editor(
                          stripslashes(get_option("{$this->activation_status}-customhtml")),
                          "{$this->activation_status}-customhtml",
                          array(
                          'textarea_name'    => "{$this->activation_status}-customhtml",
                          'editor_height'    => 350,
                          'editor_css'       => '',     // Additional CSS styling applied for both visual and HTML editors buttons, needs to include <style> tags, can use "scoped"
                          'editor_class'     => '',     // Any extra CSS Classes to append to the Editor textarea
                          'teeny'            => false,  // Whether to output the minimal editor configuration used in PressThis
                          'dfw'              => false,  // Whether to replace the default fullscreen editor with DFW (needs specific DOM elements and CSS)
                          'media_buttons'    => true,   // Whether to display media insert/upload buttons
                          'wpautop'          => false,   // Whether to use wpautop for adding in paragraphs. Note that the paragraphs are added automatically when wpautop is false.
                          'tinymce'          => true,   // Load TinyMCE, can be used to pass settings directly to TinyMCE using an array
                          'quicktags'        => true,   // Load Quicktags, can be used to pass settings directly to Quicktags using an array. Set to false to remove your editor's Visual and Text tabs.
                          'drag_drop_upload' => true,   // Enable Drag & Drop Upload Support (since WordPress 3.9)
                          )
                      );
                      ?>
                  </td>
              </tr>

            </tbody>
        </table>
        <div class="flex flex-sb hide">
          <button type="button" style="padding: 12px; display: inline-block; width: 49%;" id="profile-section-logo-new" data-title="<?php echo esc_html_x("Upload Theme Zip file","profile-section","peprodev-ups");?>" class="btn btn-primary icn-btn"><i class='material-icons'>cloud_upload</i> <?php echo esc_html_x("Upload New Style","profile-section", "peprodev-ups")?></button>
          <button type="button" style="padding: 12px; display: inline-block; width: 49%;" id="profile-section-logo-del" class="btn btn-primary icn-btn"><i class='material-icons'>delete_forever</i> <?php echo esc_html_x("Delete Style","profile-section", "peprodev-ups")?></button>
        </div>
        <button type="button" id="profile-section-save" class="login-section-save btn btn-primary icn-btn btn-wide" integrity="<?php echo esc_attr(wp_create_nonce('peprocorenounce'));?>" wparam="profile" lparam="save_setting" dparam="" fn=""><i class='material-icons'>save</i> <?php echo esc_html_x("Save Settings","profile-section", "peprodev-ups");?></button>

      </div>
    </div>

  </div>
  <div class="col-lg-6 col-md-6">
    <div class="card">
        <div class="card-header card-header-primary">
          <h4 class="card-title"><?php echo esc_html_x("Front-end Customization", "profile-section", "peprodev-ups");?></h4>
          <p class="card-category"><?php echo esc_html_x("You can add your custom javascript or css content blow to load on every pages.", "profile-section", "peprodev-ups");?></p>
        </div>
        <div class="card-body table-responsive">
          <table class="table pepcappearance">
            <thead class=""></thead>
            <tbody>
              <tr>
                <td colspan="2">
                  <?php echo esc_html_x("Global CSS","profile-section", "peprodev-ups");?>
                  <textarea name="<?php echo "{$this->activation_status}-css";?>" class="codeditor" id="csseditor" spellcheck="false" dir="ltr" rows="8" cols="80"><?php echo html_entity_decode(stripslashes(get_option("{$this->activation_status}-css")));?></textarea>
                  <textarea name="<?php echo "{$this->activation_status}-css";?>" style="display:none !important;" class="codeditor" id="css" spellcheck="false" dir="ltr" rows="8" cols="80"><?php echo html_entity_decode(stripslashes(get_option("{$this->activation_status}-css")));?></textarea>
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  <?php echo esc_html_x("Global JS","profile-section", "peprodev-ups");?>
                  <textarea name="<?php echo "{$this->activation_status}-css";?>" class="codeditor" id="jseditor" spellcheck="false" dir="ltr" rows="8" cols="80"><?php echo html_entity_decode(stripslashes(get_option("{$this->activation_status}-js")));?></textarea>
                  <textarea name="<?php echo "{$this->activation_status}-js";?>" style="display:none !important;" class="codeditor" id="js" spellcheck="false" dir="ltr" rows="8" cols="80"><?php echo html_entity_decode(stripslashes(get_option("{$this->activation_status}-js")));?></textarea>
                </td>
              </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
