<?php
# @Last modified by:   Amirhosseinhpv
# @Last modified time: 2021/09/02 16:22:45

global $PeproDevUPS_Profile;
$assets      = plugins_url("/assets/", dirname(__FILE__));
$colorScheme = $this->read_opt($this->ns."___theme-color","purple");
$assetsImg   = $this->read_opt($this->ns."___theme-sidebar-image","$assets/img/one.jpg");
$dashttle    = $this->read_opt($this->ns."___dashboard-title",_x("PeproDev Ultimate Profile Solutions","peprocore-appearance-setting","pepro"));
if($assetsImg === "custom"){
  $assetsImg = $this->read_opt($this->ns."___theme-sidebar-image-custom","$assets/img/one.jpg");
}
$default = array();
$navs = "";
$links = apply_filters("peprocore_dashboard_nav_menuitems", $default);
uasort($links,  $this->advanced_2dArray_compare('priority'));

foreach ($links as $link) {
  $allowedAdds[] = "home";
  $actibe = "";
  $state1 = "{$link['id']} ";
  $urla = $link['link'];
  if (substr($link['link'], 0 ,1) === "@"){
    $allowedAdds[] = substr($link['link'], 1);
    $urla = admin_url("?page=pepro&section=" . substr($link['link'], 1));
    if (isset($_GET["section"]) && !empty($_GET["section"]) && (sanitize_text_field( $_GET["section"] ) === substr($link['link'], 1))){
      $actibe = "active";
      $pageTitle = $link['titleW']? "{$link['titleW']}" : "";
      $pageTitle2 = $link['titleW']? "{$link['titleW']} | " : "";
      echo "<script>document.title = '{$pageTitle2}{$this->title}';</script>";
      $Ufn = (isset($link['fn']) && !empty($link['fn'])) ? $link['fn'] : array($this, "DummyData");
    }
  }
  $navs .= "<li class='nav-item $actibe $state1'><a class='nav-link' href='{$urla}'>{$link['icon']}<p>{$link['title']}</p></a></li>";
}

$allowedAdds = array_unique($allowedAdds);
$ssection = sanitize_text_field(trim($_GET['section']));
if (!isset($ssection) || empty($ssection)  || !in_array($ssection,$allowedAdds)){
  wp_safe_redirect(admin_url("admin.php?page=pepro&section=home"),301 );
  wp_die('<script type="text/javascript">location.href = "'.admin_url("admin.php?page=pepro&section=home").'";</script>');
  exit;
}

?>
<div class="wrapper" data-color="<?php echo $colorScheme;?>">
  <div class="sidebar" data-color="<?php echo $colorScheme;?>" data-background-color="black" data-image="<?php echo $assetsImg;?>">
    <div class="logo"><a href="<?php echo admin_url("?page=pepro&section=home");?>" class="simple-text logo-normal"><?php echo $dashttle;?></a></div>
    <div class="sidebar-wrapper">
      <ul class="nav">

        <li class="nav-item multiple">
          <a class="nav-link" title="<?php echo __("WordPress Admin", $this->td);?>" href="<?php echo admin_url();?>"><?php echo "<i class='material-icons'>wordpress</i>";?></a>
          <a class="nav-link" title="<?php echo __("Homepage", $this->td);?>" href="<?php echo home_url();?>"><?php echo "<i class='material-icons'>home</i>";?></a>
          <a class="nav-link" title="<?php echo __("User Profile", $this->td);?>" href="<?php echo $PeproDevUPS_Profile->url;?>"><?php echo "<i class='material-icons'>supervised_user_circle</i>";?></a>
        </li>

        <?php echo $navs;?>
      </ul>
    </div>
  </div>
  <div class="main-panel">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top " id="navigation-example">
      <div class="container-fluid">
        <div class="navbar-wrapper"><a class="navbar-brand" href="javascript:void(0)"><?php echo $pageTitle;?></a></div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation" data-target="#navigation-example">
          <span class="sr-only">Toggle navigation</span>
          <span class="navbar-toggler-icon icon-bar"></span>
          <span class="navbar-toggler-icon icon-bar"></span>
          <span class="navbar-toggler-icon icon-bar"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end"><ul class="navbar-nav"></ul></div>
      </div>
    </nav>
    <!-- End Navbar -->
    <div class="content"> <div class="container-fluid"> <?php call_user_func_array($Ufn, array()); ?> </div> </div>
    <footer class="footer">
      <div class="container-fluid">
        <nav class="float-left">
          <ul>
            <li><a href="https://pepro.dev/"><?php echo __("Pepro Dev","pepro");?></a></li>
            <li><a href="https://pepro.dev/support"><?php echo __("Support","pepro");?></a></li>
          </ul>
        </nav>
        <div class="copyright float-right">
          <?php
          // translators: %1$s: Date / %2$s: love icon / by %3$s: copyright holder
          printf(__('Copyright %1$s, made with %2$s by %3$s for a better web.',"pepro"), date("Y"), '<i class="material-icons">favorite</i>', '<a href="https://pepro.dev/" target="_blank">'.__("Pepro Dev","pepro").'</a>' );
          ?>
        </div>
      </div>
    </footer>
  </div>
</div>
