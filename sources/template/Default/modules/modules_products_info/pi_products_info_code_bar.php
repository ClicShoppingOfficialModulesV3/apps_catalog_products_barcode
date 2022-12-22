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
  use ClicShopping\OM\Registry;
  use ClicShopping\OM\CLICSHOPPING;

  class pi_products_info_code_bar {
    public $code;
    public $group;
    public $title;
    public $description;
    public $sort_order;
    public $enabled = false;

    public function __construct() {
      $this->code = get_class($this);
      $this->group = basename(__DIR__);

      $this->title = CLICSHOPPING::getDef('module_products_info_code_bar');
      $this->description = CLICSHOPPING::getDef('module_products_info_code_bar_description');

      if (defined('MODULE_PRODUCTS_INFO_CODE_BAR_STATUS')) {
        $this->sort_order = MODULE_PRODUCTS_INFO_CODE_BAR_SORT_ORDER;
        $this->enabled = (MODULE_PRODUCTS_INFO_CODE_BAR_STATUS == 'True');
      }
      
       If (!defined('CLICSHOPPING_APP_BAR_CODE_PB_STATUS') || CLICSHOPPING_APP_BAR_CODE_PB_STATUS == 'False') {
        $this->enabled = false;
      }
    }

    public function execute() {
      $CLICSHOPPING_ProductsCommon = Registry::get('ProductsCommon');

      if ($CLICSHOPPING_ProductsCommon->getID() && isset($_GET['Products'])) {
        $content_width = (int)MODULE_PRODUCTS_INFO_CODE_BAR_CONTENT_WIDTH;
        $text_position = MODULE_PRODUCTS_INFO_CODE_BAR_POSITION;

        $CLICSHOPPING_Template = Registry::get('Template');

        $products_barcode = $CLICSHOPPING_ProductsCommon->getProductsBarCode();
        $products_name = $CLICSHOPPING_ProductsCommon->getProductsName();

        if (!empty($products_barcode)) {
          $products_barcode = HTML::image($CLICSHOPPING_Template->getDirectoryTemplateImages() . 'barcode/' .  $products_barcode  . '_barcode.png', $products_name);
        }

        $products_barcode_content = '<!-- Start $products_barcode -->' . "\n";

        ob_start();
        require_once($CLICSHOPPING_Template->getTemplateModules($this->group . '/content/products_info_code_bar'));
        $products_barcode_content .= ob_get_clean();

        $products_barcode_content .= '<!-- $products_barcode -->' . "\n";

        $CLICSHOPPING_Template->addBlock($products_barcode_content, $this->group);
      }
    } // public function execute

    public function isEnabled() {
      return $this->enabled;
    }

    public function check() {
      return defined('MODULE_PRODUCTS_INFO_CODE_BAR_STATUS');
    }

    public function install() {
      $CLICSHOPPING_Db = Registry::get('Db');

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Do you want to enable this module ?',
          'configuration_key' => 'MODULE_PRODUCTS_INFO_CODE_BAR_STATUS',
          'configuration_value' => 'True',
          'configuration_description' => 'Do you want to enable this module in your shop ?',
          'configuration_group_id' => '6',
          'sort_order' => '1',
          'set_function' => 'clic_cfg_set_boolean_value(array(\'True\', \'False\'))',
          'date_added' => 'now()'
        ]
      );


      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Please select the width of the display?',
          'configuration_key' => 'MODULE_PRODUCTS_INFO_CODE_BAR_CONTENT_WIDTH',
          'configuration_value' => '12',
          'configuration_description' => 'Please enter a number between 1 and 12',
          'configuration_group_id' => '6',
          'sort_order' => '1',
          'set_function' => 'clic_cfg_set_content_module_width_pull_down',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'A quel endroit souhaitez-vous afficher le code barre ?',
          'configuration_key' => 'MODULE_PRODUCTS_INFO_CODE_BAR_POSITION',
          'configuration_value' => 'float-none',
          'configuration_description' => 'Affiche le code barre du produit à gauche ou à droite<br><br><i>(Valeur Left = Gauche <br>Valeur Right = Droite <br>Valeur None = Aucun)</i>',
          'configuration_group_id' => '6',
          'sort_order' => '2',
          'set_function' => 'clic_cfg_set_boolean_value(array(\'float-end\', \'float-start\', \'float-none\'))',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Sort order',
          'configuration_key' => 'MODULE_PRODUCTS_INFO_CODE_BAR_SORT_ORDER',
          'configuration_value' => '103',
          'configuration_description' => 'Sort order of display. Lowest is displayed first. The sort order must be different on every module',
          'configuration_group_id' => '6',
          'sort_order' => '3',
          'set_function' => '',
          'date_added' => 'now()'
        ]
      );

      return $CLICSHOPPING_Db->save('configuration', ['configuration_value' => '1'],
                                              ['configuration_key' => 'WEBSITE_MODULE_INSTALLED']
                            );
    }

    public function remove() {
      return Registry::get('Db')->exec('delete from :table_configuration where configuration_key in ("' . implode('", "', $this->keys()) . '")');
    }

    public function keys() {
      return array (
        'MODULE_PRODUCTS_INFO_CODE_BAR_STATUS',
        'MODULE_PRODUCTS_INFO_CODE_BAR_CONTENT_WIDTH',
        'MODULE_PRODUCTS_INFO_CODE_BAR_POSITION',
        'MODULE_PRODUCTS_INFO_CODE_BAR_SORT_ORDER'
      );
    }
  }
