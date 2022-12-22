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

  namespace ClicShopping\Apps\Catalog\ProductsBarCode\Sites\ClicShoppingAdmin\Pages\Home\Actions\Configure;

  use ClicShopping\OM\Registry;

  use ClicShopping\OM\Cache;

  class Install extends \ClicShopping\OM\PagesActionsAbstract {

    public function execute() {

      $CLICSHOPPING_MessageStack = Registry::get('MessageStack');
      $CLICSHOPPING_ProductsBarCode = Registry::get('ProductsBarCode');

      $current_module = $this->page->data['current_module'];

      $CLICSHOPPING_ProductsBarCode->loadDefinitions('Sites/ClicShoppingAdmin/install');

      $m = Registry::get('ProductsBarCodeAdminConfig' . $current_module);
      $m->install();

      static::installProductBarcodeDb();

      $CLICSHOPPING_MessageStack->add($CLICSHOPPING_ProductsBarCode->getDef('alert_module_install_success'), 'success', 'products_barcode');

      $CLICSHOPPING_ProductsBarCode->redirect('Configure&module=' . $current_module);
    }

    private static function installProductBarcodeDb() {
      $CLICSHOPPING_ProductsBarCode = Registry::get('ProductsBarCode');

      $Qcheck = $CLICSHOPPING_ProductsBarCode->db->query('describe :table_products products_barcode');


      if ($Qcheck->rowCount() == 0 && !defined('BAR_CODE_TYPE')) {
$sql = <<<EOD
        INSERT INTO :table_configuration VALUES (null, 'Please select the type of bar code', 'BAR_CODE_TYPE', 'EAN', 'Please indicate the type of bar code.', 9, 8, '2008-09-14 20:47:30', '2007-06-24 02:52:29', NULL, 'clic_cfg_set_boolean_value(array(\'C128\', \'C128C\', \'C25\', \'C25I\', \'MSI\', \'EAN\', \'UPC\', \'C39\', \'C11\', \'CODABAR\', \'POSTNET\', \'CMC7\', \'KIX\'))');
        INSERT INTO :table_configuration VALUES (null, 'Please indicate the size of the bar code', 'BAR_CODE_SIZE', '50', 'Please indicate the size of the image of the bar code.', 9, 9, '2006-10-23 22:49:44', '2006-04-09 16:13:47', NULL, NULL);
        INSERT INTO :table_configuration VALUES (null, 'Please indicate the color of the bar code', 'BAR_CODE_COLOR', '#254433', 'Please indicate the color of the bar code (in hezadécimal).', 9, 10, '2006-10-23 22:49:44', '2006-04-09 16:13:47', NULL, NULL);
        INSERT INTO :table_configuration VALUES (null, 'Please indicate extension image that will be generated', 'BAR_CODE_EXTENSION', 'png', 'Please indicate extension image that will be generated.', 9, 11, '2008-09-14 20:47:30', '2007-06-24 02:52:29', NULL, 'clic_cfg_set_boolean_value(array(\'png\', \'gif\', \'jpg\')');

        ALTER TABLE :table_products ADD products_barcode varchar(50) null AFTER products_type;
EOD;
        Cache::clear('configuration');

        $CLICSHOPPING_ProductsBarCode->db->exec($sql);
      }
    }
  }
