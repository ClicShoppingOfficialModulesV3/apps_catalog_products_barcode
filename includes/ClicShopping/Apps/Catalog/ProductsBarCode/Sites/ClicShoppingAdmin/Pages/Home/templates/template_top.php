<?php
/**
 *
 *  @copyright 2008 - https://www.clicshopping.org
 *  @Brand : ClicShopping(Tm) at Inpi all right Reserved
 *  @Licence GPL 2 & MIT
 *  @licence MIT - Portion of osCommerce 2.4
 *  @Info : https://www.clicshopping.org/forum/trademark/
 *
 */

use ClicShopping\OM\HTML;
  use ClicShopping\OM\CLICSHOPPING;
  use ClicShopping\OM\Registry;

  $CLICSHOPPING_MessageStack = Registry::get('MessageStack');
  $CLICSHOPPING_ProductsBarCode = Registry::get('ProductsBarCode');
  $CLICSHOPPING_Page = Registry::get('Site')->getPage();

  if ($CLICSHOPPING_MessageStack->exists('ProductsBarCode')) {
      echo $CLICSHOPPING_MessageStack->get('ProductsBarCode');
  }
?>

<div class="contentBody">
  <div class="row">
    <div class="col-md-12">
      <div class="card card-block headerCard">
        <div class="row">
          <span class="col-md-1 logoHeading"><?php echo HTML::image($CLICSHOPPING_Template->getImageDirectory() . 'categories/barcode.png', $CLICSHOPPING_ProductsBarCode->getDef('heading_title'), '40', '40'); ?></span>
          <span class="col-md-4 pageHeading"><?php echo '&nbsp;' . $CLICSHOPPING_ProductsBarCode->getDef('heading_title'); ?></span>
          <span class="col-md-7 text-end"><?php echo HTML::button($CLICSHOPPING_ProductsBarCode->getDef('button_back'), null, $CLICSHOPPING_ProductsBarCode->link(null, 'A&Catalog\ProductsBarCode'),  'primary'); ?>
        </div>
      </div>
    </div>
  </div>

