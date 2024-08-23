<?php
/*
 * @Author: Amirhossein Hosseinpour <https://amirhp.com>
 * @Date Created: 2024/05/17 12:27:18
 * @Last modified by: amirhp-com <its@amirhp.com>
 * @Last modified time: 2024/08/24 02:38:04
 */

function woodmart_login_form( $echo = true, $action = false, $message = false, $hidden = false, $redirect = false ){
  ob_start();
  ?>
  <style>
      /* .wd-dropdown-register .wd-heading { display: none !important; } */
      .woodmart-login-popup section, .woodmart-login-popup section .pepro-login-reg-container{ margin: 0 !important; padding: 0 !important; }
      .woodmart-login-popup section .pepro-login-reg-container.inline-form > form.inline { margin-top: 1rem !important; padding: 1rem !important;}
      .woodmart-login-popup section .pepro-login-reg-container .switcher a { padding: 0.5rem !important; }
      .login-dropdown-inner .wd-heading .create-account-link { display: none !important; }
      .woodmart-login-popup .pepro-login-reg-container .switcher a { font-size: 1rem; }
      .wd-dropdown.wd-dropdown-register { padding: 0.8rem !important; }
  </style>
  <div class="login woocommerce-form woodmart-login-popup <?php echo $hidden?"hidden-form":"";?>" <?php echo $hidden?'style="display:none;"':'';?> data-pepro-woodmart="integrated"><?php echo do_shortcode("[pepro-login-form]");?></div>
  <?php 
  $htmloutput = ob_get_contents(); ob_end_clean();
  if ( $echo ) { echo $htmloutput; } 
  else { return $htmloutput; }
}