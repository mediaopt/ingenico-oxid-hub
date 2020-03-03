[{* include additional templates according to payment method andactive theme *}]
[{assign var="mo_ingenico__theme" value="flow_"}]

[{oxscript include=$oViewConf->getModuleUrl('mo_ingenico','out/src/js/3dsecure.js')}]

[{* flow is the standard theme. if azure is active, include azure tpl  *}]
[{if method_exists($oViewConf, 'getActiveTheme') && $oViewConf->getActiveTheme() === 'azure'}]
    [{assign var="mo_ingenico__theme" value=''}]
    [{/if}]

[{if $sPaymentID == 'ingenico_credit_card'}]
    [{include file="mo_ingenico__`$mo_ingenico__theme`payment_creditcard.tpl"}]
[{elseif $sPaymentID == 'ingenico_open_invoice_de'}]
    [{include file="mo_ingenico__`$mo_ingenico__theme`payment_invoice.tpl"}]
[{else}]
    [{$smarty.block.parent}]
[{/if}]
