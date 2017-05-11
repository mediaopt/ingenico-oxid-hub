[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

[{ if $readonly }]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]
<form name="transfer" id="transfer" action="[{ $oViewConf->getSelfLink() }]" method="post">
    [{ $oViewConf->getHiddenSid() }]
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="cl" value="mo_ogone__aftersales">
</form>
[{if !$edit->mo_ogone__isOgoneOrder() }]
    this is not an ogone payment
[{else}]
<style type="text/css">
    .alert-success {
        background-color: #dff0d8;
        border-color: #d6e9c6;
        color: #3c763d;
    }
    .alert-danger {
        background-color: #f2dede;
        border-color: #ebccd1;
        color: #a94442;
    }
    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid transparent;
        border-radius: 4px;
    }
</style>
[{if $success_message }]
    <p class="alert alert-success">[{$success_message}]</p>
[{/if}]
[{if $error_message }]
    <p class="alert alert-danger">[{$error_message}]</p>
[{/if}]

    <table cellspacing="0" cellpadding="0" border="0" width="98%">
    <form name="search" id="search" action="[{ $oViewConf->getSelfLink() }]" method="post">
        [{ $oViewConf->getHiddenSid() }]
        <input type="hidden" name="cl" value="mo_ogone__aftersales">
        <input type="hidden" name="oxid" value="[{ $oxid }]">
        <input type="hidden" name="fnc" value="refund">
    <tr>
        <td class="listheader first"></td>
        <td class="listheader">[{ oxmultilang ident="GENERAL_SUM" }]</td>
        <td class="listheader" height="15">&nbsp;&nbsp;&nbsp;[{ oxmultilang ident="GENERAL_ITEMNR" }]</td>
        <td class="listheader">&nbsp;&nbsp;&nbsp;[{ oxmultilang ident="GENERAL_TITLE" }]</td>
        <td class="listheader">&nbsp;&nbsp;&nbsp;[{ oxmultilang ident="GENERAL_TYPE" }]</td>
        <td class="listheader">&nbsp;&nbsp;&nbsp;[{ oxmultilang ident="ORDER_ARTICLE_PARAMS" }]</td>
        <td class="listheader">&nbsp;&nbsp;&nbsp;[{ oxmultilang ident="GENERAL_SHORTDESC" }]</td>
        [{if $edit->isNettoMode() }]
            <td class="listheader">[{ oxmultilang ident="ORDER_ARTICLE_ENETTO" }]</td>
        [{else}]
            <td class="listheader">[{ oxmultilang ident="ORDER_ARTICLE_EBRUTTO" }]</td>
        [{/if}]
        <td class="listheader">[{ oxmultilang ident="GENERAL_ATALL" }]</td>
        <td class="listheader" colspan="3">[{ oxmultilang ident="ORDER_ARTICLE_MWST" }]</td>
    </tr>
    [{assign var="blWhite" value=""}]
    [{foreach from=$edit->getOrderArticles() item=listitem name=orderArticles}]
    <tr id="art.[{$smarty.foreach.orderArticles.iteration}]">
            [{ if $listitem->oxorderarticles__oxstorno->value == 1 }]
                [{assign var="listclass" value=listitem3 }]
            [{else}]
                [{assign var="listclass" value=listitem$blWhite }]
            [{/if}]
            <td valign="top" class="[{ $listclass}]"><input type="checkbox" name="aOrderArticles[]" value="[{$listitem->getId()}]" class="listedit"></td>
            <td valign="top" class="[{ $listclass}]">[{ $listitem->oxorderarticles__oxamount->value }]</td>
            <td valign="top" class="[{ $listclass}]" height="15">[{if $listitem->oxarticles__oxid->value}]<a href="Javascript:editThis('[{ $listitem->oxarticles__oxid->value}]');" class="[{ $listclass}]">[{/if}][{ $listitem->oxorderarticles__oxartnum->value }]</a></td>
            <td valign="top" class="[{ $listclass}]">[{if $listitem->oxarticles__oxid->value}]<a href="Javascript:editThis('[{ $listitem->oxarticles__oxid->value }]');" class="[{ $listclass}]">[{/if}][{ $listitem->oxorderarticles__oxtitle->value|oxtruncate:20:""|strip_tags }]</a></td>
            <td valign="top" class="[{ $listclass}]">[{ $listitem->oxorderarticles__oxselvariant->value }]</td>
            <td valign="top" class="[{ $listclass}]">
                [{if $listitem->getPersParams()}]
                    [{foreach key=sVar from=$listitem->getPersParams() item=aParam name=persparams}]
                        [{if !$smarty.foreach.persparams.first}]&nbsp;&nbsp;,&nbsp;[{/if}]
                        <em>
                            [{if $smarty.foreach.persparams.first && $smarty.foreach.persparams.last}]
                                [{ oxmultilang ident="GENERAL_LABEL" }]
                            [{else}]
                                [{$sVar}] :
                            [{/if}]
                            [{$aParam}]
                        </em>
                    [{/foreach}]
                [{/if}]
            </td>
            <td valign="top" class="[{ $listclass}]">[{ $listitem->oxorderarticles__oxshortdesc->value|oxtruncate:20:""|strip_tags }]</td>
            [{if $edit->isNettoMode() }]
            <td valign="top" class="[{ $listclass}]">[{ $listitem->getNetPriceFormated() }] <small>[{ $edit->oxorder__oxcurrency->value }]</small></td>
            <td valign="top" class="[{ $listclass}]">[{ $listitem->getTotalNetPriceFormated() }] <small>[{ $edit->oxorder__oxcurrency->value }]</small></td>
            [{else}]
            <td valign="top" class="[{ $listclass}]">[{ $listitem->getBrutPriceFormated() }] <small>[{ $edit->oxorder__oxcurrency->value }]</small></td>
            <td valign="top" class="[{ $listclass}]">[{ $listitem->getTotalBrutPriceFormated() }] <small>[{ $edit->oxorder__oxcurrency->value }]</small></td>
            [{/if}]
            <td valign="top" class="[{ $listclass}]">[{ $listitem->oxorderarticles__oxvat->value}]</td>
    </tr>
    [{if $blWhite == "2"}]
    [{assign var="blWhite" value=""}]
    [{else}]
    [{assign var="blWhite" value="2"}]
    [{/if}]
    [{/foreach}]
    </table>
    [{assign var="deliveryPrice" value=$edit->getOrderDeliveryPrice() }]
    [{if $deliveryPrice->getBruttoPrice() != 0 }]
        <input type="checkbox" name="includeShipment" value="1" >[{ oxmultilang ident="MO_OGONE__INCLUDE_SHIPMENT"}]</br>
    [{/if}]
    [{if $edit->getGiftCard() }]
        <input type="checkbox" name="includeGiftcard" value="1" >[{ oxmultilang ident="MO_OGONE__INCLUDE_GIFTCARD"}]</br>
    [{/if}]
    <input type="submit" value="[{ oxmultilang ident="ORDER_ARTICLE_UPDATE_STOCK" }]">

    </form>

    <table border="0" cellspacing="0" cellpadding="0" width="100%">
    <tr>
    <td valign="top" style="padding-left:10px;">
        <br />
        [{if $edit->oxorder__oxstorno->value}]
        <span class="orderstorno">[{ oxmultilang ident="ORDER_ARTICLE_STORNO" }]</span><br><br>
        [{/if}]
        <b>[{ oxmultilang ident="GENERAL_ATALL" }] : </b><br>
        [{block name="admin_order_article_total"}]
        <table border="0" cellspacing="0" cellpadding="0" id="order.info">
    [{if $edit->isNettoMode() }]
        <tr>
        <td class="edittext" height="15">[{ oxmultilang ident="GENERAL_INETTO" }]</td>
        <td class="edittext" align="right"><b>[{ $edit->getFormattedTotalNetSum() }]</b></td>
        <td class="edittext">&nbsp;<b>[{if $edit->oxorder__oxcurrency->value}] [{$edit->oxorder__oxcurrency->value}] [{else}] &euro; [{/if}]</b></td>
        </tr>
        <tr>
        <td class="edittext" height="15">[{ oxmultilang ident="GENERAL_DISCOUNT" }]&nbsp;&nbsp;</td>
        <td class="edittext" align="right"><b>- [{ $edit->getFormattedDiscount() }]</b></td>
        <td class="edittext">&nbsp;<b>[{if $edit->oxorder__oxcurrency->value}] [{$edit->oxorder__oxcurrency->value}] [{else}] &euro; [{/if}]</b></td>
        </tr>
        [{foreach key=iVat from=$aProductVats item=dVatPrice}]
        <tr>
        <td class="edittext" height="15">[{ oxmultilang ident="GENERAL_IVAT" }] ([{ $iVat }]%)</td>
        <td class="edittext" align="right"><b>[{ $dVatPrice }]</b></td>
        <td class="edittext">&nbsp;<b>[{if $edit->oxorder__oxcurrency->value}] [{$edit->oxorder__oxcurrency->value}] [{else}] [{ $currency->name}] [{/if}]</b></td>
        </tr>
        [{/foreach}]
        <tr>
        <td class="edittext" height="15">[{ oxmultilang ident="GENERAL_IBRUTTO" }]</td>
        <td class="edittext" align="right"><b>[{ $edit->getFormattedTotalBrutSum() }]</b></td>
        <td class="edittext">&nbsp;<b>[{if $edit->oxorder__oxcurrency->value}] [{$edit->oxorder__oxcurrency->value}] [{else}] &euro; [{/if}]</b></td>
        </tr>
    [{else}]
        <tr>
        <td class="edittext" height="15">[{ oxmultilang ident="GENERAL_IBRUTTO" }]</td>
        <td class="edittext" align="right"><b>[{ $edit->getFormattedTotalBrutSum() }]</b></td>
        <td class="edittext">&nbsp;<b>[{if $edit->oxorder__oxcurrency->value}] [{$edit->oxorder__oxcurrency->value}] [{else}] &euro; [{/if}]</b></td>
        </tr>
        <tr>
        <td class="edittext" height="15">[{ oxmultilang ident="GENERAL_DISCOUNT" }]&nbsp;&nbsp;</td>
        <td class="edittext" align="right"><b>- [{ $edit->getFormattedDiscount() }]</b></td>
        <td class="edittext">&nbsp;<b>[{if $edit->oxorder__oxcurrency->value}] [{$edit->oxorder__oxcurrency->value}] [{else}] &euro; [{/if}]</b></td>
        </tr>
        <tr>
        <td class="edittext" height="15">[{ oxmultilang ident="GENERAL_INETTO" }]</td>
        <td class="edittext" align="right"><b>[{ $edit->getFormattedTotalNetSum() }]</b></td>
        <td class="edittext">&nbsp;<b>[{if $edit->oxorder__oxcurrency->value}] [{$edit->oxorder__oxcurrency->value}] [{else}] &euro; [{/if}]</b></td>
        </tr>
        [{foreach key=iVat from=$aProductVats item=dVatPrice}]
        <tr>
        <td class="edittext" height="15">[{ oxmultilang ident="GENERAL_IVAT" }] ([{ $iVat }]%)</td>
        <td class="edittext" align="right"><b>[{ $dVatPrice }]</b></td>
        <td class="edittext">&nbsp;<b>[{if $edit->oxorder__oxcurrency->value}] [{$edit->oxorder__oxcurrency->value}] [{else}] [{ $currency->name}] [{/if}]</b></td>
        </tr>
        [{/foreach}]
    [{/if}]
        [{if $edit->oxorder__oxvoucherdiscount->value}]
        <tr>
        <td class="edittext" height="15">[{ oxmultilang ident="GENERAL_VOUCHERS" }]</td>
        <td class="edittext" align="right"><b>- [{ $edit->getFormattedTotalVouchers() }]</b></td>
        <td class="edittext">&nbsp;<b>[{if $edit->oxorder__oxcurrency->value}] [{$edit->oxorder__oxcurrency->value}] [{else}] &euro; [{/if}]</b></td>
        </tr>
        [{/if}]
        <tr>
        <td class="edittext" height="15">[{ oxmultilang ident="GENERAL_DELIVERYCOST" }]&nbsp;&nbsp;</td>
        <td class="edittext" align="right"><b>[{ $edit->getFormattedeliveryCost() }]</b></td>
        <td class="edittext">&nbsp;<b>[{if $edit->oxorder__oxcurrency->value}] [{$edit->oxorder__oxcurrency->value}] [{else}] &euro; [{/if}]</b></td>
        </tr>
        <tr>
        <td class="edittext" height="15">[{ oxmultilang ident="GENERAL_PAYCOST" }]&nbsp;&nbsp;</td>
        <td class="edittext" align="right"><b>[{ $edit->getFormattedPayCost()}]</b></td>
        <td class="edittext">&nbsp;<b>[{if $edit->oxorder__oxcurrency->value}] [{$edit->oxorder__oxcurrency->value}] [{else}] &euro; [{/if}]</b></td>
        </tr>
        [{if $edit->oxorder__oxwrapcost->value }]
        <tr>
        <td class="edittext" height="15">[{ oxmultilang ident="GENERAL_WRAPPING" }]&nbsp;&nbsp;</td>
        <td class="edittext" align="right"><b>[{ $edit->getFormattedWrapCost() }]</b></td>
        <td class="edittext">&nbsp;<b>[{if $edit->oxorder__oxcurrency->value}] [{$edit->oxorder__oxcurrency->value}] [{else}] &euro; [{/if}]</b></td>
        </tr>
        [{/if}]
        [{if $edit->oxorder__oxgiftcardcost->value }]
        <tr>
        <td class="edittext" height="15">[{ oxmultilang ident="GENERAL_CARD" }]&nbsp;&nbsp;</td>
        <td class="edittext" align="right"><b>[{ $edit->getFormattedGiftCardCost() }]</b></td>
        <td class="edittext">&nbsp;<b>[{if $edit->oxorder__oxcurrency->value}] [{$edit->oxorder__oxcurrency->value}] [{else}] &euro; [{/if}]</b></td>
        </tr>
        [{/if}]
        <tr>
        <td class="edittext" height="25">[{ oxmultilang ident="GENERAL_SUMTOTAL" }]&nbsp;&nbsp;</td>
        <td class="edittext" align="right"><b>[{ $edit->getFormattedTotalOrderSum() }]</b></td>
        <td class="edittext">&nbsp;<b>[{if $edit->oxorder__oxcurrency->value}] [{$edit->oxorder__oxcurrency->value}] [{else}] &euro; [{/if}]</b></td>
        </tr>
        </table>
        [{/block}]
      </td>
    </tr>
    </table>
[{/if}]
[{include file="bottomnaviitem.tpl"}]

[{include file="bottomitem.tpl"}]
