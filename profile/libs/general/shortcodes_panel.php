<?php
# @Last modified by:   Amirhosseinhpv
# @Last modified time: 2021/08/28 23:59:21
?>
<style media="screen">
.table.pepcappearance tr td:first-child { min-width: unset !important; }
.table.pepcappearance tr td { min-width: unset !important; vertical-align: top !important; }
.table thead tr th:last-of-type { text-align: left; }
</style>
<div class="col-lg-12">
  <div class="card">
      <div class="card-header card-header-primary">
        <h4 class="card-title"><?php echo _x("Shortcodes [BETA]", "profile-section", $this->td);?></h4>
        <p class="card-category"><?php echo _x("List of available shortcodes you can use anywhere.", "profile-section", $this->td);?></p>
      </div>
      <div class="card-body table-responsive">
        <table class="table pepcappearance table-striped">
          <thead>
            <th><?php echo __("Shortcode",$this->td);?></th>
            <th><?php echo __("Description",$this->td);?></th>
            <th><?php echo __("Syntax",$this->td);?></th>
            <th><?php echo __("Sample",$this->td);?></th>
          </thead>
          <tbody>
            <?php
              foreach (apply_filters("peprofile_shortcodes",array()) as $key => $value) {
                $samole = esc_html( $value['sample'] );
                echo "<tr>";
                  echo "<td><strong>[{$key}]</strong></td>";
                  echo "<td>" . (isset($value['title']) ? $value['title'] : $key) . "</td>";
                  if (isset($value['syntax'])){
                    echo "<td><ul style=\"margin: 0;padding: 0;list-style: none;\">";
                    foreach ($value['syntax'] as $key4444 => $value) { echo "<li><strong>$key4444</strong>: $value</li>"; }
                    echo "</ul></td>";
                  }
                  else{
                    echo "<td><i>".__("No attribute required",$this->td)."</i></td>";
                  }
                  echo "<td class='copy-shortcode' style='text-align: left;'><code title='".__("Click to copy",$this->td)."' style=\"cursor: pointer; direction: ltr; unicode-bidi: bidi-override;\" data-copy='".esc_attr($samole)."'><strong>".nl2br($samole)."</strong></code></td>";
                echo "</tr>";
              }
            ?>
          </tbody>
        </table>
    </div>
  </div>
</div>
<?php
