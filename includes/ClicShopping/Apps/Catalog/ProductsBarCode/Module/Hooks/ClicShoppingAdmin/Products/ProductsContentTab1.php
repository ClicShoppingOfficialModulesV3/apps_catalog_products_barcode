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
  use ClicShopping\OM\CLICSHOPPING;

  use ClicShopping\Apps\Catalog\ProductsBarCode\ProductsBarCode as ProductsBarCodeApps;

  class ProductsContentTab1 implements \ClicShopping\OM\Modules\HooksInterface {
    protected $app;
    protected $template;

    public function __construct() 
    {
      if (!Registry::exists('ProductsBarCode')) {
        Registry::set('ProductsBarCode', new ProductsBarCodeApps());
      }

      $this->app = Registry::get('ProductsBarCode');
      $this->template = Registry::get('TemplateAdmin');

      $this->app->loadDefinitions('Module/Hooks/ClicShoppingAdmin/Products/page_content_tab_1');
    }

    private function getBarreCode() {
      if (isset($_GET['pID'])) {
        $Qproduct = $this->app->db->prepare('select products_barcode
                                             from :table_products
                                             where products_id = :products_id
                                            ');

        $Qproduct->bindValue(':products_id', HTML::sanitize($_GET['pID']));

        $Qproduct->execute();

        $products_barcode = $Qproduct->value('products_barcode');

        return $products_barcode;
      }
   }


    public function display()  {
      $CLICSHOPPING_Template = Registry::get('TemplateAdmin');

      if (!defined('CLICSHOPPING_APP_BAR_CODE_PB_STATUS') || CLICSHOPPING_APP_BAR_CODE_PB_STATUS == 'False') {
        return false;
      }

      $barre_code = $this->getBarreCode();

      $content = '<div class="col-md-5" id="tab1ContentRow5Barcode">';
      $content .= '<div class="form-group row">';

      $content .= '<label for="' .  $this->app->getDef('text_products_barcode') . '" class="col-5 col-form-label">' .  $this->app->getDef('text_products_barcode') . '</label>';
      $content .= '<div class="col-md-5">';
      $content .=  HTML::inputField('products_barcode', $barre_code, 'id="products_barcode"');
      $content .= '<a href="' . CLICSHOPPING::link(null, 'A&Catalog%5CProducts&ConfigurationPopUpFields&cKey=BAR_CODE_TYPE') . '"  data-toggle="modal" data-refresh="true" data-target="#myModal">' .  HTML::image($CLICSHOPPING_Template->getImageDirectory() . 'icons/edit.gif', $this->app->getDef('text_edit')) . '</a>';
      $content .= '<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">';
      $content .= '<div class="modal-dialog">';
      $content .= '<div class="modal-content">';
      $content .= '<div class="modal-body"><div class="te"></div></div>';
      $content .= '</div> <!-- /.modal-content -->';
      $content .= '</div><!-- /.modal-dialog -->';
      $content .= '</div><!-- /.modal -->';
      $content .= '</div>';

      $content .= '<div class="col-md-2">';

      if (!is_null($barre_code)) {
        $content .= HTML::image($CLICSHOPPING_Template->getDirectoryShopTemplateImages() . 'barcode/' . $barre_code .'_barcode.png');
      }

      $content .= '</div>';
      $content .= '</div>';

      $content .= '</div>';
      $content .= '</div>';

      $output = <<<EOD
<!-- ######################## -->
<!--  Start Products barre code Hooks      -->
<!-- ######################## -->
<script>
$('#tab1ContentRow4').append(
    '{$content}'
);
</script>
<!-- ######################## -->
<!--  End Products barre code hoosk      -->
<!-- ######################## -->

EOD;
        return $output;

    }
  }