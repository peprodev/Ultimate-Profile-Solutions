<?php
/*
 * @Author: Amirhossein Hosseinpour <https://amirhp.com>
 * @Last modified by: amirhp-com <its@amirhp.com>
 * @Last modified time: 2024/08/21 00:45:03
 */

global $PeproDevUPS_Profile;
$rtl         = is_rtl() ? "right" : "left";
$page        = (int) isset($_GET['cpage']) ? sanitize_text_field($_GET['cpage']) : 1;
$page        = abs($page);
$srch        = isset($_GET['s']) ? sanitize_text_field(trim($_GET['s'])) : "";
$integrity   = wp_create_nonce('peprocorenounce');
$trUrgent    = _x("Urgent", "notifications-priority", "peprodev-ups");
$trHigh      = _x("High", "notifications-priority", "peprodev-ups");
$trMedium    = _x("Medium", "notifications-priority", "peprodev-ups");
$trLow       = _x("Low", "notifications-priority", "peprodev-ups");
$trNormal    = _x("Normal", "notifications-priority", "peprodev-ups");
$trRed       = _x("Red", "section-panel", "peprodev-ups");
$trOrange    = _x("Orange", "section-panel", "peprodev-ups");
$trBlue      = _x("Blue", "section-panel", "peprodev-ups");
$trGreen     = _x("Green", "section-panel", "peprodev-ups");
$trDark      = _x("Dark", "section-panel", "peprodev-ups");
$loadingRing = '<div class="lds-ring2"><div></div><div></div><div></div><div></div></div>';
?>
<div class="fa-set icon-set fontawesome-icon-picker"> <ul> <?php echo $PeproDevUPS_Profile->get_fontawesomepro_class_list();?> </ul> </div>
<div class="row">
  <div class="col-lg-12 col-md-12">
    <div class="card">
      <div class="card-header card-header-primary">
        <div class="lds-ring2"><div></div><div></div><div></div><div></div></div>
        <h4 class="card-title"><?php echo esc_html_x("Profile Sections", "section-panel", "peprodev-ups");?></h4>
        <p class="card-category"><?php echo esc_html_x("You can manage sections from here. Add, Delete, and Edit sections on the fly using panle below.", "section-panel", "peprodev-ups");?></p>
      </div>
      <div class="card-body">
        <div id="toggle_search_container" class="hide">
          <div class="input-group search mb-3">
            <input class="search-here form-control" type="text" id="search-here" placeholder="<?php echo esc_html_x("Search here and hit Enter ...", "section-panel", "peprodev-ups");?>" style="padding-inline: 0.7rem;" integrity="<?php echo $integrity;?>" wparam="<?php echo $PeproDevUPS_Profile->setting_slug;?>" lparam="search_section" value="<?php echo $srch;?>" />
            <div class="input-group-append">
              <button class="btn btn-primary clear_search btn-sm" title="<?php echo esc_html_x("Clear search", "section-panel", "peprodev-ups");?>"><i class="material-icons">close</i></button>
            </div>
        </div>
        </div>
          <p>
            <button class="btn btn-primary add_notifc loadingRings" id="add_notifpopup" href="#"><?php echo $loadingRing . _x("Add New", "section-panel", "peprodev-ups");?></button>
            <button class="btn btn-primary toggle_builtin loadingRings" id="toggle_builtin" href="#"><?php echo $loadingRing . _x("Toggle Built-in", "section-panel", "peprodev-ups");?></button>
            <button class="btn btn-primary toggle_search loadingRings" id="toggle_search" href="#"><?php echo $loadingRing . _x("Search", "section-panel", "peprodev-ups");?></button>
          </p>
          <div class="hide mb-4" id="add_new_notifications">
            <div id="modal_add_notif">
              <div>
                <div class="modal-header">
                  <h5 class="modal-title addmode"><?php echo esc_html_x("Add New Section", "section-panel", "peprodev-ups");?></h5>
                  <h5 class="modal-title editmode"><?php echo esc_html_x("Edit Section: ", "section-panel", "peprodev-ups");?><span style="font-weight: bold;"></span></h5>
                  <input type="hidden" id="current_edit_notif_id" value="" />
                </div>
                <div class="modal-body">
                  <table class="table" id="add_new">
                    <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                    <tbody>
                    <?php

                      $t  = _x("Menu Label", "section-panel", "peprodev-ups");
                      $tt = sprintf(_x("Enter %s", "section-panel", "peprodev-ups"), $t);
                      $PeproDevUPS_Profile->add_notif_input( "is-required", "notifaddedit-title", $t, $tt );

                      $t  = _x("Unique Slug", "section-panel", "peprodev-ups");
                      $tt = sprintf(_x("Enter %s, or @page_slug / #page_id", "section-panel", "peprodev-ups"), $t);
                      $PeproDevUPS_Profile->add_notif_input( "is-required", "notifaddedit-slug", $t, $tt, "text", "force-ltr slug-input", "", "dir=ltr" );

                      $t  = _x("Subject", "section-panel", "peprodev-ups");
                      $tt = sprintf(_x("Enter %s", "section-panel", "peprodev-ups"), $t);
                      $PeproDevUPS_Profile->add_notif_input( "not-required", "notifaddedit-subject", $t, $tt );

                      $t  = _x("Content", "section-panel", "peprodev-ups");
                      $tt = sprintf(_x("Enter %s", "section-panel", "peprodev-ups"), $t);
                      $PeproDevUPS_Profile->add_notif_editor( "is-required", "notifaddedit-content", $t, $tt );

                      $title  = _x("Icon", "section-panel", "peprodev-ups");
                      $titles = _x("Pick icon (provided by FontAwesome)", "section-panel", "peprodev-ups");
                      echo "<tr is-required><td><label class=\"text-primary\" for=\"notifaddedit-icon\">$title</label></td>
                              <td><div class=\"icon-picker\" data-pickerid=\"fa\" data-iconsets='{\"fa\":\"$titles\"}'>
                                <input id=\"notifaddedit-icon\" type=\"hidden\" /></div>
                              </td></tr>";

                      $title  = _x("Icon-Image", "section-panel", "peprodev-ups");
                      $titles = _x("Set Image as Icon (overwrite)", "section-panel", "peprodev-ups");
                      $PeproDevUPS_Profile->add_notif_input("not-required","notifaddedit-img", $title, $titles, "text", "", "", "");

                      $title  = _x("Priority (number)", "section-panel", "peprodev-ups");
                      $toltip = sprintf(_x("Enter %s", "section-panel", "peprodev-ups"), $title);
                      $PeproDevUPS_Profile->add_notif_input("is-required","notifaddedit-priority",$title, $toltip,"number","",300,"min='0'");

                      $Yes = _x("Hide Advanced Setting","profile-section", "peprodev-ups");
                      $No  = _x("Show Advanced Setting","profile-section", "peprodev-ups");
                      echo "<tr><td colspan='2'>
                              <a
                                class='btncheckbox'
                                type='checkbox'
                                data-text-on='$Yes'
                                data-text-off='$No'
                                data-on='remove_circle_outline'
                                data-off='add_circle_outline'
                                data-togglel='[showadvanced]'
                                data-checked='false'
                              ></a>
                          </td>
                        </tr>";


                      $titke = _x("Section is active?", "section-panel", "peprodev-ups");
                      $title = _x("Check to activate section and show it on front-end", "section-panel", "peprodev-ups");
                      echo " <tr showadvanced>
                        <td>
                        <label class=\"text-primary\" for=\"notifaddedit-active-check\">$titke</label>
                        </td>
                        <td>
                        <div class='form-check'>
                        <label class='form-check-label'>
                        <input class='form-check-input' type='checkbox'checked id='notifaddedit-active-check'>
                        <span class='form-check-sign'>
                        <span class='check'></span></span> $title
                        </label>
                        </div>
                        </td>
                      </tr>";

                      $title = _x("Restrict Access to User Roles", "notif-panel", "peprodev-ups");
                      $users = '<select id="notifaddedit-access" name="notifaddedit-access[]" multiple="true" class="form-control primary select2 mt-3">';
                      echo "<tr showadvanced><td ><label class=\"text-primary\" for=\"notifaddedit-access\">$title</label></td>
                      <td>$users"; wp_dropdown_roles(); echo "</select><br><small>".__("Select none to show section for everyone (make public)","peprodev-ups")."</small></td></tr>";

                      $sfws_posts = '<option value="0" selected>'.__("— None","peprodev-ups").'</option>';
                      $posts = get_posts(array('post_type'=> 'sfwd-courses', 'post_status'=> 'publish', 'suppress_filters' => false, 'posts_per_page' => -1 ) );
                      foreach ($posts as $post) { $sfws_posts .= "<option value='$post->ID'>$post->post_title (#$post->ID)</option>"; }
                      $title = _x("Restrict Access to Ld-Course", "notif-panel", "peprodev-ups");
                      echo "<tr showadvanced>
                              <td ><label class=\"text-primary\" for=\"notifaddedit-ld_lms\">$title</label></td><td>".'
                                <select id="notifaddedit-ld_lms" name="notifaddedit-ld_lms" class="form-control primary select2 mt-3">'.$sfws_posts.'</select>';
                      echo "<br><small>".__("Restrict Access to Ld-Course enrolled users in addition to User Roles","peprodev-ups")."</small></td></tr>";

                      echo "<tr showadvanced><td><label class=\"text-primary\" >"._x("Custom CSS","profile-section","peprodev-ups")."</label></td><td>
                            <textarea class=\"codeditor\" id=\"csseditor\" spellcheck=\"false\" dir=\"ltr\" rows=\"8\" cols=\"80\">".html_entity_decode(stripslashes(get_option("{$this->activation_status}-css")))."</textarea>
                            <textarea style=\"display:none !important;\" class=\"codeditor\" id=\"css\" spellcheck=\"false\" dir=\"ltr\" rows=\"8\" cols=\"80\">".html_entity_decode(stripslashes(get_option("{$this->activation_status}-css")))."</textarea>
                          </td></tr>";
                      echo "<tr showadvanced><td><label class=\"text-primary\" >"._x("Custom JS","profile-section","peprodev-ups")."</label></td><td>
                            <textarea class=\"codeditor\" id=\"jseditor\" spellcheck=\"false\" dir=\"ltr\" rows=\"8\" cols=\"80\">".html_entity_decode(stripslashes(get_option("{$this->activation_status}-js","(function ($){\"use strict\";})(jQuery);")))."</textarea>
                            <textarea style=\"display:none !important;\" class=\"codeditor\" id=\"js\" spellcheck=\"false\" dir=\"ltr\" rows=\"8\" cols=\"80\">".html_entity_decode(stripslashes(get_option("{$this->activation_status}-js")))."</textarea>
                          </td></tr>";
                      ?>
                  </tbody>
                  </table>
                </div>
                <div class="modal-footer">
                  <button
                    type="button"
                    id="add_notif"
                    class="add_edit_save_notification btn btn-primary loadingRings"
                    integrity="<?php echo esc_attr( $integrity );?>"
                    wparam="<?php echo esc_attr( $PeproDevUPS_Profile->setting_slug );?>"
                    lparam="add_new_section"><?php echo $loadingRing . _x("Add New", "section-panel", "peprodev-ups");?></button>
                  <button
                    type="button"
                    id="edit_notif"
                    class="add_edit_save_notification btn btn-primary loadingRings"
                    integrity="<?php echo esc_attr( $integrity );?>"
                    wparam="<?php echo esc_attr( $PeproDevUPS_Profile->setting_slug );?>"
                    lparam="add_new_section"><?php echo $loadingRing . _x("Save Edits", "section-panel", "peprodev-ups");?></button>
                  <button type="button" id="clear_notif_form" class="btn btn-action"><?php echo esc_html_x("Clear form", "section-panel", "peprodev-ups");?></button>
                  <button type="button" id='close_add_new_notifications' class="btn btn-action" data-dismiss="modal"><?php echo esc_html_x("Close", "section-panel", "peprodev-ups");?></button>
                </div>
              </div>
            </div>
          </div>
        <div class="toggle_view_form">
          <table class="table table-hover" id="notifications_list_table">
          <thead class="text-primary">
            <tr>
              <th><?php echo esc_html_x("Date", "section-panel", "peprodev-ups");?></th>
              <th><?php echo esc_html_x("Title", "section-panel", "peprodev-ups");?></th>
              <th><?php echo esc_html_x("Subject", "section-panel", "peprodev-ups");?></th>
              <th><?php echo esc_html_x("Slug", "section-panel", "peprodev-ups");?></th>
              <th><?php echo esc_html_x("Access Roles", "section-panel", "peprodev-ups");?></th>
              <th><?php echo esc_html_x("Priority", "section-panel", "peprodev-ups");?></th>
              <th><?php echo esc_html_x("Action", "section-panel", "peprodev-ups");?></th>
            </tr>
          </thead>
          <tbody>
            <?php
              echo $PeproDevUPS_Profile->show_sections_edit_panel($page, esc_html($srch));
            ?>
          </tbody>
        </table>
        </div>

      </div>

    </div>
  </div>
</div>

<div class="modal fade" data-backdrop="false" data-keyboard="true" data-focus="true" id="del_notifications" tabindex="-1" role="dialog" aria-labelledby="del_notifications" aria-hidden="true">
  <div class="modal-dialog" id="modal_add_notif" role="document" style="max-width: 60%;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="del_notifications"><?php echo esc_html_x("Permanently Remove Sections", "section-panel", "peprodev-ups");?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
      </div>
      <div class="modal-body">
        <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
        <p>
          <?php echo _x("Are you sure you want to permanently remove this section?<br>There is no Undo action available.", "section-panel", "peprodev-ups");?>
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-action" data-dismiss="modal"><?php echo esc_html_x("Cancel", "section-panel", "peprodev-ups");?></button>
        <button type="button" id="remove_section" class="btn btn-primary loadingRings"><?php echo $loadingRing . _x("Yes, Remove", "section-panel", "peprodev-ups");?></button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" data-backdrop="false" data-keyboard="true" data-focus="true" id="edit_section_builtin" tabindex="-1" role="dialog" aria-labelledby="edit_section_builtin" aria-hidden="true">
  <div class="modal-dialog" id="edit_section_built" role="document" style="max-width: 60%;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="edit_section_builtin_title"><?php echo esc_html_x("Edit Built-in Section: ", "section-panel", "peprodev-ups");?><span></span></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
      </div>
      <div class="modal-body">
        <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
        <p>
          <table class="table">

          <?php

          $title = _x("Priority (number)", "section-panel", "peprodev-ups");
          $toltip = sprintf(_x("Enter %s", "section-panel", "peprodev-ups"), $title);
          $PeproDevUPS_Profile->add_notif_input("","section_builtin_priority",$title,$toltip,"number","",1000,"min='0'");

          $titke = _x("Section is active?", "section-panel", "peprodev-ups");
          $title = _x("Check to activate section and show it on front-end", "section-panel", "peprodev-ups");
          echo " <tr>
              <td>
                <label class=\"text-primary\" for=\"section_builtin_active_check\">$titke</label>
              </td>
              <td>
                <div class='form-check'>
                  <label class='form-check-label'>
                    <input class='form-check-input' type='checkbox'checked id='section_builtin_active_check'>
                    <span class='form-check-sign'>
                      <span class='check'></span></span> $title
                  </label>
                </div>
              </td>
            </tr>";

          ?>
        </table>
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-action" data-dismiss="modal"><?php echo esc_html_x("Cancel", "section-panel", "peprodev-ups");?></button>
        <button type="button" id="save_built_in_edit" class="btn btn-primary loadingRings"><?php echo $loadingRing . _x("Save Changes", "section-panel", "peprodev-ups");?></button>
      </div>
    </div>
  </div>
</div>
