[{capture append="oxidBlock_content"}]
  [{include file="page/checkout/inc/steps.tpl" active=3 }]

  [{assign var="mo_ogone__currentPaymentId" value=$oView->getCheckedPaymentId()}]

  [{* This VIEW is only used if the customer has Javscript is disabled *}]
  [{foreach from=$oView->mo_ogone__getAliasGatewayParamsSet() item="formParams"}]
    <form action="[{ $oView->mo_ogone__getPaymentGatewayUrl() }]" 
          class="oxValidate" 
          method="post">

      [{foreach from=$formParams item="value" key="key"}]
        <input type="hidden" name="[{$key}]" value="[{$value}]" />
      [{/foreach}]

      [{if $mo_ogone__currentPaymentId == 'ogone_credit_card'}]
        [{include file="mo_ogone__payment_creditcard_form_fields.tpl" mo_ogone__brand=$formParams.BRAND}]
      [{/if}]

      <input type="submit" />
    </form>
    <hr />
  [{/foreach}]

  [{insert name="oxid_tracker" title=$template_title }]
[{/capture}]
[{include file="layout/page.tpl"}]