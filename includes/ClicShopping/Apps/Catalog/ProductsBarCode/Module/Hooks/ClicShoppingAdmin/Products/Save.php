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

  use ClicShopping\OM\CLICSHOPPING;
  use ClicShopping\OM\Registry;
  use ClicShopping\OM\HTML;

  use ClicShopping\Apps\Catalog\ProductsBarCode\ProductsBarCode as ProductsBarCodeApps;

  class Save implements \ClicShopping\OM\Modules\HooksInterface 
  {
    protected $app;
    protected $template;
    protected $lang;
    protected $db;

    public function __construct() {
      if (!Registry::exists('ProductsBarCode')) {
        Registry::set('ProductsBarCode', new ProductsBarCodeApps());
      }

      $this->app = Registry::get('ProductsBarCode');
      $this->template = Registry::get('TemplateAdmin');
      $this->db = Registry::get('Db');
    }

    private function createBarCode() 
    {
      $dir = $this->template->getDirectoryPathTemplateShopImages() . 'barcode/';

      if (!empty($_POST['products_barcode']) && isset($_POST['products_barcode'])) {
        require_once (CLICSHOPPING::BASE_DIR . 'Apps/Catalog/ProductsBarCode/Classes/Barcode.php');

        if (!empty($dir) && !is_dir($dir)) {
          @mkdir($dir, 0777, true);
        }

        $barcode = HTML::sanitize($_POST['products_barcode']);
        $objCode = new \Barcode() ;
        $objCode->setSize(BAR_CODE_SIZE);
        $objCode->hideCodeType();
        $objCode->setColors(BAR_CODE_COLOR);
        $objCode->setType(BAR_CODE_TYPE) ;
        $objCode->setCode($barcode) ;
        $objCode->setFiletype(BAR_CODE_EXTENSION) ;
        $objCode->writeBarcodeFile($this->template->getDirectoryPathTemplateShopImages() . 'barcode/' . $barcode . '_barcode.' . BAR_CODE_EXTENSION);
      }
    }

    private function save($id = null) {
      if (isset($id)) {
        $products_id = $id;
      } else {
        $Qproducts = $this->app->db->prepare('select products_id
                                              from :table_products
                                              order by products_id desc
                                              limit 1
                                            ');
        $Qproducts->execute();

        $products_id = $Qproducts->valueInt('products_id');
      }

      $sql_data_array = ['products_barcode' => HTML::sanitize($_POST['products_barcode'])];

      $this->db->save('products', $sql_data_array, ['products_id' => (int)$products_id]);

       $this->createBarCode();
    }

    public function execute() {
      if (!defined('CLICSHOPPING_APP_BAR_CODE_PB_STATUS') || CLICSHOPPING_APP_BAR_CODE_PB_STATUS == 'False') {
        return false;
      }

      if (isset($_GET['pID']) && !empty($_POST['products_barcode'])) {
        $id = HTML::sanitize($_GET['pID']);
        $this->save($id);
      }
    }
  }