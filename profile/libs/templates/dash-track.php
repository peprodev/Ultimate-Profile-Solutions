<?php
# @Last modified by:   Amirhosseinhpv
# @Last modified time: 2021/09/15 14:50:29

global $PeproDevUPS_Profile, $wp;
$current_page = $_SERVER['REQUEST_URI'];
$PeproDevUPS_Profile->change_dashboard_title(_x("Order Tracking","user-dashboard","peprodev-ups"));
$PeproDevUPS_Profile->dashboard_add_css($PeproDevUPS_Profile->get_wc_asset_url( 'assets/css/woocommerce-layout.css' ));
$PeproDevUPS_Profile->dashboard_add_css($PeproDevUPS_Profile->get_wc_asset_url( 'assets/css/woocommerce-smallscreen.css' ));
$PeproDevUPS_Profile->dashboard_add_css($PeproDevUPS_Profile->get_wc_asset_url( 'assets/css/woocommerce.css' ));
$PeproDevUPS_Profile->dashboard_add_css_inline("
@media screen and (max-width: 600px){
  .woocommerce-form.woocommerce-form-track-order.track_order {
    display: flex;
    flex-direction: column;
    align-content: center;
    align-items: center;
  }
  .woocommerce-form.woocommerce-form-track-order.track_order > * {
	   width: 100%;
   }
}
.woocommerce table.shop_table_responsive thead, .woocommerce-page table.shop_table_responsive thead {
	display: table-header-group;
}
.woocommerce table.shop_table_responsive tr td, .woocommerce-page table.shop_table_responsive tr td {
	display: table-cell;
}
.woocommerce h2{
  font-size: 1rem;
  margin: 1rem 0;
}
.woocommerce table.shop_table_responsive tbody tr:first-child td:first-child, .woocommerce-page table.shop_table_responsive tbody tr:first-child td:first-child {
	border-top: rgba(0, 0, 0, 0.1) solid 1px;
}
.o-headline.o-headline--profile {
	display: none;
}
.zmdi.zmdi-long-arrow-return{
  -webkit-transform: rotate(90deg) scaleY(-1);
  -moz-transform: rotate(90deg) scaleY(-1);
  transform: rotate(90deg) scaleY(-1);
}
.woocommerce table.shop_table_responsive tr td::before, .woocommerce-page table.shop_table_responsive tr td::before {
  content: '';
}
.woocommerce table.shop_table_responsive tr, .woocommerce-page table.shop_table_responsive tr {
	display: table-row;
}
");
is_rtl() AND $PeproDevUPS_Profile->dashboard_add_css_inline('
.zmdi.zmdi-long-arrow-return{
  -webkit-transform: rotate(90deg);
  -moz-transform: rotate(90deg);
  transform: rotate(90deg);
}
[dir="rtl"] address, [dir="rtl"] .woocommerce-table.order_details {
	text-align: right !important;
}
.woocommerce .woocommerce-customer-details .woocommerce-customer-details--email, .woocommerce .woocommerce-customer-details .woocommerce-customer-details--phone {
	margin-bottom: 0;
	padding-left: 0;
	padding-right: 1.5em;
}
.woocommerce .woocommerce-customer-details .woocommerce-customer-details--email::before,
.woocommerce .woocommerce-customer-details .woocommerce-customer-details--phone::before {
	margin-right: -1.5em;
	margin-left: auto;
  line-height: 1.2;
}
');
?>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
        <div class="overview-wrap">
            <h2 class="title-1"><?php echo esc_html_x("Order Tracking","user-dashboard","peprodev-ups");?></h2>
        </div>
    </div>
  </div>
  <div class="row m-t-25">
    <div class="col-12">
        <div class="card">
            <div class="card-header"><?php echo esc_html_x("Order Tracking","user-dashboard","peprodev-ups");?>
             <a class="back-to-top btn btn-warning btn-sm mr-2 ml-2" style="padding: 0 0.3rem; font-size: 0.8rem; display:none;" href="<?php echo $current_page;?>">
             <i class="zmdi zmdi-long-arrow-return"></i>
           </a>
            </div>
            <div class="card-body">
              <?php
              echo do_shortcode( "[woocommerce_order_tracking]");
              ?>
            </div>
        </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  window.onload = function(e){
    var divs = document.querySelectorAll(`input.input-text`), i;for (i = 0; i < divs.length; ++i) {divs[i].className = 'form-control';}
    var divs = document.querySelectorAll(`.track_order .form-row>label`), i;for (i = 0; i < divs.length; ++i) {divs[i].className = 'mb-2 mt-4';}
    var divs = document.querySelectorAll(`.track_order button[name="track"]`), i;for (i = 0; i < divs.length; ++i) {divs[i].className = 'mt-4 btn btn-lg btn-info btn-block';}
    var divs = document.querySelectorAll(`.woocommerce-MyAccount-downloads-file`), i;for (i = 0; i < divs.length; ++i) {
      divs[i].className = 'btn btn-outline-primary btn-sm btn-block <?php echo ("text-left")?>';
      divs[i].innerHTML = '<i class="fa fa-download mr-10 ml-10"></i> ' + divs[i].innerHTML;
    }
    var divs = document.querySelectorAll(`p.order-again>a.button`), i;for (i = 0; i < divs.length; ++i) {divs[i].className = 'btn btn-outline-primary btn-block active';}
    var divs = document.querySelectorAll(`form.track_order`), i;for (i = 0; i < divs.length; ++i) {divs[i].action = '<?php echo $current_page;?>';}
    if (document.querySelector(`p.order-info`)){
      document.querySelector(`a.back-to-top`).style.display = "";
    }
  }
</script>
