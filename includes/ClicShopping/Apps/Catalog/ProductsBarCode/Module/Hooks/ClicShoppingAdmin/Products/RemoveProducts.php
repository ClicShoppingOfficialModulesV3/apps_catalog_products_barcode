<?php
  /**
   *
   * @copyright 2008 - https://www.clicshopping.org
   * @Brand : ClicShopping(Tm) at Inpi all right Reserved
   * @Licence GPL 2 & MIT
   * @licence MIT - Portion of osCommerce 2.4
   * @Info : https://www.clicshopping.org/forum/trademark/
   *
   */

  namespace ClicShopping\Apps\Catalog\ProductsBarCode\Module\Hooks\ClicShoppingAdmin\Products;

  use ClicShopping\OM\Registry;
  use ClicShopping\OM\HTML;

  use ClicShopping\Apps\Catalog\ProductsBarCode\ProductsBarCode as ProductsBarCodeApps;

  class RemoveProduct implements \ClicShopping\OM\Modules\HooksInterface 
  {
    protected $app;

    public function __construct() 
    {
      if (!Registry::exists('ProductsBarCode')) {
        Registry::set('ProductsBarCode', new ProductsBarCodeApps());
      }

      $this->app = Registry::get('ProductsBarCode');
      $this->template = Registry::get('TemplateAdmin');
      $this->db = Registry::get('Db');
    }

    private function remove($id) 
    {
      $Qimage = Registry::get('Db')->get('products', 'products_bar_code', ['products_id' => (int)$id]);

      if ($Qimage !== false) {
        $barcode = $Qimage->value('products_bar_code');

        if (file_exists($this->template->getDirectoryPathTemplateShopImages() . 'barcode/' . $barcode . '_barcode.' . BAR_CODE_EXTENSION)) {
          @unlink($this->template->getDirectoryPathTemplateShopImages() . 'barcode/' . $barcode . '_barcode.' . BAR_CODE_EXTENSION);
        }
      }
    }

    public function execute() {
      if (!defined('CLICSHOPPING_APP_BAR_CODE_PB_STATUS') || CLICSHOPPING_APP_BAR_CODE_PB_STATUS == 'False') {
        return false;
      }

      if (isset($_POST['pID'])) {
        $id = HTML::sanitize($_POST['pID']);
        $this->remove($id);
      }
    }
  }