<?php
# @Last modified by:   Amirhosseinhpv
# @Last modified time: 2021/08/28 22:55:27
?>
<div class="row">
  <div class="col-lg-6 col-md-6">
    <div class="card">
      <div class="card-header card-header-primary">
        <h4 class="card-title"><?=_x("Profile Dashboard","profile-section",$this->td);?></h4>
        <p class="card-category"><?=_x("You can control profile front-end style from here.","profile-section",$this->td);?></p>
      </div>

      <div class="card-body table-responsive">

        <table class="table pepcappearance table-striped">
          <thead class=""></thead>
          <tbody>

              <!-- Profile Logo Image -->
              <tr>
                <td><?=_x("Profile Logo Image","profile-section", $this->td);?></td>
                <td>
                  <input type="hidden" data-id="<?=get_option("{$this->activation_status}-logo-id","");?>" id="profile-section-logo" value="<?=get_option("{$this->activation_status}-logo", $this->icon);?>" class="form-control primary" placeholder="<?=_x("Logo URL","profile-section",$this->td);?>" />
                  <div class="flex flex-sb">
                    <img style="border-radius: 5px;" src="<?=get_option("{$this->activation_status}-logo","");?>" id="profile-img" width="86px"/>
                    <button type="button" id="selectlogoimg" style="padding: 12px; display: block;" class="btn btn-primary mediapicker icn-btn"
                    data-ref="#profile-section-logo"
                    data-ref4="#profile-img"
                    data-title="<?=_x("Select Custom Image","profile-section", $this->td);?>"
                    ><i class='material-icons'>cloud_upload</i> <?=_x("Select or Upload Custom Image","profile-section", $this->td);?></button>
                  </div>
                </td>
              </tr>
              <!-- Dashboard page -->
              <tr>
                <td><?=_x("Dashboard page","profile-section", $this->td);?></td>
                <td>
                  <?php
                    $dashpage = get_option("{$this->activation_status}-profile-dash-page","");
                    $dropdown_args = array(
                      'selected'         => $dashpage,
                      'name'             => 'profile_dash_page',
                      'sort_column'      => 'menu_order, post_title',
                    );
                    $pages = wp_dropdown_pages( $dropdown_args );

                    $notify_user_of_page_template = sprintf(
                      __("Current selected dashboard page's template should be 'PeproDev Ultimate Profile Solutions — Profile'. %s",$this->td),
                      "<a class='btm btn btn-sm btn-danger' href='".
                      admin_url("post.php?post=$dashpage&action=edit#page_template")."' target='_blank'>".
                      __("Edit",$this->td)."</a>"
                    );

                    $slug = get_page_template_slug($dashpage);
                    $len = strlen("peprofile-");
                    $show_alert = false;
                    if((substr("$slug", 0, $len) !== "peprofile-")) {
                      $show_alert = true;
                    }

                   ?>
                </td>

              </tr>
                <?="<div class='alert dashpagetemplatenotice alert-inverse'>$notify_user_of_page_template</div>";?>
                <script type="text/javascript">
                  (function($) {
                    $(".dashpagetemplatenotice").<?=$show_alert?"show":"hide";?>();
                  })(jQuery);
                </script>
              <!-- Show Welcome message -->
              <tr>
                <td><?=_x("Show Welcome message","profile-section", $this->td);?></td>
                <td>
                  <a class='btncheckbox'
                  data-text-on='<?=_x("Yes, Show it","profile-section", $this->td);?>'
                  data-text-off='<?=_x("No, Hide it","profile-section", $this->td);?>'
                  data-on='check_box'
                  data-off='check_box_outline_blank'
                  data-checked='<?=get_option("{$this->activation_status}-showwelcome","true") === "true" ? "true" : "false";?>' id="showwelcome"></a>
                </td>
              </tr>
              <?php if ($this->_wc_activated()){ ?>
                <!-- Show WooCommerce Stats -->
                <tr>
                  <td><?=_x("Show WooCommerce Stats","profile-section", $this->td);?></td>
                  <td>
                    <a class='btncheckbox'
                    data-text-on='<?=_x("Yes, Show it","profile-section", $this->td);?>'
                    data-text-off='<?=_x("No, Hide it","profile-section", $this->td);?>'
                    data-on='check_box'
                    data-off='check_box_outline_blank'
                    data-checked='<?=get_option("{$this->activation_status}-woocommercestats","true") === "true" ? "true" : "false";?>' id="woocommercestats"></a>
                  </td>
                </tr>
                <!-- Show WooCommerce Orders -->
                <tr>
                  <td><?=_x("Show WooCommerce Orders","profile-section", $this->td);?></td>
                  <td>
                    <a class='btncheckbox'
                    data-text-on='<?=_x("Yes, Show it","profile-section", $this->td);?>'
                    data-text-off='<?=_x("No, Hide it","profile-section", $this->td);?>'
                    data-on='check_box'
                    data-off='check_box_outline_blank'
                    data-checked='<?=get_option("{$this->activation_status}-woocommerceorders","true") === "true" ? "true" : "false";?>' id="woocommerceorders"></a>
                  </td>
                </tr>
              <?php } ?>
              <!-- Show Custom Content -->
              <tr>
                <td><?=_x("Show Custom Content","profile-section", $this->td);?></td>
                <td>
                  <a class='btncheckbox'
                  data-text-on='<?=_x("Yes, Show it","profile-section", $this->td);?>'
                  data-text-off='<?=_x("No, Hide it","profile-section", $this->td);?>'
                  data-on='check_box'
                  data-off='check_box_outline_blank'
                  data-togglel='[showoncustomcontent]'
                  data-checked='<?=get_option("{$this->activation_status}-showcustomtext","true") === "true" ? "true" : "false";?>' id="showcustomtext"></a>
                </td>
              </tr>
              <tr showoncustomcontent>
                <td><?=_x("Content Position","profile-section", $this->td);?></td>
                <td>
                  <select id="customposition">
                    <?="<option " . selected(get_option("{$this->activation_status}-customposition","p2"),"p1",false)." value='p1'>" . _x("Before Welcome message","profile-section",$this->td) . "</option>";?>
                    <?="<option " . selected(get_option("{$this->activation_status}-customposition","p2"),"p2",false)." value='p2'>" . _x("After Welcome message","profile-section",$this->td) . "</option>";?>
                    <?="<option " . selected(get_option("{$this->activation_status}-customposition","p2"),"p3",false)." value='p3'>" . _x("After WooCommerce Stats","profile-section",$this->td) . "</option>";?>
                    <?="<option " . selected(get_option("{$this->activation_status}-customposition","p2"),"p4",false)." value='p4'>" . _x("After WooCommerce Orders","profile-section",$this->td) . "</option>";?>
                  </select>
                </td>
              </tr>
              <tr showoncustomcontent>
                  <td colspan="2">
                    <?php
                      wp_editor(
                          stripslashes(get_option("{$this->activation_status}-customhtml")), "{$this->activation_status}-customhtml",
                          array(
                          'textarea_name'    => "{$this->activation_status}-customhtml",
                          'editor_height'    => 350,
                          'editor_css'       => '',     // Additional CSS styling applied for both visual and HTML editors buttons, needs to include <style> tags, can use "scoped"
                          'editor_class'     => '',     // Any extra CSS Classes to append to the Editor textarea
                          'teeny'            => false,  // Whether to output the minimal editor configuration used in PressThis
                          'dfw'              => false,  // Whether to replace the default fullscreen editor with DFW (needs specific DOM elements and CSS)
                          'media_buttons'    => true,   // Whether to display media insert/upload buttons
                          'wpautop'          => true,   // Whether to use wpautop for adding in paragraphs. Note that the paragraphs are added automatically when wpautop is false.
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
          <button type="button" style="padding: 12px; display: inline-block; width: 49%;" id="profile-section-logo-new" data-title="<?=_x("Upload Theme Zip file","profile-section",$this->td);?>" class="btn btn-primary icn-btn"><i class='material-icons'>cloud_upload</i> <?=_x("Upload New Style","profile-section", $this->td)?></button>
          <button type="button" style="padding: 12px; display: inline-block; width: 49%;" id="profile-section-logo-del" class="btn btn-primary icn-btn"><i class='material-icons'>delete_forever</i> <?=_x("Delete Style","profile-section", $this->td)?></button>
        </div>
        <button type="button" id="profile-section-save" class="login-section-save btn btn-primary icn-btn btn-wide" integrity="<?=wp_create_nonce('peprocorenounce')?>" wparam="profile" lparam="save_setting" dparam="" fn=""><i class='material-icons'>save</i> <?=_x("Save Settings","profile-section", $this->td);?></button>

      </div>
    </div>

  </div>
  <div class="col-lg-6 col-md-6">
    <div class="card">
        <div class="card-header card-header-primary">
          <h4 class="card-title"><?php echo _x("Front-end Customization", "profile-section", $this->td);?></h4>
          <p class="card-category"><?php echo _x("You can add your custom javascript or css content blow to load on every pages.", "profile-section", $this->td);?></p>
        </div>
        <div class="card-body table-responsive">
          <table class="table pepcappearance">
            <thead class=""></thead>
            <tbody>
              <tr>
                <td colspan="2">
                  <?=_x("Global CSS","profile-section", $this->td);?>
                  <textarea name="<?="{$this->activation_status}-css";?>" class="codeditor" id="csseditor" spellcheck="false" dir="ltr" rows="8" cols="80"><?=stripslashes(get_option("{$this->activation_status}-css",""));?></textarea>
                  <textarea name="<?="{$this->activation_status}-css";?>" style="display:none !important;" class="codeditor" id="css" spellcheck="false" dir="ltr" rows="8" cols="80"><?=stripslashes(get_option("{$this->activation_status}-css",""));?></textarea>
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  <?=_x("Global JS","profile-section", $this->td);?>
                  <textarea name="<?="{$this->activation_status}-css";?>" class="codeditor" id="jseditor" spellcheck="false" dir="ltr" rows="8" cols="80"><?=stripslashes(get_option("{$this->activation_status}-js",""));?></textarea>
                  <textarea name="<?="{$this->activation_status}-js";?>" style="display:none !important;" class="codeditor" id="js" spellcheck="false" dir="ltr" rows="8" cols="80"><?=stripslashes(get_option("{$this->activation_status}-js",""));?></textarea>
                </td>
              </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
