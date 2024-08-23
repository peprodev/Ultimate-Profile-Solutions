<?php
/*
 * @Author: Amirhossein Hosseinpour <https://amirhp.com>
 * @Last modified by: amirhp-com <its@amirhp.com>
 * @Last modified time: 2023/03/13 15:17:38
 */

?>
<style media="screen">
.table.pepcappearance tr td {vertical-align: top !important; }
.table thead tr th:last-of-type { text-align: left; }
</style>
<div class="col-lg-12">
  <div class="card">
      <div class="card-header card-header-primary">
        <h4 class="card-title"><?php echo esc_html_x("Shortcodes [BETA]", "profile-section", "peprodev-ups");?></h4>
        <p class="card-category"><?php echo esc_html_x("List of available shortcodes you can use anywhere.", "profile-section", "peprodev-ups");?></p>
      </div>
      <div class="card-body table-responsive">
        <table class="table pepcappearance table-striped">
          <thead>
            <th><?php esc_html_e("Shortcode","peprodev-ups");?></th>
            <th><?php esc_html_e("Description","peprodev-ups");?></th>
            <th><?php esc_html_e("Syntax","peprodev-ups");?></th>
            <th><?php esc_html_e("Sample","peprodev-ups");?></th>
          </thead>
          <tbody>
            <?php
              foreach (apply_filters("peprofile_shortcodes",array()) as $key => $value) {
                $samole = esc_html( $value['sample'] );
                echo "<tr>";
                  echo "<td><pre>[{$key}]</pre></td>";
                  echo "<td>" . wp_kses_post(isset($value['title']) ? $value['title'] : $key) . "</td>";
                  if (isset($value['syntax'])){
                    echo "<td><ul style=\"margin: 0;padding: 0;list-style: none;\">";
                    foreach ($value['syntax'] as $key4444 => $value) { echo wp_kses_post("<li><strong>$key4444</strong>: $value</li>"); }
                    echo "</ul></td>";
                  }
                  else{
                    echo "<td><i>".__("No attribute required","peprodev-ups")."</i></td>";
                  }
                  echo "<td class='copy-shortcode' style='text-align: left;'>
                  <code title='".__("Click to copy","peprodev-ups")."' style=\"cursor: pointer; direction: ltr; unicode-bidi: bidi-override;\"
                  data-copy='".esc_attr($samole)."'><pre>".wp_kses_post($samole)."</pre></code></td>";
                echo "</tr>";
              }
            ?>
          </tbody>
        </table>
    </div>
  </div>
</div>
<?php
