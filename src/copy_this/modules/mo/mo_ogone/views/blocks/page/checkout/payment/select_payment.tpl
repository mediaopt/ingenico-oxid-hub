[{* include additional templates according to payment method andactive theme *}]
[{assign var="mo_ogone__theme" value="flow_"}]



[{* flow is the standard theme. if azure is active, include azure tpl  *}]
[{if method_exists($oViewConf, 'getActiveTheme') && $oViewConf->getActiveTheme() === 'azure'}]
    [{assign var="mo_ogone__theme" value=''}]
    [{/if}]

[{if $sPaymentID == 'ogone_credit_card'}]
    [{include file="mo_ogone__`$mo_ogone__theme`payment_creditcard.tpl"}]
    [{elseif $sPaymentID == 'ogone_open_invoice_de'}]
    [{include file="mo_ogone__`$mo_ogone__theme`payment_invoice.tpl"}]
    [{else}]
    [{$smarty.block.parent}]
    [{/if}]