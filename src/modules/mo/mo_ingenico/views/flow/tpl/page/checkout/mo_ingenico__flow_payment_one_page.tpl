[{capture append="oxidBlock_content"}]
  [{include file="page/checkout/inc/steps.tpl" active=3 }]

  [{assign var="mo_ingenico__currentPaymentId" value=$oView->getCheckedPaymentId()}]

  [{* This VIEW is only used if the customer has Javscript is disabled *}]
  [{foreach from=$oView->mo_ingenico__getAliasGatewayParamsSet() item="formParams"}]
    <form action="[{$oView->mo_ingenico__getPaymentGatewayUrl() }]"
          class="oxValidate" 
          method="post">

      [{foreach from=$formParams item="value" key="key"}]
        <input type="hidden" name="[{$key}]" value="[{$value}]" />
      [{/foreach}]

      [{if $mo_ingenico__currentPaymentId == 'ingenico_credit_card'}]
        [{include file="mo_ingenico__flow_payment_creditcard_form_fields.tpl" mo_ingenico__brand=$formParams.BRAND}]
      [{/if}]

      <div class="form-group">
        <div class="col-lg-9 col-lg-offset-3">
          <button class="submitButton btn btn-primary" type="submit">[{oxmultilang ident="SEND"}]</button>
        </div>
      </div>
    </form>
  [{/foreach}]

  [{insert name="oxid_tracker" title=$template_title }]
[{/capture}]
[{include file="layout/page.tpl"}]