{*
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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2024 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div class="panel">
    <h3>Setup tracking</h3>
    <form method="post">
        <label class="control-label" for="gadsTrackingId">Ads tracking ID</label>
        <input class="form-control" type="text" id="gadsTrackingId" name="gadsTrackingId" value="{$gads_id}">
        <hr>
        <label class="control-label" for="pageViewLabel">Page View Label</label>
        <input class="form-control" type="text" id="pageViewLabel" name="pageViewLabel" value="{$gads_pageview_label}">
        <hr>
        <label class="control-label" for="addToCartLabel">Add To Cart Label</label>
        <input class="form-control" type="text" id="addToCartLabel" name="addToCartLabel"  value="{$gads_addtocart_label}">
        <hr>
        <label class="control-label" for="buyLabel">Buy Label</label>
        <input class="form-control" type="text" id="buyLabel" name="buyLabel" value="{$gads_buy_label}">
        <div class="panel-footer">
            <button class="btn btn-primary" type="submit" name="submitConf">Save</button>
        </div>
    </form>
</div>
