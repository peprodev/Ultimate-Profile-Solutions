<?php
# @Last modified by:   Amirhosseinhpv
# @Last modified time: 2021/08/29 00:33:20

global $PeproDevUPS_Profile;
$td = $PeproDevUPS_Profile->td;
$rtl = is_rtl() ? "right" : "left";
$page = isset($_GET['cpage']) ? abs((int) $_GET['cpage']) : 1;
$srch = isset($_GET['s']) ? sanitize_text_field(esc_html(trim($_GET['s']))) : "";
$integrity = wp_create_nonce('peprocorenounce');
$trUrgent =_x("Urgent", "notifications-priority", "pepro");
$trHigh =_x("High", "notifications-priority", "pepro");
$trMedium =_x("Medium", "notifications-priority", "pepro");
$trLow =_x("Low", "notifications-priority", "pepro");
$trNormal =_x("Normal", "notifications-priority", "pepro");
$trRed =_x("Red", "section-panel", "pepro");
$trOrange =_x("Orange", "section-panel", "pepro");
$trBlue =_x("Blue", "section-panel", "pepro");
$trGreen =_x("Green", "section-panel", "pepro");
$trDark =_x("Dark", "section-panel", "pepro");
$loadingRing = '<div class="lds-ring2"><div></div><div></div><div></div><div></div></div>';

?>

<div class="fa-set icon-set fontawesome-icon-picker"> <ul> <?php echo $PeproDevUPS_Profile->get_fontawesomepro_class_list();?> </ul> </div>


<div class="row">
  <div class="col-lg-12 col-md-12">
    <div class="card">
      <div class="card-header card-header-primary">
        <div class="lds-ring2"><div></div><div></div><div></div><div></div></div>
        <h4 class="card-title"><?php echo _x("Profile Sections", "section-panel", "pepro");?></h4>
        <p class="card-category"><?php echo _x("You can manage sections from here. Add, Delete, and Edit sections on the fly using panle below.", "section-panel", "pepro");?></p>
      </div>
      <div class="card-body">
        <div id="toggle_search_container" class="hide">
          <div class="input-group search mb-3">
            <input class="search-here form-control" type="text" id="search-here" placeholder="<?php echo _x("Search here and hit Enter ...", "section-panel", "pepro");?>" style="padding-inline: 0.7rem;" integrity="<?php echo $integrity;?>" wparam="<?php echo $PeproDevUPS_Profile->setting_slug;?>" lparam="search_section" value="<?php echo $srch;?>" />
            <div class="input-group-append">
              <button class="btn btn-primary clear_search btn-sm" title="<?php echo _x("Clear search", "section-panel", "pepro");?>"><i class="material-icons">close</i></button>
            </div>
        </div>
        </div>
          <p>
            <button class="btn btn-primary add_notifc loadingRings" id="add_notifpopup" href="#"><?php echo $loadingRing . _x("Add New", "section-panel", "pepro");?></button>
            <button class="btn btn-primary toggle_builtin loadingRings" id="toggle_builtin" href="#"><?php echo $loadingRing . _x("Toggle Built-in", "section-panel", "pepro");?></button>
            <button class="btn btn-primary toggle_search loadingRings" id="toggle_search" href="#"><?php echo $loadingRing . _x("Search", "section-panel", "pepro");?></button>
          </p>
          <div class="hide mb-4" id="add_new_notifications">
            <div id="modal_add_notif">
              <div>
                <div class="modal-header">
                  <h5 class="modal-title addmode"><?php echo _x("Add New Section", "section-panel", "pepro");?></h5>
                  <h5 class="modal-title editmode"><?php echo _x("Edit Section: ", "section-panel", "pepro");?><span style="font-weight: bold;"></span></h5>
                  <input type="hidden" id="current_edit_notif_id" value="" />
                </div>
                <div class="modal-body">
                  <table class="table" id="add_new">
                    <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                    <tbody>
                    <?php

                      $t = _x("Menu Label", "section-panel", "pepro");
                      $tt = sprintf(_x("Enter %s", "section-panel", "pepro"), $t);
                      $PeproDevUPS_Profile->add_notif_input( "is-required", "notifaddedit-title", $t, $tt );

                      $t = _x("Unique Slug", "section-panel", "pepro");
                      $tt = sprintf(_x("Enter %s", "section-panel", "pepro"), $t);
                      $PeproDevUPS_Profile->add_notif_input( "is-required", "notifaddedit-slug", $t, $tt );

                      $t = _x("Subject", "section-panel", "pepro");
                      $tt = sprintf(_x("Enter %s", "section-panel", "pepro"), $t);
                      $PeproDevUPS_Profile->add_notif_input( "is-required", "notifaddedit-subject", $t, $tt );

                      $t = _x("Content", "section-panel", "pepro");
                      $tt = sprintf(_x("Enter %s", "section-panel", "pepro"), $t);
                      $PeproDevUPS_Profile->add_notif_editor( "is-required", "notifaddedit-content", $t, $tt );

                      $title = _x("Icon", "section-panel", "pepro");
                      $titles = _x("Pick icon (provided by FontAwesome)", "section-panel", "pepro");
                      echo "<tr is-required><td><label class=\"text-primary\" for=\"notifaddedit-icon\">$title</label></td>
                              <td><div class=\"icon-picker\" data-pickerid=\"fa\" data-iconsets='{\"fa\":\"$titles\"}'>
                                <input id=\"notifaddedit-icon\" type=\"hidden\" /></div>
                              </td></tr>";

                      $title = _x("Priority (number)", "section-panel", "pepro");
                      $toltip = sprintf(_x("Enter %s", "section-panel", "pepro"), $title);
                      $PeproDevUPS_Profile->add_notif_input("is-required","notifaddedit-priority",$title,$toltip,"number","",300,"min='0'");

                      $Yes = _x("Hide Advanced Setting","profile-section","pepro");
                      $No = _x("Show Advanced Setting","profile-section","pepro");
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


                      $titke = _x("Section is active?", "section-panel", "pepro");
                      $title = _x("Check to activate section and show it on front-end", "section-panel", "pepro");
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

                      $title = _x("Restrict Access to User Roles", "notif-panel", "pepro");
                      $users = '<select id="notifaddedit-access" name="notifaddedit-access[]" multiple="true" class="form-control primary select2 mt-3">';
                      echo "<tr showadvanced><td ><label class=\"text-primary\" for=\"notifaddedit-access\">$title</label></td>
                      <td>$users"; wp_dropdown_roles(); echo "</select><br><small>".__("Select none to show section for everyone (make public)",$this->td)."</small></td></tr>";

                      $sfws_posts = '<option value="0" selected>'.__("— None",$this->td).'</option>';
                      $posts = get_posts(array('post_type'=> 'sfwd-courses', 'post_status'=> 'publish', 'suppress_filters' => false, 'posts_per_page' => -1 ) );
                      foreach ($posts as $post) { $sfws_posts .= "<option value='$post->ID'>$post->post_title (#$post->ID)</option>"; }
                      $title = _x("Restrict Access to Ld-Course", "notif-panel", "pepro");
                      echo "<tr showadvanced>
                              <td ><label class=\"text-primary\" for=\"notifaddedit-ld_lms\">$title</label></td><td>".'
                                <select id="notifaddedit-ld_lms" name="notifaddedit-ld_lms" class="form-control primary select2 mt-3">'.$sfws_posts.'</select>';
                      echo "<br><small>".__("Restrict Access to Ld-Course enrolled users in addition to User Roles",$this->td)."</small></td></tr>";

                      echo "<tr showadvanced><td><label class=\"text-primary\" >"._x("Custom CSS","profile-section",$this->td)."</label></td><td>
                            <textarea class=\"codeditor\" id=\"csseditor\" spellcheck=\"false\" dir=\"ltr\" rows=\"8\" cols=\"80\">".stripslashes(get_option("{$this->activation_status}-css",""))."</textarea>
                            <textarea style=\"display:none !important;\" class=\"codeditor\" id=\"css\" spellcheck=\"false\" dir=\"ltr\" rows=\"8\" cols=\"80\">".stripslashes(get_option("{$this->activation_status}-css",""))."</textarea>
                          </td></tr>";
                      echo "<tr showadvanced><td><label class=\"text-primary\" >"._x("Custom JS","profile-section",$this->td)."</label></td><td>
                            <textarea class=\"codeditor\" id=\"jseditor\" spellcheck=\"false\" dir=\"ltr\" rows=\"8\" cols=\"80\">".stripslashes(get_option("{$this->activation_status}-js","(function ($){\"use strict\";})(jQuery);"))."</textarea>
                            <textarea style=\"display:none !important;\" class=\"codeditor\" id=\"js\" spellcheck=\"false\" dir=\"ltr\" rows=\"8\" cols=\"80\">".stripslashes(get_option("{$this->activation_status}-js",""))."</textarea>
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
                    integrity="<?php echo $integrity;?>"
                    wparam="<?php echo $PeproDevUPS_Profile->setting_slug;?>"
                    lparam="add_new_section"><?php echo $loadingRing . _x("Add New", "section-panel", "pepro");?></button>
                  <button
                    type="button"
                    id="edit_notif"
                    class="add_edit_save_notification btn btn-primary loadingRings"
                    integrity="<?php echo $integrity;?>"
                    wparam="<?php echo $PeproDevUPS_Profile->setting_slug;?>"
                    lparam="add_new_section"><?php echo $loadingRing . _x("Save Edits", "section-panel", "pepro");?></button>
                  <button type="button" id="clear_notif_form" class="btn btn-action"><?php echo _x("Clear form", "section-panel", "pepro");?></button>
                  <button type="button" id='close_add_new_notifications' class="btn btn-action" data-dismiss="modal"><?php echo _x("Close", "section-panel", "pepro");?></button>
                </div>
              </div>
            </div>
          </div>
        <div class="toggle_view_form">
          <table class="table table-hover" id="notifications_list_table">
          <thead class="text-primary">
            <tr>
              <th><?php echo _x("Date", "section-panel", "pepro");?></th>
              <th><?php echo _x("Title", "section-panel", "pepro");?></th>
              <th><?php echo _x("Subject", "section-panel", "pepro");?></th>
              <th><?php echo _x("Slug", "section-panel", "pepro");?></th>
              <th><?php echo _x("Access Roles", "section-panel", "pepro");?></th>
              <th><?php echo _x("Priority", "section-panel", "pepro");?></th>
              <th><?php echo _x("Action", "section-panel", "pepro");?></th>
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
        <h5 class="modal-title" id="del_notifications"><?php echo _x("Permanently Remove Sections", "section-panel", "pepro");?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
      </div>
      <div class="modal-body">
        <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
        <p>
          <?php echo _x("Are you sure you want to permanently remove this section?<br>There is no Undo action available.", "section-panel", "pepro");?>
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-action" data-dismiss="modal"><?php echo _x("Cancel", "section-panel", "pepro");?></button>
        <button type="button" id="remove_section" class="btn btn-primary loadingRings"><?php echo $loadingRing . _x("Yes, Remove", "section-panel", "pepro");?></button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" data-backdrop="false" data-keyboard="true" data-focus="true" id="edit_section_builtin" tabindex="-1" role="dialog" aria-labelledby="edit_section_builtin" aria-hidden="true">
  <div class="modal-dialog" id="edit_section_built" role="document" style="max-width: 60%;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="edit_section_builtin_title"><?php echo _x("Edit Built-in Section: ", "section-panel", "pepro");?><span></span></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
      </div>
      <div class="modal-body">
        <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
        <p>
          <table class="table">

          <?php

          $title = _x("Priority (number)", "section-panel", "pepro");
          $toltip = sprintf(_x("Enter %s", "section-panel", "pepro"), $title);
          $PeproDevUPS_Profile->add_notif_input("","section_builtin_priority",$title,$toltip,"number","",1000,"min='0'");

          $titke = _x("Section is active?", "section-panel", "pepro");
          $title = _x("Check to activate section and show it on front-end", "section-panel", "pepro");
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
        <button type="button" class="btn btn-action" data-dismiss="modal"><?php echo _x("Cancel", "section-panel", "pepro");?></button>
        <button type="button" id="save_built_in_edit" class="btn btn-primary loadingRings"><?php echo $loadingRing . _x("Save Changes", "section-panel", "pepro");?></button>
      </div>
    </div>
  </div>
</div>
