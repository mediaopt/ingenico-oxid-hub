[{assign var="oxConfig" value=$oView->getConfig()}]

<dl>
    <dt>
        <input id="payment_[{$sPaymentID}]" type="radio" name="paymentid" value="[{$sPaymentID}]"
               [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]checked[{/if}]>
        <label for="payment_[{$sPaymentID}]"><b>[{$paymentmethod->oxpayments__oxdesc->value}]</b></label>
    </dt>
    <dd class="[{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]activePayment[{/if}]">

        [{include file="mo_ingenico__flow_payment_creditcard_form_fields.tpl"}]

        [{if $oxConfig->getShopConfVar('mo_ingenico__use_hidden_auth')}]
    <input type="hidden" id="mo_ingenico__paramvar" name="PARAMVAR" value=""/>
        [{if $oxConfig->getShopConfVar('mo_ingenico__use_iframe')}]
        [{$oView->mo_ingenico__loadRequestParams('ingenico_credit_card')}]

        <script type="text/javascript">
            (function () {
                var mo_ingenico__iframeUrls = [{$oView->mo_ingenico_getIFrameURLsAsJson('ingenico_credit_card')}];

                document.getElementById('mo_ingenico__brand').onchange = function () {
                    if (this.value.startsWith('alias_')) {
                        document.getElementById('mo_ingenico__iframe').src = '';
                        document.getElementById('mo_ingenico__iframe').style.display = "none";
                    } else {
                        document.getElementById('mo_ingenico__iframe').src = mo_ingenico__iframeUrls[this.value];
                        document.getElementById('mo_ingenico__iframe').style.display = "initial";
                    }
                }
                if (document.getElementById('mo_ingenico__brand').value.startsWith('alias_')) {
                    document.getElementById('mo_ingenico__iframe').src = '';
                    document.getElementById('mo_ingenico__iframe').style.display = "none";
                } else {
                    document.getElementById('mo_ingenico__iframe').src = mo_ingenico__iframeUrls[document.getElementById('mo_ingenico__brand').value];
                    document.getElementById('mo_ingenico__iframe').style.display = "initial";
                }
                document.getElementById('mo_ingenico__paramvar').value = 'JS_ENABLED';

            })();
        </script>
        [{else}]
        [{$oView->mo_ingenico__loadRequestParams('ingenico_credit_card')}]

        [{foreach from=$oView->mo_ingenico__getAliasGatewayParamsForJavascriptVersion() item="value" key="key"}]
    <input type="hidden" name="[{$key}]" value="[{$value}]"/>
        [{/foreach}]
    <input type="hidden" id="mo_ingenico__shasign" name="SHASIGN" value=""/>


        <script type="text/javascript">
            (function () {
                var mo_ingenico__shaSignatures = [{$oView->getAliasGatewayShaSignaturesAsJson()}];
                var mo_ingenico__aliasGatewayUrl = '[{$oView->getAliasUrl()}]';

                function mo_ingenico__isAlias(brand) {
                    return brand.value.startsWith('alias_');
                }

                function mo_ingenico__setShaSignature() {
                    var brand = document.getElementById('mo_ingenico__brand');
                    if (!mo_ingenico__isAlias(brand)) {
                        document.getElementById('mo_ingenico__shasign').value = mo_ingenico__shaSignatures[brand.value];
                    }
                }

                var oldWindowOnload = function () {};
                if (typeof window.onload != 'undefined') {
                    oldWindowOnload = window.onload;
                }

                window.onload = function () {
                    document.getElementById('mo_ingenico__brand').onchange = function () {
                        if (mo_ingenico__isAlias(this)) {
                            document.getElementById('mo_ingenico__creditcard_fields').style.display = "none";
                        } else {
                            document.getElementById('mo_ingenico__creditcard_fields').style.display = "initial";
                        }
                    }
                    if (mo_ingenico__isAlias(document.getElementById('mo_ingenico__brand'))) {
                        document.getElementById('mo_ingenico__creditcard_fields').style.display = "none";
                    } else {
                        document.getElementById('mo_ingenico__creditcard_fields').style.display = "initial";
                    }
                    var form = document.getElementById('mo_ingenico__shasign').form;
                    var radioButton = document.getElementById('payment_[{$sPaymentID}]');

                    document.getElementById('mo_ingenico__paramvar').value = 'JS_ENABLED';

                    var oldOnSubmit = function () {
                        return true
                    };
                    if (typeof form.onsubmit != 'undefined') {
                        oldOnSubmit = form.onsubmit;
                    }

                    form.onsubmit = function () {
                        if (radioButton.checked && !mo_ingenico__isAlias(document.getElementById('mo_ingenico__brand'))) {
                            mo_ingenico__setShaSignature();
                            this.action = mo_ingenico__aliasGatewayUrl;
                        }

                        return oldOnSubmit();
                    };

                    oldWindowOnload();
                };
            })();
        </script>
        [{/if}]
        [{/if}]
    </dd>
</dl>
