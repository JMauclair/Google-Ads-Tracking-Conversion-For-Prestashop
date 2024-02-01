<?php

class AdminMclgoogleadstrackingConfigurationController extends ModuleAdminControllerCore
{
    public function __construct()
    {
        parent::__construct();
        $this->bootstrap = true;
    }

    public function initContent()
    {
        $this->context->smarty->assign([
            'gads_id' => Configuration::get('MCL_GADS_TRACKING_ID', null, null, $this->context->shop->id),
            'gads_pageview_label' => Configuration::get('MCL_GADS_TRACKING_PAGEVIEW_LABEL', null, null, $this->context->shop->id),
            'gads_addtocart_label' => Configuration::get('MCL_GADS_TRACKING_ADDTOCART_LABEL', null, null, $this->context->shop->id),
            'gads_buy_label' => Configuration::get('MCL_GADS_TRACKING_BUY_LABEL', null, null, $this->context->shop->id)
        ]);
        $this->setTemplate('configure.tpl');
    }

    public function postProcess()
    {
        $id_shop = Context::getContext()->shop->id;
        if (Tools::isSubmit('submitConf')) {
            $tag = Tools::getValue('gadsTrackingId');
            $pageviewLabel = Tools::getValue('pageViewLabel');
            $addtocartLabel = Tools::getValue('addToCartLabel');
            $buyLabel = Tools::getValue('buyLabel');
            if (
                Configuration::updateValue('MCL_GADS_TRACKING_ID', $tag, false, null, $id_shop) &
                Configuration::updateValue('MCL_GADS_TRACKING_PAGEVIEW_LABEL', $pageviewLabel, false, null, $id_shop) &
                Configuration::updateValue('MCL_GADS_TRACKING_ADDTOCART_LABEL', $addtocartLabel, false, null, $id_shop) &
                Configuration::updateValue('MCL_GADS_TRACKING_BUY_LABEL', $buyLabel, false, null, $id_shop)
            ) {
                $this->confirmations[] = 'Success';
            } else {
                $this->errors[] = 'Error';
            }
        }
    }

}