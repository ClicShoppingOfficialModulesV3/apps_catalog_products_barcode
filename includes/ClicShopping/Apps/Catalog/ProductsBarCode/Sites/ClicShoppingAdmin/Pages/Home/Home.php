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

  namespace ClicShopping\Apps\Catalog\ProductsBarCode\Sites\ClicShoppingAdmin\Pages\Home;

  use ClicShopping\OM\Registry;

  use ClicShopping\Apps\Catalog\ProductsBarCode\ProductsBarCode;

  class Home extends \ClicShopping\OM\PagesAbstract {
    public mixed $app;

    protected function init() {
      $CLICSHOPPING_ProductsBarCode = new ProductsBarCode();
      Registry::set('ProductsBarCode', $CLICSHOPPING_ProductsBarCode);

      $this->app = $CLICSHOPPING_ProductsBarCode;

      $this->app->loadDefinitions('Sites/ClicShoppingAdmin/main');
    }
  }
