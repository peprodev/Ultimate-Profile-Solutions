<?php
/*
 * @Author: Amirhossein Hosseinpour <https://amirhp.com>
 * @Last modified by: amirhp-com <its@amirhp.com>
 * @Last modified time: 2023/03/13 15:17:20
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
<div class="row">
  <div class="col-lg-12 col-md-12">
    <div class="card">
      <div class="card-header card-header-primary">
        <div class="lds-ring2"><div></div><div></div><div></div><div></div></div>
        <h4 class="card-title"><?php echo esc_html_x("Manage Newsletter Subscriber", "section-panel", "peprodev-ups");?></h4>
        <p class="card-category"><?php echo esc_html_x("You can manage Newsletter Subscribers from here, to use subscription form add this shortcode to your page: ", "section-panel", "peprodev-ups");?> <strong class="copyme" data-copy="[pepro-sms-subscription]">[pepro-sms-subscription]</strong></p>
      </div>
      <div class="card-body">
        <p class="float-right">
          <a class="btn btn-primary loadingRings" href="<?php echo admin_url("?peprodev_subscribers=export_csv");?>"><?php _ex("Export All Users to Excel CSV", "notif-panel", "peprodev-ups");?></a>
          <a class="btn btn-primary loadingRings" href="<?php echo admin_url("?page=peprodev-ups&section=newsletter&cpage=1&per_page=9999999999999");?>"><?php _ex("Show ALL at Once", "notif-panel", "peprodev-ups");?></a>
          <button class="btn btn-primary loadingRings" data-integrity="<?php echo wp_create_nonce('peprocorenounce');?>" data-wparam="<?php echo $this->setting_slug;?>" data-lparam="clear_newsletterdb" id="clear_database" href="#"><?php echo $loadingRing . _x("Clear Database", "notif-panel", "peprodev-ups");?></button>
        </p>
        <p class="float-left">
          <?php echo sprintf(__("Total Records: %s",$this->td), "<span class='totaltcountspana'>".$PeproDevUPS_Profile->show_newsletter_edit_panel(1,"","", 1)."</span>");?>
        </p>
        <div class="toggle_view_form">
          <table class="table table-hover" id="notifications_list_table">
          <thead class="text-primary">
            <tr>
              <th><?php echo esc_html_x("Date", "section-panel", "peprodev-ups");?></th>
              <th><?php echo esc_html_x("Name", "section-panel", "peprodev-ups");?></th>
              <th><?php echo esc_html_x("Mobile", "section-panel", "peprodev-ups");?></th>
              <th><?php echo esc_html_x("Email", "section-panel", "peprodev-ups");?></th>
              <th><?php echo esc_html_x("UID", "section-panel", "peprodev-ups");?></th>
              <th><?php echo esc_html_x("Action", "section-panel", "peprodev-ups");?></th>
            </tr>
          </thead>
          <tbody><?php echo $PeproDevUPS_Profile->show_newsletter_edit_panel($page, esc_html($srch));?></tbody>
        </table>
        </div>
      </div>
    </div>
  </div>
</div>
