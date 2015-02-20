<dl>
  <dt>
  <input id="payment_[{$sPaymentID}]" type="radio" name="paymentid" value="[{$sPaymentID}]" [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]checked[{/if}]>
  <label for="payment_[{$sPaymentID}]"><b>[{ $paymentmethod->oxpayments__oxdesc->value}]</b></label>
  </dt>
  <dd class="[{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]activePayment[{/if}]">
    [{include file="mo_ogone__payment_creditcard_form_fields.tpl"}]

    [{$oView->mo_ogone__loadRequestParams('ogone_credit_card')}]
    [{foreach from=$oView->mo_ogone__getAliasGatewayParamsForJavascriptVersion() item="value" key="key"}]
      <input type="hidden" name="[{$key}]" value="[{$value}]" />
    [{/foreach}]
    <input type="hidden" id="mo_ogone__shasign" name="SHASIGN" value="" />
    <input type="hidden" id="mo_ogone__paramvar" name="PARAMVAR" value="" />

    [{assign var="oxConfig" value=$oView->getConfig()}]
    <script type="text/javascript">
      (function () {
        var mo_ogone__shaSignatures = [{$oView->getAliasGatewayShaSignaturesAsJson()}];
        var mo_ogone__aliasGatewayUrl = '[{$oxConfig->getShopConfVar('mo_ogone__gateway_url_alias')}]';
        
        function mo_ogone__setShaSignature()
        {
          var brand = document.getElementById('mo_ogone__brand').value;
          document.getElementById('mo_ogone__shasign').value = mo_ogone__shaSignatures[brand];
        }
        
        var oldWindowOnload = function () {};
        if (typeof window.onload != 'undefined') {
          oldWindowOnload = window.onload;
        }
        
        window.onload = function () {
          var form = document.getElementById('mo_ogone__shasign').form;
          var radioButton = document.getElementById('payment_[{$sPaymentID}]');
          
          document.getElementById('mo_ogone__paramvar').value = 'JS_ENABLED';
          
          var oldOnSubmit = function () { return true };
          if (typeof form.onsubmit != 'undefined') {
            oldOnSubmit = form.onsubmit;
          }
          
          form.onsubmit = function () {
            if (radioButton.checked) {
              mo_ogone__setShaSignature();
              this.action = mo_ogone__aliasGatewayUrl;
            }
            
            return oldOnSubmit();
          };
          
          oldWindowOnload();
        };
      })();
    </script>

  </dd>
</dl>