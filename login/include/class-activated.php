<?php
# @Last modified by:   Amirhosseinhpv
# @Last modified time: 2021/09/01 22:24:35
add_thickbox();
wp_enqueue_style("wp-color-picker");
wp_enqueue_script("wp-color-picker");
wp_enqueue_style("pepro-register-fields-ide",      "$this->assets_url/assets/ide/ace.css" , array(), current_time("timestamp"));
wp_enqueue_style("pepro-register-fields",          "$this->assets_url/assets/register.css", array(), current_time("timestamp"));
wp_enqueue_style("pepro-register-jqconfirm",       "$this->assets_url/assets/jquery-confirm.css", array(), current_time("timestamp"));
wp_enqueue_script("pepro-register-jqconfirm",      "$this->assets_url/assets/jquery-confirm.js", array('jquery'), current_time("timestamp"));
wp_enqueue_script("color-picker-alpha",            "$this->assets_url/assets/wp-color-picker-alpha.min.js", array("jquery"), current_time("timestamp"));
wp_enqueue_script("pepro-register-fields-hotkeys", "$this->assets_url/assets/hotkeys.min.js", array('jquery'), current_time("timestamp"));
wp_enqueue_script("pepro-register-fields-ide",     "$this->assets_url/assets/ide/ace.js", array('jquery'), current_time("timestamp"));
wp_enqueue_script("pepro-register-fields",         "$this->assets_url/assets/register.js", array("jquery"), current_time("timestamp"));
wp_localize_script("pepro-register-fields",        "_register_fields", array(
  "_added"     => __("New Field Successfully Added", $this->td),
  "_removed"   => __("Field Successfully Removed",   $this->td),
  "_palett"    => array( '#444444', '#dd3333', '#dd9933', '#eeee22', '#81d742', '#1e73be', '#8224e3', '#ff2255', '#559999', '#99CCFF', '#00c1e8', '#F9DE0E', '#111111', '#EEEEDD' ),
  "_copy"      => __("Copied!",   $this->td),
  "saving"     => __("Saving ...",   $this->td),
  "loading"    => _x("Please wait ...", "js-translate", $this->td),
  "error"      => _x("An unknown error occured.", "js-translate", $this->td),
  "errorTxt"   => _x("Error", "js-translate", $this->td),
  "gohome_txt" => _x("Go Home", "js-translate", $this->td),
  "confirmTxt" => _x("Confirm", "js-translate", $this->td),
  "confirmCap" => _x("You sure to clear out all items?<red>THIS CANNOT BE UNDONE.", "js-translate", $this->td),
  "successTtl" => _x("Success", "js-translate", $this->td),
  "closeTxt"   => _x("Close", "js-translate", $this->td),
  "locked"     => _x("This item is locked! To unlock, Change Login/Registeration Type field.", "js-translate", $this->td),
  "successCap" => _x("All items deleted successfully", "js-translate", $this->td),
  "submitTxt"  => _x("Submit", "js-translate", $this->td),
  "okTxt"      => _x("Okay", "js-translate", $this->td),
  "txtYes"     => _x("Yes", "js-translate", $this->td),
  "txtNop"     => _x("No", "js-translate", $this->td),
  "cancelbTn"  => _x("Cancel", "js-translate", $this->td),
));

$styles = "";
$styleFiles = glob(dirname(plugin_dir_path(__FILE__))."/styles/*/*.css");
foreach ($styleFiles as $style) {
  $contents='';
  $file = file($style);
  foreach ($file as $lines => $line) { $contents .= $line; }
  $badge = "";
  $styleExifDAta = $this->parseTemplate($contents);
  $theme = sprintf(_x('%1$s theme by %2$s', "theme-name", $this->td), $styleExifDAta["name"], $styleExifDAta["designer"]);
  if (isset($styleExifDAta["original"]) && !empty($styleExifDAta["original"])  && strtolower($styleExifDAta["original"]) === "yes") { $badge = "data-verified='true'"; }
  $styles .= "<option " . selected(get_option("{$this->activation_status}-style", "default.css"), basename($style), false)." $badge value='".basename($style)."'>" . $theme . "</option>";
}
?>
<div class="row">
  <div class="col-lg-12 col-md-12">
    <div class="card mb-2">
      <div class="nav-tabs-navigation card-header-primary m-0">
        <div class="nav-tabs-wrapper">
          <ul class="nav nav-tabs">
            <li class="nav-item tab_login">
              <a class="nav-link active show" href="#tab_login" ><i class="material-icons">login</i> <?=_x("Login", "login-section", $this->td);?></a>
            </li>
            <li class="nav-item tab_registration">
              <a class="nav-link" href="#tab_registration" ><i class="material-icons">app_registration</i> <?=_x("Registration","login-section", $this->td);?></a>
            </li>
            <li class="nav-item tab_redirection">
              <a class="nav-link" href="#tab_redirection"  ><i class="material-icons">call_split</i> <?=_x("Redirection","login-section", $this->td);?></a>
            </li>
            <li class="nav-item tab_verification">
              <a class="nav-link" href="#tab_verification" ><i class="material-icons">how_to_reg</i> <?=_x("Verification","login-section", $this->td);?></a>
            </li>
            <li class="nav-item tab_security">
              <a class="nav-link" href="#tab_security" ><i class="material-icons">security</i> <?=_x("Security", "login-section", $this->td);?></a>
            </li>
            <li class="nav-item tab_advanced">
              <a class="nav-link" href="#tab_advanced" ><i class="material-icons">developer_board</i> <?=_x("Advanced", "login-section", $this->td);?></a>
            </li>
            <li class="nav-item tab_migrate">
              <a class="nav-link" href="#tab_migrate" ><i class="material-icons">cloud_done</i> <?=_x("Import/Export", "login-section", $this->td);?></a>
            </li>
            <li class="nav-item tab_samrt_button">
              <a class="nav-link" href="#tab_samrt_button" ><i class="material-icons">auto_fix_high</i> <?=_x("Samrt Button", "login-section", $this->td);?></a>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <br>
    <div class="tab-content">
      <div class="tab-pane active show" id="tab_login">
        <div class="card">
          <div class="card-header card-header-primary">
            <h4 class="card-title"><?=_x("Login", "login-section", $this->td);?></h4>
            <p class="card-category"><?=_x("You can control wordpress login behavior and style from here.", "login-section", $this->td);?></p>
          </div>
          <div class="card-body table-responsive">
            <table class="table pepcappearance">
              <tbody>
                <tr>
                  <td><?=_x("Login Theme", "login-section", $this->td);?></td>
                  <td>
                    <select id="login-section-style">
                        <?=$styles;?>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td><?=_x("WordPress Style", "login-section", $this->td);?></td>
                  <td>
                      <a class='btncheckbox'
                        data-text-on='<?=_x("Overwite WordPress Style with Theme", "login-section", $this->td);?>'
                        data-text-off='<?=_x("Load with WordPress Style with Theme", "login-section", $this->td);?>'
                        data-on='invert_colors_off'
                        data-off='invert_colors'
                        data-checked='<?=get_option("{$this->activation_status}-wp", "false") === "true" ? "true" : "false";?>' id="login-section-style-force"></a>
                  </td>
                </tr>
                <tr>
                  <td><?=_x("Show Logo", "login-section", $this->td);?></td>
                  <td>
                      <a class='btncheckbox'
                        data-text-on='<?=_x("Yes, Show it", "login-section", $this->td);?>'
                        data-text-off='<?=_x("No, Hide it", "login-section", $this->td);?>'
                        data-on='visibility'
                        data-togglel='[showonlogoshow]'
                        data-off='visibility_off'
                        data-checked='<?=get_option("{$this->activation_status}-showlogo", "false") === "true" ? "true" : "false";?>' id="login-section-show-logo"></a>
                  </td>
                </tr>
                <tr showonlogoshow="true">
                  <td><?=_x("Logo Image", "login-section", $this->td);?></td>
                  <td>
                    <input type="hidden" data-id="<?=get_option("{$this->activation_status}-logo-id", "");?>" id="login-section-logo" value="<?=get_option("{$this->activation_status}-logo", "");?>" class="form-control primary" placeholder="<?=_x("Logo URL", "login-section", $this->td);?>" />
                    <div class="flex">
                      <img style="border-radius: 5px;" src="<?=get_option("{$this->activation_status}-logo", "");?>" id="profile-img" width="86px"/>
                      <button type="button" style="padding: 12px; display: block;" class="btn btn-primary mediapicker icn-btn mr-4 ml-4"
                      data-ref="#login-section-logo"
                      data-ref4="#profile-img"
                      data-title="<?=_x("Select Custom Image", "login-section", $this->td);?>"
                      ><i class='material-icons'>cloud_upload</i> <?=_x("Select or Upload Custom Image", "login-section", $this->td);?></button>
                    </div>
                  </td>
                </tr>
                <tr showonlogoshow="true">
                  <td><?=_x("Logo Dimensions", "login-section", $this->td);?></td>
                  <td>
                    <div class="flex">
                      <input type="text" id="login-section-logo-w" title="<?=_x("Logo Width (e.g. 84px)", "login-section", $this->td);?>" value="<?=get_option("{$this->activation_status}-logo-w", "84px");?>" class="text-center form-control primary" placeholder="<?=_x("Logo Width (e.g. 84px)", "login-section", $this->td);?>" />
                      &times;
                      <input type="text" id="login-section-logo-h" title="<?=_x("Logo Height (e.g. 84px)", "login-section", $this->td);?>" value="<?=get_option("{$this->activation_status}-logo-h", "84px");?>" class="text-center form-control primary" placeholder="<?=_x("Logo Height (e.g. 84px)", "login-section", $this->td);?>" />
                    </div>
                  </td>
                </tr>
                <tr showonlogoshow="true">
                  <td><?=_x("Logo Href", "login-section", $this->td);?></td>
                  <td>
                    <input type="text" id="login-section-logohref" value="<?=get_option("{$this->activation_status}-logo-href", home_url());?>" class="form-control primary" placeholder="<?=_x("Login slug", "login-section", $this->td);?>" />
                  </td>
                </tr>
                <tr showonlogoshow="true">
                  <td><?=_x("Logo Title", "login-section", $this->td);?></td>
                  <td>
                    <input type="text" id="login-section-logotitle" value="<?=get_option("{$this->activation_status}-logo-title", get_bloginfo('name'));?>" class="form-control primary" placeholder="<?=_x("Login slug", "login-section", $this->td);?>" />
                  </td>
                </tr>
                <tr>
                  <td><?=_x("'Remeber Me' Checkbox", "login-section", $this->td);?></td>
                  <td>
                      <a class='btncheckbox'
                        data-text-on='<?=_x("Yes, Show it", "login-section", $this->td);?>'
                        data-text-off='<?=_x("No, Hide it", "login-section", $this->td);?>'
                        data-on='visibility'
                        data-off='visibility_off'
                        data-checked='<?=get_option("{$this->activation_status}-rmc", "true") === "true" ? "true" : "false";?>' id="login-section-rmc"></a>
                  </td>
                </tr>
                <tr>
                  <td><?=_x("Back to Blog Link", "login-section", $this->td);?></td>
                  <td>
                      <a class='btncheckbox'
                        data-text-on='<?=_x("Yes, Show it", "login-section", $this->td);?>'
                        data-text-off='<?=_x("No, Hide it", "login-section", $this->td);?>'
                        data-on='visibility'
                        data-off='visibility_off'
                        data-checked='<?=get_option("{$this->activation_status}-b2b", "true") === "true" ? "true" : "false";?>' id="login-section-b2b"></a>
                  </td>
                </tr>
                <tr>
                  <td><?=_x("Privacy Link", "login-section", $this->td);?></td>
                  <td>
                      <a class='btncheckbox'
                        data-text-on='<?=_x("Yes, Show it", "login-section", $this->td);?>'
                        data-text-off='<?=_x("No, Hide it", "login-section", $this->td);?>'
                        data-on='visibility'
                        data-off='visibility_off'
                        data-checked='<?=get_option("{$this->activation_status}-privacy", "true") === "true" ? "true" : "false";?>' id="login-section-privacy"></a>
                  </td>
                </tr>
                <tr>
                  <td><?=_x("Show Password Button", "login-section", $this->td);?></td>
                  <td>
                      <a class='btncheckbox'
                        data-text-on='<?=_x("Yes, Show it", "login-section", $this->td);?>'
                        data-text-off='<?=_x("No, Hide it", "login-section", $this->td);?>'
                        data-on='visibility'
                        data-off='visibility_off'
                        data-checked='<?=get_option("{$this->activation_status}-spb", "true") === "true" ? "true" : "false";?>' id="login-section-spb"></a>
                  </td>
                </tr>
                <tr>
                  <td><?=_x("Force Background?", "login-section", $this->td);?></td>
                  <td>
                    <a class='btncheckbox'
                      data-text-on='<?=_x("Yes, Apply My Setting for Background", "login-section", $this->td);?>'
                      data-text-off='<?=_x("No, Use Default Login Theme's Background setting", "login-section", $this->td);?>'
                      data-on='visibility'
                      data-togglel='[onlyforcedbg]'
                      data-off='visibility_off'
                      data-checked='<?=get_option("{$this->activation_status}-forcebg", "false") === "true" ? "true" : "false";?>' id="login-section-forcebg"></a>
                  </td>
                </tr>

                <tr onlyforcedbg="true">
                  <td><?=_x("Background Type", "login-section", $this->td);?></td>
                  <td>
                    <select id="login-section-bgtype" class="filteritem" data-filter='showfilteredbybgtype'>
                        <?="<option filter-item='solid'" . selected(get_option("{$this->activation_status}-bgtype", "color"), "color", false)." value='color'>" . _x("Solid Color", "login-section", $this->td) . "</option>";?>
                        <?="<option filter-item='gradient'" . selected(get_option("{$this->activation_status}-bgtype", "color"), "gradient", false)." value='gradient'>" . _x("Gradient Color", "login-section", $this->td) . "</option>";?>
                        <?="<option filter-item='image'" . selected(get_option("{$this->activation_status}-bgtype", "color"), "image", false)." value='image'>" . _x("Image", "login-section", $this->td) . "</option>";?>
                        <?="<option filter-item='video'" . selected(get_option("{$this->activation_status}-bgtype", "color"), "video", false)." value='video'>" . _x("Video", "login-section", $this->td) . "</option>";?>
                    </select>

                    <div showfilteredbybgtype="solid">
                      <input type="text" colorpicker="true" data-alpha="true" id="login-section-bg-solid" value="<?=get_option("{$this->activation_status}-bg-solid", "white");?>" class="form-control primary" placeholder="<?=_x("Background Solid Color", "login-section", $this->td);?>" />
                    </div>

                    <div showfilteredbybgtype="gradient">
                      <input type="text" autocomplete="off" data-alpha="true" colorpicker="true" id="login-section-bg-gradient1" value="<?=get_option("{$this->activation_status}-bg-gradient1", "#6a11cb");?>" class="form-control primary" placeholder="<?=_x("Background Solid Color", "login-section", $this->td);?>" />
                      <input type="text" autocomplete="off" data-alpha="true" colorpicker="true" id="login-section-bg-gradient2" value="<?=get_option("{$this->activation_status}-bg-gradient2", "#2575fc");?>" class="form-control primary float-right" placeholder="<?=_x("Background Solid Color", "login-section", $this->td);?>" />
                      <div style="margin: 1rem 0 0 0;" class="text-center">👇 <info><?=_x("Linear Gradient direction or Angels", "login-section", $this->td);?></info> 👇</div>
                      <input type="text" id="login-section-bg-gradient3" value="<?=get_option("{$this->activation_status}-bg-gradient3", "to left");?>" class="form-control primary" title="<?=sprintf(_x("Linear Gradient direction or Angels (and extra color steps)%sExamples: to left | to top | 65deg | 90deg, blue, green, rgba(203, 24, 201, 0.86), #13c253", "login-section", $this->td), PHP_EOL);?>" placeholder="<?=_x("e.g. to left | to top | 65deg | 90deg, blue, green, rgba(203, 24, 201, 0.86), #13c253", "login-section", $this->td);?>" />
                    </div>

                    <div showfilteredbybgtype="image">
                      <input type="hidden" data-id="<?=get_option("{$this->activation_status}-bg-img-id", "");?>" id="login-section-bg-img" value="<?=get_option("{$this->activation_status}-bg-img", "");?>" class="form-control primary"/>
                      <div class="flex">
                        <img style="border-radius: 5px;" src="<?=get_option("{$this->activation_status}-bg-img", "");?>" id="login-section-bg-img-preview" width="86px"/>
                        <button type="button" style="padding: 12px; display: block;" class="btn btn-primary mediapicker icn-btn mr-4 ml-4"
                        data-ref="#login-section-bg-img"
                        data-ref4="#login-section-bg-img-preview"
                        data-title="<?=_x("Select Custom Background Image", "login-section", $this->td);?>"
                        ><i class='material-icons'>cloud_upload</i> <?=_x("Select or Upload Background Image", "login-section", $this->td);?></button>
                      </div>
                    </div>

                    <div showfilteredbybgtype="video">
                      <input type="hidden" data-id="<?=get_option("{$this->activation_status}-bg-video-id", "");?>" id="login-section-bg-video" value="<?=get_option("{$this->activation_status}-bg-video", "");?>" class="form-control primary" />
                      <div class="flex">
                        <video width="200" controls>
                          <source style="border-radius: 5px;" id="login-section-bg-video-preview" src="<?=get_option("{$this->activation_status}-bg-video", "");?>" type="video/mp4" />
                          Your browser does not support HTML5 video.
                        </video>
                        <button type="button" style="padding: 12px; display: block;" class="btn btn-primary mediapicker icn-btn mr-4 ml-4"
                        data-picktype="video/mp4"
                        data-ref="#login-section-bg-video"
                        data-ref4="#login-section-bg-video-preview"
                        data-title="<?=_x("Select Custom Background Video", "login-section", $this->td);?>"
                        ><i class='material-icons'>cloud_upload</i> <?=_x("Upload Video", "login-section", $this->td);?></button>
                      </div>
                      <div class="flex margin-top">

                        <a class='mr-2 ml-2 btncheckbox'
                          data-text-on='<?=_x("Autoplay: ON", "login-section", $this->td);?>'
                          data-text-off='<?=_x("Autoplay: OFF", "login-section", $this->td);?>'
                          data-on='play_circle_outline'
                          data-off='pause_circle_outline'
                          data-checked='<?=get_option("{$this->activation_status}-bg-video-autoplay", "true") === "true" ? "true" : "false";?>' id="login-section-bg-video-autoplay"></a>

                        <a class='mr-2 ml-2 btncheckbox'
                          data-text-on='<?=_x("Muted", "login-section", $this->td);?>'
                          data-text-off='<?=_x("Sound", "login-section", $this->td);?>'
                          data-on='volume_off'
                          data-off='volume_up'
                          data-checked='<?=get_option("{$this->activation_status}-bg-video-muted", "true") === "true" ? "true" : "false";?>' id="login-section-bg-video-muted"></a>

                        <a class='mr-2 ml-2 btncheckbox'
                          data-text-on='<?=_x("Loop: ON", "login-section", $this->td);?>'
                          data-text-off='<?=_x("Loop: OFF", "login-section", $this->td);?>'
                          data-on='sync'
                          data-off='sync_disabled'
                          data-checked='<?=get_option("{$this->activation_status}-bg-video-loop", "true") === "true" ? "true" : "false";?>' id="login-section-bg-video-loop"></a>

                      </div>

                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
            <br>
            <button
              class="login-section-save btn btn-success btn-primary icn-btn btn-wide"
              integrity="<?=wp_create_nonce('peprocorenounce')?>"
              wparam="loginregister" lparam="savelogin" dparam="" fn="">
              <i class='material-icons'>save</i> <?=_x("Save Settings", "login-section", $this->td);?>
            </button>
          </div>
        </div>
      </div>
      <div class="tab-pane" id="tab_registration">
        <div class="card">
          <div class="card-header card-header-primary">
            <h4 class="card-title"><?=_x("Registration Configuration","login-section", $this->td);?></h4>
            <p class="card-category"><?=_x("You can control wordpress registeration fields from this section.","login-section", $this->td);?></p>
          </div>
          <div class="card-body">
            <div class="register-fields">
              <template id="raw_field">
                <div class="register-field-single p-2 mb-2 border">
                  <div class="register-field-single-title row justify-content-between align-items-center">
                    <div class="col-lg-9 ">
                      <h5 class='live-title m-0 p-2 text-primary font-weight-bold' data-default="<?=__("New Field",$this->td);?>"><?=__("New Field",$this->td);?></h5>
                    </div>
                    <div class="col-lg-3 <?=is_rtl()?"text-left":"text-right";?>">
                      <div class="btn-group m-0 p-0 ">
                        <a href="javascript:;" class='btn btn-sm btn-primary register--duplicate-field'><span class="material-icons">content_copy</span></a>
                        <a href="javascript:;" class='btn btn-sm btn-primary register--clear-field'><span class="material-icons">delete</span></a>
                        <a href="javascript:;" class='btn btn-sm btn-primary register--arrow-down-field'><span class="material-icons">arrow_downward</span></a>
                        <a href="javascript:;" class='btn btn-sm btn-primary register--arrow-up-field'><span class="material-icons">arrow_upward</span></a>
                      </div>
                    </div>
                  </div>
                  <div class="register-field-single-content slide-up">
                    <!-- text|textarea|number|email|mobile|editor|date|select|color|recaptcha -->
                    <div class="fields-wrapper-inner row justify-content-between align-items-center mt-3 field-opt-meta_name mb-2"  data-show="all" >
                      <div class="col-lg-4"><?=__("Field Name (meta_name)",$this->td);?></div>
                      <div class="col-lg-8"><input autocomplete="off" type="text" placeholder="<?=__("Field Name (meta_name)",$this->td);?>" class='form-input meta-name' name="meta_name" /></div>
                    </div>
                    <div class="fields-wrapper-inner row justify-content-between align-items-center mb-3 field-opt-type"            data-show="all" >
                      <div class="col-lg-4"><?=__("Field Type",$this->td);?></div>
                      <div class="col-lg-8">
                        <select autocomplete="off" class="filteritem single-field-type" name="type">
                          <?php
                          $get_fileds_types = $this->get_fileds_types();
                          array_walk($get_fileds_types, function($val, $key){ echo "<option value=\"$key\">$val</option>"; });
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="fields-wrapper-inner row justify-content-between align-items-center mb-3 field-opt-title"           data-show="all" >
                      <div class="col-lg-4"><?=__("Field Title",$this->td);?></div>
                      <div class="col-lg-8"><input autocomplete="off" type="text" placeholder="<?=__("Field Title",$this->td);?>" class='form-input single-title' name="title" /></div>
                    </div>
                    <div class="fields-wrapper-inner row justify-content-between align-items-center mb-3 field-opt-placeholder"     data-show="text|textarea|number|email|mobile|date" >
                      <div class="col-lg-4"><?=__("Field Placeholder",$this->td);?></div>
                      <div class="col-lg-8"><input autocomplete="off" type="text" placeholder="<?=__("Field Placeholder",$this->td);?>" class='form-input single-placeholder' name="placeholder" /></div>
                    </div>
                    <div class="fields-wrapper-inner row justify-content-between align-items-center mb-3 field-opt-classes"         data-show="all" >
                      <div class="col-lg-4"><?=__("Field Classes",$this->td);?></div>
                      <div class="col-lg-8"><input autocomplete="off" type="text" placeholder="<?=__("Field Classes",$this->td);?>" class='form-input single-classes' name="classes" /></div>
                    </div>
                    <div class="fields-wrapper-inner row justify-content-between align-items-center mb-3 field-opt-attributes"      data-show="text|textarea|number|email|mobile|date|select|color" >
                      <div class="col-lg-4"><?=__("Field HTML attributes",$this->td);?></div>
                      <div class="col-lg-8"><input autocomplete="off" type="text" placeholder="<?=__("Field HTML attributes",$this->td);?>" class='form-input single-html-attributes' name="attributes" /></div>
                    </div>
                    <div class="fields-wrapper-inner row justify-content-between align-items-center mb-3 field-opt-site-key"        data-show="recaptcha" >
                      <div class="col-lg-4"><?=__("Site key",$this->td);?> <a href="https://www.google.com/recaptcha/admin/" target="_blank" class="btn btn-sm btn-round btn-group btn-info float-left m-0"><i class="fas fa-external-link-alt"></i></a></div>
                      <div class="col-lg-8"><input autocomplete="off" type="text" placeholder="<?=__("Site key",$this->td);?>" class='form-input single-html-attributes' name="site-key" /></div>
                    </div>
                    <div class="fields-wrapper-inner row justify-content-between align-items-center mb-3 field-opt-secret-key"      data-show="recaptcha" >
                      <div class="col-lg-4"><?=__("Secret key",$this->td);?> <a href="https://www.google.com/recaptcha/admin/" target="_blank" class="btn btn-sm btn-round btn-group btn-info float-left m-0"><i class="fas fa-external-link-alt"></i></a></div>
                      <div class="col-lg-8"><input autocomplete="off" type="text" placeholder="<?=__("Secret key",$this->td);?>" class='form-input single-html-attributes' name="secret-key" /></div>
                    </div>
                    <div class="fields-wrapper-inner row justify-content-between align-items-center mb-3 field-opt-size"            data-show="recaptcha" >
                      <div class="col-lg-4"><?=__("Theme Color",$this->td);?></div>
                      <div class="col-lg-8"><select autocomplete="off" class='form-input single-html-attributes' name="size">
                        <option value="normal"><?=__("Normal",   $this->td);?></option>
                        <option value="compact"><?=__("Compact", $this->td);?></option>
                      </select></div>
                    </div>
                    <div class="fields-wrapper-inner row justify-content-between align-items-center mb-3 field-opt-theme"           data-show="recaptcha" >
                      <div class="col-lg-4"><?=__("Widget Size",$this->td);?></div>
                      <div class="col-lg-8"><select autocomplete="off" class='form-input single-html-attributes' name="theme">
                        <option value="light"><?=__("Light", $this->td);?></option>
                        <option value="dark"><?=__("Dark",   $this->td);?></option>
                      </select></div>
                    </div>
                    <div class="fields-wrapper-inner row justify-content-between align-items-center mb-3 field-opt-select-options"  data-show="select" >
                      <div class="col-lg-4"><?=__("Select Field Options",$this->td);?></div>
                      <div class="col-lg-8"><textarea autocomplete="off" type="text" placeholder="<?=__("Field Options per line, e.g. value:Title",$this->td);?>" class='form-input single-options' rows="4" name="options"></textarea></div>
                    </div>
                    <div class="fields-wrapper-inner row justify-content-between align-items-center mb-3 field-opt-default"         data-show="text|textarea|number|email|mobile|editor|date|select|color" >
                      <div class="col-lg-4"><?=__("Field Default Value",$this->td);?></div>
                      <div class="col-lg-8"><input autocomplete="off" type="text" placeholder="<?=__("Field Default Value",$this->td);?>" class='form-input single-default-value' name="default" /></div>
                    </div>
                    <div class="fields-wrapper-inner row justify-content-between align-items-center mb-3 field-opt-login"           data-show="recaptcha" >
                      <div class="col-lg-4"><?=__("Use in Login form?",$this->td);?></div>
                      <div class="col-lg-8">
                        <label class="row w-100 align-items-center pl-3 pr-1">
                          <input autocomplete="off" type="checkbox" class='form-checkbox single-required mr-2' checked name="login" /> <?=__("Check to Use in Login form",$this->td);?>
                        </label>
                      </div>
                    </div>
                    <div class="fields-wrapper-inner row justify-content-between align-items-center mb-3 field-opt-verification"    data-show="recaptcha" >
                      <div class="col-lg-4"><?=__("Use in Verification form?",$this->td);?></div>
                      <div class="col-lg-8">
                        <label class="row w-100 align-items-center pl-3 pr-1">
                          <input autocomplete="off" type="checkbox" class='form-checkbox single-required mr-2' checked name="verification" /> <?=__("Check to Use in Verification form",$this->td);?>
                        </label>
                      </div>
                    </div>
                    <div class="fields-wrapper-inner row justify-content-between align-items-center mb-3 field-opt-is-required"     data-show="all" data-hide="recaptcha">
                      <div class="col-lg-4"><?=__("Is Required field?",$this->td);?></div>
                      <div class="col-lg-8">
                        <label class="row w-100 align-items-center pl-3 pr-1">
                          <input autocomplete="off" type="checkbox" class='form-checkbox single-required mr-2' name="is-required" />
                          <?=__("Check to mark field as Required",$this->td);?>
                        </label>
                      </div>
                    </div>
                    <div class="fields-wrapper-inner row justify-content-between align-items-center mb-3 field-opt-is-editable"     data-show="all" data-hide="recaptcha">
                      <div class="col-lg-4"><?=__("Editable on Profile?",$this->td);?></div>
                      <div class="col-lg-8">
                        <label class="row w-100 align-items-center pl-3 pr-1">
                          <input autocomplete="off" type="checkbox" class='form-checkbox single-required mr-2' checked name="is-editable" />
                          <?=__("Check to make field Editable on Profile",$this->td);?>
                        </label>
                      </div>
                    </div>
                    <div class="fields-wrapper-inner row justify-content-between align-items-center mb-3 field-opt-is-public"       data-show="all" data-hide="recaptcha">
                      <div class="col-lg-4"><?=__("Show in Registeration form?",$this->td);?></div>
                      <div class="col-lg-8">
                        <label class="row w-100 align-items-center pl-3 pr-1">
                          <input autocomplete="off" type="checkbox" class='form-checkbox single-required mr-2' checked name="is-public" />
                          <?=__("Check to Show field in Registeration form",$this->td);?>
                        </label>
                      </div>
                    </div>
                    <div class="fields-wrapper-inner row justify-content-between align-items-center mb-3 field-opt-in-column"       data-show="all" data-hide="recaptcha">
                      <div class="col-lg-4"><?=__("Show in Admin Column?",$this->td);?></div>
                      <div class="col-lg-8">
                        <label class="row w-100 align-items-center pl-3 pr-1">
                          <input autocomplete="off" type="checkbox" class='form-checkbox single-required mr-2' checked name="in-column" />
                          <?=__("Check to Show field in Admin Column",$this->td);?>
                        </label>
                      </div>
                    </div>
                    <?php do_action( "pepro_reglogin_register_fields_inner_options"); ?>
                  </div>
                </div>
              </template>
              <div class="row">
                <div class="col-lg-4 col-md-12">
                  <div class="mt-3 mb-3 fields--tools">
                    <h4 class="mb-3"><?=__("Login/Registeration Type", $this->td);?></h4>
                    <label class="row w-100 align-items-center m-0">
                      <input autocomplete="off" type="radio" class='form-checkbox iostoggle single-required mr-2 reglogin_type' <?=checked($this->reglogin_type === "mobile", true);?> name="reglogin_type" value="mobile" /><?=__("Using Mobile OTP", $this->td);?>
                    </label>
                    <label class="row w-100 align-items-center m-0">
                      <input autocomplete="off" type="radio" class='form-checkbox iostoggle single-required mr-2 reglogin_type' <?=checked($this->reglogin_type === "email", true);?> name="reglogin_type" value="email" /><?=__("Using Email/Username & Password", $this->td);?>
                    </label>
                  </div>
                  <div class="workspace">
                    <h4 class="mb-3 mt-4"><?=__("Registeration Default Fields", $this->td);?></h4>
                    <p><?=__("To activate and show a field, click on its name and check it.", $this->td);?></p>
                    <div class="save_checkboxes">
                      <?php do_action( "pepro_reglogin_show_hide_defaul_registeration_fields"); ?>
                    </div>
                  </div>
                </div>
                <div class="col-lg-8 col-md-12">
                  <div class="mt-3 mb-3 fields--tools">
                  <h4 class="mb-3"><?=__("Registeration Additional Fields", $this->td);?></h4>
                    <a href="javascript:;" class='btn btn-sm btn-primary register--add-field'><span class="material-icons">add_circle</span> <?=__("Add Field",$this->td);?></a>
                    <a href="javascript:;" class='btn btn-sm btn-primary register--toggle-fields'><span class="material-icons">expand</span> <?=__("Collapse / Expand",$this->td);?></a>
                    <a href="javascript:;" class='btn btn-sm btn-danger ml-4 mr-4 register--clear-fields'><span class="material-icons">delete_sweep</span> <?=__("Clear Fields",$this->td);?></a>
                  </div>
                  <div class="register-workspace workspace" data-empty="<?=__("No registerations field found.",$this->td);?>"></div>
                </div>
              </div>
              <br>
              <button
                class="login-section-save btn btn-success btn-primary icn-btn btn-wide"
                integrity="<?=wp_create_nonce('peprocorenounce')?>"
                wparam="loginregister" lparam="savelogin" dparam="" fn="">
                <i class='material-icons'>save</i> <?=_x("Save Settings", "login-section", $this->td);?>
              </button>
            </div>
          </div>
        </div>
      </div>
      <div class="tab-pane" id="tab_verification">
        <div class="card">
          <div class="card-header card-header-primary">
            <h4 class="card-title"><?=_x("Verification & Extras","login-section", $this->td);?></h4>
            <p class="card-category"><?=_x("You can control how verification and some extra feature works from here","login-section", $this->td);?></p>
          </div>
          <div class="card-body">
            <br>
            <div class="row save_checkboxes justify-content-between">
              <div class='col-lg-12 row justify-content-between align-items-center mb-3 field-opt-reg-auto-login '>
                <div class="col-lg-12">
                  <label class="row w-100 align-items-center pl-3 pr-1">
                    <input autocomplete="off" type="checkbox" class='form-checkbox single-required mr-2' <?=checked( $this->auto_login_after_reg, true);?>
                    id="auto_login_after_reg" name="auto_login_after_reg" /> <?=__("Registeration: Auto-login after signup",$this->td);?>
                  </label>
                </div>
              </div>
              <div class='col-lg-12 row justify-content-between align-items-center mb-3 field-opt-reg-verify-email '>
                <div class="col-lg-12">
                  <label class="row w-100 align-items-center pl-3 pr-1">
                    <input autocomplete="off" type="checkbox" class='form-checkbox single-required mr-2' <?=checked( $this->verify_email, true);?>
                    id="verify_email" name="verify_email" /> <?=__("Registeration: Verify E-mail",$this->td);?>
                  </label>
                </div>
              </div>
              <div class='col-lg-12 row justify-content-between align-items-center mb-3 field-opt-reg-verify-mobile '>
                <div class="col-lg-12">
                  <label class="row w-100 align-items-center pl-3 pr-1">
                    <input autocomplete="off" type="checkbox" class='form-checkbox single-required mr-2' <?=checked( $this->verify_mobile, true);?>
                    id="verify_mobile" name="verify_mobile" /> <?=__("Registeration: Verify Mobile by SMS?",$this->td);?>
                  </label>
                </div>
              </div>
              <div class='col-lg-12 row justify-content-between align-items-center mb-3 field-opt-reg-email-as-username '>
                <div class="col-lg-12">
                  <label class="row w-100 align-items-center pl-3 pr-1">
                    <input autocomplete="off" type="checkbox" class='form-checkbox single-required mr-2' <?=checked( $this->use_email_as_username, true);?>
                    id="use_email_as_username" name="use_email_as_username" /> <?=__("Registeration: Use E-mail as Username",$this->td);?>
                  </label>
                </div>
              </div>
              <div class='col-lg-12 row justify-content-between align-items-center mb-3 field-opt-rag-mobile-as-username '>
                <div class="col-lg-12">
                  <label class="row w-100 align-items-center pl-3 pr-1">
                    <input autocomplete="off" type="checkbox" class='form-checkbox single-required mr-2' <?=checked( $this->use_mobile_as_username, true);?>
                    id="use_mobile_as_username" name="use_mobile_as_username" /> <?=__("Registeration: Use Mobile as Username",$this->td);?>
                  </label>
                </div>
              </div>
              <?php do_action( "pepro_reglogin_verify_fields_inner_options"); ?>
            </div>
            <br>
            <button
              class="login-section-save btn btn-success btn-primary icn-btn btn-wide"
              integrity="<?=wp_create_nonce('peprocorenounce')?>"
              wparam="loginregister" lparam="savelogin" dparam="" fn="">
              <i class='material-icons'>save</i> <?=_x("Save Settings", "login-section", $this->td);?>
            </button>
          </div>
        </div>

        <div class="row">
          <div class="col-6">
            <div class="card">
              <div class="card-body">
                <div class="row justify-content-between save_sms_settings ">
                  <p class="font-weight-bold p-3"><?=__("Config SMS.ir WebAPI to send SMS Verification Codes", $this->td);?></p>
                  <div class='col-lg-12 row justify-content-between align-items-center mb-3 field-opt-sms_api_url '>
                    <div class="col-lg-6">
                      <?=__("Sending Number",$this->td);?> <a href="https://ip.sms.ir/#/UserSetting" class="btn btn-sm btn-round btn-group btn-info float-left m-0" target="_blank"><i class="fas fa-external-link-alt"></i></a>
                    </div>
                    <div class="col-lg-6">
                      <input autocomplete="off" type="text" id="sms_api_url" placeholder="<?=__("Sending Number",$this->td);?>" dir="ltr" class='form-input single-required mr-2' name="sms_api_url" value="<?=$this->sms_api_url;?>" />
                    </div>
                  </div>
                  <div class='col-lg-12 row justify-content-between align-items-center mb-3 field-opt-sms_secret_key '>
                    <div class="col-lg-6">
                      <?=__("Security code",$this->td);?> <a href="https://ip.sms.ir/#/UserApiKey" class="btn btn-sm btn-round btn-group btn-info float-left m-0" target="_blank"><i class="fas fa-external-link-alt"></i></a>
                    </div>
                    <div class="col-lg-6">
                      <input autocomplete="off" type="text" id="sms_secret_key" placeholder="<?=__("Security code",$this->td);?>" dir="ltr" class='form-input single-required mr-2' name="sms_secret_key" value="<?=$this->sms_secret_key;?>" />
                    </div>
                  </div>
                  <div class='col-lg-12 row justify-content-between align-items-center mb-3 field-opt-sms_api_key '>
                    <div class="col-lg-6">
                      <?=__("API Key",$this->td);?>
                    </div>
                    <div class="col-lg-6">
                      <input autocomplete="off" type="text" id="sms_api_key" placeholder="<?=__("API Key",$this->td);?>" dir="ltr" class='form-input single-required mr-2' name="sms_api_key" value="<?=$this->sms_api_key;?>" />
                    </div>
                  </div>
                  <div class='col-lg-12 row justify-content-between align-items-center mb-3 field-opt-sms_ultrafastsend_id '>
                    <div class="col-lg-6">
                      <?=__("UltraFastSend ID or SMS text containing [OTP]",$this->td);?> <a href="https://ip.sms.ir/#/User/UltraFastSendSetting" class="btn btn-sm btn-round btn-group btn-info float-left m-0" target="_blank"><i class="fas fa-external-link-alt"></i></a>
                    </div>
                    <div class="col-lg-6">
                      <input autocomplete="off" type="text" id="sms_ultrafastsend_id" placeholder="<?=__("UltraFastSend ID or SMS text containing [OTP]",$this->td);?>" dir="ltr" class='form-input single-required mr-2' name="sms_ultrafastsend_id" value="<?=$this->sms_ultrafastsend;?>" />
                    </div>
                  </div>
                  <div class='col-lg-12 row justify-content-between align-items-center mb-3 field-opt-sms_expiration '>
                    <div class="col-lg-6">
                      <?=__("Verification expire duration (in seconds)",$this->td);?>
                    </div>
                    <div class="col-lg-6">
                      <input autocomplete="off" type="text" id="sms_expiration" placeholder="<?=__("Verification expire duration",$this->td);?>" dir="ltr" class='form-input single-required mr-2' name="sms_expiration" value="<?=$this->sms_expiration;?>" />
                    </div>
                  </div>
                  <div class='col-lg-12 row justify-content-between align-items-center mb-3 field-opt-verification_digits '>
                    <div class="col-lg-6">
                      <?=__("Verification Code length",$this->td);?>
                    </div>
                    <div class="col-lg-6">
                      <input autocomplete="off" type="text" id="verification_digits" placeholder="<?=__("Verification Code length",$this->td);?>" dir="ltr" class='form-input single-required mr-2' name="verification_digits" value="<?=$this->verification_digits;?>" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-6">
            <div class="card">
              <div class="card-body">
                <div class="row justify-content-between save_email_settings ">
                  <p class="font-weight-bold p-3"><?=__("Config Email Settings to send Verification Codes", $this->td);?></p>
                  <div class='col-lg-12 row justify-content-between align-items-center mb-3 field-opt-email_expiration '>
                    <div class="col-lg-6">
                      <?=__("Verification expire duration (in seconds)",$this->td);?>
                    </div>
                    <div class="col-lg-6">
                      <input autocomplete="off" type="text" id="email_expiration" placeholder="<?=__("Verification expire duration",$this->td);?>" dir="ltr" class='form-input single-required mr-2' name="email_expiration" value="<?=$this->email_expiration;?>" />
                    </div>
                  </div>
                  <div class='col-lg-12 row justify-content-between align-items-center mb-3 field-opt-verification_email_digits '>
                    <div class="col-lg-6">
                      <?=__("Verification Code length",$this->td);?>
                    </div>
                    <div class="col-lg-6">
                      <input autocomplete="off" type="text" id="verification_email_digits" placeholder="<?=__("Verification Code length",$this->td);?>" dir="ltr" class='form-input single-required mr-2' name="verification_email_digits" value="<?=$this->verification_email_digits;?>" />
                    </div>
                  </div>
                  <div class='col-lg-12 row justify-content-between align-items-center mb-3 field-opt-verification_email_sender_name '>
                    <div class="col-lg-6">
                      <?=__("Verification Email Sender name",$this->td);?>
                    </div>
                    <div class="col-lg-6">
                      <input autocomplete="off" type="text" id="verification_email_sender_name" placeholder="<?=__("Verification Email Sender name",$this->td);?>" dir="ltr" class='form-input single-required mr-2' name="verification_email_sender_name" value="<?=$this->verification_email_sender_name;?>" />
                    </div>
                  </div>
                  <div class='col-lg-12 row justify-content-between align-items-center mb-3 field-opt-verification_email_sender '>
                    <div class="col-lg-6">
                      <?=__("Verification Email Sender address",$this->td);?>
                    </div>
                    <div class="col-lg-6">
                      <input autocomplete="off" type="text" id="verification_email_sender" title="<?="e.g. Enter noreply to send mail from noreply@".parse_url(get_bloginfo('url'), PHP_URL_HOST);?>" placeholder="<?="e.g. Enter noreply to send mail from noreply@".parse_url(get_bloginfo('url'), PHP_URL_HOST);?>" dir="ltr" class='form-input single-required mr-2' name="verification_email_sender" value="<?=$this->verification_email_sender;?>" />
                    </div>
                  </div>
                  <div class='col-lg-12 row justify-content-between align-items-center mb-3 field-opt-verification_email_template '>
                    <div class="col-lg-12">
                      <?=__("Verification Email Template",$this->td);?>
                      <textarea class="codeditor" id="verification_email_template_editor" autocomplete="off" spellcheck="false" dir="ltr" rows="8" cols="80"><?=wp_unslash($this->verification_email_template);?></textarea>
                      <textarea class="codeditor" id="verification_email_template" autocomplete="off" name="verification_email_template" spellcheck="false" dir="ltr" rows="8" cols="80" style="display:none !important;"><?=wp_unslash($this->verification_email_template);?></textarea>
                      <?=__("Available tags: ",$this->td);?>
                      <?php
                      $tags = (array) apply_filters( "pepro_reglogin_verification_email_replacements", array(
                        "[OTP]"           => "",
                        "[request_email]" => "",
                        "[username]"      => "",
                        "[first_name]"    => "",
                        "[last_name]"     => "",
                        "[display_name]"  => "",
                        "[user_email]"    => "",));
                        foreach ($tags as $key => $value) {
                          echo "<copy>$key</copy> ";
                        }
                        ?>
                      </div>
                    </div>
                  </div>
              </div>
            </div>
          </div>
        </div>


      </div>
      <div class="tab-pane" id="tab_redirection">
        <div class="card">
          <div class="card-header card-header-primary">
            <h4 class="card-title"><?=_x("Redirection","login-section", $this->td);?></h4>
            <p class="card-category"><?=_x("You can control wordpress redirection hooks from this section.","login-section", $this->td);?></p>
          </div>
          <div class="card-body">
            <div class="redirection-fields">
              <template id="redirection_raw_field">
                <div class="redirection-field-single p-2 mb-2 border">
                  <div class="redirection-field-single-title row justify-content-between align-items-center">
                    <div class="col-lg-9 ">
                      <h5 class='live-title m-0 p-2 text-primary font-weight-bold' data-default="<?=__("Redirection for: ",$this->td);?>"><?=__("New Redirection",$this->td);?></h5>
                    </div>
                    <div class="col-lg-3 <?=is_rtl()?"text-left":"text-right";?>">
                      <div class="btn-group m-0 p-0 ">
                        <a href="javascript:;" class='btn btn-sm btn-primary redirection--duplicate-field'><span class="material-icons">content_copy</span></a>
                        <a href="javascript:;" class='btn btn-sm btn-primary redirection--clear-field'><span class="material-icons">delete</span></a>
                        <a href="javascript:;" class='btn btn-sm btn-primary redirection--arrow-down-field'><span class="material-icons">arrow_downward</span></a>
                        <a href="javascript:;" class='btn btn-sm btn-primary redirection--arrow-up-field'><span class="material-icons">arrow_upward</span></a>
                      </div>
                    </div>
                  </div>
                  <div class="redirection-field-single-content">
                    <div class='fields-wrapper-inner row justify-content-between align-items-center mb-3 mt-3 field-opt-role' data-show="all" >
                      <div class="col-lg-4"><?=__("User Role",$this->td);?></div>
                      <div class="col-lg-8">
                        <select autocomplete="off" class="filteritem single-field-role" name="role">
                          <?php
                          $roles = $this->get_all_users_role();
                          array_walk($roles, function($val, $key){ echo "<option value=\"{$val["role"]}\">{$val["name"]}</option>"; }); ?>
                        </select>
                      </div>
                    </div>
                    <div class='fields-wrapper-inner row justify-content-between align-items-center mb-3 field-opt-url' data-show="all" >
                      <?php
                      $special_pages = $this->special_pages();
                      $newarray = $newarray2 = array();
                      foreach ($special_pages as $key => $value) { $newarray[] = "{{$key}}: {$value["name"]}"; $newarray2[] = "<div class='mt-2 ".(is_rtl()?"text-right":"text-left")." small'><strong><copy>{{$key}}</copy></strong> : {$value["name"]}</div>"; }
                      ?>
                      <div id="speciallinks" class="hide">
                        <div class="mt-2 <?=(is_rtl()?"text-right":"text-left");?> small"><?=__("You can use page ID as <b>#page_id</b>, e.g. for ID 275 use <b>#275</b>", $this->td);?></div>
                        <div class="mt-2 <?=(is_rtl()?"text-right":"text-left");?> small"><?=__("You can use page slug as <b>@page_slug</b>, e.g. for slug about-us use <b>@about-us</b>", $this->td);?></div>
                        <div class="mt-2 <?=(is_rtl()?"text-right":"text-left");?> small"><?=__("You can also enter full URL for external addresses, e.g. https://google.com/", $this->td);?></div>
                        <div class="mt-2 mb-2 <?=(is_rtl()?"text-right":"text-left");?> small"><?=__("And use following macros too:", $this->td);?></div>
                        <?=implode("", $newarray2);?>
                      </div>
                      <div class="col-lg-4"><?=__("Redirect to",$this->td);?> <a href="#TB_inline?width=800&height=500&inlineId=speciallinks" class="thickbox btn btn-sm btn-round btn-group btn-info float-left m-0"><i class="fas fa-exclamation-circle"></i></a></div>
                      <div class="col-lg-8">
                        <input autocomplete="off" title="<?=implode("\n", $newarray);?>" type="url" dir="ltr" required placeholder="<?=__('#page_id / @page_slug / {special_pages} / Full URL',$this->td);?>" class='form-input redirect-url' name="url" />
                      </div>
                    </div>
                    <div class='fields-wrapper-inner row justify-content-between align-items-center mb-3 field-opt-text' data-show="all" >
                      <div class="col-lg-4"><?=__("Ajax Popup Button text",$this->td);?></div>
                      <div class="col-lg-8">
                        <input autocomplete="off" type="text" value="<?=__("Let's go", $this->td);?>" required placeholder="<?=__('Ajax Popup Button text',$this->td);?>" class='form-input redirect-url' name="text" />
                      </div>
                    </div>
                    <div class='fields-wrapper-inner row justify-content-between align-items-center mb-3 mt-3 field-opt-usedfor' data-show="all" >
                      <div class="col-lg-4"><?=__("Used for",$this->td);?></div>
                      <div class="col-lg-8">
                        <div class='justify-content-between align-items-center mb-3 field-opt-login '>
                          <div class="col-lg-12">
                            <label class="row w-100 align-items-center">
                              <input autocomplete="off" type="checkbox" class='form-checkbox single-required mr-2' checked name="login" /> <?=__("Login Redirect",$this->td);?>
                            </label>
                          </div>
                        </div>
                        <div class='justify-content-between align-items-center mb-3 field-opt-register '>
                          <div class="col-lg-12">
                            <label class="row w-100 align-items-center">
                              <input autocomplete="off" type="checkbox" class='form-checkbox single-required mr-2' name="register" /> <?=__("Registeration Redirect",$this->td);?>
                            </label>
                          </div>
                        </div>
                        <div class='justify-content-between align-items-center mb-3 field-opt-logout '>
                          <div class="col-lg-12">
                            <label class="row w-100 align-items-center">
                              <input autocomplete="off" type="checkbox" class='form-checkbox single-required mr-2' name="logout" /> <?=__("Logout Redirect",$this->td);?>
                            </label>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php do_action( "pepro_reglogin_redirection_fields_inner_options"); ?>
                  </div>
                </div>
              </template>
              <div class="mt-3 mb-3">
                <a href="javascript:;" class='btn btn-primary redirection--add-field'><?=__("Add Rule",$this->td);?></a>
                <a href="javascript:;" class='btn btn-primary redirection--toggle-fields'><?=__("Collapse / Expand",$this->td);?></a>
                <a href="javascript:;" class='btn btn-danger ml-4 mr-4 redirection--clear-fields'><?=__("Clear Rules",$this->td);?></a>
              </div>
              <div class="redirection-workspace workspace" data-empty="<?=__("No redirections rule found.",$this->td);?>"></div>
            </div>
            <br>
            <button
              class="login-section-save btn btn-success btn-primary icn-btn btn-wide"
              integrity="<?=wp_create_nonce('peprocorenounce')?>"
              wparam="loginregister" lparam="savelogin" dparam="" fn="">
              <i class='material-icons'>save</i> <?=_x("Save Settings", "login-section", $this->td);?>
            </button>
          </div>
        </div>
      </div>
      <div class="tab-pane" id="tab_advanced">
        <div class="card">
          <div class="card-header card-header-primary">
            <h4 class="card-title"><?=_x("Custom Codes", "login-section", $this->td);?></h4>
            <p class="card-category"><?=_x("Add extra CSS or Custom Heading/Footer (supports shortcodes)", "login-section", $this->td);?></p>
          </div>
          <div class="card-body table-responsive">
            <table class="table">
              <tbody>
                <tr>
                  <td class="w-100">
                    <?=_x("Custom CSS Codes", "login-section", $this->td);?>
                    <textarea autocomplete="off" name="customcss" spellcheck="false" dir="ltr" rows="8" cols="80" id="customcss" style="display:none !important;"><?=stripslashes(get_option("{$this->activation_status}-customcss", ""));?></textarea>
                    <textarea autocomplete="off" spellcheck="false" dir="ltr" rows="8" cols="80" id="customcss_editor"><?=stripslashes(get_option("{$this->activation_status}-customcss", ""));?></textarea>
                  </td>
                </tr>
                <tr>
                  <td class="w-100">
                    <?=_x("Heading HTML Content", "login-section", $this->td);?>
                    <textarea autocomplete="off" name="headerhtml" spellcheck="false" dir="ltr" rows="8" cols="80" style="display:none !important;" id="headerhtml"><?=stripslashes(get_option("{$this->activation_status}-headerhtml", ""));?></textarea>
                    <textarea autocomplete="off" spellcheck="false" dir="ltr" rows="8" cols="80" id="headerhtml_editor"><?=stripslashes(get_option("{$this->activation_status}-headerhtml", ""));?></textarea>
                  </td>
                </tr>
                <tr>
                  <td class="w-100">
                    <?=_x("Footer HTML Content", "login-section", $this->td);?>
                    <textarea autocomplete="off" name="footerhtml" spellcheck="false" dir="ltr" rows="8" cols="80" style="display:none !important;" id="footerhtml"><?=stripslashes(get_option("{$this->activation_status}-footerhtml", ""));?></textarea>
                    <textarea autocomplete="off" spellcheck="false" dir="ltr" rows="8" cols="80" id="footerhtml_editor"><?=stripslashes(get_option("{$this->activation_status}-footerhtml", ""));?></textarea>
                  </td>
                </tr>
              </tbody>
            </table>
            <br>
            <button
              class="login-section-save btn btn-success btn-primary icn-btn btn-wide"
              integrity="<?=wp_create_nonce('peprocorenounce')?>"
              wparam="loginregister" lparam="savelogin" dparam="" fn="">
              <i class='material-icons'>save</i> <?=_x("Save Settings", "login-section", $this->td);?>
            </button>
          </div>
        </div>
      </div>
      <div class="tab-pane" id="tab_security">
        <div class="card">
          <div class="card-header card-header-primary">
            <h4 class="card-title"><?=_x("Security & Permalinks","login-section", $this->td);?></h4>
            <p class="card-category"><?=_x("You can control wordpress login, register and lost password permalinks from here","login-section", $this->td);?></p>
          </div>
          <div class="card-body table-responsive">
            <div id="alert-primary"><div class="alert alert-success alert-dismissible fade show" role="alert"> <?php printf( _x('%1$s Your login page is: %2$s. Bookmark this page!',"login-section",$this->td), "<strong>"._x("Attention!","login-section",$this->td)."</strong>", "<strong><u><a href='".wp_login_url()."' target='_blank'>".untrailingslashit(wp_login_url())."</a></u></strong>" ); ?></div></div>
            <table class="table pepcappearance">
              <tbody>
                <tr>
                  <td><?=_x("Login Base Slug","login-section", $this->td);?></td>
                  <td>
                    <input type="text" id="login-section-loginslug" value="<?=get_option("whl_page","login");?>" class="form-control primary" placeholder="<?=_x("Login slug","login-section", $this->td);?>" />
                  </td>
                </tr>
                <tr>
                  <td><?=_x("Redirect Slug","login-section", $this->td);?></td>
                  <td>
                    <input type="text" id="login-section-redirectslug" value="<?=get_option("whl_redirect_admin","403");?>" class="form-control primary" placeholder="<?=_x("Redirect slug","login-section", $this->td);?>" />
                  </td>
                </tr>
                <tr>
                  <td><?=_x("Shake Effect","login-section", $this->td);?></td>
                  <td>
                      <a class='btncheckbox'
                        data-text-on='<?=_x("Yes, Keep it","login-section", $this->td);?>'
                        data-text-off='<?=_x("No, Remove it","login-section", $this->td);?>'
                        data-on='visibility'
                        data-off='visibility_off'
                        data-checked='<?=get_option("{$this->activation_status}-shake","true") === "true" ? "true" : "false";?>' id="login-section-shake"></a>
                  </td>
                </tr>
                <tr>
                  <td><?=_x("Login Errors","login-section", $this->td);?></td>
                  <td>
                      <a class='btncheckbox'
                        data-text-on='<?=_x("Yes, Show them","login-section", $this->td);?>'
                        data-text-off='<?=_x("No, Hide them","login-section", $this->td);?>'
                        data-on='visibility'
                        data-off='visibility_off'
                        data-checked='<?=get_option("{$this->activation_status}-error","true") === "true" ? "true" : "false";?>' id="login-section-error"></a>
                  </td>
                </tr>
                <tr>
                  <td><?=_x("Login Messages","login-section", $this->td);?></td>
                  <td>
                      <a class='btncheckbox'
                        data-text-on='<?=_x("Yes, Show them","login-section", $this->td);?>'
                        data-text-off='<?=_x("No, Hide them","login-section", $this->td);?>'
                        data-on='visibility'
                        data-off='visibility_off'
                        data-checked='<?=get_option("{$this->activation_status}-msg","true") === "true" ? "true" : "false";?>' id="login-section-msg"></a>
                  </td>
                </tr>
              </tbody>
            </table>
            <br>
            <button
              class="login-section-save btn btn-success btn-primary icn-btn btn-wide"
              integrity="<?=wp_create_nonce('peprocorenounce')?>"
              wparam="loginregister" lparam="savelogin" dparam="" fn="">
              <i class='material-icons'>save</i> <?=_x("Save Settings", "login-section", $this->td);?>
            </button>
          </div>
        </div>
      </div>
      <div class="tab-pane" id="tab_migrate">
        <div class="card">
          <div class="card-header card-header-primary">
            <h4 class="card-title"><?=_x("Import/Export","login-section", $this->td);?></h4>
            <p class="card-category"><?=_x("Sync settings between your sites or make a backup for future","login-section", $this->td);?></p>
          </div>
          <div class="card-body table-responsive">
            <div class="register-fields-import-export">
              <h4><?=__("Import/Export Fields as JSON data",$this->td);?></h4>
              <textarea class="codeditor" id="register_fileds_editor" autocomplete="off" spellcheck="false" dir="ltr" rows="8" cols="80"><?=wp_unslash(get_option("pepro-profile-register-fileds"));?></textarea>
              <textarea class="codeditor" id="register_fileds" autocomplete="off" spellcheck="false" dir="ltr" rows="8" cols="80" style="display:none !important;"><?=wp_unslash(get_option("pepro-profile-register-fileds"));?></textarea>
            </div>
            <div class="redirection-fields-import-export">
              <h4><?=__("Import/Export Redirection Rules as JSON data",$this->td);?></h4>
              <textarea class="codeditor" id="redirection_fileds_editor" autocomplete="off" spellcheck="false" dir="ltr" rows="8" cols="80"><?=wp_unslash(get_option("pepro-profile-redirection-fileds"));?></textarea>
              <textarea class="codeditor" id="redirection_fileds" autocomplete="off" spellcheck="false" dir="ltr" rows="8" cols="80" style="display:none !important;"><?=wp_unslash(get_option("pepro-profile-redirection-fileds"));?></textarea>
            </div>
            <br>
            <button
              class="login-section-save btn btn-success btn-primary icn-btn btn-wide"
              integrity="<?=wp_create_nonce('peprocorenounce')?>"
              wparam="loginregister" lparam="savelogin" dparam="" fn="">
              <i class='material-icons'>save</i> <?=_x("Import (Overwrite) Settings", "login-section", $this->td);?>
            </button>
          </div>
        </div>
      </div>
      <div class="tab-pane" id="tab_samrt_button">
        <div class="card">
          <div class="card-header card-header-primary">
            <h4 class="card-title"><?=_x("Samrt Button","login-section", $this->td);?></h4>
            <p class="card-category"><?=_x("Use this button to show login/register popup to guests and welcome logged in users","login-section", $this->td);?></p>
          </div>
          <div class="card-body table-responsive">
            <div class="row justify-content-between smart_btn_workspace">
              <div class="col-lg-6 justify-content-between mt-3 mb-3">
                <pre style="direction: ltr;text-align: left;" class="border p-3">[pepro-smart-btn
  loggedin_text="Hi {display_name}"
  loggedin_href="/profile"
  loggedin_avatar="yes"
  loggedin_avatar_size="32"
  loggedin_class="button button-primary"
  loggedout_text="Login/Register"
  loggedout_form="login"
  loggedout_class="button button-primary"
  login_popup_title="Login"
  register_popup_title="Register" ]</pre>
<button type="button" id="copyshortcode" class="btn btn-primary" style="position: relative;transform: translate(-0.5rem, -4.5rem);"><span class="material-icons">content_copy</span></button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
