<?php
/*
 * @Author: Amirhossein Hosseinpour <https://amirhp.com>
 * @Date Created: 2023/02/06 01:05:23
 * @Last modified by: amirhp-com <its@amirhp.com>
 * @Last modified time: 2024/08/24 02:38:04
 */


global $PeproDevUPS_Profile;
$rtl         = is_rtl() ? "right" : "left";
$page        = isset($_GET['cpage']) ? abs((int) sanitize_text_field($_GET['cpage'])) : 1;
$srch        = isset($_GET['s']) ? sanitize_text_field(esc_html(trim($_GET['s']))) : "";
$integrity   = wp_create_nonce('peprocorenounce');
$trUrgent    = _x("Urgent", "notifications-priority", "peprodev-ups");
$trHigh      = _x("High", "notifications-priority", "peprodev-ups");
$trMedium    = _x("Medium", "notifications-priority", "peprodev-ups");
$trLow       = _x("Low", "notifications-priority", "peprodev-ups");
$trNormal    = _x("Normal", "notifications-priority", "peprodev-ups");
$trRed       = _x("Red", "notif-panel", "peprodev-ups");
$trOrange    = _x("Orange", "notif-panel", "peprodev-ups");
$trBlue      = _x("Blue", "notif-panel", "peprodev-ups");
$trGreen     = _x("Green", "notif-panel", "peprodev-ups");
$trDark      = _x("Dark", "notif-panel", "peprodev-ups");
$loadingRing = "<div class='lds-ring2'><div></div><div></div><div></div><div></div></div>";
$otif404     = sprintf(_x("No notification found! please consider %s.", "notif-panel", "peprodev-ups"), '<a data-toggle="modal" id="add_notifpopup" data-target="#add_new_notifications" href="#">' . _x("adding new one", "notif-panel", "peprodev-ups") . '</a>');

?>

<div class="fa-set icon-set fontawesome-icon-picker">
  <ul>
    <?php echo $PeproDevUPS_Profile->get_fontawesomepro_class_list(); ?>
  </ul>
</div>


<div class="row">
  <div class="col-lg-12 col-md-12">
    <div class="card">
      <div class="card-header card-header-primary">
        <div class="lds-ring2">
          <div></div>
          <div></div>
          <div></div>
          <div></div>
        </div>
        <h4 class="card-title"><?php echo esc_html_x("Notifications", "notif-panel", "peprodev-ups"); ?></h4>
        <p class="card-category"><?php echo esc_html_x("You can manage notifications from here. Add, Delete, Schedule, Duplicate and Edit notifications on the fly using panle below.", "notif-panel", "peprodev-ups"); ?></p>
      </div>
      <div class="card-body">
        <div id="toggle_search_container" class="hide">
          <div class="input-group search mb-3">
            <input class="search-here form-control" type="text" id="search-here" placeholder="<?php echo esc_html_x("Search here and hit Enter ...", "notif-panel", "peprodev-ups"); ?>" style="padding-inline: 0.7rem;" integrity="<?php echo esc_attr($integrity); ?>" wparam="<?php echo esc_attr($PeproDevUPS_Profile->setting_slug); ?>" lparam="search" value="<?php echo $srch; ?>" />
            <div class="input-group-append">
              <button class="btn btn-primary clear_search btn-sm" title="<?php echo esc_html_x("Clear search", "notif-panel", "peprodev-ups"); ?>"><i class="material-icons">close</i></button>
            </div>
          </div>
        </div>
        <p>
          <button class="btn btn-primary add_notifc loadingRings" id="add_notifpopup" href="#"><?php echo $loadingRing . _x("Add New", "notif-panel", "peprodev-ups"); ?></button>
          <button class="btn btn-primary toggle_search loadingRings" id="toggle_search" href="#"><?php echo $loadingRing . _x("Search", "notif-panel", "peprodev-ups"); ?></button>
        </p>
        <div class="hide mb-4" id="add_new_notifications">
          <div id="modal_add_notif">
            <div>
              <div class="modal-header">
                <h5 class="modal-title addmode"><?php echo esc_html_x("Add New Notifications", "notif-panel", "peprodev-ups"); ?></h5>
                <h5 class="modal-title editmode"><?php echo esc_html_x("Edit Notification: ", "notif-panel", "peprodev-ups"); ?><span style="font-weight: bold;"></span></h5>
                <input type="hidden" id="current_edit_notif_id" value="" />
              </div>
              <div class="modal-body">
                <table class="table" id="add_new">
                  <div class="lds-ring">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                  </div>
                  <tbody>
                    <?php
                    $t  = _x("Title", "notif-panel", "peprodev-ups");
                    $tt = sprintf(_x("Enter %s", "notif-panel", "peprodev-ups"), $t);
                    $PeproDevUPS_Profile->add_notif_input("is-required", "notifaddedit-title", $t, $tt);
                    $t  = _x("Content", "notif-panel", "peprodev-ups");
                    $tt = sprintf(_x("Enter %s", "notif-panel", "peprodev-ups"), $t);
                    $PeproDevUPS_Profile->add_notif_editor("is-required", "notifaddedit-content", $t, $tt);


                    $title = _x("Icon", "notif-panel", "peprodev-ups");
                    $titles = _x("Pick icon (provided by FontAwesome)", "notif-panel", "peprodev-ups");
                    echo "<tr>
                              <td>
                                <label class=\"text-primary\" for=\"notifaddedit-icon\">$title</label>
                              </td>
                              <td>
                                <div class=\"icon-picker\" data-pickerid=\"fa\" data-iconsets='{\"fa\":\"$titles\"}'>
                                <input id=\"notifaddedit-icon\" type=\"hidden\" /></div>
                              </td>
                            </tr>";


                    $title = _x("Icon Color", "notif-panel", "peprodev-ups");
                    $toltip = sprintf(_x("Enter %s", "notif-panel", "peprodev-ups"), $title);
                    echo "<tr><td ><label class=\"text-primary\" for=\"notifaddedit-color-bg-c4\">$title</label></td>
          	            <td>
          	              <div class=\"form-check form-check-radio form-check-inline\">
          	                <label class=\"form-check-label\">
          	                  <input class=\"form-check-input\" type=\"radio\" title=\"$toltip\" name=\"notifaddedit-color\" id=\"notifaddedit-color-bg-c2\" value=\"bg-c2\"> $trRed
          	                  <span class=\"circle\"><span class=\"check\"></span></span>
          	                </label>
          	              </div>
          	              <div class=\"form-check form-check-radio form-check-inline\">
          	                <label class=\"form-check-label\">
          	                  <input class=\"form-check-input\" type=\"radio\" title=\"$toltip\" name=\"notifaddedit-color\" id=\"notifaddedit-color-bg-c3\" value=\"bg-c3\"> $trOrange
          	                  <span class=\"circle\"><span class=\"check\"></span></span>
          	                </label>
          	              </div>
          	              <div class=\"form-check form-check-radio form-check-inline\">
          	                <label class=\"form-check-label\">
          	                  <input class=\"form-check-input\" type=\"radio\" title=\"$toltip\" name=\"notifaddedit-color\" id=\"notifaddedit-color-bg-c4\" value=\"bg-c4\"> $trBlue
          	                  <span class=\"circle\"><span class=\"check\"></span></span>
          	                </label>
          	              </div>
          	              <div class=\"form-check form-check-radio form-check-inline\">
          	                <label class=\"form-check-label\">
          	                  <input class=\"form-check-input\" type=\"radio\" title=\"$toltip\" name=\"notifaddedit-color\" id=\"notifaddedit-color-bg-c1\" value=\"bg-c1\"> $trGreen
          	                  <span class=\"circle\"><span class=\"check\"></span></span>
          	                </label>
          	              </div>
          	              <div class=\"form-check form-check-radio form-check-inline\">
          	                <label class=\"form-check-label\">
          	                  <input class=\"form-check-input\" type=\"radio\" title=\"$toltip\" name=\"notifaddedit-color\" id=\"notifaddedit-color-bg-c5\" value=\"bg-c5\"> $trDark
          	                  <span class=\"circle\"><span class=\"check\"></span></span>
          	                </label>
          	              </div>
          	          </td></tr>";


                    $title = _x("Priority", "notif-panel", "peprodev-ups");
                    $toltip = sprintf(_x("Enter %s", "notif-panel", "peprodev-ups"), $title);
                    echo "<tr><td ><label class=\"text-primary\" for=\"notifaddedit-priority-5\">$title</label></td>
                        <td>
                          <div class=\"form-check form-check-radio form-check-inline\">
                            <label class=\"form-check-label\">
                              <input class=\"form-check-input\" type=\"radio\" title=\"$toltip\" name=\"notifaddedit-priority\" id=\"notifaddedit-priority-1\" value=\"1\"> $trUrgent
                              <span class=\"circle\"><span class=\"check\"></span></span>
                            </label>
                          </div>
                          <div class=\"form-check form-check-radio form-check-inline\">
                            <label class=\"form-check-label\">
                              <input class=\"form-check-input\" type=\"radio\" title=\"$toltip\" name=\"notifaddedit-priority\" id=\"notifaddedit-priority-2\" value=\"2\"> $trHigh
                              <span class=\"circle\"><span class=\"check\"></span></span>
                            </label>
                          </div>
                          <div class=\"form-check form-check-radio form-check-inline\">
                            <label class=\"form-check-label\">
                              <input class=\"form-check-input\" type=\"radio\" title=\"$toltip\" name=\"notifaddedit-priority\" id=\"notifaddedit-priority-3\" value=\"3\"> $trMedium
                              <span class=\"circle\"><span class=\"check\"></span></span>
                            </label>
                          </div>
                          <div class=\"form-check form-check-radio form-check-inline\">
                            <label class=\"form-check-label\">
                              <input class=\"form-check-input\" type=\"radio\" title=\"$toltip\" name=\"notifaddedit-priority\" id=\"notifaddedit-priority-4\" value=\"4\"> $trLow
                              <span class=\"circle\"><span class=\"check\"></span></span>
                            </label>
                          </div>
                          <div class=\"form-check form-check-radio form-check-inline\">
                            <label class=\"form-check-label\">
                              <input class=\"form-check-input\" type=\"radio\" title=\"$toltip\" name=\"notifaddedit-priority\" id=\"notifaddedit-priority-5\" value=\"5\"> $trNormal
                              <span class=\"circle\"><span class=\"check\"></span></span>
                            </label>
                          </div>
                        </td>
                      </tr>";

                    $t  = _x("Action 1 Title", "notif-panel", "peprodev-ups");
                    $tt = sprintf(_x("Enter %s", "notif-panel", "peprodev-ups"), $t);
                    $PeproDevUPS_Profile->add_notif_input("", "notifaddedit-act1", $t, $tt);
                    $t  = _x("Action 1 URL", "notif-panel", "peprodev-ups");
                    $tt = sprintf(_x("Enter %s", "notif-panel", "peprodev-ups"), $t);
                    $PeproDevUPS_Profile->add_notif_input("", "notifaddedit-act1url", $t, $tt);
                    $t  = _x("Action 2 Title", "notif-panel", "peprodev-ups");
                    $tt = sprintf(_x("Enter %s", "notif-panel", "peprodev-ups"), $t);
                    $PeproDevUPS_Profile->add_notif_input("", "notifaddedit-act2", $t, $tt);
                    $t  = _x("Action 2 URL", "notif-panel", "peprodev-ups");
                    $tt = sprintf(_x("Enter %s", "notif-panel", "peprodev-ups"), $t);
                    $PeproDevUPS_Profile->add_notif_input("", "notifaddedit-act2url", $t, $tt);
                    $usersarray = "";
                    $users      = get_users(array('fields' => array("id", "display_name", "user_login", "user_email")));
                    foreach ($users as $user) {
                      $usersarray .= "<option value='$user->id'>#$user->id - $user->display_name (@$user->user_login | $user->user_email)</option>";
                    }
                    $title = _x("Global Notification (for current and future users) / Uncheck to Specify Users", "notif-panel", "peprodev-ups");
                    $users = '
                      <div class="form-check">
                      <label class="form-check-label">
                        <input class="form-check-input" type="checkbox" id="notifaddedit-users-check" checked>
                        <span class="form-check-sign">
                          <span class="check"></span>
                        </span>' . $title . '
                      </label>
                      </div>
                        <div user-select-area="true">
                        <select id="notifaddedit-usersList" name="notifaddedit-usersList[]" multiple="true" class="form-control primary select2 mt-3">
                          ' . $usersarray . '
                        </select>
                        </div>';
                    $title = _x("Limit to Specific Users", "notif-panel", "peprodev-ups");
                    echo "<tr><td ><label class=\"text-primary\" for=\"notifaddedit-users-check\">$title</label></td>
                      <td>$users</td></tr>";

                    global $wpdb;
                    $groups = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}groups_group`");
                    if (!$groups || $groups == null || empty($groups)) { $groups = false; }
                    echo "<tr><td ><label class=\"text-primary\">" . _x("Limit to Groups User", "notif-panel", "peprodev-ups") . " <a href='https://wordpress.org/plugins/groups/' target='_blank'><i class='fa fa-external-link'></i></a></label></td><td><div class='groups-wrapper'>";
                    ?>
                    <select name="access_groups[]" class="select2 select-groups enhanced" multiple autocomplete="off">
                      <option value='0'><?php echo __("-- None --", $this->td);?></option>
                      <?php
                        if ($groups) {
                          foreach ($groups as $group) { echo "<option value='{$group->group_id}'>{$group->name}</option>"; }
                        }
                      ?>
                    </select>
                    <?php
                    echo "</div></td></tr>";



                    echo "<tr><td ><label class=\"text-primary\">" . _x("Limit to Specified Roles", "notif-panel", "peprodev-ups") . "</label></td>
                    <td><div class='groups-wrapper'><select name='user_roles[]' class='select2 select-groups enhanced' multiple autocomplete='off'>";
                    echo "<option value='0'>".__("-- None --", $this->td)."</option>";
                    wp_dropdown_roles();
                    echo "</select></div></td></tr>";


                    echo "<tr><td ><label class=\"text-primary\">" . _x("Limit to Course Users", "notif-panel", "peprodev-ups") . " <a href='https://www.learndash.com/' target='_blank'><i class='fa fa-external-link'></i></label></td>
                    <td><div class='groups-wrapper'><select name='learn_dash[]' class='select2 select-groups enhanced' multiple autocomplete='off'>";
                    echo "<option value='0'>".__("-- None --", $this->td)."</option>";
                    foreach ($this->learndash_get_all_course_ids() as $id) {
                      echo "<option value='$id'>".get_the_title($id)."</option>";
                    }
                    echo "</select></div></td></tr>";




                    $datepicker = sprintf(
                      '<div class="form-check">
                        <label class="form-check-label">
                          <input class="form-check-input" type="checkbox" id="notifaddedit-schedule-check">
                          <span class="form-check-sign">
                            <span class="check"></span>
                          </span>%4$s
                        </label>
                        </div>
                          <div date-picker-area="true" style="">
                            <div id="calenderContainer" class="mt-4" data-date="%2$s" style="width: 320px;"></div>
                            <div date-picker-data="true" style="width: 320px;">
                              <input readonly type="text" id="%1$sFA" name="%1$sFA" value="%3$s" style="width: 100%%;text-align:center;font-size: 0.75rem;">
                              <input readonly type="text" id="%1$s" name="%1$s" value="%2$s" style="width: 100%%;text-align:center;font-size: 0.75rem;">
                            </div>
                          </div>',
                      "notifaddedit-schedule",
                      "", /*$grigorianDate*/
                      "", /*$persianDate*/
                      _x("Check to schedule for later / Keep unchecked to Publish Now", "notif-panel", "peprodev-ups")
                    );
                    $title = _x("Publish Later?", "notif-panel", "peprodev-ups");
                    echo "<tr><td ><label class=\"text-primary\" for=\"notifaddedit-schedule-check\">$title</label></td>
                      <td>$datepicker</td></tr>";
                    ?>
                  </tbody>
                </table>
              </div>
              <div class="modal-footer">
                <button type="button" id="add_notif" class="add_edit_save_notification btn btn-primary loadingRings" integrity="<?php echo esc_attr($integrity); ?>" wparam="<?php echo esc_attr($PeproDevUPS_Profile->setting_slug); ?>" lparam="add_new"><?php echo $loadingRing . _x("Add New", "notif-panel", "peprodev-ups"); ?></button>
                <button type="button" id="edit_notif" class="add_edit_save_notification btn btn-primary loadingRings" integrity="<?php echo esc_attr($integrity); ?>" wparam="<?php echo esc_attr($PeproDevUPS_Profile->setting_slug); ?>" lparam="add_new"><?php echo $loadingRing . _x("Save Edits", "notif-panel", "peprodev-ups"); ?></button>
                <button type="button" id="clear_notif_form" class="btn btn-action"><?php echo esc_html_x("Clear form", "notif-panel", "peprodev-ups"); ?></button>
                <button type="button" id='close_add_new_notifications' class="btn btn-action" data-dismiss="modal"><?php echo esc_html_x("Close", "notif-panel", "peprodev-ups"); ?></button>
              </div>
            </div>
          </div>
        </div>
        <div class="toggle_view_form">
          <table class="table table-hover" id="notifications_list_table">
            <thead class="text-primary">
              <tr>
                <th><?php echo esc_html_x("Date", "notif-panel", "peprodev-ups"); ?></th>
                <th><?php echo esc_html_x("Title", "notif-panel", "peprodev-ups"); ?></th>
                <th><?php echo esc_html_x("Publish status", "notif-panel", "peprodev-ups"); ?></th>
                <th><?php echo esc_html_x("User range", "notif-panel", "peprodev-ups"); ?></th>
                <th><?php echo esc_html_x("Priority", "notif-panel", "peprodev-ups"); ?></th>
                <th><?php echo esc_html_x("Action", "notif-panel", "peprodev-ups"); ?></th>
              </tr>
            </thead>
            <tbody>
              <?php
              echo $PeproDevUPS_Profile->show_notifications_edit_panel($page, esc_html($srch));
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
        <h5 class="modal-title" id="del_notifications"><?php echo esc_html_x("Permanently Remove Notifications", "notif-panel", "peprodev-ups"); ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
      </div>
      <div class="modal-body">
        <div class="lds-ring">
          <div></div>
          <div></div>
          <div></div>
          <div></div>
        </div>
        <p>
          <?php echo _x("Are you sure you want to permanently remove this notification?<br>There is no Undo action available.", "notif-panel", "peprodev-ups"); ?>
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" id="remove_notif" class="btn btn-primary loadingRings"><?php echo $loadingRing . _x("Yes, Remove", "notif-panel", "peprodev-ups"); ?></button>
        <button type="button" class="btn btn-action" data-dismiss="modal"><?php echo esc_html_x("Cancel", "notif-panel", "peprodev-ups"); ?></button>
      </div>
    </div>
  </div>
</div>