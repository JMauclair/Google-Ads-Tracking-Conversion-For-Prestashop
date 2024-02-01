<?php
/**
 * 2007-2024 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2024 PrestaShop SA
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class Mcl_googleadstracking extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'mcl_googleadstracking';
        $this->tab = 'advertising_marketing';
        $this->version = '1.0.0';
        $this->author = 'MCL DEV';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Google Ads Tracking');
        $this->description = $this->l('Get your Google ADS through API');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => '8.0');
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {

        return parent::install() &&
            $this->registerHook('displayHeader') &&
            $this->registerHook('actionFrontControllerSetMedia') &&
            $this->registerHook('actionCartSave') &&
            $this->registerHook('displayOrderConfirmation') &&
            $this->registerHook('displayProductAdditionalInfo');
    }

    public function uninstall()
    {

        return parent::uninstall() &&
            $this->unregisterHook('displayHeader') &&
            $this->unregisterHook('actionFrontControllerSetMedia') &&
            $this->unregisterHook('actionCartSave') &&
            $this->unregisterHook('displayOrderConfirmation') &&
            $this->unregisterHook('displayProductAdditionalInfo');
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        Tools::redirectAdmin($this->context->link->getAdminLink('AdminMclgoogleadstrackingConfiguration'));
    }

    public function hookActionFrontControllerSetMedia()
    {
        $atcLabel = Configuration::get('MCL_GADS_TRACKING_ADDTOCART_LABEL', null, null, $this->context->shop->id);
        $tag = Configuration::get('MCL_GADS_TRACKING_ID', null, null, $this->context->shop->id);
        if (!$tag) {
            return;
        }
        Media::addJsDef([
            'mclGadsTrackingAddToCartLabel' => [
                $atcLabel
            ],
            'mclGadsTrackingAdsId' => [
                $tag
            ],
            'mclGadsTrackingCurrency' => [
                $this->context->currency->iso_code
            ],
        ]);
        $this->context->controller->addJS("{$this->_path}views/js/gads_atc.js");
    }

    public function hookDisplayHeader()
    {
        $tag = Configuration::get('MCL_GADS_TRACKING_ID', null, null, $this->context->shop->id);
        if (!$tag) {
            return;
        }
        $type = false;
        $id_order = null;
        $currency = $this->context->currency->iso_code;
        if ($this->context->controller instanceof OrderConfirmationControllerCore) {
            $type = 'Buy';
            $label = Configuration::get('MCL_GADS_TRACKING_BUY_LABEL', null, null, $this->context->shop->id);
            $order = new Order($this->context->controller->id_order);
            $amount = round($order->getTotalProductsWithTaxes(), 2);
            $id_order = $order->id;
        }
        if ($this->context->controller instanceof ProductControllerCore) {
            $type = 'Product View';

            $label = Configuration::get('MCL_GADS_TRACKING_PAGEVIEW_LABEL', null, null, $this->context->shop->id);
            /** @var Product $product */
            $product = $this->context->controller->getProduct();
            $amount = round($product->getPublicPrice(), 2);
        }
        if ($type) {
            $data = $this->generateData($tag, $label, $amount, $currency, $id_order);
            $this->smarty->assign([
                'type' => $type,
                'data' => $data
            ]);
            return $this->display(__FILE__, 'gads_data.tpl');
        }
    }


    public function generateData($tag, $label, $amount, $currency, $id_transaction = null)
    {
        $id_transaction = ($id_transaction != null) ? ",'transaction_id':" . $id_transaction : '';
        return (string)"gtag('event', 'conversion', {'send_to': '{$tag}/{$label}',
        'value': {$amount},
        'currency': '{$currency}'
        " . $id_transaction . "
      });";
    }
}
