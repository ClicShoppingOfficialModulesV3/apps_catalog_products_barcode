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

use ClicShopping\OM\CLICSHOPPING;
?>
<div class="<?php echo $text_position; ?> col-md-<?php echo $content_width; ?>">
  <div class="separator"></div>
  <div class="ModulesProductsInfoCodeEan">
    <span><?php echo CLICSHOPPING::getDef('module_products_info_code_bar_text'); ?></span>
    <span class="ModulesProductsInfoBarCode"><?php echo $products_barcode; ?></span>
  </div>
</div>
